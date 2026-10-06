<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Yeni Sipariş Bildirimi</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f8fafc; color: #1e293b; margin: 0; padding: 20px;">
    <div style="max-width: 600px; margin: 0 auto; background: #ffffff; border-radius: 12px; overflow: hidden; border: 1px solid #e2e8f0;">
        <div style="background: #2563eb; padding: 20px; text-align: center;">
            <h1 style="color: #ffffff; margin: 0; font-size: 18px;">Yeni Sipariş Alındı!</h1>
            <p style="color: #dbeafe; margin: 4px 0 0; font-size: 13px;">{{ $order->order_number }}</p>
        </div>
        <div style="padding: 24px;">
            <p style="font-size: 14px; margin-top: 0;">Mağazanızdan yeni bir sipariş verildi. Detaylar:</p>
            
            <div style="background: #f1f5f9; border-radius: 8px; padding: 16px; margin-bottom: 20px; font-size: 13px;">
                <p style="margin: 0 0 6px;"><strong>Müşteri:</strong> {{ $order->customer_name }}</p>
                <p style="margin: 0 0 6px;"><strong>Telefon:</strong> {{ $order->customer_phone }}</p>
                <p style="margin: 0 0 6px;"><strong>E-Posta:</strong> {{ $order->customer_email ?? 'Belirtilmedi' }}</p>
                <p style="margin: 0 0 6px;"><strong>Şehir:</strong> {{ $order->district }} / {{ $order->city }}</p>
                <p style="margin: 0;"><strong>Toplam Tutar:</strong> <span style="color: #2563eb; font-weight: bold;">{{ $order->formatted_total }}</span></p>
            </div>

            <div style="text-align: center; margin: 24px 0;">
                <a href="{{ route('admin.orders.show', $order->id) }}" 
                   style="background: #0f172a; color: #ffffff; padding: 10px 24px; text-decoration: none; border-radius: 6px; font-weight: bold; font-size: 13px; display: inline-block;">
                    Siparişi Yönetim Panelinde Gör
                </a>
            </div>
        </div>
    </div>
</body>
</html>
