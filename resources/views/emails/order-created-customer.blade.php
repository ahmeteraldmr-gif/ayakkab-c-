<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Siparişiniz Alındı</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f8fafc; color: #1e293b; margin: 0; padding: 20px;">
    <div style="max-width: 600px; margin: 0 auto; background: #ffffff; border-radius: 12px; overflow: hidden; border: 1px solid #e2e8f0;">
        
        <!-- Header -->
        <div style="background: #0f172a; padding: 24px; text-align: center;">
            <h1 style="color: #ffffff; margin: 0; font-size: 20px; font-weight: bold;">{{ $siteName }}</h1>
            <p style="color: #60a5fa; margin: 6px 0 0; font-size: 13px; letter-spacing: 1px;">SİPARİŞ ONAYI</p>
        </div>

        <!-- Body -->
        <div style="padding: 24px;">
            <p style="font-size: 15px; margin-top: 0;">Sayın <strong>{{ $order->customer_name }}</strong>,</p>
            <p style="font-size: 14px; line-height: 1.6; color: #475569;">
                Siparişiniz başarıyla alınmıştır. En kısa sürede hazırlanıp kargoya teslim edilecektir.
            </p>

            <!-- Order Box -->
            <div style="background: #f1f5f9; border-radius: 8px; padding: 16px; margin: 20px 0;">
                <table style="width: 100%; font-size: 13px;">
                    <tr>
                        <td style="color: #64748b;">Sipariş Numarası:</td>
                        <td style="text-align: right; font-weight: bold; color: #0f172a;">{{ $order->order_number }}</td>
                    </tr>
                    <tr>
                        <td style="color: #64748b;">Sipariş Tarihi:</td>
                        <td style="text-align: right; font-weight: bold; color: #0f172a;">{{ $order->created_at->format('d.m.Y H:i') }}</td>
                    </tr>
                    <tr>
                        <td style="color: #64748b;">Ödeme Yöntemi:</td>
                        <td style="text-align: right; font-weight: bold; color: #0f172a;">{{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}</td>
                    </tr>
                </table>
            </div>

            <!-- Items -->
            <h3 style="font-size: 15px; margin: 20px 0 10px; color: #0f172a; border-bottom: 2px solid #e2e8f0; padding-bottom: 6px;">Sipariş Edilen Ürünler</h3>
            <table style="width: 100%; border-collapse: collapse; font-size: 13px; margin-bottom: 20px;">
                @foreach($order->items as $item)
                    <tr style="border-bottom: 1px solid #f1f5f9;">
                        <td style="padding: 10px 0;">
                            <strong>{{ $item->product_name }}</strong>
                            <div style="color: #64748b; font-size: 12px;">Numara: {{ $item->size_number }} | Adet: {{ $item->quantity }}</div>
                        </td>
                        <td style="text-align: right; padding: 10px 0; font-weight: bold;">
                            {{ number_format((float)$item->total, 2, ',', '.') }} ₺
                        </td>
                    </tr>
                @endforeach
            </table>

            <!-- Totals -->
            <table style="width: 100%; font-size: 13px; margin-bottom: 24px;">
                <tr>
                    <td style="color: #64748b;">Ara Toplam:</td>
                    <td style="text-align: right;">{{ number_format((float)$order->subtotal, 2, ',', '.') }} ₺</td>
                </tr>
                @if($order->discount_amount > 0)
                    <tr>
                        <td style="color: #16a34a;">İndirim ({{ $order->coupon_code }}):</td>
                        <td style="text-align: right; color: #16a34a;">-{{ number_format((float)$order->discount_amount, 2, ',', '.') }} ₺</td>
                    </tr>
                @endif
                <tr>
                    <td style="color: #64748b;">Kargo:</td>
                    <td style="text-align: right;">{{ $order->shipping_cost == 0 ? 'Ücretsiz' : number_format((float)$order->shipping_cost, 2, ',', '.') . ' ₺' }}</td>
                </tr>
                <tr style="font-size: 15px; font-weight: bold; border-top: 2px solid #cbd5e1;">
                    <td style="padding-top: 8px; color: #0f172a;">Toplam Tutar:</td>
                    <td style="padding-top: 8px; text-align: right; color: #2563eb;">{{ $order->formatted_total }}</td>
                </tr>
            </table>

            <!-- CTA Button -->
            <div style="text-align: center; margin: 30px 0 10px;">
                <a href="{{ route('order.track.index') }}?order_number={{ $order->order_number }}&phone={{ urlencode($order->customer_phone) }}" 
                    style="background: #2563eb; color: #ffffff; padding: 12px 28px; text-decoration: none; border-radius: 8px; font-weight: bold; font-size: 14px; display: inline-block;">
                    Siparişimi Takip Et
                </a>
            </div>

            <!-- Delivery Address -->
            <div style="background: #f8fafc; border-left: 3px solid #2563eb; padding: 12px 16px; margin-top: 24px; font-size: 12px; color: #64748b;">
                <strong style="color: #334155;">Teslimat Adresi:</strong><br>
                {{ $order->address }}<br>
                {{ $order->district }} / {{ $order->city }}
            </div>
        </div>

        <!-- Footer -->
        <div style="background: #f1f5f9; padding: 16px; text-align: center; font-size: 12px; color: #64748b; border-top: 1px solid #e2e8f0;">
            Sorularınız için bizimle iletişime geçebilirsiniz.<br>
            © {{ date('Y') }} {{ $siteName }}. Tüm hakları saklıdır.
        </div>

    </div>
</body>
</html>
