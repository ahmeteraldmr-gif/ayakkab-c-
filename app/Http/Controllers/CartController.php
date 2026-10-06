<?php

namespace App\Http\Controllers;

use App\Services\CartService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CartController extends Controller
{
    protected CartService $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    /**
     * Display cart page
     */
    public function index(): View
    {
        $cartSummary = $this->cartService->getSummary();
        return view('pages.cart', compact('cartSummary'));
    }

    /**
     * Add item to cart
     */
    public function add(Request $request): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'product_id' => 'required|integer|exists:products,id',
            'size_id' => 'required|integer|exists:sizes,id',
            'quantity' => 'nullable|integer|min:1|max:20',
        ], [
            'size_id.required' => 'Lütfen bir ayakkabı numarası seçiniz.',
        ]);

        $quantity = (int) ($validated['quantity'] ?? 1);
        $result = $this->cartService->add(
            (int) $validated['product_id'],
            (int) $validated['size_id'],
            $quantity
        );

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json($result, $result['success'] ? 200 : 422);
        }

        if (!$result['success']) {
            return back()->with('error', $result['message']);
        }

        return redirect()->route('cart.index')->with('success', $result['message']);
    }

    /**
     * Update item quantity
     */
    public function update(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'key' => 'required|string',
            'quantity' => 'required|integer|min:0|max:50',
        ]);

        $result = $this->cartService->update($validated['key'], (int) $validated['quantity']);

        return response()->json($result, $result['success'] ? 200 : 422);
    }

    /**
     * Remove item
     */
    public function remove(Request $request): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'key' => 'required|string',
        ]);

        $result = $this->cartService->remove($validated['key']);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json($result);
        }

        return back()->with('success', 'Ürün sepetten çıkarıldı.');
    }

    /**
     * Clear cart
     */
    public function clear(): RedirectResponse
    {
        $this->cartService->clear();
        return redirect()->route('cart.index')->with('success', 'Sepetiniz temizlendi.');
    }

    /**
     * Get cart info for dynamic mini-cart header badge / drawer
     */
    public function summary(): JsonResponse
    {
        return response()->json($this->cartService->getSummary());
    }
}
