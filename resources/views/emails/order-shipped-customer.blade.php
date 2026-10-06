<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Siparişiniz Kargoya Verildi</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f8fafc; color: #1e293b; margin: 0; padding: 20px;">
    <div style="max-width: 600px; margin: 0 auto; background: #ffffff; border-radius: 12px; overflow: hidden; border: 1px solid #e2e8f0;">
        <div style="background: #0f172a; padding: 24px; text-align: center;">
            <h1 style="color: #ffffff; margin: 0; font-size: 20px; font-weight: bold;">{{ $siteName }}</h1>
            <p style="color: #a855f7; margin: 6px 0 0; font-size: 13px; letter-spacing: 1px; font-weight: bold;">🚚 KARGOYA VERİLDİ</p>
        </div>
        <div style="padding: 24px;">
            <p style="font-size: 15px; margin-top: 0;">Sayın <strong>{{ $order->customer_name }}</strong>,</p>
            <p style="font-size: 14px; line-height: 1.6; color: #475569;">
                <strong>{{ $order->order_number }}</strong> numaralı siparişiniz kargo firmasına teslim edilmiştir.
            </p>

            <div style="background: #faf5ff; border: 1px solid #e9d5ff; border-radius: 8px; padding: 16px; margin: 20px 0;">
                <table style="width: 100%; font-size: 13px;">
                    <tr>
                        <td style="color: #6b21a8; font-weight: bold;">Kargo Firması:</td>
                        <td style="text-align: right; font-weight: bold; color: #0f172a;">{{ $order->shipping_company ?? 'Kargo Şirketi' }}</td>
                    </tr>
                    @if($order->tracking_number)
                        <tr>
                            <td style="color: #6b21a8; font-weight: bold;">Takip Numarası:</td>
                            <td style="text-align: right; font-weight: bold; color: #0f172a; font-family: monospace; font-size: 14px;">{{ $order->tracking_number }}</td>
                        </tr>
                    @endif
                </table>
            </div>

            @if($order->effective_tracking_url)
                <div style="text-align: center; margin: 28px 0 16px;">
                    <a href="{{ $order->effective_tracking_url }}" target="_blank" 
                       style="background: #9333ea; color: #ffffff; padding: 12px 28px; text-decoration: none; border-radius: 8px; font-weight: bold; font-size: 14px; display: inline-block;">
                        Kargomu Takip Et
                    </a>
                </div>
            @else
                <div style="text-align: center; margin: 28px 0 16px;">
                    <a href="{{ route('order.track.page') }}?order_number={{ $order->order_number }}&phone={{ urlencode($order->customer_phone) }}" 
                       style="background: #2563eb; color: #ffffff; padding: 12px 28px; text-decoration: none; border-radius: 8px; font-weight: bold; font-size: 14px; display: inline-block;">
                        Sipariş Durumunu Görüntüle
                    </a>
                </div>
            @endif

            <div style="background: #f8fafc; border-left: 3px solid #9333ea; padding: 12px 16px; margin-top: 24px; font-size: 12px; color: #64748b;">
                <strong style="color: #334155;">Teslimat Adresi:</strong><br>
                {{ $order->address }}<br>
                {{ $order->district }} / {{ $order->city }}
            </div>
        </div>
        <div style="background: #f1f5f9; padding: 16px; text-align: center; font-size: 12px; color: #64748b; border-top: 1px solid #e2e8f0;">
            © {{ date('Y') }} {{ $siteName }}. Tüm hakları saklıdır.
        </div>
    </div>
</body>
</html>
