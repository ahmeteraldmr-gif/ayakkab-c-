@extends('layouts.app')

@section('title', '404 - Sayfa Bulunamadı | Yusuf Akboğa Ayakkabı')

@section('content')
<div class="min-h-[60vh] flex items-center justify-center py-16 px-4">
    <div class="max-w-md w-full text-center space-y-6 bg-white p-8 sm:p-10 rounded-3xl border border-black/5 shadow-xl">
        <div class="w-20 h-20 mx-auto rounded-3xl bg-dark text-accent flex items-center justify-center text-3xl shadow-lg shadow-black/10">
            <i class="fa-solid fa-shoe-prints -rotate-45"></i>
        </div>

        <div class="space-y-2">
            <span class="text-accent font-extrabold text-xs uppercase tracking-widest">Hata 404</span>
            <h1 class="font-display font-extrabold text-2xl sm:text-3xl text-dark">
                Aradığınız Sayfa Bulunamadı
            </h1>
            <p class="text-black/60 text-xs sm:text-sm leading-relaxed">
                Ulaşmaya çalıştığınız sayfa kaldırılmış, adı değiştirilmiş ya da geçici olarak kullanım dışı kalmış olabilir.
            </p>
        </div>

        <div class="pt-4 flex flex-col sm:flex-row gap-3 justify-center">
            <a href="{{ route('home') }}" class="w-full sm:w-auto px-6 py-3.5 bg-dark hover:bg-accent text-white hover:text-dark font-display font-bold text-xs uppercase tracking-wider rounded-xl transition-all shadow-md flex items-center justify-center space-x-2">
                <i class="fa-solid fa-house text-xs"></i>
                <span>Ana Sayfaya Dön</span>
            </a>
            <a href="{{ route('products.index') }}" class="w-full sm:w-auto px-6 py-3.5 bg-[#F7F7F5] hover:bg-gray-200 text-dark font-display font-bold text-xs uppercase tracking-wider rounded-xl transition-all flex items-center justify-center space-x-2">
                <i class="fa-solid fa-bag-shopping text-xs"></i>
                <span>Modelleri İncele</span>
            </a>
        </div>
    </div>
</div>
@endsection
