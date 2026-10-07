<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\ProductSizeStock;
use App\Models\ReturnRequest;
use App\Models\Size;
use App\Models\StockMovement;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ReturnRequestController extends Controller
{
    /**
     * Display list of return and exchange requests
     */
    public function index(Request $request): View
    {
        $query = ReturnRequest::with(['order', 'orderItem', 'user'])->orderByDesc('created_at');

        if ($status = $request->query('status')) {
            $query->where('status', $status);
        }

        if ($type = $request->query('type')) {
            $query->where('type', $type);
        }

        $requests = $query->paginate(20)->withQueryString();

        $stats = [
            'total' => ReturnRequest::count(),
            'pending' => ReturnRequest::where('status', 'bekliyor')->count(),
            'processing' => ReturnRequest::where('status', 'inceleniyor')->count(),
            'approved' => ReturnRequest::where('status', 'onaylandi')->count(),
            'completed' => ReturnRequest::where('status', 'tamamlandi')->count(),
        ];

        return view('admin.returns.index', compact('requests', 'stats', 'status', 'type'));
    }

    /**
     * Show return request details
     */
    public function show(ReturnRequest $returnRequest): View
    {
        $returnRequest->load(['order.items', 'orderItem', 'user']);
        return view('admin.returns.show', ['return' => $returnRequest]);
    }

    /**
     * Update return request status and admin note
     */
    public function updateStatus(Request $request, ReturnRequest $returnRequest): RedirectResponse
    {
        $validated = $request->validate([
            'status' => 'required|in:bekliyor,inceleniyor,onaylandi,reddedildi,tamamlandi',
            'admin_note' => 'nullable|string|max:2000',
            'restock' => 'nullable|boolean',
        ]);

        $oldStatus = $returnRequest->status;
        $newStatus = $validated['status'];

        DB::transaction(function () use ($returnRequest, $validated, $oldStatus, $newStatus, $request) {
            // If marked as completed and restock requested (or default return completion), increment size stock and record movement
            if ($newStatus === 'tamamlandi' && $oldStatus !== 'tamamlandi' && $returnRequest->type === 'return') {
                $item = $returnRequest->orderItem;
                if ($item && $item->product_id) {
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
                                'user_id' => Auth::id(),
                                'type' => 'return_restock',
                                'quantity_before' => $qtyBefore,
                                'quantity_change' => $qtyChange,
                                'quantity_after' => $qtyAfter,
                                'reason' => "İade Kabul Restoğu (Talep #{$returnRequest->id} - Sipariş #{$returnRequest->order->order_number})",
                                'reference_type' => 'ReturnRequest',
                                'reference_id' => $returnRequest->id,
                            ]);
                        }
                    }
                }
            }

            $returnRequest->update([
                'status' => $newStatus,
                'admin_note' => $validated['admin_note'] ?? $returnRequest->admin_note,
            ]);

            AuditLog::record(
                'return_request_status_updated',
                ReturnRequest::class,
                $returnRequest->id,
                "İade/Değişim #{$returnRequest->id} durumu '{$oldStatus}' -> '{$newStatus}' olarak güncellendi."
            );
        });

        return back()->with('success', "Talep durumu '{$returnRequest->status_label}' olarak güncellendi.");
    }

    /**
     * Delete return request
     */
    public function destroy(ReturnRequest $return): RedirectResponse
    {
        AuditLog::record(
            'return_request_deleted',
            ReturnRequest::class,
            $return->id,
            "İade/Değişim talebi #{$return->id} silindi."
        );

        $return->delete();

        return redirect()->route('admin.returns.index')->with('success', 'Talep kaydı silindi.');
    }
}
