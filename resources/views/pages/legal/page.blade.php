@extends('layouts.app')

@section('title', $title . ' | Yusuf Akboğa Ayakkabı')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <!-- Breadcrumb -->
    <nav class="flex items-center space-x-2 text-xs text-white/50 mb-6">
        <a href="{{ route('home') }}" class="hover:text-accent">Ana Sayfa</a>
        <i class="fa-solid fa-chevron-right text-[10px]"></i>
        <span class="text-white/60">Yasal Bilgilendirme</span>
        <i class="fa-solid fa-chevron-right text-[10px]"></i>
        <span class="text-accent font-semibold">{{ $title }}</span>
    </nav>

    <!-- Content Card -->
    <div class="bg-[#161616] border border-white/10 rounded-2xl p-6 sm:p-10 shadow-2xl space-y-8">
        <div class="border-b border-white/10 pb-6">
            <span class="text-xs font-semibold text-accent uppercase tracking-widest">Yasal Bilgilendirme & Şartlar</span>
            <h1 class="font-display font-black text-2xl sm:text-3xl text-white mt-1.5">{{ $title }}</h1>
            <p class="text-xs text-white/50 mt-1">Son Güncelleme: 2026 • Yusuf Akboğa Ayakkabı & Ayakkabıcılık</p>
        </div>

        <div class="prose prose-invert max-w-none text-white/80 text-sm leading-relaxed space-y-6">
            {!! $content !!}
        </div>

        <div class="pt-6 border-t border-white/10 flex flex-wrap items-center justify-between gap-4 text-xs text-white/50">
            <div>
                Sorularınız veya talepleriniz için: <a href="mailto:{{ \App\Models\Setting::get('site_email', 'info@yusufakboga.com') }}" class="text-accent underline">{{ \App\Models\Setting::get('site_email', 'info@yusufakboga.com') }}</a>
            </div>
            <a href="{{ route('home') }}" class="text-accent hover:underline flex items-center">
                <i class="fa-solid fa-arrow-left mr-1.5"></i> Ana Sayfaya Dön
            </a>
        </div>
    </div>
</div>
@endsection
