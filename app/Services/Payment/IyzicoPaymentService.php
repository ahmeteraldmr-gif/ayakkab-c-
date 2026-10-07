<?php

namespace App\Services\Payment;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class IyzicoPaymentService implements PaymentServiceInterface
{
    protected ?string $apiKey;
    protected ?string $secretKey;
    protected ?string $baseUrl;

    public function __construct()
    {
        $this->apiKey = config('services.iyzico.api_key', env('IYZICO_API_KEY'));
        $this->secretKey = config('services.iyzico.secret_key', env('IYZICO_SECRET_KEY'));
        $this->baseUrl = config('services.iyzico.base_url', env('IYZICO_BASE_URL', 'https://sandbox-api.iyzipay.com'));
    }

    /**
     * Prepare Checkout Form initialize request for iyzico.
     */
    public function processPayment(Order $order, array $paymentData = []): array
    {
        try {
            $buyer = [
                'id' => (string) ($order->user_id ?? $order->customer_phone),
                'name' => explode(' ', $order->customer_name)[0] ?? 'Müşteri',
                'surname' => explode(' ', $order->customer_name)[1] ?? 'Soyisim',
                'gsmNumber' => $order->customer_phone,
                'email' => $order->customer_email,
                'identityNumber' => '11111111110',
                'registrationAddress' => $order->customer_address,
                'ip' => request()->ip() ?? '127.0.0.1',
                'city' => $order->customer_city,
                'country' => 'Turkey',
            ];

            $basketItems = [];
            foreach ($order->items as $item) {
                $basketItems[] = [
                    'id' => (string) $item->id,
                    'name' => $item->product_name . ' (' . $item->size . ')',
                    'category1' => 'Ayakkabı',
                    'itemType' => 'PHYSICAL',
                    'price' => number_format($item->price, 2, '.', ''),
                ];
            }

            return [
                'success' => true,
                'gateway' => 'iyzico',
                'conversation_id' => $order->order_number,
                'price' => number_format($order->total_amount, 2, '.', ''),
                'payment_status' => 'beklemede',
                'message' => 'iyzico Checkout formu hazırlandı.',
                'buyer' => $buyer,
                'basket_items' => $basketItems,
                'callback_url' => route('payment.callback', ['driver' => 'iyzico']),
            ];
        } catch (\Throwable $e) {
            Log::error('iyzico Initialization Error: ' . $e->getMessage());
            return [
                'success' => false,
                'payment_status' => 'hata',
                'message' => 'iyzico ödeme başlatılamadı: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Handle iyzico 3D Secure / Checkout Form callback.
     */
    public function handleCallback(Request $request): array
    {
        $token = $request->input('token');
        if (!$token) {
            return ['success' => false, 'message' => 'Token bulunamadı.'];
        }

        // Simulating/processing response verification
        $status = $request->input('status', 'success');
        $orderNumber = $request->input('conversationId');

        $order = $orderNumber ? Order::where('order_number', $orderNumber)->first() : null;

        if ($order && $order->payment_status === 'odendi') {
            return ['success' => true, 'order' => $order, 'message' => 'Ödeme zaten işlenmiş.', 'idempotent' => true];
        }

        if ($order && $status === 'success') {
            $order->update([
                'payment_status' => 'odendi',
                'status' => 'hazirlaniyor',
            ]);
            return ['success' => true, 'order' => $order, 'message' => 'Ödeme başarıyla tamamlandı.'];
        }

        if ($order) {
            $order->update([
                'payment_status' => 'iptal_edildi',
                'status' => 'iptal_edildi',
            ]);
        }

        return ['success' => false, 'order' => $order, 'message' => 'Ödeme onaylanamadı.'];
    }
}
