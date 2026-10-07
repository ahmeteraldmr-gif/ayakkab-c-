<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Stok Bildirimi</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #F7F7F5; margin: 0; padding: 20px; color: #111111;">
    <div style="max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 16px; overflow: hidden; border: 1px solid #E5E7EB;">
        
        <div style="background-color: #111111; padding: 25px 30px; text-align: center;">
            <h1 style="color: #ffffff; margin: 0; font-size: 20px; letter-spacing: 1px;">YUSUF AKBOĞA</h1>
            <p style="color: #C79A58; margin: 5px 0 0 0; font-size: 11px; text-transform: uppercase; letter-spacing: 2px;">Ayakkabı / Footwear</p>
        </div>

        <div style="padding: 30px;">
            <div style="text-align: center; margin-bottom: 25px;">
                <span style="display: inline-block; background-color: #ECFDF5; color: #047857; font-weight: bold; font-size: 12px; padding: 6px 14px; rounded: 8px; border: 1px solid #A7F3D0; border-radius: 20px;">
                    🎉 Beklediğiniz Model Yeniden Stokta!
                </span>
            </div>

            <p style="font-size: 14px; line-height: 1.6; color: #374151;">
                Merhaba,
            </p>
            <p style="font-size: 14px; line-height: 1.6; color: #374151;">
                Daha önce stok bildirim talebi oluşturduğunuz <strong>{{ $product->name }}</strong>
                @if($size)
                    (<strong>{{ $size->size_number }} Numara</strong>)
                @endif
                modelimiz mağazamızda yeniden satışa açılmıştır!
            </p>

            <div style="text-align: center; margin: 25px 0;">
                <img src="{{ $product->primary_image_url }}" alt="{{ $product->name }}" style="max-width: 200px; max-height: 200px; object-fit: contain; border-radius: 12px; background: #F7F7F5; padding: 10px; border: 1px solid #E5E7EB;">
                <h3 style="margin: 15px 0 5px 0; font-size: 16px; color: #111827;">{{ $product->name }}</h3>
                <p style="font-size: 18px; font-weight: bold; color: #C79A58; margin: 0;">{{ $product->formatted_effective_price }}</p>
            </div>

            <div style="text-align: center; margin: 30px 0 20px 0;">
                <a href="{{ route('product.detail', $product->slug) }}" style="display: inline-block; background-color: #111111; color: #ffffff; text-decoration: none; padding: 14px 30px; border-radius: 12px; font-weight: bold; font-size: 13px; text-transform: uppercase; letter-spacing: 1px;">
                    Hemen İncele & Sipariş Ver &rarr;
                </a>
            </div>

            <p style="font-size: 11px; color: #9CA3AF; text-align: center; margin-top: 25px;">
                * Stoklar hızla tükenebileceğinden siparişinizi geciktirmeden vermenizi öneririz.
            </p>
        </div>

        <div style="background-color: #F9FAFB; padding: 20px 30px; border-top: 1px solid #E5E7EB; text-align: center; font-size: 11px; color: #6B7280;">
            <p style="margin: 0 0 5px 0;">Yusuf Akboğa Ayakkabı • Bağdat Caddesi No: 184/A Kadıköy / İstanbul</p>
            <p style="margin: 0;">Bu e-posta, web sitemiz üzerinden talep ettiğiniz stok bildirimi doğrultusunda gönderilmiştir.</p>
        </div>
    </div>
</body>
</html>
