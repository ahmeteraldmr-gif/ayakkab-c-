@extends('layouts.app')

@section('title', '500 - Sunucu Hatası | VELORA')

@section('content')
<div class="min-h-[60vh] flex items-center justify-center py-16 px-4">
    <div class="max-w-md w-full text-center space-y-6 bg-white p-8 sm:p-10 rounded-3xl border border-black/5 shadow-xl">
        <div class="w-20 h-20 mx-auto rounded-3xl bg-rose-500/10 text-rose-600 flex items-center justify-center text-3xl shadow-lg border border-rose-500/20">
            <i class="fa-solid fa-triangle-exclamation"></i>
        </div>

        <div class="space-y-2">
            <span class="text-rose-600 font-extrabold text-xs uppercase tracking-widest">Hata 500</span>
            <h1 class="font-display font-extrabold text-2xl sm:text-3xl text-dark">
                Beklenmeyen Bir Hata Oluştu
            </h1>
            <p class="text-black/60 text-xs sm:text-sm leading-relaxed">
                İsteğiniz işlenirken sunucumuzda geçici bir sorun meydana geldi. Teknik ekibimiz durumdan haberdardır.
            </p>
        </div>

        <div class="pt-4 flex flex-col sm:flex-row gap-3 justify-center">
            <a href="{{ route('home') }}" class="w-full sm:w-auto px-6 py-3.5 bg-dark hover:bg-accent text-white hover:text-dark font-display font-bold text-xs uppercase tracking-wider rounded-xl transition-all shadow-md flex items-center justify-center space-x-2">
                <i class="fa-solid fa-house text-xs"></i>
                <span>Ana Sayfaya Dön</span>
            </a>
            <button type="button" onclick="window.location.reload()" class="w-full sm:w-auto px-6 py-3.5 bg-[#F7F7F5] hover:bg-gray-200 text-dark font-display font-bold text-xs uppercase tracking-wider rounded-xl transition-all flex items-center justify-center space-x-2">
                <i class="fa-solid fa-rotate-right text-xs"></i>
                <span>Yeniden Dene</span>
            </button>
        </div>
    </div>
</div>
@endsection
