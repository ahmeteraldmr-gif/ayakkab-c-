<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Campaign;
use App\Models\Category;
use App\Models\ContactMessage;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductSizeStock;
use App\Models\Setting;
use App\Models\Size;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Create Admin User
        $admin = User::firstOrCreate(
            ['email' => 'admin@velora.com'],
            [
                'name' => 'VELORA Admin',
                'password' => Hash::make('admin123'),
                'is_admin' => true,
                'phone' => '+90 532 123 45 67',
            ]
        );

        // Demo Normal User
        User::firstOrCreate(
            ['email' => 'demo@musteri.com'],
            [
                'name' => 'Can Demir',
                'password' => Hash::make('password123'),
                'is_admin' => false,
                'phone' => '+90 533 987 65 43',
            ]
        );

        // 2. Default Settings
        $defaultSettings = [
            'site_name' => 'VELORA',
            'site_title' => 'VELORA | Tarzın Adımlarında',
            'site_description' => 'VELORA kalitesiyle en yeni ve tarz lüks ayakkabı modellerini keşfedin. Orijinal sneaker, klasik ve günlük ayakkabılar.',
            'site_phone' => '+90 (216) 450 10 20',
            'site_whatsapp' => '905321234567',
            'site_instagram' => 'https://instagram.com/velora_official',
            'site_email' => 'info@velora.com',
            'site_address' => 'Bağdat Caddesi No: 184/A Kadıköy / İstanbul',
            'site_hours' => 'Pazartesi - Cumartesi: 09:30 - 20:30 | Pazar: 11:00 - 19:00',
            'about_mini' => 'VELORA, adımlarınıza prestij, estetik ve konfor katmak amacıyla kurulmuş seçkin ve modern bir ayakkabı markasıdır. En trend sneaker modellerinden el işçiliği klasik tasarımlara kadar en seçkin koleksiyonları müşterilerimizle buluşturuyoruz.',
            'about_full' => 'VELORA, ayakkabı sektöründe kalite, zarafet ve estetiği bir araya getirme vizyonuyla yola çıkmıştır. Müşteri memnuniyetini en üst düzeyde tutarak, dünya standartlarında orijinal modelleri ve seçkin koleksiyonları sunmaktayız.',
            'free_shipping_threshold' => '1500',
            'shipping_cost' => '89.90',
            'facebook_url' => 'https://facebook.com',
            'twitter_url' => 'https://twitter.com',
        ];

        foreach ($defaultSettings as $key => $val) {
            Setting::set($key, $val);
        }

        // 3. Shoe Sizes
        $shoeSizes = [36, 37, 38, 39, 40, 41, 42, 43, 44, 45];
        $sizeModels = [];
        foreach ($shoeSizes as $idx => $sizeNum) {
            $sizeModels[$sizeNum] = Size::firstOrCreate(
                ['size_number' => (string) $sizeNum],
                ['sort_order' => $idx + 1]
            );
        }

        // 4. Categories
        $categoriesData = [
            [
                'name' => 'Erkek',
                'slug' => 'erkek',
                'description' => 'Modern ve güçlü erkek sneaker & ayakkabı koleksiyonu',
                'image' => 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=800&auto=format&fit=crop&q=80',
                'sort_order' => 1,
            ],
            [
                'name' => 'Kadın',
                'slug' => 'kadin',
                'description' => 'Şık, konforlu ve dinamik kadın ayakkabı modelleri',
                'image' => 'https://images.unsplash.com/photo-1543163521-1bf539c55dd2?w=800&auto=format&fit=crop&q=80',
                'sort_order' => 2,
            ],
            [
                'name' => 'Spor',
                'slug' => 'spor',
                'description' => 'Yüksek performanslı koşu, antrenman ve basketbol ayakkabıları',
                'image' => 'https://images.unsplash.com/photo-1552346154-21d32810aba3?w=800&auto=format&fit=crop&q=80',
                'sort_order' => 3,
            ],
            [
                'name' => 'Günlük',
                'slug' => 'gunluk',
                'description' => 'Gün boyu üst düzey rahatlık sunan casual modeller',
                'image' => 'https://images.unsplash.com/photo-1525966222134-fcfa99b8ae77?w=800&auto=format&fit=crop&q=80',
                'sort_order' => 4,
            ],
            [
                'name' => 'Klasik',
                'slug' => 'klasik',
                'description' => 'Özel davetler ve iş hayatı için el yapımı deri klasik ayakkabılar',
                'image' => 'https://images.unsplash.com/photo-1614252235316-8c857d38b5f4?w=800&auto=format&fit=crop&q=80',
                'sort_order' => 5,
            ],
        ];

        $categories = [];
        foreach ($categoriesData as $cData) {
            $categories[$cData['slug']] = Category::firstOrCreate(
                ['slug' => $cData['slug']],
                $cData
            );
        }

        // 5. Brands
        $brandsData = [
            ['name' => 'Nike', 'slug' => 'nike', 'description' => 'Dünyaca ünlü spor ve sokak modası devi'],
            ['name' => 'Adidas', 'slug' => 'adidas', 'description' => 'Konfor ve sokak stilinin ikonik buluşması'],
            ['name' => 'Puma', 'slug' => 'puma', 'description' => 'Hızlı, dinamik ve enerjik sneaker modelleri'],
            ['name' => 'New Balance', 'slug' => 'new-balance', 'description' => 'Efsanevi retro tasarım ve ergonomi'],
            ['name' => 'VELORA Signature', 'slug' => 'velora-signature', 'description' => 'VELORA özel üretim hakiki deri ve lüks sneaker koleksiyonu'],
        ];

        $brands = [];
        foreach ($brandsData as $bData) {
            $brands[$bData['slug']] = Brand::firstOrCreate(
                ['slug' => $bData['slug']],
                $bData
            );
        }

        // 6. Products
        $productsData = [
            [
                'name' => 'Nike Air Max 270 Black Gold',
                'category_slug' => 'spor',
                'brand_slug' => 'nike',
                'sku' => 'YSA-NK-270-BG',
                'price' => 5499.00,
                'discount_price' => 4699.00,
                'gender' => 'erkek',
                'color' => 'Siyah & Altın',
                'color_code' => '#1A1A1A',
                'is_featured' => true,
                'is_new' => true,
                'view_count' => 142,
                'short_description' => 'Göz alıcı topuk Max Air birimi ile olağanüstü yastıklama ve siyah-altın modern uyum.',
                'description' => 'Nike Air Max 270, ikonik Air Max modellerinden ilham alınarak günümüz sokak modası için tasarlandı. Büyük hacimli Air yastıklaması her adımda yumuşaklık sunarken, nefes alabilir file sayası ayağınızı kusursuz sarar.',
                'images' => [
                    'https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=1000&auto=format&fit=crop&q=80',
                    'https://images.unsplash.com/photo-1552346154-21d32810aba3?w=1000&auto=format&fit=crop&q=80',
                ],
                'stocks' => [40 => 4, 41 => 6, 42 => 2, 43 => 5, 44 => 3, 45 => 1],
            ],
            [
                'name' => 'Adidas Originals Forum Low White',
                'category_slug' => 'gunluk',
                'brand_slug' => 'adidas',
                'sku' => 'YSA-AD-FORUM-W',
                'price' => 4299.00,
                'discount_price' => null,
                'gender' => 'unisex',
                'color' => 'Beyaz',
                'color_code' => '#FFFFFF',
                'is_featured' => true,
                'is_new' => true,
                'view_count' => 98,
                'short_description' => '80\'lerin basketbol ruhunu günümüz sokak stiliyle birleştiren ikonik deri sneaker.',
                'description' => 'Adidas Forum Low, premium deri yüzeyi ve karakteristik bilek bandı detayıyla zamansız bir stil sunar. Hem jean hem şort kombinlerinizin vazgeçilmezi olacak.',
                'images' => [
                    'https://images.unsplash.com/photo-1608231387042-66d1773070a5?w=1000&auto=format&fit=crop&q=80',
                    'https://images.unsplash.com/photo-1595950653106-6c9ebd614d3a?w=1000&auto=format&fit=crop&q=80',
                ],
                'stocks' => [38 => 3, 39 => 5, 40 => 7, 41 => 4, 42 => 6, 43 => 2],
            ],
            [
                'name' => 'New Balance 550 Vintage Green',
                'category_slug' => 'gunluk',
                'brand_slug' => 'new-balance',
                'sku' => 'YSA-NB-550-VG',
                'price' => 6199.00,
                'discount_price' => 5499.00,
                'gender' => 'unisex',
                'color' => 'Beyaz & Yeşil',
                'color_code' => '#2E5A36',
                'is_featured' => true,
                'is_new' => true,
                'view_count' => 210,
                'short_description' => 'Retro basketbol mirasının en popüler silueti; retro deri dokusu ve yeşil vurgular.',
                'description' => '1989 yılında profesyonel basketbol parkelerine damga vuran New Balance 550, bugün sokak modasının en çok aranan sneaker modellerinden biridir. Yüksek kaliteli deri ve dayanıklı kauçuk taban.',
                'images' => [
                    'https://images.unsplash.com/photo-1551107696-a4b0c5a0d9a2?w=1000&auto=format&fit=crop&q=80',
                    'https://images.unsplash.com/photo-1539185441755-769473a23570?w=1000&auto=format&fit=crop&q=80',
                ],
                'stocks' => [39 => 2, 40 => 0, 41 => 5, 42 => 3, 43 => 2, 44 => 1],
            ],
            [
                'name' => 'Puma RS-X Triple Black Chunky',
                'category_slug' => 'spor',
                'brand_slug' => 'puma',
                'sku' => 'YSA-PM-RSX-TB',
                'price' => 3899.00,
                'discount_price' => 3199.00,
                'gender' => 'erkek',
                'color' => 'Tam Siyah',
                'color_code' => '#0A0A0A',
                'is_featured' => false,
                'is_new' => true,
                'view_count' => 74,
                'short_description' => 'Cesur hatlar ve fütüristik kalın taban yapısıyla maksimum yastıklama.',
                'description' => 'Puma RS-X serisi, Running System teknolojisi ile retro stili geleceğin çizgileriyle harmanlar. Çok katmanlı materyal yapısı ve dolgulu bilek kısmı gün boyu konfor sağlar.',
                'images' => [
                    'https://images.unsplash.com/photo-1607522370275-f14206abe5d3?w=1000&auto=format&fit=crop&q=80',
                ],
                'stocks' => [40 => 3, 41 => 4, 42 => 6, 43 => 3, 44 => 2],
            ],
            [
                'name' => 'Nike Dunk Low Retro Panda',
                'category_slug' => 'gunluk',
                'brand_slug' => 'nike',
                'sku' => 'YSA-NK-DUNK-PND',
                'price' => 5899.00,
                'discount_price' => null,
                'gender' => 'unisex',
                'color' => 'Siyah & Beyaz',
                'color_code' => '#000000',
                'is_featured' => true,
                'is_new' => false,
                'view_count' => 350,
                'short_description' => 'Sokakların en çok tercih edilen ikonik siyah-beyaz renk bloğu.',
                'description' => 'Nike Dunk Low, vintage tasarımı ve her kıyafetle kusursuz uyum sağlayan monokrom renkleriyle gardırobunuzun demirbaşı. Hafif yastıklamalı orta taban.',
                'images' => [
                    'https://images.unsplash.com/photo-1595950653106-6c9ebd614d3a?w=1000&auto=format&fit=crop&q=80',
                    'https://images.unsplash.com/photo-1584735935682-2f2b69dff9d2?w=1000&auto=format&fit=crop&q=80',
                ],
                'stocks' => [37 => 2, 38 => 4, 39 => 3, 40 => 5, 41 => 6, 42 => 2, 43 => 0],
            ],
            [
                'name' => 'YSA Signature Oxford Hakiki Deri',
                'category_slug' => 'klasik',
                'brand_slug' => 'ysa-signature',
                'sku' => 'YSA-OXF-DERI-BN',
                'price' => 4799.00,
                'discount_price' => 3999.00,
                'gender' => 'erkek',
                'color' => 'Koyu Kahve',
                'color_code' => '#3D2314',
                'is_featured' => true,
                'is_new' => false,
                'view_count' => 115,
                'short_description' => 'VELORA atölyesinde el işçiliğiyle üretilmiş %100 dana derisi lüks klasik ayakkabı.',
                'description' => 'İş görüşmeleri, resmi davetler ve şık takım elbiseler için özel olarak tasarlanan VELORA Signature Oxford modeli, nefes alan iç astarı ve özel kösele tabanı ile zarafetin doruk noktasıdır.',
                'images' => [
                    'https://images.unsplash.com/photo-1614252235316-8c857d38b5f4?w=1000&auto=format&fit=crop&q=80',
                    'https://images.unsplash.com/photo-1533867617858-e7b97e060509?w=1000&auto=format&fit=crop&q=80',
                ],
                'stocks' => [40 => 3, 41 => 5, 42 => 4, 43 => 3, 44 => 2],
            ],
            [
                'name' => 'Adidas Ultraboost Light Core Black',
                'category_slug' => 'spor',
                'brand_slug' => 'adidas',
                'sku' => 'YSA-AD-UB-LIGHT',
                'price' => 7299.00,
                'discount_price' => 6499.00,
                'gender' => 'erkek',
                'color' => 'Siyah',
                'color_code' => '#111111',
                'is_featured' => true,
                'is_new' => true,
                'view_count' => 89,
                'short_description' => 'En hafif BOOST kapsülleriyle her adımda benzersiz enerji geri dönüşümü.',
                'description' => 'Primeknit+ sayasıyla ayağı çorap gibi saran Adidas Ultraboost Light, uzun yürüyüşlerde ve yoğun koşularda eşsiz bir konfor deneyimi yaşatır.',
                'images' => [
                    'https://images.unsplash.com/photo-1587563871167-1ee9c731aefb?w=1000&auto=format&fit=crop&q=80',
                ],
                'stocks' => [41 => 3, 42 => 5, 43 => 4, 44 => 2, 45 => 1],
            ],
            [
                'name' => 'Nike Air Force 1 \'07 Triple White',
                'category_slug' => 'gunluk',
                'brand_slug' => 'nike',
                'sku' => 'YSA-NK-AF1-WHT',
                'price' => 4999.00,
                'discount_price' => null,
                'gender' => 'unisex',
                'color' => 'Tam Beyaz',
                'color_code' => '#FFFFFF',
                'is_featured' => true,
                'is_new' => false,
                'view_count' => 420,
                'short_description' => 'Efsane devam ediyor; canlı deri kaplamalar ve ikonik Air yastıklama.',
                'description' => 'Nike Air Force 1 \'07, 1982\'den bu yana sokak stilinin en güçlü simgesi olmaya devam ediyor. Pürüzsüz deri yüzeyi ve zamansız beyaz tasarımıyla her kombine uyum sağlar.',
                'images' => [
                    'https://images.unsplash.com/photo-1595950653106-6c9ebd614d3a?w=1000&auto=format&fit=crop&q=80',
                    'https://images.unsplash.com/photo-1608231387042-66d1773070a5?w=1000&auto=format&fit=crop&q=80',
                ],
                'stocks' => [36 => 2, 37 => 3, 38 => 4, 39 => 5, 40 => 6, 41 => 8, 42 => 5, 43 => 3, 44 => 2],
            ],
            [
                'name' => 'New Balance 9060 Sea Salt Beige',
                'category_slug' => 'gunluk',
                'brand_slug' => 'new-balance',
                'sku' => 'YSA-NB-9060-SS',
                'price' => 6999.00,
                'discount_price' => 5999.00,
                'gender' => 'kadin',
                'color' => 'Bej & Krem',
                'color_code' => '#E5DFD3',
                'is_featured' => true,
                'is_new' => true,
                'view_count' => 180,
                'short_description' => 'Y2K estetiğini modern teknolojiyle buluşturan heykelsi chunky siluet.',
                'description' => 'New Balance 9060, 99X serisinin klasik unsurlarını abartılı ve fütüristik hatlarla yeniden yorumluyor. ABZORB ve SBS yastıklama sistemleri ile adımlarınız bulut gibi yumuşak.',
                'images' => [
                    'https://images.unsplash.com/photo-1539185441755-769473a23570?w=1000&auto=format&fit=crop&q=80',
                ],
                'stocks' => [36 => 2, 37 => 4, 38 => 5, 39 => 3, 40 => 1],
            ],
            [
                'name' => 'YSA Signature Loafer Süet Taba',
                'category_slug' => 'klasik',
                'brand_slug' => 'ysa-signature',
                'sku' => 'YSA-LOAF-SUET-TB',
                'price' => 3999.00,
                'discount_price' => 3499.00,
                'gender' => 'erkek',
                'color' => 'Taba',
                'color_code' => '#B87333',
                'is_featured' => false,
                'is_new' => true,
                'view_count' => 67,
                'short_description' => 'İtalyan esintili hakiki süet deri loafer; hafif, zarif ve sofistike.',
                'description' => 'Gündelik şıklık ve akıllı gündelik (smart casual) kombinlerin anahtar parçası. Yumuşak süet derisi ve ergonomik tabanı ile gün boyu ayağınızı yormaz.',
                'images' => [
                    'https://images.unsplash.com/photo-1533867617858-e7b97e060509?w=1000&auto=format&fit=crop&q=80',
                ],
                'stocks' => [40 => 2, 41 => 3, 42 => 4, 43 => 2, 44 => 1],
            ],
            [
                'name' => 'Nike Pegasus 40 Running',
                'category_slug' => 'spor',
                'brand_slug' => 'nike',
                'sku' => 'YSA-NK-PEG-40',
                'price' => 4599.00,
                'discount_price' => 3899.00,
                'gender' => 'kadin',
                'color' => 'Antrasit & Pembe',
                'color_code' => '#292929',
                'is_featured' => false,
                'is_new' => false,
                'view_count' => 84,
                'short_description' => 'Koşu tutkunları için dengeli, esnek ve enerji dolu koşu ayakkabısı.',
                'description' => 'Nike React köpük ve iki adet Zoom Air birimiyle desteklenen Pegasus 40, adımlarınıza yay etkisi kazandırır. Özel olarak geliştirilmiş file yapısı hava sirkülasyonu sağlar.',
                'images' => [
                    'https://images.unsplash.com/photo-1543163521-1bf539c55dd2?w=1000&auto=format&fit=crop&q=80',
                ],
                'stocks' => [36 => 3, 37 => 5, 38 => 4, 39 => 2, 40 => 0],
            ],
            [
                'name' => 'Adidas Gazelle Indoor Blue',
                'category_slug' => 'gunluk',
                'brand_slug' => 'adidas',
                'sku' => 'YSA-AD-GAZ-BLU',
                'price' => 4499.00,
                'discount_price' => null,
                'gender' => 'unisex',
                'color' => 'Mavi & Beyaz',
                'color_code' => '#1E3A8A',
                'is_featured' => true,
                'is_new' => true,
                'view_count' => 155,
                'short_description' => '1966\'dan günümüze taşınan süet efsane; şeffaf kauçuk taban detayıyla.',
                'description' => 'Adidas Gazelle Indoor, yumuşak süet sayası ve yarı saydam taban yapısıyla nostaljik bir hava katar. Altın yaldızlı Gazelle yazısı tasarımı tamamlar.',
                'images' => [
                    'https://images.unsplash.com/photo-1525966222134-fcfa99b8ae77?w=1000&auto=format&fit=crop&q=80',
                ],
                'stocks' => [37 => 2, 38 => 3, 39 => 4, 40 => 5, 41 => 3, 42 => 2],
            ],
        ];

        foreach ($productsData as $pData) {
            $cat = $categories[$pData['category_slug']] ?? null;
            $brn = $brands[$pData['brand_slug']] ?? null;

            $product = Product::create([
                'category_id' => $cat?->id,
                'brand_id' => $brn?->id,
                'name' => $pData['name'],
                'slug' => Str::slug($pData['name']),
                'sku' => $pData['sku'],
                'price' => $pData['price'],
                'discount_price' => $pData['discount_price'],
                'gender' => $pData['gender'],
                'color' => $pData['color'],
                'color_code' => $pData['color_code'],
                'short_description' => $pData['short_description'],
                'description' => $pData['description'],
                'is_featured' => $pData['is_featured'],
                'is_new' => $pData['is_new'],
                'view_count' => $pData['view_count'],
                'is_active' => true,
                'meta_title' => $pData['name'] . ' | VELORA',
                'meta_description' => $pData['short_description'],
            ]);

            // Add images
            foreach ($pData['images'] as $imgIdx => $imgUrl) {
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $imgUrl,
                    'is_primary' => $imgIdx === 0,
                    'sort_order' => $imgIdx,
                ]);
            }

            // Add size stocks
            foreach ($pData['stocks'] as $sNum => $stockQty) {
                if (isset($sizeModels[$sNum])) {
                    ProductSizeStock::create([
                        'product_id' => $product->id,
                        'size_id' => $sizeModels[$sNum]->id,
                        'stock' => $stockQty,
                    ]);
                }
            }
        }

        // 7. Hero Campaigns / Banners
        $campaignsData = [
            [
                'title' => 'Tarzını Adımlarınla Göster',
                'subtitle' => 'Yeni sezon ayakkabı modellerini keşfet. Günlük kullanımdan özel kombinlere kadar tarzına en uygun modeli bul.',
                'badge' => 'Yeni Sezon 2026',
                'discount_text' => '%20 İndirim Fırsatı',
                'button_text' => 'Modelleri Keşfet',
                'button_url' => '/urunler',
                'bg_color' => '#111111',
                'image' => 'https://images.unsplash.com/photo-1552346154-21d32810aba3?w=1200&auto=format&fit=crop&q=80',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'title' => 'Sneaker & Sokak Kültürü',
                'subtitle' => 'Nike, Adidas ve New Balance en çok aranan modelleri VELORA güvencesiyle vitrinde.',
                'badge' => 'Öne Çıkanlar',
                'discount_text' => 'Ücretsiz Kargo',
                'button_text' => 'Koleksiyonu İncele',
                'button_url' => '/urunler?category=spor',
                'bg_color' => '#1c1c1c',
                'image' => 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=1200&auto=format&fit=crop&q=80',
                'sort_order' => 2,
                'is_active' => true,
            ],
        ];

        foreach ($campaignsData as $c) {
            Campaign::create($c);
        }

        // 8. Sample Contact Messages
        ContactMessage::create([
            'name' => 'Ahmet Yıldız',
            'email_or_phone' => 'ahmet@example.com',
            'subject' => 'Nike Air Max 270 Stok Durumu',
            'message' => 'Merhaba, Nike Air Max 270 42 numaranın yeni serisi ne zaman mağazaya gelir acaba?',
            'is_read' => false,
            'ip_address' => '127.0.0.1',
        ]);

        ContactMessage::create([
            'name' => 'Elif Kaya',
            'email_or_phone' => '05341112233',
            'subject' => 'Mağaza Çalışma Saatleri',
            'message' => 'Pazar günleri Bağdat caddesi mağazanız açık mı?',
            'is_read' => true,
            'ip_address' => '127.0.0.1',
        ]);

        // 9. Sample Orders
        $firstProduct = Product::first();
        $secondProduct = Product::skip(1)->first();

        $order1 = Order::create([
            'order_number' => 'YSA-2026-000101',
            'customer_name' => 'Mert Yılmaz',
            'customer_email' => 'mert@example.com',
            'customer_phone' => '05329998877',
            'city' => 'İstanbul',
            'district' => 'Kadıköy',
            'address' => 'Fenerbahçe Mah. Lale Sok. No: 12 D: 4',
            'order_notes' => 'Lütfen zile basmadan önce arayınız.',
            'subtotal' => 4699.00,
            'shipping_cost' => 0.00,
            'discount_amount' => 0.00,
            'total_amount' => 4699.00,
            'status' => 'hazirlaniyor',
            'payment_method' => 'kapida_odeme',
            'payment_status' => 'kapida_odenecek',
            'created_at' => now()->subDays(1),
        ]);

        OrderItem::create([
            'order_id' => $order1->id,
            'product_id' => $firstProduct->id,
            'product_name' => $firstProduct->name,
            'product_sku' => $firstProduct->sku,
            'product_image' => $firstProduct->primary_image_url,
            'size_number' => '42',
            'price' => 4699.00,
            'quantity' => 1,
            'total' => 4699.00,
        ]);

        $order2 = Order::create([
            'order_number' => 'YSA-2026-000102',
            'customer_name' => 'Selin Aksoy',
            'customer_email' => 'selin@example.com',
            'customer_phone' => '05445556677',
            'city' => 'İzmir',
            'district' => 'Karşıyaka',
            'address' => 'Bostanlı Mah. 1800 Sok. No: 5 D: 2',
            'subtotal' => 4299.00,
            'shipping_cost' => 0.00,
            'discount_amount' => 0.00,
            'total_amount' => 4299.00,
            'status' => 'yeni',
            'payment_method' => 'online_kart',
            'payment_status' => 'odendi',
            'created_at' => now(),
        ]);

        OrderItem::create([
            'order_id' => $order2->id,
            'product_id' => $secondProduct->id,
            'product_name' => $secondProduct->name,
            'product_sku' => $secondProduct->sku,
            'product_image' => $secondProduct->primary_image_url,
            'size_number' => '39',
            'price' => 4299.00,
            'quantity' => 1,
            'total' => 4299.00,
        ]);
    }
}
