@extends('layouts.admin')

@section('title', 'Kampanya & Banner Yönetimi | VELORA Yönetim Paneli')
@section('page_title', 'Kampanya & Banner Yönetimi')

@section('content')

    <div class="space-y-6">
        
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <p class="text-xs text-gray-500">Ana sayfadaki hero alanlarını ve kampanya tanıtım bannerlarını yönetin.</p>
            <a href="{{ route('admin.campaigns.create') }}" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-xs rounded-lg transition-all shadow-xs flex items-center space-x-1.5 self-start sm:self-auto">
                <i class="fa-solid fa-plus text-[11px]"></i>
                <span>Yeni Kampanya / Banner</span>
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @forelse($campaigns as $camp)
                <div class="bg-white border border-[#E5E7EB] rounded-2xl p-6 shadow-xs space-y-4 relative overflow-hidden flex flex-col justify-between">
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="px-2.5 py-1 rounded-md bg-blue-50 text-blue-700 border border-blue-100 text-xs font-bold uppercase">
                                {{ $camp->badge ?? 'Kampanya' }}
                            </span>
                            <span class="px-2.5 py-1 rounded-full {{ $camp->is_active ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-red-50 text-red-700 border border-red-200' }} text-[11px] font-bold">
                                {{ $camp->is_active ? 'Yayında' : 'Pasif' }}
                            </span>
                        </div>

                        <h3 class="font-sans font-bold text-lg text-[#111827]">
                            {{ $camp->title }}
                        </h3>

                        @if($camp->subtitle)
                            <p class="text-xs text-gray-500 leading-relaxed">{{ $camp->subtitle }}</p>
                        @endif

                        @if($camp->image_url)
                            <div class="h-36 w-full rounded-xl overflow-hidden bg-[#F8FAFC] border border-[#E5E7EB]">
                                <img src="{{ $camp->image_url }}" alt="{{ $camp->title }}" class="w-full h-full object-cover">
                            </div>
                        @endif
                    </div>

                    <div class="pt-4 border-t border-[#E5E7EB] flex items-center justify-between">
                        <span class="text-[11px] text-gray-400">Sıra: {{ $camp->sort_order }}</span>
                        <div class="space-x-1.5">
                            <a href="{{ route('admin.campaigns.edit', $camp->id) }}" class="px-3 py-1.5 bg-blue-50 text-blue-600 hover:bg-blue-100 rounded-lg text-xs font-semibold transition-colors inline-block">
                                <i class="fa-solid fa-pen mr-1 text-[11px]"></i> Düzenle
                            </a>
                            <form method="POST" action="{{ route('admin.campaigns.destroy', $camp->id) }}" class="inline-block" onsubmit="return confirm('Bu kampanyayı silmek istediğinize emin misiniz?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 bg-red-50 hover:bg-red-100 text-red-600 rounded-lg transition-colors" title="Sil">
                                    <i class="fa-solid fa-trash text-xs"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-2 bg-white border border-[#E5E7EB] rounded-2xl p-12 text-center text-gray-400 text-xs shadow-xs">
                    Henüz kayıtlı kampanya bulunmuyor.
                </div>
            @endforelse
        </div>

    </div>

@endsection
