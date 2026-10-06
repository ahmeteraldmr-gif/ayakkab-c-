<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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
     * Update order status
     */
    public function updateStatus(Request $request, Order $order): RedirectResponse
    {
        $validated = $request->validate([
            'status' => 'required|in:yeni,hazirlaniyor,kargoda,tamamlandi,iptal',
            'payment_status' => 'nullable|string|max:50',
        ]);

        $newStatus = $validated['status'];
        if (!$order->canTransitionTo($newStatus)) {
            return back()->with('error', "Geçersiz durum geçişi! '{$order->status_label}' durumundaki bir sipariş '{$newStatus}' yapılamaz.");
        }

        $oldStatusLabel = $order->status_label;
        $order->update([
            'status' => $newStatus,
            'payment_status' => $validated['payment_status'] ?? $order->payment_status,
        ]);

        \App\Models\AuditLog::record(
            'order_status_updated',
            Order::class,
            $order->id,
            "Sipariş #{$order->order_number} durumu '{$oldStatusLabel}' -> '{$order->status_label}' olarak güncellendi."
        );

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

        \App\Models\AuditLog::record(
            'order_deleted',
            Order::class,
            $orderId,
            "Sipariş #{$orderNum} kaydı silindi."
        );

        return redirect()->route('admin.orders.index')->with('success', 'Sipariş kaydı silindi.');
    }
}
