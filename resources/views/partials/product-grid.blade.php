@if($products->count() > 0)
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5 sm:gap-6">
        @foreach($products as $product)
            @include('partials.product-card', ['product' => $product])
        @endforeach
    </div>
@else
    <div class="text-center py-20 bg-white rounded-2xl border border-black/[0.07] p-8 shadow-sm">
        <div class="w-16 h-16 bg-[#F4F4F2] rounded-2xl flex items-center justify-center mx-auto mb-4 text-accent text-2xl">
            <i class="fa-solid fa-shoe-prints"></i>
        </div>
        <h3 class="font-display font-bold text-lg text-dark mb-1">Seçtiğiniz kriterlere uygun model bulunamadı</h3>
        <p class="text-black/50 text-xs sm:text-sm max-w-md mx-auto mb-6 leading-relaxed">Filtrelerinizi temizleyerek veya arama teriminizi değiştirerek diğer koleksiyonlarımızı keşfedebilirsiniz.</p>
        <button type="button" onclick="resetFilters()" class="inline-flex items-center px-6 py-3 bg-dark hover:bg-accent text-white hover:text-dark font-bold text-xs rounded-xl transition-all shadow-sm space-x-2">
            <i class="fa-solid fa-rotate-left text-[11px]"></i>
            <span>Filtreleri Temizle</span>
        </button>
    </div>
@endif
