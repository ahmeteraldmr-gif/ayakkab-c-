@extends('layouts.app')

@section('title', $product->meta_title ?? ($product->name . ' | Yusuf Akboğa Ayakkabı'))
@section('meta_description', $product->meta_description ?? $product->short_description)
@section('og_image', $product->primary_image_url)
@section('og_type', 'product')

@section('content')

    @php
        $breadcrumbItems = [
            [
                '@type' => 'ListItem',
                'position' => 1,
                'name' => 'Ana Sayfa',
                'item' => route('home'),
            ],
            [
                '@type' => 'ListItem',
                'position' => 2,
                'name' => 'Ürünler',
                'item' => route('products.index'),
            ],
        ];

        if ($product->category) {
            $breadcrumbItems[] = [
                '@type' => 'ListItem',
                'position' => 3,
                'name' => $product->category->name,
                'item' => route('products.index', ['category' => $product->category->slug]),
            ];
            $breadcrumbItems[] = [
                '@type' => 'ListItem',
                'position' => 4,
                'name' => $product->name,
                'item' => url()->current(),
            ];
        } else {
            $breadcrumbItems[] = [
                '@type' => 'ListItem',
                'position' => 3,
                'name' => $product->name,
                'item' => url()->current(),
            ];
        }

        $productJsonLd = [
            '@context' => 'https://schema.org/',
            '@type' => 'Product',
            'name' => $product->name,
            'image' => [$product->primary_image_url],
            'description' => $product->short_description ?? $product->name,
            'sku' => $product->sku,
            'brand' => [
                '@type' => 'Brand',
                'name' => $product->brand?->name ?? 'Yusuf Akboğa',
            ],
            'offers' => [
                '@type' => 'Offer',
                'url' => url()->current(),
                'priceCurrency' => 'TRY',
                'price' => $product->effective_price,
                'availability' => $product->total_stock > 0 ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock',
                'itemCondition' => 'https://schema.org/NewCondition',
            ]
        ];

        $breadcrumbJsonLd = [
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => $breadcrumbItems,
        ];
    @endphp

    <!-- JSON-LD Product & Breadcrumb Schema -->
    <script type="application/ld+json">
    {!! json_encode($productJsonLd, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
    </script>
    <script type="application/ld+json">
    {!! json_encode($breadcrumbJsonLd, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
    </script>

    <!-- Breadcrumb -->
    <div class="bg-dark text-white py-4 border-b border-white/10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <nav class="flex items-center space-x-2 text-xs text-white/50">
                <a href="{{ route('home') }}" class="hover:text-accent transition-colors">Ana Sayfa</a>
                <i class="fa-solid fa-chevron-right text-[8px]"></i>
                <a href="{{ route('products.index') }}" class="hover:text-accent transition-colors">Ürünler</a>
                @if($product->category)
                    <i class="fa-solid fa-chevron-right text-[8px]"></i>
                    <a href="{{ route('products.index', ['category' => $product->category->slug]) }}" class="hover:text-accent transition-colors">{{ $product->category->name }}</a>
                @endif
                <i class="fa-solid fa-chevron-right text-[8px]"></i>
                <span class="text-accent font-semibold truncate max-w-xs">{{ $product->name }}</span>
            </nav>
        </div>
    </div>

    <!-- Main Product Detail -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
            
            <!-- LEFT: GALLERY & IMAGES (lg:col-span-7) -->
            <div class="lg:col-span-7 space-y-4">
                
                <!-- Main Large Image with Zoom Feature -->
                <div class="relative aspect-square w-full rounded-3xl bg-white border border-black/5 p-8 flex items-center justify-center overflow-hidden group shadow-sm">
                    
                    <!-- Badges -->
                    <div class="absolute top-4 left-4 z-10 flex flex-col gap-2">
                        @if($product->is_new)
                            <span class="bg-dark text-white text-xs font-extrabold uppercase px-3 py-1.5 rounded-lg tracking-wider">
                                YENİ SEZON
                            </span>
                        @endif
                        @if($product->has_discount)
                            <span class="bg-rose-600 text-white text-xs font-extrabold px-3 py-1.5 rounded-lg tracking-wider">
                                -%{{ $product->discount_percent }} İNDİRİM
                            </span>
                        @endif
                    </div>

                    <!-- Favorite Button -->
                    <button type="button" 
                            data-fav-id="{{ $product->id }}"
                            data-product="{{ json_encode([
                                'id' => $product->id,
                                'name' => $product->name,
                                'price' => $product->formatted_effective_price,
                                'image' => $product->primary_image_url,
                                'brand' => $product->brand?->name ?? 'YSA',
                                'url' => route('product.detail', $product->slug)
                            ]) }}"
                            onclick="toggleFavoriteFromButton(this)"
                            class="absolute top-4 right-4 z-10 w-11 h-11 rounded-full bg-cream hover:bg-white text-dark/70 hover:text-rose-500 shadow-md flex items-center justify-center transition-all"
                            title="Favorilere Ekle">
                        <i class="fa-regular fa-heart text-lg"></i>
                    </button>

                    <!-- Main Display Image with Zoom -->
                    <div id="zoomContainer" class="w-full h-full flex items-center justify-center cursor-crosshair overflow-hidden">
                        <img id="mainProductImage" 
                             src="{{ $product->primary_image_url }}" 
                             alt="{{ $product->name }}" 
                             class="max-h-[480px] w-full object-contain filter drop-shadow-xl transition-transform duration-200">
                    </div>
                </div>

                <!-- Thumbnail Switcher -->
                @if($product->images->count() > 1)
                    <div class="flex items-center space-x-3 overflow-x-auto pb-2">
                        @foreach($product->images as $img)
                            <button type="button" 
                                    onclick="switchMainImage('{{ $img->url }}', this)" 
                                    class="thumb-btn relative w-20 h-20 rounded-2xl bg-white border-2 {{ $loop->first ? 'border-accent' : 'border-transparent' }} p-2 flex items-center justify-center overflow-hidden hover:border-accent transition-all flex-shrink-0 shadow-sm">
                                <img src="{{ $img->url }}" alt="{{ $product->name }}" class="w-full h-full object-contain">
                            </button>
                        @endforeach
                    </div>
                @endif

                <!-- Product Trust & Highlight Badges -->
                <div class="grid grid-cols-3 gap-3 pt-4">
                    <div class="bg-white p-3.5 rounded-2xl border border-black/5 text-center flex flex-col items-center justify-center">
                        <i class="fa-solid fa-box-open text-accent text-lg mb-1"></i>
                        <span class="text-[11px] font-bold text-dark">Orijinal Kutusunda</span>
                    </div>
                    <div class="bg-white p-3.5 rounded-2xl border border-black/5 text-center flex flex-col items-center justify-center">
                        <i class="fa-solid fa-rotate-left text-accent text-lg mb-1"></i>
                        <span class="text-[11px] font-bold text-dark">14 Gün Kolay Değişim</span>
                    </div>
                    <div class="bg-white p-3.5 rounded-2xl border border-black/5 text-center flex flex-col items-center justify-center">
                        <i class="fa-solid fa-truck-fast text-accent text-lg mb-1"></i>
                        <span class="text-[11px] font-bold text-dark">Hızlı Kargo</span>
                    </div>
                </div>

            </div>

            <!-- RIGHT: PRODUCT BUYING INFO (lg:col-span-5) -->
            <div class="lg:col-span-5 space-y-6">
                
                <div class="bg-white rounded-3xl p-6 sm:p-8 border border-black/5 shadow-sm space-y-6">
                    
                    <!-- Brand & SKU -->
                    <div class="flex items-center justify-between border-b border-black/5 pb-3">
                        <span class="font-display font-extrabold text-sm text-accent uppercase tracking-widest">
                            {{ $product->brand?->name ?? 'Yusuf Akboğa Signature' }}
                        </span>
                        <span class="text-xs font-mono text-black/40">
                            Kod: {{ $product->sku }}
                        </span>
                    </div>

                    <!-- Title & Rating & Color -->
                    <div>
                        <h1 class="font-display font-extrabold text-2xl sm:text-3xl text-dark leading-tight">
                            {{ $product->name }}
                        </h1>
                        
                        <!-- Review Rating Snippet -->
                        <div class="flex items-center space-x-2 mt-2">
                            <div class="flex items-center text-amber-400 text-xs">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="{{ $i <= round($product->average_rating) ? 'fa-solid' : 'fa-regular' }} fa-star"></i>
                                @endfor
                            </div>
                            <span class="text-xs font-bold text-dark">{{ $product->average_rating }}</span>
                            <span class="text-xs text-black/40">({{ $product->approvedReviews->count() }} Değerlendirme)</span>
                            <a href="#reviewsSection" class="text-xs text-accent font-semibold hover:underline ml-2">Yorumları Oku</a>
                        </div>

                        @if($product->color)
                            <div class="flex items-center space-x-2 mt-2 text-xs text-black/60 font-semibold">
                                <span>Renk:</span>
                                @if($product->color_code)
                                    <span class="w-3.5 h-3.5 rounded-full border border-black/10 inline-block" style="background-color: {{ $product->color_code }}"></span>
                                @endif
                                <span class="text-dark">{{ $product->color }}</span>
                            </div>
                        @endif
                    </div>

                    <!-- Price Block -->
                    <div class="bg-[#F7F7F5] rounded-2xl p-4 flex items-center justify-between">
                        <div>
                            @if($product->has_discount)
                                <span class="text-xs text-black/40 line-through font-semibold block mb-0.5">
                                    {{ $product->formatted_price }}
                                </span>
                                <div class="flex items-baseline space-x-2">
                                    <span class="font-display font-extrabold text-2xl sm:text-3xl text-rose-600">
                                        {{ $product->formatted_effective_price }}
                                    </span>
                                    <span class="text-xs font-bold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-md border border-emerald-200">
                                        Kazancınız: {{ number_format($product->price - $product->discount_price, 2, ',', '.') }} TL
                                    </span>
                                </div>
                            @else
                                <span class="font-display font-extrabold text-2xl sm:text-3xl text-dark">
                                    {{ $product->formatted_price }}
                                </span>
                            @endif
                        </div>
                        <div class="text-right">
                            <span class="text-[11px] text-emerald-600 font-bold block">
                                <i class="fa-solid fa-circle-check"></i> Stokta Mevcut
                            </span>
                            <span class="text-[10px] text-black/40">KDV Dahil Fiyattır</span>
                        </div>
                    </div>

                    <!-- Size Selection (NUMARA SEÇİMİ) & FIT INFO -->
                    <div>
                        <!-- Beden / Kalıp Bilgisi Kutusu -->
                        <div class="mb-4 bg-amber-500/10 border border-amber-500/20 rounded-2xl p-3.5 flex items-start space-x-3 text-xs text-amber-900">
                            <div class="w-7 h-7 rounded-lg bg-amber-500/20 text-amber-700 flex items-center justify-center flex-shrink-0 mt-0.5">
                                <i class="fa-solid fa-arrows-left-right"></i>
                            </div>
                            <div class="space-y-0.5">
                                <strong class="font-bold block text-dark">
                                    @if($product->fit_type === 'dar_kalip')
                                        Kalıp Bilgisi: Dar Kalıp (1 numara büyük tercih edebilirsiniz)
                                    @elseif($product->fit_type === 'genis_kalip')
                                        Kalıp Bilgisi: Geniş Kalıp (1 numara küçük tercih edebilirsiniz)
                                    @else
                                        Kalıp Bilgisi: Bu model tam kalıptır (Kendi numaranızı alabilirsiniz)
                                    @endif
                                </strong>
                                @if($product->size_note)
                                    <p class="text-black/70">{{ $product->size_note }}</p>
                                @endif
                            </div>
                        </div>

                        <div class="flex items-center justify-between mb-3">
                            <span class="text-xs font-extrabold uppercase tracking-wider text-dark flex items-center">
                                <i class="fa-solid fa-shoe-prints text-accent mr-1.5"></i> Ayakkabı Numarası Seçiniz:
                            </span>
                            <button type="button" onclick="openSizeGuideModal()" class="text-xs font-bold text-accent hover:underline flex items-center">
                                <i class="fa-solid fa-ruler mr-1"></i> Beden Tablosu
                            </button>
                        </div>

                        <!-- Size Buttons Grid -->
                        <div class="grid grid-cols-4 sm:grid-cols-5 gap-2" id="sizeSelectorGroup">
                            @foreach($sizesWithStock as $sItem)
                                @if($sItem['in_stock'])
                                    <button type="button" 
                                            onclick="selectSize({{ $sItem['size_id'] }}, '{{ $sItem['size_number'] }}', {{ $sItem['stock'] }}, this)" 
                                            class="size-btn relative py-3 rounded-xl border-2 border-black/10 hover:border-dark text-dark font-display font-bold text-sm transition-all flex flex-col items-center justify-center">
                                        <span>{{ $sItem['size_number'] }}</span>
                                        @if($sItem['is_low_stock'])
                                            <span class="text-[9px] text-rose-600 font-semibold mt-0.5">Son {{ $sItem['stock'] }}</span>
                                        @endif
                                    </button>
                                @else
                                    <button type="button" 
                                            onclick="openStockNotifyModal({{ $sItem['size_id'] }}, '{{ $sItem['size_number'] }}')"
                                            class="py-3 rounded-xl border border-rose-200 bg-rose-50/40 text-rose-700 hover:bg-rose-100 hover:border-rose-300 font-display font-bold text-sm flex flex-col items-center justify-center transition-colors cursor-pointer"
                                            title="Tükendi - Stok Gelince Haber Ver">
                                        <span class="line-through text-black/40">{{ $sItem['size_number'] }}</span>
                                        <span class="text-[9px] text-rose-600 mt-0.5 font-semibold flex items-center">
                                            <i class="fa-regular fa-bell text-[8px] mr-1"></i> Haber Ver
                                        </span>
                                    </button>
                                @endif
                            @endforeach
                        </div>
                        
                        <!-- Selected Size Alert / Validation Feedback -->
                        <div id="sizeAlertText" class="mt-2 text-xs font-semibold text-rose-600 hidden">
                            <i class="fa-solid fa-circle-exclamation mr-1"></i> Lütfen devam etmek için bir numara seçiniz.
                        </div>

                        <!-- Stock Notification Notice -->
                        <div class="mt-3 flex items-center justify-between text-xs text-black/60 bg-[#F7F7F5] p-3 rounded-xl">
                            <span class="flex items-center">
                                <i class="fa-regular fa-bell text-accent mr-1.5"></i> Numaranız tükenmiş mi?
                            </span>
                            <button type="button" onclick="openStockNotifyModal(null, 'Tüm Bedenler')" class="font-bold text-dark hover:text-accent underline transition-colors">
                                Stok Gelince Haber Ver
                            </button>
                        </div>
                    </div>

                    <!-- Quantity & Add to Cart -->
                    <form id="addToCartForm" method="POST" action="{{ route('cart.add') }}" onsubmit="handleAddToCart(event)">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <input type="hidden" name="size_id" id="selectedSizeId" value="">

                        <div class="flex items-center space-x-3 pt-2">
                            <!-- Quantity Selector -->
                            <div class="flex items-center border border-black/10 rounded-2xl bg-[#F7F7F5] p-1 h-14">
                                <button type="button" onclick="adjustQty(-1)" class="w-10 h-full rounded-xl hover:bg-white text-dark font-bold text-sm transition-colors flex items-center justify-center">
                                    <i class="fa-solid fa-minus text-xs"></i>
                                </button>
                                <input type="number" name="quantity" id="productQty" value="1" min="1" max="10" readonly class="w-10 text-center bg-transparent font-bold text-sm text-dark focus:outline-none">
                                <button type="button" onclick="adjustQty(1)" class="w-10 h-full rounded-xl hover:bg-white text-dark font-bold text-sm transition-colors flex items-center justify-center">
                                    <i class="fa-solid fa-plus text-xs"></i>
                                </button>
                            </div>

                            <!-- Add to Cart CTA Button -->
                            <button type="submit" 
                                    id="addToCartBtn"
                                    class="flex-grow h-14 bg-dark hover:bg-accent text-white hover:text-dark font-display font-bold text-sm uppercase tracking-wider rounded-2xl transition-all shadow-xl shadow-black/10 flex items-center justify-center space-x-2.5">
                                <i class="fa-solid fa-bag-shopping text-base"></i>
                                <span>Sepete Ekle</span>
                            </button>
                        </div>
                    </form>

                    <!-- WhatsApp Inquiry Button -->
                    <div>
                        <a href="{{ $whatsappLink }}" target="_blank" class="w-full h-12 rounded-2xl bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs transition-colors flex items-center justify-center space-x-2 shadow-md">
                            <i class="fa-brands fa-whatsapp text-lg"></i>
                            <span>WhatsApp ile Bilgi Al & Sipariş Ver</span>
                        </a>
                    </div>

                    <!-- Short Description -->
                    @if($product->short_description)
                        <div class="text-xs text-black/70 leading-relaxed pt-2 border-t border-black/5">
                            {{ $product->short_description }}
                        </div>
                    @endif

                </div>

            </div>

        </div>

        <!-- PRODUCT DESCRIPTION TABS & DETAILS -->
        <div class="mt-16 bg-white rounded-3xl p-8 border border-black/5 shadow-sm space-y-6">
            <h3 class="font-display font-extrabold text-xl text-dark border-b border-black/5 pb-4 flex items-center">
                <i class="fa-solid fa-circle-info text-accent mr-2.5"></i> Ürün Detayları ve Özellikleri
            </h3>
            
            <div class="prose max-w-none text-black/70 text-sm leading-relaxed">
                {!! nl2br(e($product->description ?? $product->short_description ?? 'Bu ürün için detaylı açıklama yakında eklenecektir.')) !!}
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 pt-6 border-t border-black/5 text-xs">
                <div class="p-3.5 bg-[#F7F7F5] rounded-xl">
                    <span class="text-black/40 block font-medium">Kategori:</span>
                    <strong class="text-dark">{{ $product->category?->name ?? 'Sneaker' }}</strong>
                </div>
                <div class="p-3.5 bg-[#F7F7F5] rounded-xl">
                    <span class="text-black/40 block font-medium">Marka:</span>
                    <strong class="text-dark">{{ $product->brand?->name ?? 'YSA' }}</strong>
                </div>
                <div class="p-3.5 bg-[#F7F7F5] rounded-xl">
                    <span class="text-black/40 block font-medium">Cinsiyet:</span>
                    <strong class="text-dark capitalize">{{ $product->gender }}</strong>
                </div>
                <div class="p-3.5 bg-[#F7F7F5] rounded-xl">
                    <span class="text-black/40 block font-medium">Orijinallik:</span>
                    <strong class="text-emerald-700 font-bold">%100 Orijinal</strong>
                </div>
            </div>
        </div>

        <!-- PRODUCT REVIEWS & RATINGS SECTION -->
        <div id="reviewsSection" class="mt-12 bg-white rounded-3xl p-8 border border-black/5 shadow-sm space-y-8">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-black/5 pb-6">
                <div>
                    <span class="text-xs font-bold uppercase tracking-widest text-accent">Müşteri Deneyimleri</span>
                    <h3 class="font-display font-extrabold text-2xl text-dark mt-1">Değerlendirmeler & Yorumlar</h3>
                </div>
                <button type="button" onclick="document.getElementById('writeReviewCard').classList.toggle('hidden')" class="px-5 py-2.5 bg-dark hover:bg-accent text-white hover:text-dark rounded-xl text-xs font-bold transition-colors shadow">
                    <i class="fa-solid fa-pen-to-square mr-1.5"></i> Yorum Yaz
                </button>
            </div>

            <!-- Rating Summary & Breakdown Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 items-center bg-[#F7F7F5] p-6 rounded-2xl">
                <!-- Big Score -->
                <div class="text-center md:border-r border-black/10 md:pr-6 space-y-1">
                    <div class="font-display font-black text-5xl text-dark">{{ $product->average_rating }}</div>
                    <div class="flex items-center justify-center text-amber-400 text-sm">
                        @for($i = 1; $i <= 5; $i++)
                            <i class="{{ $i <= round($product->average_rating) ? 'fa-solid' : 'fa-regular' }} fa-star"></i>
                        @endfor
                    </div>
                    <p class="text-xs text-black/50">Toplam {{ $product->approvedReviews->count() }} yorum yapıldı</p>
                </div>

                <!-- Star Distribution Bars -->
                <div class="md:col-span-2 space-y-2 text-xs">
                    @php
                        $breakdown = $product->rating_breakdown;
                    @endphp
                    @for($star = 5; $star >= 1; $star--)
                        @php
                            $cnt = $breakdown[$star]['count'] ?? 0;
                            $pct = $breakdown[$star]['percent'] ?? 0;
                        @endphp
                        <div class="flex items-center space-x-3">
                            <span class="w-12 text-black/60 font-medium text-right">{{ $star }} Yıldız</span>
                            <div class="flex-grow h-2.5 bg-black/10 rounded-full overflow-hidden">
                                <div class="h-full bg-amber-400 rounded-full" style="width: {{ $pct }}%"></div>
                            </div>
                            <span class="w-8 text-black/40 text-[11px] font-mono">{{ $cnt }}</span>
                        </div>
                    @endfor
                </div>
            </div>

            <!-- Write Review Form Container (Toggleable) -->
            <div id="writeReviewCard" class="hidden bg-dark text-white p-6 sm:p-8 rounded-2xl space-y-5 border border-white/10">
                <div class="border-b border-white/10 pb-4">
                    <h4 class="font-display font-bold text-lg text-white">Ürünü Değerlendirin</h4>
                    <p class="text-xs text-white/50">Deneyiminizi diğer müşterilerimizle paylaşın. Yorumunuz yönetici onayından sonra yayınlanacaktır.</p>
                </div>

                <form method="POST" action="{{ route('reviews.store', $product->id) }}" class="space-y-4">
                    @csrf
                    <!-- Star Rating Select -->
                    <div>
                        <label class="block text-xs font-semibold text-white/80 uppercase tracking-wider mb-2">Puanınız</label>
                        <div class="flex items-center space-x-3 text-2xl text-amber-400 cursor-pointer" id="starPicker">
                            <input type="hidden" name="rating" id="ratingInput" value="5" required>
                            @for($s = 1; $s <= 5; $s++)
                                <i class="fa-solid fa-star transition-transform hover:scale-125" onclick="setRating({{ $s }})" data-star="{{ $s }}"></i>
                            @endfor
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="customer_name" class="block text-xs font-semibold text-white/80 uppercase tracking-wider mb-1.5">Adınız Soyadınız</label>
                            <input type="text" name="customer_name" id="customer_name" required value="{{ auth()->user()->name ?? old('customer_name') }}" placeholder="Adınız Soyadınız" class="w-full bg-[#222] text-white text-xs px-4 py-3 rounded-xl border border-white/10 focus:outline-none focus:border-accent">
                        </div>
                        <div>
                            <label for="title" class="block text-xs font-semibold text-white/80 uppercase tracking-wider mb-1.5">Başlık (İsteğe Bağlı)</label>
                            <input type="text" name="title" id="title" placeholder="Örn: Çok rahat ve şık bir ayakkabı" class="w-full bg-[#222] text-white text-xs px-4 py-3 rounded-xl border border-white/10 focus:outline-none focus:border-accent">
                        </div>
                    </div>

                    <div>
                        <label for="comment" class="block text-xs font-semibold text-white/80 uppercase tracking-wider mb-1.5">Yorumunuz</label>
                        <textarea name="comment" id="comment" rows="3" required placeholder="Ayakkabının kalıbı, kalitesi ve duruşu hakkında görüşleriniz..." class="w-full bg-[#222] text-white text-xs p-4 rounded-xl border border-white/10 focus:outline-none focus:border-accent"></textarea>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="px-6 py-3 bg-accent hover:bg-accent-light text-dark font-bold text-xs rounded-xl transition-colors shadow">
                            Yorumu Gönder
                        </button>
                    </div>
                </form>
            </div>

            <!-- Approved Customer Reviews List -->
            <div class="space-y-4">
                @if($product->approvedReviews->isEmpty())
                    <p class="text-xs text-black/50 text-center py-6">Bu ürün için henüz onaylı bir yorum bulunmuyor. İlk yorumu siz yapın!</p>
                @else
                    <div class="divide-y divide-black/5">
                        @foreach($product->approvedReviews as $rev)
                            <div class="py-5 space-y-2">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-2">
                                        <span class="font-bold text-dark text-sm">{{ $rev->customer_name }}</span>
                                        @if($rev->is_verified_purchase)
                                            <span class="text-[10px] bg-emerald-50 text-emerald-700 font-semibold px-2 py-0.5 rounded border border-emerald-200 flex items-center">
                                                <i class="fa-solid fa-circle-check mr-1"></i> Doğrulanmış Alışveriş
                                            </span>
                                        @endif
                                    </div>
                                    <span class="text-[11px] text-black/40">{{ $rev->created_at->format('d.m.Y') }}</span>
                                </div>

                                <div class="flex items-center text-amber-400 text-xs">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="{{ $i <= $rev->rating ? 'fa-solid' : 'fa-regular' }} fa-star"></i>
                                    @endfor
                                </div>

                                @if($rev->title)
                                    <h5 class="font-bold text-dark text-xs">{{ $rev->title }}</h5>
                                @endif

                                <p class="text-xs text-black/70 leading-relaxed">{{ $rev->comment }}</p>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        <!-- RELATED PRODUCTS (BENZER ÜRÜNLER) -->
        @if($relatedProducts->count() > 0)
            <div class="mt-20">
                <div class="flex items-center justify-between mb-8">
                    <div>
                        <span class="text-xs font-bold uppercase tracking-widest text-accent">İlginizi Çekebilir</span>
                        <h2 class="font-display font-extrabold text-2xl text-dark mt-1">Benzer Modeller</h2>
                    </div>
                    <a href="{{ route('products.index', ['category' => $product->category?->slug]) }}" class="text-xs font-bold text-dark hover:text-accent flex items-center">
                        Daha Fazla Gör <i class="fa-solid fa-arrow-right ml-1.5 text-accent"></i>
                    </a>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 sm:gap-6">
                    @foreach($relatedProducts as $relProd)
                        @include('partials.product-card', ['product' => $relProd])
                    @endforeach
                </div>
            </div>
        @endif

    </div>

    <!-- SIZE GUIDE MODAL (DOĞRU NUMARA SEÇİMİ) -->
    <div id="sizeGuideModal" class="fixed inset-0 z-50 bg-black/80 backdrop-blur-sm hidden items-center justify-center p-4">
        <div class="bg-white rounded-3xl w-full max-w-2xl p-6 sm:p-8 shadow-2xl relative max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between border-b border-black/5 pb-4 mb-6">
                <div>
                    <h3 class="font-display font-extrabold text-xl text-dark">Ayakkabı Numarası Rehberi</h3>
                    <p class="text-black/50 text-xs mt-0.5">Doğru kalıbı ve ayağınıza en uygun numarayı bulun</p>
                </div>
                <button onclick="closeSizeGuideModal()" class="text-black/40 hover:text-dark text-2xl">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <div class="space-y-6 text-xs text-black/70">
                <!-- Measurement instructions -->
                <div class="bg-[#F7F7F5] p-5 rounded-2xl border border-black/5 space-y-2">
                    <h4 class="font-display font-bold text-sm text-dark flex items-center">
                        <i class="fa-solid fa-ruler-horizontal text-accent mr-2"></i> Ayak Ölçüsü Nasıl Alınır?
                    </h4>
                    <p>1. Bir kağıdı düz bir zemine koyup ayağınızı üzerine basın.</p>
                    <p>2. Topuğunuzun en arka noktasını ve en uzun parmağınızın ucunu kalemle işaretleyin.</p>
                    <p>3. İki nokta arasındaki mesafeyi santimetre (cm) cinsinden ölçüp aşağıdaki tablodan numaranızı belirleyin.</p>
                </div>

                <!-- Conversion Table -->
                <div class="overflow-x-auto border border-black/10 rounded-2xl">
                    <table class="w-full text-center divide-y divide-black/10">
                        <thead class="bg-dark text-white font-bold text-[11px]">
                            <tr>
                                <th class="p-3">EU Numara</th>
                                <th class="p-3">Ayak Uzunluğu (cm)</th>
                                <th class="p-3">US Erkek</th>
                                <th class="p-3">US Kadın</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-black/5 font-semibold text-dark">
                            <tr><td class="p-2.5 bg-gray-50">36</td><td>22.5 cm</td><td>4.0</td><td>5.5</td></tr>
                            <tr><td class="p-2.5 bg-gray-50">37</td><td>23.5 cm</td><td>5.0</td><td>6.5</td></tr>
                            <tr><td class="p-2.5 bg-gray-50">38</td><td>24.0 cm</td><td>5.5</td><td>7.0</td></tr>
                            <tr><td class="p-2.5 bg-gray-50">39</td><td>24.5 cm</td><td>6.5</td><td>8.0</td></tr>
                            <tr><td class="p-2.5 bg-gray-50">40</td><td>25.0 cm</td><td>7.0</td><td>8.5</td></tr>
                            <tr><td class="p-2.5 bg-gray-50">41</td><td>26.0 cm</td><td>8.0</td><td>9.5</td></tr>
                            <tr><td class="p-2.5 bg-gray-50">42</td><td>26.5 cm</td><td>8.5</td><td>10.0</td></tr>
                            <tr><td class="p-2.5 bg-gray-50">43</td><td>27.5 cm</td><td>9.5</td><td>11.0</td></tr>
                            <tr><td class="p-2.5 bg-gray-50">44</td><td>28.0 cm</td><td>10.0</td><td>11.5</td></tr>
                            <tr><td class="p-2.5 bg-gray-50">45</td><td>29.0 cm</td><td>11.0</td><td>12.5</td></tr>
                        </tbody>
                    </table>
                </div>

                <div class="text-center pt-2">
                    <button onclick="closeSizeGuideModal()" class="px-8 py-3 bg-dark text-white font-bold text-xs rounded-xl hover:bg-accent hover:text-dark transition-colors">
                        Anladım, Numarayı Seç
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- STOCK NOTIFICATION MODAL -->
    <div id="stockNotifyModal" class="fixed inset-0 z-50 bg-black/80 backdrop-blur-sm hidden items-center justify-center p-4">
        <div class="bg-white rounded-3xl w-full max-w-md p-6 sm:p-8 shadow-2xl relative">
            <div class="flex items-center justify-between border-b border-black/5 pb-4 mb-5">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-2xl bg-amber-50 text-accent flex items-center justify-center">
                        <i class="fa-solid fa-bell text-lg"></i>
                    </div>
                    <div>
                        <h3 class="font-display font-extrabold text-base text-dark">Stok Gelince Haber Ver</h3>
                        <p class="text-black/50 text-xs mt-0.5 truncate max-w-[200px]">{{ $product->name }}</p>
                    </div>
                </div>
                <button type="button" onclick="closeStockNotifyModal()" class="text-black/40 hover:text-dark text-xl">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <form id="stockNotifyForm" onsubmit="handleStockNotifySubmit(event)" class="space-y-4">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <input type="hidden" name="size_id" id="notifySizeId" value="">

                <div class="p-3 bg-amber-50/70 border border-amber-200/60 rounded-xl text-xs text-amber-900 flex items-center justify-between">
                    <span>İstenen Numara:</span>
                    <strong id="notifySizeBadge" class="font-bold text-xs bg-white px-2.5 py-0.5 rounded-lg border border-amber-200">Tüm Bedenler</strong>
                </div>

                <div>
                    <label class="block text-xs font-bold text-dark mb-1">E-posta Adresiniz</label>
                    <input type="email" name="email" id="notifyEmail" placeholder="ornek@mail.com" class="w-full bg-[#F7F7F5] border border-black/10 rounded-xl px-3.5 py-2.5 text-xs text-dark focus:outline-none focus:border-accent">
                </div>

                <div>
                    <label class="block text-xs font-bold text-dark mb-1">veya Telefon Numaranız (SMS için)</label>
                    <input type="tel" name="phone" id="notifyPhone" placeholder="05XXXXXXXXX" class="w-full bg-[#F7F7F5] border border-black/10 rounded-xl px-3.5 py-2.5 text-xs text-dark focus:outline-none focus:border-accent">
                </div>

                <p class="text-[11px] text-black/50">Bu ürün stoğa girdiğinde size e-posta veya SMS ile anında bildirim göndereceğiz.</p>

                <button type="submit" id="stockNotifyBtn" class="w-full py-3 bg-dark hover:bg-accent text-white hover:text-dark font-display font-bold text-xs uppercase tracking-wider rounded-xl transition-all shadow-md flex items-center justify-center space-x-2">
                    <i class="fa-solid fa-paper-plane"></i>
                    <span>Bildirim Kaydı Oluştur</span>
                </button>
            </form>
        </div>
    </div>

@endsection

@push('scripts')
<script>
    let selectedSize = null;

    function switchMainImage(src, btn) {
        document.getElementById('mainProductImage').src = src;
        document.querySelectorAll('.thumb-btn').forEach(b => {
            b.classList.remove('border-accent');
            b.classList.add('border-transparent');
        });
        btn.classList.remove('border-transparent');
        btn.classList.add('border-accent');
    }

    function selectSize(sizeId, sizeNum, stock, btn) {
        selectedSize = sizeId;
        document.getElementById('selectedSizeId').value = sizeId;
        document.getElementById('sizeAlertText').classList.add('hidden');

        // Style selected button
        document.querySelectorAll('.size-btn').forEach(b => {
            b.classList.remove('bg-dark', 'text-white', 'border-dark');
            b.classList.add('border-black/10', 'text-dark');
        });

        btn.classList.remove('border-black/10', 'text-dark');
        btn.classList.add('bg-dark', 'text-white', 'border-dark');
    }

    function adjustQty(amount) {
        const input = document.getElementById('productQty');
        let current = parseInt(input.value) || 1;
        current = Math.max(1, Math.min(10, current + amount));
        input.value = current;
    }

    function handleAddToCart(e) {
        e.preventDefault();

        if (!selectedSize) {
            document.getElementById('sizeAlertText').classList.remove('hidden');
            showToast('Lütfen sepetinize eklemek istediğiniz numarayı seçiniz.', 'error');
            return;
        }

        const form = document.getElementById('addToCartForm');
        const formData = new FormData(form);
        const submitBtn = document.getElementById('addToCartBtn');
        const originalContent = submitBtn.innerHTML;

        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin"></i><span>Ekleniyor...</span>';

        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(r => r.json())
        .then(data => {
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalContent;

            if (data.success) {
                showToast(data.message, 'success');
                // Update header cart count
                const countBadge = document.getElementById('headerCartCount');
                if (countBadge) countBadge.innerText = data.cart_count;
            } else {
                showToast(data.message || 'Ürün eklenemedi.', 'error');
            }
        })
        .catch(err => {
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalContent;
            showToast('İşlem sırasında bir hata oluştu.', 'error');
        });
    }

    // Stock Notification Handlers
    function openStockNotifyModal(sizeId, sizeNum) {
        const modal = document.getElementById('stockNotifyModal');
        document.getElementById('notifySizeId').value = sizeId || '';
        document.getElementById('notifySizeBadge').innerText = sizeNum || 'Tüm Bedenler';
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeStockNotifyModal() {
        const modal = document.getElementById('stockNotifyModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    function handleStockNotifySubmit(e) {
        e.preventDefault();
        const form = document.getElementById('stockNotifyForm');
        const email = document.getElementById('notifyEmail').value.trim();
        const phone = document.getElementById('notifyPhone').value.trim();

        if (!email && !phone) {
            showToast('Lütfen e-posta veya telefon numaranızdan en az birini girin.', 'error');
            return;
        }

        const btn = document.getElementById('stockNotifyBtn');
        const origText = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin"></i><span>Kaydediliyor...</span>';

        fetch('{{ route("stock.notify") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                product_id: {{ $product->id }},
                size_id: document.getElementById('notifySizeId').value || null,
                email: email || null,
                phone: phone || null
            })
        })
        .then(r => r.json())
        .then(data => {
            btn.disabled = false;
            btn.innerHTML = origText;
            if (data.success) {
                showToast(data.message, 'success');
                closeStockNotifyModal();
                form.reset();
            } else {
                showToast(data.message || 'Kayıt yapılamadı.', 'error');
            }
        })
        .catch(() => {
            btn.disabled = false;
            btn.innerHTML = origText;
            showToast('İşlem sırasında hata oluştu.', 'error');
        });
    }

    // Star rating picker
    function setRating(rating) {
        document.getElementById('ratingInput').value = rating;
        const stars = document.querySelectorAll('#starPicker i');
        stars.forEach(star => {
            const val = parseInt(star.getAttribute('data-star'));
            if (val <= rating) {
                star.classList.remove('fa-regular');
                star.classList.add('fa-solid');
            } else {
                star.classList.remove('fa-solid');
                star.classList.add('fa-regular');
            }
        });
    }

    // Modal Handlers
    function openSizeGuideModal() {
        const modal = document.getElementById('sizeGuideModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeSizeGuideModal() {
        const modal = document.getElementById('sizeGuideModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    document.getElementById('sizeGuideModal')?.addEventListener('click', function(e) {
        if (e.target === this) closeSizeGuideModal();
    });
    document.getElementById('stockNotifyModal')?.addEventListener('click', function(e) {
        if (e.target === this) closeStockNotifyModal();
    });

    // Image Zoom Effect on Hover
    const zoomContainer = document.getElementById('zoomContainer');
    const mainImg = document.getElementById('mainProductImage');
    if (zoomContainer && mainImg) {
        zoomContainer.addEventListener('mousemove', (e) => {
            const rect = zoomContainer.getBoundingClientRect();
            const x = ((e.clientX - rect.left) / rect.width) * 100;
            const y = ((e.clientY - rect.top) / rect.height) * 100;
            mainImg.style.transformOrigin = `${x}% ${y}%`;
            mainImg.style.transform = 'scale(1.4)';
        });
        zoomContainer.addEventListener('mouseleave', () => {
            mainImg.style.transformOrigin = 'center center';
            mainImg.style.transform = 'scale(1)';
        });
    }
</script>
@endpush
