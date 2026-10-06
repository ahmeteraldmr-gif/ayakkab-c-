<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderTrackController extends Controller
{
    /**
     * Show tracking search form and optional pre-filled query result
     */
    public function index(Request $request): View
    {
        $order = null;
        $orderNumber = $request->query('order_number');
        $phone = $request->query('phone');

        if (!empty($orderNumber) && !empty($phone)) {
            $order = $this->lookupOrder((string) $orderNumber, (string) $phone);
            if (!$order) {
                session()->flash('track_error', 'Girilen sipariş numarası ve telefon bilgisine ait sipariş bulunamadı.');
            }
        }

        return view('pages.order-track', [
            'order' => $order,
            'searchedOrderNumber' => $orderNumber,
            'searchedPhone' => $phone,
        ]);
    }

    /**
     * Handle tracking form POST query
     */
    public function track(Request $request): View
    {
        $validated = $request->validate([
            'order_number' => 'required|string|min:5|max:50',
            'phone' => 'required|string|min:7|max:25',
        ], [
            'order_number.required' => 'Lütfen sipariş numaranızı giriniz.',
            'phone.required' => 'Lütfen siparişte kullandığınız telefon numarasını giriniz.',
        ]);

        $order = $this->lookupOrder($validated['order_number'], $validated['phone']);

        if (!$order) {
            session()->flash('error', 'Girilen sipariş numarası ve telefon bilgisine ait sipariş bulunamadı. Lütfen bilgilerinizi kontrol ediniz.');
            return view('pages.order-track', [
                'order' => null,
                'searchedOrderNumber' => $validated['order_number'],
                'searchedPhone' => $validated['phone'],
                'trackError' => 'Girilen sipariş numarası ve telefon bilgisine ait sipariş bulunamadı. Lütfen bilgilerinizi kontrol ediniz.',
            ]);
        }

        return view('pages.order-track', [
            'order' => $order,
            'searchedOrderNumber' => $validated['order_number'],
            'searchedPhone' => $validated['phone'],
            'trackError' => null,
        ]);
    }

    /**
     * Clean and normalize phone numbers for secure comparison.
     */
    protected function normalizePhone(string $phone): string
    {
        // Remove all non-digits
        $digits = preg_replace('/\D+/', '', $phone) ?? '';

        // If starts with 90 and length > 10, strip leading 90
        if (str_starts_with($digits, '90') && strlen($digits) === 12) {
            $digits = substr($digits, 2);
        }
        // If starts with 0 and length is 11, strip leading 0
        if (str_starts_with($digits, '0') && strlen($digits) === 11) {
            $digits = substr($digits, 1);
        }

        return $digits;
    }

    /**
     * Find order strictly matching both order number and phone number.
     */
    protected function lookupOrder(string $orderNumber, string $phone): ?Order
    {
        $order = Order::with('items')
                      ->where('order_number', trim($orderNumber))
                      ->first();

        if (!$order) {
            return null;
        }

        $normalizedInputPhone = $this->normalizePhone($phone);
        $normalizedOrderPhone = $this->normalizePhone($order->customer_phone);

        if (empty($normalizedInputPhone) || $normalizedInputPhone !== $normalizedOrderPhone) {
            return null;
        }

        return $order;
    }
}
