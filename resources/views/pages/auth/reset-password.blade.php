@extends('layouts.app')

@section('title', 'Yeni Şifre Belirle | Yusuf Akboğa Ayakkabı')

@section('content')
<div class="min-h-[75vh] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full bg-[#161616] border border-white/10 rounded-2xl p-8 shadow-2xl space-y-6">
        <div class="text-center space-y-2">
            <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-accent/10 border border-accent/30 text-accent mb-2">
                <i class="fa-solid fa-lock-open text-2xl"></i>
            </div>
            <h1 class="font-display font-bold text-2xl text-white">Yeni Şifre Belirle</h1>
            <p class="text-xs text-white/60">Lütfen hesabınız için yeni ve güvenli bir şifre belirleyin.</p>
        </div>

        @if($errors->any())
            <div class="bg-rose-500/10 border border-rose-500/30 text-rose-400 p-4 rounded-xl text-xs space-y-1">
                @foreach($errors->all() as $error)
                    <p><i class="fa-solid fa-circle-exclamation mr-1.5"></i> {{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('password.update') }}" class="space-y-4">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">

            <div>
                <label for="email" class="block text-xs font-semibold text-white/80 uppercase tracking-wider mb-1.5">E-posta Adresi</label>
                <div class="relative">
                    <input type="email" id="email" name="email" value="{{ $email ?? old('email') }}" required autofocus
                           placeholder="ornek@email.com"
                           class="w-full bg-dark/60 text-white placeholder-white/30 text-sm px-4 py-3.5 pl-10 rounded-xl border border-white/10 focus:outline-none focus:border-accent">
                    <i class="fa-solid fa-envelope absolute left-3.5 top-4 text-white/40 text-xs"></i>
                </div>
            </div>

            <div>
                <label for="password" class="block text-xs font-semibold text-white/80 uppercase tracking-wider mb-1.5">Yeni Şifre</label>
                <div class="relative">
                    <input type="password" id="password" name="password" required
                           placeholder="••••••••"
                           class="w-full bg-dark/60 text-white placeholder-white/30 text-sm px-4 py-3.5 pl-10 rounded-xl border border-white/10 focus:outline-none focus:border-accent">
                    <i class="fa-solid fa-lock absolute left-3.5 top-4 text-white/40 text-xs"></i>
                </div>
            </div>

            <div>
                <label for="password_confirmation" class="block text-xs font-semibold text-white/80 uppercase tracking-wider mb-1.5">Yeni Şifre Tekrarı</label>
                <div class="relative">
                    <input type="password" id="password_confirmation" name="password_confirmation" required
                           placeholder="••••••••"
                           class="w-full bg-dark/60 text-white placeholder-white/30 text-sm px-4 py-3.5 pl-10 rounded-xl border border-white/10 focus:outline-none focus:border-accent">
                    <i class="fa-solid fa-shield-check absolute left-3.5 top-4 text-white/40 text-xs"></i>
                </div>
            </div>

            <button type="submit" class="w-full py-4 bg-accent hover:bg-accent-light text-dark font-display font-bold text-sm rounded-xl transition-colors shadow-lg flex items-center justify-center space-x-2">
                <span>Şifremi Güncelle ve Giriş Yap</span>
                <i class="fa-solid fa-check"></i>
            </button>
        </form>
    </div>
</div>
@endsection
