<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductSizeStock;
use App\Models\Size;
use App\Models\StockNotification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StockNotificationController extends Controller
{
    /**
     * Store a new stock alert notification request
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'product_id' => 'required|integer|exists:products,id',
            'size_id' => 'nullable|integer|exists:sizes,id',
            'email' => 'nullable|email|max:150',
            'phone' => 'nullable|string|max:25',
        ]);

        if (empty($validated['email']) && empty($validated['phone'])) {
            return response()->json([
                'success' => false,
                'message' => 'Lütfen en az bir iletişim bilgisi (e-posta veya telefon) giriniz.',
            ], 422);
        }

        $product = Product::findOrFail($validated['product_id']);
        $size = !empty($validated['size_id']) ? Size::find($validated['size_id']) : null;

        // Check if stock is currently depleted
        if ($size) {
            $stockRecord = ProductSizeStock::where('product_id', $product->id)
                                           ->where('size_id', $size->id)
                                           ->first();

            $currentStock = $stockRecord ? $stockRecord->stock : 0;

            if ($currentStock > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Bu ürünün seçili numarası şu anda stokta mevcuttur. Hemen sipariş verebilirsiniz.',
                ], 422);
            }
        }

        $email = !empty($validated['email']) ? strtolower(trim($validated['email'])) : null;
        $phone = !empty($validated['phone']) ? trim($validated['phone']) : null;

        // Check if duplicate alert already exists
        $existing = StockNotification::where('product_id', $product->id)
                                     ->where('size_id', $size?->id)
                                     ->where('is_notified', false)
                                     ->where(function ($q) use ($email, $phone) {
                                         if ($email && $phone) {
                                             $q->where('email', $email)->orWhere('phone', $phone);
                                         } elseif ($email) {
                                             $q->where('email', $email);
                                         } else {
                                             $q->where('phone', $phone);
                                         }
                                     })
                                     ->first();

        if ($existing) {
            return response()->json([
                'success' => false,
                'message' => 'Bu ürün için zaten aktif bir bildirim kaydınız bulunmaktadır.',
            ]);
        }

        StockNotification::create([
            'product_id' => $product->id,
            'size_id' => $size?->id,
            'email' => $email,
            'phone' => $phone,
            'is_notified' => false,
        ]);

        $sizeText = $size ? " ({$size->size_number} numara)" : "";

        return response()->json([
            'success' => true,
            'message' => "'{$product->name}'{$sizeText} için stok bildirim kaydınız oluşturuldu. Stok geldiğinde bilgilendirileceksiniz.",
        ]);
    }
}
