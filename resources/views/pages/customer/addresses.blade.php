@extends('layouts.app')

@section('title', 'Adres Defterim | Yusuf Akboğa Ayakkabı')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <!-- Breadcrumb -->
    <nav class="flex items-center space-x-2 text-xs text-white/50 mb-6">
        <a href="{{ route('home') }}" class="hover:text-accent">Ana Sayfa</a>
        <i class="fa-solid fa-chevron-right text-[10px]"></i>
        <a href="{{ route('customer.dashboard') }}" class="hover:text-accent">Hesabım</a>
        <i class="fa-solid fa-chevron-right text-[10px]"></i>
        <span class="text-accent font-semibold">Adres Defterim</span>
    </nav>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        <!-- Sidebar Navigation -->
        <div class="lg:col-span-1">
            <div class="bg-[#161616] border border-white/10 rounded-2xl p-6 shadow-xl space-y-6 sticky top-24">
                <div class="flex items-center space-x-3 pb-6 border-b border-white/10">
                    <div class="w-12 h-12 rounded-xl bg-accent/15 text-accent font-bold text-lg flex items-center justify-center border border-accent/30">
                        {{ strtoupper(mb_substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    <div class="overflow-hidden">
                        <h2 class="font-display font-bold text-sm text-white truncate">{{ auth()->user()->name }}</h2>
                        <p class="text-xs text-white/50 truncate">{{ auth()->user()->email }}</p>
                    </div>
                </div>

                <nav class="space-y-1.5 text-xs font-medium">
                    <a href="{{ route('customer.dashboard') }}" class="flex items-center px-4 py-3 rounded-xl text-white/80 hover:bg-white/5 hover:text-accent transition-colors">
                        <i class="fa-solid fa-gauge w-5 text-sm text-accent"></i>
                        <span>Hesap Özeti</span>
                    </a>
                    <a href="{{ route('customer.orders') }}" class="flex items-center px-4 py-3 rounded-xl text-white/80 hover:bg-white/5 hover:text-accent transition-colors">
                        <i class="fa-solid fa-box-open w-5 text-sm text-accent"></i>
                        <span>Siparişlerim</span>
                    </a>
                    <a href="{{ route('customer.addresses') }}" class="flex items-center px-4 py-3 rounded-xl bg-accent text-dark font-bold shadow-md">
                        <i class="fa-solid fa-location-dot w-5 text-sm"></i>
                        <span>Adres Defterim</span>
                    </a>
                    <a href="{{ route('customer.profile') }}" class="flex items-center px-4 py-3 rounded-xl text-white/80 hover:bg-white/5 hover:text-accent transition-colors">
                        <i class="fa-solid fa-user-pen w-5 text-sm text-accent"></i>
                        <span>Profil & Güvenlik</span>
                    </a>
                </nav>
            </div>
        </div>

        <!-- Addresses Main Content -->
        <div class="lg:col-span-3 space-y-6">
            <div class="bg-[#161616] border border-white/10 rounded-2xl p-6 shadow-xl space-y-6">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-white/10 pb-4">
                    <div>
                        <h1 class="font-display font-bold text-xl text-white">Kayıtlı Adreslerim</h1>
                        <p class="text-xs text-white/50 mt-0.5">Siparişlerinizde kolayca kullanabileceğiniz teslimat adreslerinizi yönetin.</p>
                    </div>
                    <button type="button" onclick="openNewAddressModal()" class="px-4 py-2.5 bg-accent hover:bg-accent-light text-dark font-bold text-xs rounded-xl transition-colors shadow flex items-center space-x-2">
                        <i class="fa-solid fa-plus"></i>
                        <span>Yeni Adres Ekle</span>
                    </button>
                </div>

                @if($addresses->isEmpty())
                    <div class="text-center py-12 space-y-3">
                        <div class="w-16 h-16 rounded-full bg-white/5 text-white/40 flex items-center justify-center mx-auto text-2xl">
                            <i class="fa-solid fa-map-location-dot"></i>
                        </div>
                        <p class="text-sm text-white/60">Henüz kayıtlı bir adresiniz bulunmamaktadır.</p>
                        <button onclick="openNewAddressModal()" class="px-5 py-2.5 bg-white/10 hover:bg-white/15 text-white text-xs font-bold rounded-xl transition-colors">
                            İlk Adresinizi Ekleyin
                        </button>
                    </div>
                @else
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($addresses as $addr)
                            <div class="bg-dark/60 border border-white/10 rounded-2xl p-5 space-y-3 relative hover:border-accent/40 transition-colors">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-2">
                                        <h3 class="font-bold text-white text-sm">{{ $addr->title }}</h3>
                                        @if($addr->is_default)
                                            <span class="text-[10px] bg-accent/20 text-accent font-bold px-2 py-0.5 rounded">Varsayılan</span>
                                        @endif
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <form method="POST" action="{{ route('customer.addresses.destroy', $addr->id) }}" onsubmit="return confirm('Bu adresi silmek istediğinize emin misiniz?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-white/40 hover:text-rose-400 text-xs p-1" title="Adresi Sil">
                                                <i class="fa-solid fa-trash-can"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>

                                <div class="text-xs text-white/70 space-y-1">
                                    <p class="font-semibold text-white/90">{{ $addr->full_name }}</p>
                                    <p class="text-white/60">{{ $addr->phone }}</p>
                                    <p class="leading-relaxed">{{ $addr->address }}</p>
                                    <p class="text-accent font-medium">{{ $addr->district }} / {{ $addr->city }}</p>
                                </div>

                                <div class="pt-3 border-t border-white/5 flex items-center justify-between">
                                    @if(!$addr->is_default)
                                        <form method="POST" action="{{ route('customer.addresses.set-default', $addr->id) }}">
                                            @csrf
                                            <button type="submit" class="text-[11px] text-accent hover:underline flex items-center">
                                                <i class="fa-regular fa-star mr-1"></i> Varsayılan Yap
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-[11px] text-emerald-400 flex items-center">
                                            <i class="fa-solid fa-check mr-1"></i> Birincil Adres
                                        </span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- New Address Modal -->
<div id="addressModal" class="fixed inset-0 z-50 bg-black/80 backdrop-blur-sm hidden items-center justify-center p-4">
    <div class="bg-[#161616] border border-white/10 w-full max-w-lg rounded-2xl p-6 sm:p-8 shadow-2xl relative text-white space-y-5">
        <div class="flex items-center justify-between border-b border-white/10 pb-4">
            <h3 class="font-display font-bold text-lg text-white flex items-center">
                <i class="fa-solid fa-location-dot text-accent mr-2.5"></i> Yeni Adres Ekle
            </h3>
            <button onclick="closeAddressModal()" class="text-white/60 hover:text-white text-xl">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <form method="POST" action="{{ route('customer.addresses.store') }}" class="space-y-4">
            @csrf
            <div>
                <label for="title" class="block text-xs font-semibold text-white/80 uppercase tracking-wider mb-1.5">Adres Başlığı</label>
                <input type="text" name="title" id="title" required placeholder="Örn: Evim, İşyeri" class="w-full bg-dark/70 text-white text-sm px-4 py-3 rounded-xl border border-white/10 focus:outline-none focus:border-accent">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="full_name" class="block text-xs font-semibold text-white/80 uppercase tracking-wider mb-1.5">Alıcı Adı Soyadı</label>
                    <input type="text" name="full_name" id="full_name" required value="{{ auth()->user()->name }}" class="w-full bg-dark/70 text-white text-sm px-4 py-3 rounded-xl border border-white/10 focus:outline-none focus:border-accent">
                </div>
                <div>
                    <label for="phone" class="block text-xs font-semibold text-white/80 uppercase tracking-wider mb-1.5">Telefon Numarası</label>
                    <input type="tel" name="phone" id="phone" required value="{{ auth()->user()->phone }}" placeholder="0532 123 45 67" class="w-full bg-dark/70 text-white text-sm px-4 py-3 rounded-xl border border-white/10 focus:outline-none focus:border-accent">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="city" class="block text-xs font-semibold text-white/80 uppercase tracking-wider mb-1.5">Şehir (İl)</label>
                    <input type="text" name="city" id="city" required placeholder="Örn: İstanbul" class="w-full bg-dark/70 text-white text-sm px-4 py-3 rounded-xl border border-white/10 focus:outline-none focus:border-accent">
                </div>
                <div>
                    <label for="district" class="block text-xs font-semibold text-white/80 uppercase tracking-wider mb-1.5">İlçe</label>
                    <input type="text" name="district" id="district" required placeholder="Örn: Kadıköy" class="w-full bg-dark/70 text-white text-sm px-4 py-3 rounded-xl border border-white/10 focus:outline-none focus:border-accent">
                </div>
            </div>

            <div>
                <label for="address" class="block text-xs font-semibold text-white/80 uppercase tracking-wider mb-1.5">Açık Adres</label>
                <textarea name="address" id="address" rows="3" required placeholder="Cadde, sokak, bina ve daire numarası..." class="w-full bg-dark/70 text-white text-sm px-4 py-3 rounded-xl border border-white/10 focus:outline-none focus:border-accent"></textarea>
            </div>

            <div class="flex items-center">
                <input type="checkbox" name="is_default" id="is_default" value="1" class="w-4 h-4 rounded bg-dark border-white/20 text-accent focus:ring-accent">
                <label for="is_default" class="ml-2.5 text-xs text-white/80 select-none cursor-pointer">Varsayılan teslimat adresi olarak ayarla</label>
            </div>

            <button type="submit" class="w-full py-3.5 bg-accent hover:bg-accent-light text-dark font-display font-bold text-sm rounded-xl transition-colors shadow-lg">
                Adresi Kaydet
            </button>
        </form>
    </div>
</div>

<script>
    function openNewAddressModal() {
        const modal = document.getElementById('addressModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }
    function closeAddressModal() {
        const modal = document.getElementById('addressModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }
</script>
@endsection
