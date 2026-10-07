<?php

namespace App\Services\Payment;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PayTrPaymentService implements PaymentServiceInterface
{
    protected ?string $merchantId;
    protected ?string $merchantKey;
    protected ?string $merchantSalt;
    protected bool $testMode;

    public function __construct()
    {
        $this->merchantId = config('services.paytr.merchant_id', env('PAYTR_MERCHANT_ID'));
        $this->merchantKey = config('services.paytr.merchant_key', env('PAYTR_MERCHANT_KEY'));
        $this->merchantSalt = config('services.paytr.merchant_salt', env('PAYTR_MERCHANT_SALT'));
        $this->testMode = (bool) config('services.paytr.test_mode', env('PAYTR_TEST_MODE', true));
    }

    /**
     * Generate PayTR iframe token / 3D Secure initialization payload.
     */
    public function processPayment(Order $order, array $paymentData = []): array
    {
        try {
            $userIp = request()->ip() ?? '127.0.0.1';
            $merchantOid = $order->order_number;
            $email = $order->customer_email;
            $paymentAmount = (int) round($order->total_amount * 100); // Kuruş cinsinden

            $userBasket = [];
            foreach ($order->items as $item) {
                $userBasket[] = [$item->product_name . ' (' . $item->size . ')', number_format($item->price, 2, '.', ''), $item->quantity];
            }
            $userBasketJson = base64_encode(json_encode($userBasket));

            $hashStr = $this->merchantId . $userIp . $merchantOid . $email . $paymentAmount . $userBasketJson . '0' . '0' . 'TL' . ($this->testMode ? '1' : '0');
            $paytrToken = base64_encode(hash_hmac('sha256', $hashStr . $this->merchantSalt, $this->merchantKey ?? '', true));

            return [
                'success' => true,
                'gateway' => 'paytr',
                'merchant_oid' => $merchantOid,
                'token' => $paytrToken,
                'payment_status' => 'beklemede',
                'message' => 'PayTR 3D Secure ödeme oturumu hazırlandı.',
                'iframe_url' => 'https://www.paytr.com/odeme/guvenli/' . $paytrToken,
            ];
        } catch (\Throwable $e) {
            Log::error('PayTR Initialization Error: ' . $e->getMessage());
            return [
                'success' => false,
                'payment_status' => 'hata',
                'message' => 'PayTR ödeme başlatılamadı: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Handle PayTR IPN webhook/callback.
     */
    public function handleCallback(Request $request): array
    {
        $post = $request->all();

        if (!isset($post['merchant_oid'], $post['status'], $post['total_amount'], $post['hash'])) {
            return ['success' => false, 'message' => 'Geçersiz callback parametreleri'];
        }

        // Validate hash
        $hashStr = $post['merchant_oid'] . $this->merchantSalt . $post['status'] . $post['total_amount'];
        $calculatedHash = base64_encode(hash_hmac('sha256', $hashStr, $this->merchantKey ?? '', true));

        if ($calculatedHash !== $post['hash']) {
            Log::warning('PayTR Hash verification failed for OID: ' . $post['merchant_oid']);
            return ['success' => false, 'message' => 'Hash doğrulaması başarısız'];
        }

        $order = Order::where('order_number', $post['merchant_oid'])->first();
        if (!$order) {
            return ['success' => false, 'message' => 'Sipariş bulunamadı'];
        }

        // Validate amount to prevent price tampering
        $expectedKurus = (int) round($order->total_amount * 100);
        $receivedKurus = (int) $post['total_amount'];
        if ($expectedKurus !== $receivedKurus) {
            Log::warning("PayTR Amount mismatch for Order #{$order->order_number}: Expected {$expectedKurus}, Received {$receivedKurus}");
            return ['success' => false, 'message' => 'Ödeme tutarı sipariş tutarı ile uyuşmuyor'];
        }

        // Idempotency: If already marked as paid, return OK without re-processing
        if ($order->payment_status === 'odendi') {
            return ['success' => true, 'order' => $order, 'message' => 'OK', 'idempotent' => true];
        }

        if ($post['status'] === 'success') {
            $order->update([
                'payment_status' => 'odendi',
                'status' => 'hazirlaniyor',
            ]);
            return ['success' => true, 'order' => $order, 'message' => 'OK'];
        }

        $order->update([
            'payment_status' => 'iptal_edildi',
            'status' => 'iptal_edildi',
            'admin_notes' => 'PayTR Ödeme Başarısız: ' . ($post['failed_reason_msg'] ?? 'Bilinmeyen hata'),
        ]);

        return ['success' => false, 'order' => $order, 'message' => 'Ödeme başarısız'];
    }
}
