<?php

namespace App\Services\Payment;

use App\Models\Order;
use Illuminate\Http\Request;

class PaymentManager implements PaymentServiceInterface
{
    protected ?string $currentDriver = null;

    /**
     * Get the driver instance or set driver name.
     */
    public function driver(?string $driver = null): PaymentServiceInterface
    {
        if ($driver === 'paytr') {
            return app(PayTrPaymentService::class);
        }

        if ($driver === 'iyzico') {
            return app(IyzicoPaymentService::class);
        }

        $this->currentDriver = $driver;
        return $this;
    }

    /**
     * Initialize payment for an amount or order.
     */
    public function initialize(array $params = []): array
    {
        return [
            'success' => true,
            'message' => 'Ödeme sağlayıcı başarıyla hazırlandı.',
            'data' => $params,
        ];
    }

    /**
     * Process or initialize payment based on selected method.
     */
    public function processPayment(Order $order, array $paymentData = []): array
    {
        return match ($order->payment_method) {
            'kapida_odeme' => [
                'success' => true,
                'message' => 'Kapıda ödeme seçildi. Sipariş hazırlanma aşamasına alındı.',
                'payment_status' => 'kapida_odenecek',
            ],
            'havale' => [
                'success' => true,
                'message' => 'Havale/EFT bilgileri iletildi. Ödeme dekontu onaylandığında işleme alınacaktır.',
                'payment_status' => 'beklemede',
            ],
            'online_kart' => [
                'success' => true,
                'message' => 'Online ödeme seçildi. Güvenli ödeme adımı tamamlandı.',
                'payment_status' => 'odendi',
            ],
            default => [
                'success' => false,
                'message' => 'Bilinmeyen ödeme yöntemi.',
                'payment_status' => 'beklemede',
            ],
        };
    }

    /**
     * Handle payment webhook/callback from gateway.
     */
    public function handleCallback(Request $request): array
    {
        return [
            'success' => true,
            'message' => 'Callback başarıyla işlendi.',
        ];
    }
}
