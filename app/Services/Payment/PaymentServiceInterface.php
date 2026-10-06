<?php

namespace App\Services\Payment;

use App\Models\Order;
use Illuminate\Http\Request;

interface PaymentServiceInterface
{
    /**
     * Process or initialize payment for an order.
     *
     * @return array{success: bool, message: string, redirect_url?: string, payment_status: string}
     */
    public function processPayment(Order $order, array $paymentData = []): array;

    /**
     * Handle payment webhook/callback from gateway.
     */
    public function handleCallback(Request $request): array;
}
