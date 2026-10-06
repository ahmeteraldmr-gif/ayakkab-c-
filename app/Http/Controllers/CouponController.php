<?php

namespace App\Http\Controllers;

use App\Services\CartService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    /**
     * Apply coupon to current shopping cart
     */
    public function apply(Request $request, CartService $cartService): JsonResponse
    {
        $validated = $request->validate([
            'code' => 'required|string|max:50',
        ], [
            'code.required' => 'Lütfen bir indirim kuponu kodu giriniz.',
        ]);

        $result = $cartService->applyCoupon($validated['code']);

        return response()->json($result, $result['success'] ? 200 : 422);
    }

    /**
     * Remove applied coupon
     */
    public function remove(CartService $cartService): JsonResponse
    {
        $result = $cartService->removeCoupon();

        return response()->json($result);
    }
}
