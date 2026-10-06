<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\CartService;
use App\Services\OrderService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    protected CartService $cartService;
    protected OrderService $orderService;

    public function __construct(CartService $cartService, OrderService $orderService)
    {
        $this->cartService = $cartService;
        $this->orderService = $orderService;
    }

    /**
     * Display checkout form
     */
    public function index(): View|RedirectResponse
    {
        $cartSummary = $this->cartService->getSummary();

        if (empty($cartSummary['items'])) {
            return redirect()->route('cart.index')->with('error', 'Sepetiniz boş olduğu için ödeme adımına geçilemez.');
        }

        return view('pages.checkout', compact('cartSummary'));
    }

    /**
     * Process checkout submission
     */
    public function process(Request $request): RedirectResponse
    {
        $cartSummary = $this->cartService->getSummary();

        if (empty($cartSummary['items'])) {
            return redirect()->route('cart.index')->with('error', 'Sepetinizde ürün bulunmamaktadır.');
        }

        $validated = $request->validate([
            'customer_name' => 'required|string|min:3|max:100',
            'customer_phone' => 'required|string|min:10|max:20',
            'customer_email' => 'nullable|email|max:100',
            'city' => 'required|string|min:2|max:50',
            'district' => 'required|string|min:2|max:50',
            'address' => 'required|string|min:10|max:500',
            'order_notes' => 'nullable|string|max:500',
            'payment_method' => 'required|in:kapida_odeme,havale,online_kart',
        ], [
            'customer_name.required' => 'Lütfen adınızı ve soyadınızı giriniz.',
            'customer_phone.required' => 'Lütfen geçerli bir telefon numarası giriniz.',
            'city.required' => 'Lütfen il seçiniz/yazınız.',
            'district.required' => 'Lütfen ilçe bilgisini giriniz.',
            'address.required' => 'Lütfen açık teslimat adresinizi giriniz.',
        ]);

        $result = $this->orderService->createOrder($validated);

        if (!$result['success']) {
            return back()->withInput()->with('error', $result['message']);
        }

        $order = $result['order'];

        return redirect()->route('order.success', [
            'orderNumber' => $order->order_number,
            'token' => $order->access_token,
        ]);
    }

    /**
     * Display order success confirmation page (Protected against IDOR)
     */
    public function success(Request $request, string $orderNumber): View|RedirectResponse
    {
        $order = Order::with('items')
                      ->where('order_number', $orderNumber)
                      ->firstOrFail();

        // Security check: Only allow access if:
        // 1. Logged-in admin user
        // 2. Session created this specific order (matching order token)
        // 3. Valid access_token provided in request query
        $isAdmin = auth()->check() && auth()->user()->isAdmin();
        $isSessionOwner = session('authorized_order_' . $orderNumber) === $order->access_token;
        $hasValidToken = !empty($order->access_token) && ($request->query('token') === $order->access_token);

        if (!$isAdmin && !$isSessionOwner && !$hasValidToken) {
            return redirect()->route('home')->with('error', 'Bu sipariş bilgilerini görüntüleme yetkiniz bulunmamaktadır.');
        }

        return view('pages.order-success', compact('order'));
    }
}
