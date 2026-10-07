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

        $savedAddresses = auth()->check() ? auth()->user()->addresses()->orderByDesc('is_default')->get() : collect();

        return view('pages.checkout', compact('cartSummary', 'savedAddresses'));
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
            'pre_info_approval' => 'required|accepted',
            'distance_selling_approval' => 'required|accepted',
            'save_address' => 'nullable|boolean',
            'address_title' => 'nullable|string|max:100',
        ], [
            'customer_name.required' => 'Lütfen adınızı ve soyadınızı giriniz.',
            'customer_phone.required' => 'Lütfen geçerli bir telefon numarası giriniz.',
            'city.required' => 'Lütfen il seçiniz/yazınız.',
            'district.required' => 'Lütfen ilçe bilgisini giriniz.',
            'address.required' => 'Lütfen açık teslimat adresinizi giriniz.',
            'pre_info_approval.accepted' => 'Ön Bilgilendirme Koşullarını onaylamanız gerekmektedir.',
            'distance_selling_approval.accepted' => 'Mesafeli Satış Sözleşmesini onaylamanız gerekmektedir.',
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
        // 2. Logged-in owner user (user_id match)
        // 3. Session created this specific order (matching order token)
        // 4. Valid access_token provided in request query
        $isAdmin = auth()->check() && auth()->user()->isAdmin();
        $isUserOwner = auth()->check() && $order->user_id === auth()->id();
        $isSessionOwner = session('authorized_order_' . $orderNumber) === $order->access_token;
        $hasValidToken = !empty($order->access_token) && ($request->query('token') === $order->access_token);

        if (!$isAdmin && !$isUserOwner && !$isSessionOwner && !$hasValidToken) {
            return redirect()->route('home')->with('error', 'Bu sipariş bilgilerini görüntüleme yetkiniz bulunmamaktadır.');
        }

        return view('pages.order-success', compact('order'));
    }

    /**
     * Handle generic payment gateway callback
     */
    public function paymentCallback(Request $request, string $driver = 'paytr'): RedirectResponse
    {
        /** @var \App\Services\Payment\PaymentManager $paymentManager */
        $paymentManager = app(\App\Services\Payment\PaymentManager::class);
        $result = $paymentManager->driver($driver)->handleCallback($request);

        if ($result['success'] && isset($result['order'])) {
            return redirect()->route('order.success', [
                'orderNumber' => $result['order']->order_number,
                'token' => $result['order']->access_token,
            ])->with('success', 'Ödemeniz başarıyla alındı.');
        }

        return redirect()->route('home')->with('error', $result['message'] ?? 'Ödeme işlemi onaylanamadı.');
    }
}
