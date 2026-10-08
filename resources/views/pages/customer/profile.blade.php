@extends('layouts.app')

@section('title', 'Profil & Güvenlik | VELORA')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <!-- Breadcrumb -->
    <nav class="flex items-center space-x-2 text-xs text-white/50 mb-6">
        <a href="{{ route('home') }}" class="hover:text-accent">Ana Sayfa</a>
        <i class="fa-solid fa-chevron-right text-[10px]"></i>
        <a href="{{ route('customer.dashboard') }}" class="hover:text-accent">Hesabım</a>
        <i class="fa-solid fa-chevron-right text-[10px]"></i>
        <span class="text-accent font-semibold">Profil & Güvenlik</span>
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
                    <a href="{{ route('customer.addresses') }}" class="flex items-center px-4 py-3 rounded-xl text-white/80 hover:bg-white/5 hover:text-accent transition-colors">
                        <i class="fa-solid fa-location-dot w-5 text-sm text-accent"></i>
                        <span>Adres Defterim</span>
                    </a>
                    <a href="{{ route('customer.profile') }}" class="flex items-center px-4 py-3 rounded-xl bg-accent text-dark font-bold shadow-md">
                        <i class="fa-solid fa-user-pen w-5 text-sm"></i>
                        <span>Profil & Güvenlik</span>
                    </a>
                </nav>
            </div>
        </div>

        <!-- Profile Forms -->
        <div class="lg:col-span-3 space-y-8">
            <!-- Account Info Form -->
            <div class="bg-[#161616] border border-white/10 rounded-2xl p-6 sm:p-8 shadow-xl space-y-6">
                <div class="border-b border-white/10 pb-4">
                    <h2 class="font-display font-bold text-lg text-white">Hesap Bilgileri</h2>
                    <p class="text-xs text-white/50 mt-0.5">Kişisel bilgilerinizi güncelleyebilirsiniz.</p>
                </div>

                <form method="POST" action="{{ route('customer.profile.update') }}" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="name" class="block text-xs font-semibold text-white/80 uppercase tracking-wider mb-1.5">Ad Soyad</label>
                            <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required class="w-full bg-dark/70 text-white text-sm px-4 py-3 rounded-xl border border-white/10 focus:outline-none focus:border-accent">
                        </div>

                        <div>
                            <label for="phone" class="block text-xs font-semibold text-white/80 uppercase tracking-wider mb-1.5">Telefon Numarası</label>
                            <input type="tel" name="phone" id="phone" value="{{ old('phone', $user->phone) }}" placeholder="0532 123 45 67" class="w-full bg-dark/70 text-white text-sm px-4 py-3 rounded-xl border border-white/10 focus:outline-none focus:border-accent">
                        </div>
                    </div>

                    <div>
                        <label for="email" class="block text-xs font-semibold text-white/80 uppercase tracking-wider mb-1.5">E-posta Adresi</label>
                        <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required class="w-full bg-dark/70 text-white text-sm px-4 py-3 rounded-xl border border-white/10 focus:outline-none focus:border-accent">
                    </div>

                    <div class="pt-2 flex justify-end">
                        <button type="submit" class="px-6 py-3 bg-accent hover:bg-accent-light text-dark font-bold text-xs rounded-xl transition-colors shadow">
                            Bilgileri Güncelle
                        </button>
                    </div>
                </form>
            </div>

            <!-- Password Change Form -->
            <div class="bg-[#161616] border border-white/10 rounded-2xl p-6 sm:p-8 shadow-xl space-y-6">
                <div class="border-b border-white/10 pb-4">
                    <h2 class="font-display font-bold text-lg text-white">Şifre Değiştir</h2>
                    <p class="text-xs text-white/50 mt-0.5">Hesap güvenliğiniz için şifrenizi düzenli aralıklarla değiştirin.</p>
                </div>

                <form method="POST" action="{{ route('customer.password.update') }}" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label for="current_password" class="block text-xs font-semibold text-white/80 uppercase tracking-wider mb-1.5">Mevcut Şifre</label>
                        <input type="password" name="current_password" id="current_password" required placeholder="••••••••" class="w-full bg-dark/70 text-white text-sm px-4 py-3 rounded-xl border border-white/10 focus:outline-none focus:border-accent">
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="password" class="block text-xs font-semibold text-white/80 uppercase tracking-wider mb-1.5">Yeni Şifre (En az 6 karakter)</label>
                            <input type="password" name="password" id="password" required placeholder="••••••••" class="w-full bg-dark/70 text-white text-sm px-4 py-3 rounded-xl border border-white/10 focus:outline-none focus:border-accent">
                        </div>

                        <div>
                            <label for="password_confirmation" class="block text-xs font-semibold text-white/80 uppercase tracking-wider mb-1.5">Yeni Şifre Tekrarı</label>
                            <input type="password" name="password_confirmation" id="password_confirmation" required placeholder="••••••••" class="w-full bg-dark/70 text-white text-sm px-4 py-3 rounded-xl border border-white/10 focus:outline-none focus:border-accent">
                        </div>
                    </div>

                    <div class="pt-2 flex justify-end">
                        <button type="submit" class="px-6 py-3 bg-white/10 hover:bg-white/20 text-white font-bold text-xs rounded-xl transition-colors border border-white/10">
                            Şifremi Değiştir
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
