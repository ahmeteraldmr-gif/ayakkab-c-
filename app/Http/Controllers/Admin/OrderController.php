<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\OrderShippedCustomerMail;
use App\Models\AuditLog;
use App\Models\Order;
use App\Models\ProductSizeStock;
use App\Models\Size;
use App\Models\StockMovement;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class OrderController extends Controller
{
    /**
     * Display orders listing
     */
    public function index(Request $request): View
    {
        $query = Order::with('items');

        if ($request->filled('q')) {
            $q = $request->input('q');
            $query->where(function ($sub) use ($q) {
                $sub->where('order_number', 'like', "%{$q}%")
                    ->orWhere('customer_name', 'like', "%{$q}%")
                    ->orWhere('customer_phone', 'like', "%{$q}%")
                    ->orWhere('city', 'like', "%{$q}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        $orders = $query->orderByDesc('id')->paginate(15)->withQueryString();

        $statusCounts = [
            'all' => Order::count(),
            'yeni' => Order::where('status', 'yeni')->count(),
            'hazirlaniyor' => Order::where('status', 'hazirlaniyor')->count(),
            'kargoda' => Order::where('status', 'kargoda')->count(),
            'tamamlandi' => Order::where('status', 'tamamlandi')->count(),
            'iptal' => Order::where('status', 'iptal')->count(),
        ];

        return view('admin.orders.index', compact('orders', 'statusCounts'));
    }

    /**
     * Display order detail
     */
    public function show(Order $order): View
    {
        $order->load(['items.product']);
        return view('admin.orders.show', compact('order'));
    }

    /**
     * Update order status & shipping info
     */
    public function updateStatus(Request $request, Order $order): RedirectResponse
    {
        $validated = $request->validate([
            'status' => 'required|in:yeni,hazirlaniyor,kargoda,tamamlandi,iptal',
            'payment_status' => 'nullable|string|max:50',
            'shipping_company' => 'nullable|string|max:100',
            'tracking_number' => 'nullable|string|max:100',
            'tracking_url' => 'nullable|url|max:255',
        ]);

        $newStatus = $validated['status'];
        if (!$order->canTransitionTo($newStatus)) {
            return back()->with('error', "Geçersiz durum geçişi! '{$order->status_label}' durumundaki bir sipariş '{$newStatus}' yapılamaz.");
        }

        $oldStatus = $order->status;
        $oldStatusLabel = $order->status_label;

        DB::transaction(function () use ($order, $validated, $newStatus, $oldStatus, $oldStatusLabel) {
            $updateData = [
                'status' => $newStatus,
                'payment_status' => $validated['payment_status'] ?? $order->payment_status,
                'shipping_company' => $validated['shipping_company'] ?? $order->shipping_company,
                'tracking_number' => $validated['tracking_number'] ?? $order->tracking_number,
                'tracking_url' => $validated['tracking_url'] ?? $order->tracking_url,
            ];

            if ($newStatus === 'kargoda' && empty($order->shipped_at)) {
                $updateData['shipped_at'] = now();
            }

            if ($newStatus === 'tamamlandi' && empty($order->delivered_at)) {
                $updateData['delivered_at'] = now();
            }

            // If cancelling order, restore stock and log stock movements
            if ($newStatus === 'iptal' && $oldStatus !== 'iptal') {
                foreach ($order->items as $item) {
                    if ($item->product_id) {
                        $size = Size::where('size_number', $item->size_number)->first();
                        if ($size) {
                            $stockRecord = ProductSizeStock::where('product_id', $item->product_id)
                                                           ->where('size_id', $size->id)
                                                           ->lockForUpdate()
                                                           ->first();
                            if ($stockRecord) {
                                $qtyBefore = $stockRecord->stock;
                                $qtyChange = (int) $item->quantity;
                                $qtyAfter = $qtyBefore + $qtyChange;

                                $stockRecord->increment('stock', $qtyChange);

                                StockMovement::create([
                                    'product_id' => $item->product_id,
                                    'size_id' => $size->id,
                                    'user_id' => auth()->id(),
                                    'type' => 'order_cancel',
                                    'quantity_before' => $qtyBefore,
                                    'quantity_change' => $qtyChange,
                                    'quantity_after' => $qtyAfter,
                                    'reason' => "Sipariş İptali: #{$order->order_number}",
                                    'reference_type' => 'Order',
                                    'reference_id' => $order->id,
                                ]);
                            }
                        }
                    }
                }
            }

            $order->update($updateData);

            AuditLog::record(
                'order_status_updated',
                Order::class,
                $order->id,
                "Sipariş #{$order->order_number} durumu '{$oldStatusLabel}' -> '{$order->status_label}' olarak güncellendi."
            );
        });

        // If shipped, trigger customer shipment mail (Error-safe)
        if ($newStatus === 'kargoda' && !empty($order->customer_email)) {
            try {
                Mail::to($order->customer_email)->send(new OrderShippedCustomerMail($order));
            } catch (\Throwable $mailEx) {
                Log::warning("Shipment email notification failed: " . $mailEx->getMessage(), [
                    'order_id' => $order->id,
                ]);
            }
        }

        return back()->with('success', "Sipariş durumu '{$order->status_label}' olarak güncellendi.");
    }

    /**
     * Delete order
     */
    public function destroy(Order $order): RedirectResponse
    {
        $orderNum = $order->order_number;
        $orderId = $order->id;
        $order->delete();

        AuditLog::record(
            'order_deleted',
            Order::class,
            $orderId,
            "Sipariş #{$orderNum} kaydı silindi."
        );

        return redirect()->route('admin.orders.index')->with('success', 'Sipariş kaydı silindi.');
    }
}
