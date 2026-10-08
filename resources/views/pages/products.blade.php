@extends('layouts.app')

@section('title', 'Tüm Ayakkabı Modelleri | VELORA')

@section('content')

    <!-- Header Breadcrumb & Banner (Generous Vertical Spacing & Clean Container Alignment) -->
    <div class="bg-dark text-white pt-12 pb-14 border-b border-white/10 relative overflow-hidden">
        <!-- Ambient Subtle Glow -->
        <div class="absolute -top-24 -left-24 w-80 h-80 bg-accent/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-24 -right-24 w-80 h-80 bg-accent/10 rounded-full blur-3xl pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <!-- Breadcrumb -->
            <nav class="flex items-center space-x-2 text-xs text-white/50 mb-3">
                <a href="{{ route('home') }}" class="hover:text-accent transition-colors">Ana Sayfa</a>
                <i class="fa-solid fa-chevron-right text-[9px] text-white/30"></i>
                <span class="text-accent font-semibold">Tüm Ürünler</span>
            </nav>

            <div class="max-w-3xl">
                <h1 class="font-display font-extrabold text-3xl sm:text-4xl text-white tracking-tight">
                    Ayakkabı Koleksiyonu
                </h1>
                <p class="text-white/70 text-sm sm:text-base mt-2 font-normal leading-relaxed">
                    Tarzınıza ve adımlarınıza değer katan en yeni sneaker modelleri, deri klasik tasarımlar ve özel koleksiyonlar.
                </p>
            </div>
        </div>
    </div>

    <!-- Main Catalog Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 lg:py-12">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            
            <!-- DESKTOP FILTER SIDEBAR (lg:col-span-3 - Sticky) -->
            <aside class="hidden lg:block lg:col-span-3 sticky top-28 space-y-6 z-10">
                <form id="filterForm" method="GET" action="{{ route('products.index') }}" onsubmit="event.preventDefault(); applyFilter();">
                    <div class="bg-white rounded-2xl p-6 border border-black/[0.07] shadow-sm space-y-7 max-h-[calc(100vh-140px)] overflow-y-auto">
                        
                        <!-- Header & Reset -->
                        <div class="flex items-center justify-between border-b border-black/[0.06] pb-4">
                            <h3 class="font-display font-bold text-sm text-dark flex items-center">
                                <i class="fa-solid fa-sliders text-accent mr-2"></i> Filtreler
                            </h3>
                            <button type="button" onclick="resetFilters()" class="text-xs font-semibold text-black/40 hover:text-rose-600 transition-colors flex items-center gap-1.5" title="Tüm filtreleri temizle">
                                <i class="fa-solid fa-rotate-left text-[10px]"></i>
                                <span>Sıfırla</span>
                            </button>
                        </div>

                        <!-- 1. Search Box -->
                        <div>
                            <label class="block text-[11px] font-extrabold text-black/80 uppercase tracking-wider mb-2.5">Model Arama</label>
                            <div class="relative">
                                <input type="text" name="q" id="filterSearch" value="{{ request('q') }}" 
                                       placeholder="Model, kod veya renk..." 
                                       class="w-full bg-[#F7F7F5] border border-black/10 rounded-xl px-3.5 py-2.5 text-xs text-dark placeholder-black/40 focus:outline-none focus:border-accent transition-colors"
                                       oninput="debounceFilter()">
                                <i class="fa-solid fa-magnifying-glass absolute right-3.5 top-3 text-xs text-black/30"></i>
                            </div>
                        </div>

                        <!-- 2. Gender (Cinsiyet) -->
                        <div>
                            <label class="block text-[11px] font-extrabold text-black/80 uppercase tracking-wider mb-2.5">Cinsiyet</label>
                            <div class="space-y-2 text-xs">
                                @php
                                    $selectedGenders = (array) request('gender', []);
                                @endphp
                                @foreach(['erkek' => 'Erkek', 'kadin' => 'Kadın', 'unisex' => 'Unisex'] as $gVal => $gLabel)
                                    <label class="flex items-center space-x-2.5 cursor-pointer py-0.5 hover:text-accent transition-colors">
                                        <input type="checkbox" name="gender[]" value="{{ $gVal }}" 
                                               {{ in_array($gVal, $selectedGenders) ? 'checked' : '' }}
                                               onchange="applyFilter()"
                                               class="rounded border-gray-300 text-dark accent-[#C79A58] focus:ring-accent w-4 h-4 cursor-pointer">
                                        <span class="font-medium text-black/80">{{ $gLabel }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <!-- 3. Categories -->
                        <div>
                            <label class="block text-[11px] font-extrabold text-black/80 uppercase tracking-wider mb-2.5">Kategori</label>
                            <div class="space-y-2 text-xs max-h-44 overflow-y-auto pr-1">
                                @php
                                    $selectedCategories = (array) request('category', []);
                                @endphp
                                @foreach($availableCategories as $cat)
                                    <label class="flex items-center justify-between cursor-pointer py-0.5 hover:text-accent transition-colors">
                                        <div class="flex items-center space-x-2.5">
                                            <input type="checkbox" name="category[]" value="{{ $cat->slug }}" 
                                                   {{ in_array($cat->slug, $selectedCategories) || in_array($cat->id, $selectedCategories) ? 'checked' : '' }}
                                                   onchange="applyFilter()"
                                                   class="rounded border-gray-300 text-dark accent-[#C79A58] focus:ring-accent w-4 h-4 cursor-pointer">
                                            <span class="font-medium text-black/80">{{ $cat->name }}</span>
                                        </div>
                                        <span class="text-[11px] text-black/40 font-semibold">({{ $cat->products_count }})</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <!-- 4. Brands (Markalar) -->
                        <div>
                            <label class="block text-[11px] font-extrabold text-black/80 uppercase tracking-wider mb-2.5">Marka</label>
                            <div class="space-y-2 text-xs max-h-44 overflow-y-auto pr-1">
                                @php
                                    $selectedBrands = (array) request('brand', []);
                                @endphp
                                @foreach($availableBrands as $brn)
                                    <label class="flex items-center justify-between cursor-pointer py-0.5 hover:text-accent transition-colors">
                                        <div class="flex items-center space-x-2.5">
                                            <input type="checkbox" name="brand[]" value="{{ $brn->slug }}" 
                                                   {{ in_array($brn->slug, $selectedBrands) || in_array($brn->id, $selectedBrands) ? 'checked' : '' }}
                                                   onchange="applyFilter()"
                                                   class="rounded border-gray-300 text-dark accent-[#C79A58] focus:ring-accent w-4 h-4 cursor-pointer">
                                            <span class="font-medium text-black/80">{{ $brn->name }}</span>
                                        </div>
                                        <span class="text-[11px] text-black/40 font-semibold">({{ $brn->products_count }})</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <!-- 5. Shoe Sizes (Numaralar) -->
                        <div>
                            <label class="block text-[11px] font-extrabold text-black/80 uppercase tracking-wider mb-2.5">Numara</label>
                            <div class="grid grid-cols-5 gap-1.5">
                                @php
                                    $selectedSizes = (array) request('size', []);
                                @endphp
                                @foreach($availableSizes as $sz)
                                    @php $isActiveSize = in_array($sz->size_number, $selectedSizes); @endphp
                                    <label class="cursor-pointer">
                                        <input type="checkbox" name="size[]" value="{{ $sz->size_number }}" 
                                               {{ $isActiveSize ? 'checked' : '' }}
                                               onchange="applyFilter()"
                                               class="peer sr-only">
                                        <div class="h-9 rounded-xl border border-black/10 bg-[#F9F9F8] flex items-center justify-center text-xs font-semibold text-black/80 transition-all peer-checked:bg-dark peer-checked:text-accent peer-checked:border-dark peer-checked:shadow-sm hover:border-accent hover:text-accent">
                                            {{ $sz->size_number }}
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <!-- 6. Colors (Renkler) -->
                        @if($availableColors->count() > 0)
                            <div>
                                <label class="block text-[11px] font-extrabold text-black/80 uppercase tracking-wider mb-2.5">Renk</label>
                                <div class="space-y-2 text-xs max-h-36 overflow-y-auto pr-1">
                                    @php $selectedColors = (array) request('color', []); @endphp
                                    @foreach($availableColors as $col)
                                        <label class="flex items-center space-x-2.5 cursor-pointer py-0.5 hover:text-accent transition-colors">
                                            <input type="checkbox" name="color[]" value="{{ $col }}" 
                                                   {{ in_array($col, $selectedColors) ? 'checked' : '' }}
                                                   onchange="applyFilter()"
                                                   class="rounded border-gray-300 text-dark accent-[#C79A58] focus:ring-accent w-4 h-4 cursor-pointer">
                                            <span class="font-medium text-black/80">{{ $col }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- 7. Price Range (Fiyat Aralığı) -->
                        <div>
                            <label class="block text-[11px] font-extrabold text-black/80 uppercase tracking-wider mb-2.5">Fiyat Aralığı (TL)</label>
                            <div class="grid grid-cols-2 gap-2">
                                <div class="relative">
                                    <input type="number" name="min_price" id="minPrice" placeholder="Min" value="{{ request('min_price') }}"
                                           class="w-full bg-[#F7F7F5] border border-black/10 rounded-xl px-3 py-2 text-xs focus:outline-none focus:border-accent"
                                           onchange="applyFilter()">
                                </div>
                                <div class="relative">
                                    <input type="number" name="max_price" id="maxPrice" placeholder="Maks" value="{{ request('max_price') }}"
                                           class="w-full bg-[#F7F7F5] border border-black/10 rounded-xl px-3 py-2 text-xs focus:outline-none focus:border-accent"
                                           onchange="applyFilter()">
                                </div>
                            </div>
                        </div>

                        <!-- 8. Special Checkboxes (İndirimli & Stokta Olanlar) -->
                        <div class="pt-3 border-t border-black/[0.06] space-y-2.5 text-xs">
                            <label class="flex items-center space-x-2.5 cursor-pointer hover:text-rose-600 transition-colors font-semibold">
                                <input type="checkbox" name="discounted" value="1" 
                                       {{ request('discounted') ? 'checked' : '' }}
                                       onchange="applyFilter()"
                                       class="rounded border-gray-300 text-rose-600 accent-rose-600 focus:ring-rose-500 w-4 h-4 cursor-pointer">
                                <span class="text-rose-600">Sadece İndirimli Ürünler</span>
                            </label>
                            <label class="flex items-center space-x-2.5 cursor-pointer hover:text-accent transition-colors font-medium">
                                <input type="checkbox" name="in_stock" value="1" 
                                       {{ request('in_stock') ? 'checked' : '' }}
                                       onchange="applyFilter()"
                                       class="rounded border-gray-300 text-dark accent-[#C79A58] focus:ring-accent w-4 h-4 cursor-pointer">
                                <span class="text-black/80">Sadece Stokta Olanlar</span>
                            </label>
                        </div>

                    </div>
                </form>
            </aside>

            <!-- PRODUCT LISTINGS (lg:col-span-9) -->
            <main class="lg:col-span-9 space-y-6">
                
                <!-- Compact Refined Sort & Count Toolbar -->
                <div class="bg-white rounded-2xl px-5 py-3.5 border border-black/[0.07] shadow-sm flex flex-col sm:flex-row items-center justify-between gap-4">
                    
                    <!-- Left: Product Count Info -->
                    <div class="text-xs sm:text-sm text-black/60 font-medium flex items-center space-x-2 w-full sm:w-auto justify-between sm:justify-start">
                        <span id="productCountText">
                            <strong class="text-dark font-bold">{{ $products->total() }}</strong> ürün bulundu
                        </span>

                        <!-- Mobile Filter Button Trigger (Visible on small screens) -->
                        <button type="button" onclick="toggleMobileFilter(true)" class="lg:hidden px-3.5 py-1.5 bg-dark text-white hover:bg-accent hover:text-dark text-xs font-bold rounded-xl transition-all flex items-center space-x-1.5 shadow-sm">
                            <i class="fa-solid fa-sliders text-accent text-[11px]"></i>
                            <span>Filtrele</span>
                        </button>
                    </div>

                    <!-- Right: Sort Dropdown -->
                    <div class="flex items-center space-x-2 w-full sm:w-auto justify-end">
                        <label for="sortSelect" class="text-xs text-black/50 font-medium hidden md:inline-block">
                            Sırala:
                        </label>
                        <select id="sortSelect" name="sort" onchange="applyFilter()" class="w-full sm:w-auto bg-[#F7F7F5] border border-black/10 rounded-xl px-3.5 py-2 text-xs font-semibold text-dark focus:outline-none focus:border-accent cursor-pointer transition-colors">
                            <option value="newest" {{ request('sort') === 'newest' ? 'selected' : '' }}>En Yeni Modeller</option>
                            <option value="popular" {{ request('sort') === 'popular' ? 'selected' : '' }}>En Çok Satan / Popüler</option>
                            <option value="price_asc" {{ request('sort') === 'price_asc' ? 'selected' : '' }}>Fiyat: Artan (Düşük > Yüksek)</option>
                            <option value="price_desc" {{ request('sort') === 'price_desc' ? 'selected' : '' }}>Fiyat: Azalan (Yüksek > Düşük)</option>
                            <option value="discount" {{ request('sort') === 'discount' ? 'selected' : '' }}>En Yüksek İndirim Oranı</option>
                        </select>
                    </div>
                </div>

                <!-- Product Grid Container -->
                <div id="productGridContainer" class="relative min-h-[420px]">
                    <!-- Spinner on AJAX -->
                    <div id="gridLoadingSpinner" class="hidden absolute inset-0 bg-white/75 backdrop-blur-xs z-20 flex items-center justify-center rounded-2xl">
                        <div class="flex flex-col items-center bg-white px-6 py-4 rounded-2xl shadow-xl border border-black/10">
                            <i class="fa-solid fa-circle-notch fa-spin text-accent text-3xl"></i>
                            <span class="text-xs font-bold text-dark mt-2.5">Modeller Güncelleniyor...</span>
                        </div>
                    </div>

                    <div id="gridInner">
                        @include('partials.product-grid', ['products' => $products])
                    </div>
                </div>

                <!-- Pagination -->
                <div id="paginationContainer" class="mt-8 flex justify-center">
                    {{ $products->links('pagination::tailwind') }}
                </div>

            </main>

        </div>
    </div>

    <!-- MOBILE FILTER DRAWER -->
    <div id="mobileFilterDrawer" onclick="if(event.target === this) toggleMobileFilter(false)" class="fixed inset-0 z-50 bg-black/80 backdrop-blur-sm hidden flex-col justify-end transition-opacity duration-300">
        <div class="bg-white rounded-t-3xl p-5 sm:p-6 max-h-[85vh] overflow-y-auto space-y-6 shadow-2xl" onclick="event.stopPropagation()">
            <div class="flex items-center justify-between border-b border-black/[0.06] pb-4 sticky top-0 bg-white z-10">
                <h3 class="font-display font-bold text-lg text-dark flex items-center">
                    <i class="fa-solid fa-sliders text-accent mr-2"></i> Ürün Filtreleri
                </h3>
                <button type="button" onclick="toggleMobileFilter(false)" class="w-9 h-9 rounded-xl bg-gray-100 hover:bg-gray-200 text-dark/70 flex items-center justify-center text-lg">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <!-- Mobile duplicate of filter form -->
            <div class="space-y-5">
                <!-- Mobile Search -->
                <div>
                    <label class="block text-xs font-bold text-dark uppercase tracking-wider mb-2">Model Arama</label>
                    <div class="relative">
                        <input type="text" id="mobileFilterSearch" placeholder="Model, kod veya renk..." 
                               value="{{ request('q') }}"
                               class="w-full bg-[#F7F7F5] border border-black/10 rounded-xl px-4 py-2.5 text-xs text-dark focus:outline-none focus:border-accent">
                    </div>
                </div>

                <!-- Mobile Gender -->
                <div>
                    <label class="block text-xs font-bold text-dark uppercase tracking-wider mb-2">Cinsiyet</label>
                    <div class="flex flex-wrap gap-2">
                        @foreach(['erkek' => 'Erkek', 'kadin' => 'Kadın', 'unisex' => 'Unisex'] as $gVal => $gLabel)
                            <label class="cursor-pointer">
                                <input type="checkbox" name="gender[]" value="{{ $gVal }}" 
                                       {{ in_array($gVal, (array) request('gender', [])) ? 'checked' : '' }}
                                       class="peer sr-only mobile-filter-input">
                                <div class="px-4 py-2.5 rounded-xl border border-black/10 text-xs font-semibold peer-checked:bg-dark peer-checked:text-accent peer-checked:border-dark">
                                    {{ $gLabel }}
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>

                <!-- Mobile Categories -->
                <div>
                    <label class="block text-xs font-bold text-dark uppercase tracking-wider mb-2">Kategoriler</label>
                    <div class="grid grid-cols-2 gap-2">
                        @foreach($availableCategories as $cat)
                            <label class="cursor-pointer">
                                <input type="checkbox" name="category[]" value="{{ $cat->slug }}" 
                                       {{ in_array($cat->slug, (array) request('category', [])) ? 'checked' : '' }}
                                       class="peer sr-only mobile-filter-input">
                                <div class="p-2.5 rounded-xl border border-black/10 text-xs font-semibold peer-checked:bg-dark peer-checked:text-accent peer-checked:border-dark text-center truncate">
                                    {{ $cat->name }}
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>

                <!-- Mobile Brands -->
                <div>
                    <label class="block text-xs font-bold text-dark uppercase tracking-wider mb-2">Markalar</label>
                    <div class="grid grid-cols-2 gap-2">
                        @foreach($availableBrands as $brn)
                            <label class="cursor-pointer">
                                <input type="checkbox" name="brand[]" value="{{ $brn->slug }}" 
                                       {{ in_array($brn->slug, (array) request('brand', [])) ? 'checked' : '' }}
                                       class="peer sr-only mobile-filter-input">
                                <div class="p-2.5 rounded-xl border border-black/10 text-xs font-semibold peer-checked:bg-dark peer-checked:text-accent peer-checked:border-dark text-center truncate">
                                    {{ $brn->name }}
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>

                <!-- Mobile Sizes -->
                <div>
                    <label class="block text-xs font-bold text-dark uppercase tracking-wider mb-2">Numara</label>
                    <div class="grid grid-cols-5 gap-1.5">
                        @foreach($availableSizes as $sz)
                            <label class="cursor-pointer">
                                <input type="checkbox" name="size[]" value="{{ $sz->size_number }}" 
                                       {{ in_array($sz->size_number, (array) request('size', [])) ? 'checked' : '' }}
                                       class="peer sr-only mobile-filter-input">
                                <div class="h-10 rounded-xl border border-black/10 flex items-center justify-center text-xs font-bold peer-checked:bg-dark peer-checked:text-accent peer-checked:border-dark">
                                    {{ $sz->size_number }}
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>

                <!-- Mobile Price Range -->
                <div>
                    <label class="block text-xs font-bold text-dark uppercase tracking-wider mb-2">Fiyat Aralığı (TL)</label>
                    <div class="grid grid-cols-2 gap-2">
                        <input type="number" id="mobileMinPrice" placeholder="Min" value="{{ request('min_price') }}"
                               class="w-full bg-[#F7F7F5] border border-black/10 rounded-xl px-3 py-2 text-xs focus:outline-none focus:border-accent">
                        <input type="number" id="mobileMaxPrice" placeholder="Maks" value="{{ request('max_price') }}"
                               class="w-full bg-[#F7F7F5] border border-black/10 rounded-xl px-3 py-2 text-xs focus:outline-none focus:border-accent">
                    </div>
                </div>

                <!-- Mobile Special Checks -->
                <div class="space-y-2 pt-2 border-t border-black/[0.06] text-xs">
                    <label class="flex items-center space-x-2.5 cursor-pointer font-semibold text-rose-600">
                        <input type="checkbox" id="mobileDiscounted" value="1" {{ request('discounted') ? 'checked' : '' }}
                               class="rounded border-gray-300 text-rose-600 accent-rose-600 w-4 h-4">
                        <span>Sadece İndirimli Modeller</span>
                    </label>
                    <label class="flex items-center space-x-2.5 cursor-pointer font-medium text-black/80">
                        <input type="checkbox" id="mobileInStock" value="1" {{ request('in_stock') ? 'checked' : '' }}
                               class="rounded border-gray-300 text-dark accent-[#C79A58] w-4 h-4">
                        <span>Sadece Stokta Olanlar</span>
                    </label>
                </div>

                <div class="pt-4 flex gap-3 sticky bottom-0 bg-white pb-2">
                    <button type="button" onclick="resetFilters(); toggleMobileFilter(false);" class="w-1/2 py-3.5 bg-gray-100 hover:bg-gray-200 text-dark text-xs font-bold rounded-xl transition-colors">
                        Sıfırla
                    </button>
                    <button type="button" onclick="applyMobileFilter(); toggleMobileFilter(false);" class="w-1/2 py-3.5 bg-accent hover:bg-accent-light text-dark text-xs font-bold rounded-xl shadow-lg transition-colors">
                        Filtreleri Uygula
                    </button>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
<script>
    let filterDebounce;

    function debounceFilter() {
        clearTimeout(filterDebounce);
        filterDebounce = setTimeout(() => {
            applyFilter();
        }, 400);
    }

    function toggleMobileFilter(open) {
        const drawer = document.getElementById('mobileFilterDrawer');
        if (!drawer) return;
        if (open) {
            drawer.classList.remove('hidden');
            drawer.classList.add('flex');
            document.body.style.overflow = 'hidden';
        } else {
            drawer.classList.add('hidden');
            drawer.classList.remove('flex');
            document.body.style.overflow = '';
        }
    }

    function applyMobileFilter() {
        const form = document.getElementById('filterForm');
        // sync mobile checkbox inputs to desktop form
        document.querySelectorAll('.mobile-filter-input').forEach(input => {
            const corresponding = form.querySelector(`input[name="${input.name}"][value="${input.value}"]`);
            if (corresponding) {
                corresponding.checked = input.checked;
            }
        });
        // sync search & price inputs
        const mSearch = document.getElementById('mobileFilterSearch');
        const dSearch = document.getElementById('filterSearch');
        if (mSearch && dSearch) dSearch.value = mSearch.value;

        const mMin = document.getElementById('mobileMinPrice');
        const dMin = document.getElementById('minPrice');
        if (mMin && dMin) dMin.value = mMin.value;

        const mMax = document.getElementById('mobileMaxPrice');
        const dMax = document.getElementById('maxPrice');
        if (mMax && dMax) dMax.value = mMax.value;

        const mDisc = document.getElementById('mobileDiscounted');
        const dDisc = form.querySelector('input[name="discounted"]');
        if (mDisc && dDisc) dDisc.checked = mDisc.checked;

        const mStock = document.getElementById('mobileInStock');
        const dStock = form.querySelector('input[name="in_stock"]');
        if (mStock && dStock) dStock.checked = mStock.checked;

        applyFilter();
    }

    function resetFilters() {
        const form = document.getElementById('filterForm');
        form.reset();
        form.querySelectorAll('input[type="checkbox"]').forEach(c => c.checked = false);
        form.querySelectorAll('input[type="text"], input[type="number"]').forEach(i => i.value = '');
        document.querySelectorAll('.mobile-filter-input').forEach(c => c.checked = false);
        applyFilter();
    }

    function applyFilter(pageUrl = null) {
        const form = document.getElementById('filterForm');
        const formData = new FormData(form);
        const params = new URLSearchParams(formData);

        const sortVal = document.getElementById('sortSelect')?.value;
        if (sortVal) {
            params.set('sort', sortVal);
        }

        const url = pageUrl || `${window.location.pathname}?${params.toString()}`;
        
        // Show spinner
        document.getElementById('gridLoadingSpinner')?.classList.remove('hidden');

        // Update URL in browser without refresh
        window.history.pushState({}, '', url);

        fetch(url, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            document.getElementById('gridLoadingSpinner')?.classList.add('hidden');
            if (data.html) {
                document.getElementById('gridInner').innerHTML = data.html;
            }
            if (data.pagination) {
                document.getElementById('paginationContainer').innerHTML = data.pagination;
            }
            if (data.count_text) {
                document.getElementById('productCountText').innerHTML = data.count_text;
            }
            // re-bind favorites
            updateFavoritesBadge();
        })
        .catch(err => {
            document.getElementById('gridLoadingSpinner')?.classList.add('hidden');
            console.error(err);
        });
    }

    // Intercept pagination clicks for AJAX
    document.addEventListener('click', function(e) {
        const paginationLink = e.target.closest('#paginationContainer a');
        if (paginationLink) {
            e.preventDefault();
            applyFilter(paginationLink.href);
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }
    });
</script>
@endpush
