<div class="group relative bg-white rounded-2xl p-3 sm:p-5 border border-black/[0.07] hover:border-accent/40 shadow-[0_2px_8px_rgba(0,0,0,0.03)] hover:shadow-[0_12px_24px_rgba(0,0,0,0.06)] transition-all duration-300 flex flex-col justify-between h-full">
    
    <!-- Badges & Visual Container -->
    <div class="relative w-full aspect-square rounded-xl bg-[#F4F4F2] overflow-hidden flex items-center justify-center">
        
        <!-- Badges Top Left -->
        <div class="absolute top-2 left-2 sm:top-2.5 sm:left-2.5 z-10 flex flex-col gap-1 items-start">
            @if($product->is_new)
                <span class="bg-dark text-white text-[9px] sm:text-[10px] font-extrabold uppercase px-2 py-0.5 sm:px-2.5 sm:py-1 rounded-md tracking-wider shadow-sm">
                    YENİ
                </span>
            @endif
            @if($product->has_discount)
                <span class="bg-rose-600 text-white text-[9px] sm:text-[10px] font-extrabold px-2 py-0.5 sm:px-2.5 sm:py-1 rounded-md tracking-wider shadow-sm">
                    -%{{ $product->discount_percent }}
                </span>
            @endif
            @if(!$product->is_in_stock)
                <span class="bg-zinc-800/90 text-zinc-300 text-[9px] sm:text-[10px] font-bold px-2 py-0.5 sm:px-2.5 sm:py-1 rounded-md">
                    TÜKENDİ
                </span>
            @elseif($product->total_stock <= 3)
                <span class="bg-amber-600 text-white text-[9px] sm:text-[10px] font-bold px-2 py-0.5 sm:px-2.5 sm:py-1 rounded-md shadow-sm">
                    Son {{ $product->total_stock }}
                </span>
            @endif
        </div>

        <!-- Favorite Button Top Right (Min 40px touch area) -->
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
                class="absolute top-2 right-2 sm:top-2.5 sm:right-2.5 z-10 w-8 h-8 sm:w-9 sm:h-9 rounded-full bg-white/95 backdrop-blur hover:bg-white text-dark/60 hover:text-rose-500 shadow-sm flex items-center justify-center transition-all duration-200"
                title="Favorilere Ekle">
            <i class="fa-regular fa-heart text-xs sm:text-sm"></i>
        </button>

        <!-- Product Image (Standardized & Centered) -->
        <a href="{{ route('product.detail', $product->slug) }}" class="w-full h-full flex items-center justify-center p-3 sm:p-5">
            <img src="{{ $product->primary_image_url }}" 
                 alt="{{ $product->name }}" 
                 loading="lazy"
                 class="product-card-img object-contain w-full h-full filter drop-shadow-[0_4px_10px_rgba(0,0,0,0.12)]">
        </a>
    </div>

    <!-- Product Info Details -->
    <div class="pt-3 sm:pt-4 flex flex-col flex-grow justify-between">
        <div>
            <!-- Brand and Gender -->
            <div class="flex items-center justify-between text-[10px] sm:text-[11px] font-semibold text-black/50 mb-1">
                <span class="text-accent font-bold uppercase tracking-wider truncate mr-1">{{ $product->brand?->name ?? 'YSA Signature' }}</span>
                <span class="capitalize shrink-0">{{ $product->gender === 'kadin' ? 'Kadın' : ($product->gender === 'erkek' ? 'Erkek' : 'Unisex') }}</span>
            </div>

            <!-- Product Title (2-line allowance with standard height) -->
            <a href="{{ route('product.detail', $product->slug) }}" class="block group-hover:text-accent transition-colors">
                <h3 class="font-display font-bold text-xs sm:text-sm text-dark leading-snug line-clamp-2 min-h-[2.2rem] sm:min-h-[2.5rem] flex items-start">
                    {{ $product->name }}
                </h3>
            </a>

            <!-- Color / Variant text -->
            <p class="text-[11px] sm:text-[12px] text-black/50 mt-0.5 truncate">
                {{ $product->color ?? 'Standart Renk' }}
            </p>
        </div>

        <!-- Price and Action CTA -->
        <div class="pt-2.5 sm:pt-3.5 mt-2 sm:mt-3 border-t border-black/[0.06] flex items-center justify-between gap-2 min-h-[42px] sm:min-h-[46px]">
            <div class="flex flex-col justify-center min-w-0">
                @if($product->has_discount)
                    <span class="text-[10px] sm:text-[11px] text-black/40 line-through font-medium leading-none mb-0.5">
                        {{ $product->formatted_price }}
                    </span>
                    <span class="font-extrabold text-sm sm:text-base text-rose-600 leading-none tracking-tight">
                        {{ $product->formatted_effective_price }}
                    </span>
                @else
                    <span class="font-extrabold text-sm sm:text-base text-dark leading-none tracking-tight">
                        {{ $product->formatted_price }}
                    </span>
                @endif
            </div>

            <!-- Action Button -->
            <a href="{{ route('product.detail', $product->slug) }}" 
               class="px-2.5 py-1.5 sm:px-3.5 sm:py-2 bg-dark hover:bg-accent text-white hover:text-dark text-[11px] sm:text-xs font-semibold rounded-lg sm:rounded-xl transition-all duration-300 flex items-center space-x-1 shadow-sm group/btn shrink-0">
                <span>İncele</span>
                <i class="fa-solid fa-arrow-right text-[9px] sm:text-[10px] transform group-hover/btn:translate-x-0.5 transition-transform"></i>
            </a>
        </div>
    </div>
</div>
