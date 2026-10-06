@extends('layouts.admin')

@section('title', 'Yeni Marka Ekle | Yusuf Akboğa Yönetim Paneli')
@section('page_title', 'Yeni Marka Ekle')

@section('content')

    <div class="max-w-2xl bg-white border border-[#E5E7EB] rounded-2xl p-6 sm:p-8 shadow-xs space-y-6">
        <form method="POST" action="{{ route('admin.brands.store') }}" class="space-y-4">
            @csrf

            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Marka Adı <span class="text-red-500">*</span></label>
                <input type="text" name="name" value="{{ old('name') }}" required placeholder="Örn: New Balance" 
                       class="w-full bg-white border border-[#D1D5DB] rounded-lg px-3.5 py-2.5 text-xs text-[#111827] focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20">
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Slug (URL)</label>
                <input type="text" name="slug" value="{{ old('slug') }}" placeholder="Boş bırakılırsa otomatik üretilir" 
                       class="w-full bg-white border border-[#D1D5DB] rounded-lg px-3.5 py-2.5 text-xs text-[#111827] focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20 font-mono">
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Açıklama</label>
                <textarea name="description" rows="3" class="w-full bg-white border border-[#D1D5DB] rounded-lg p-3 text-xs text-[#111827] focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20">{{ old('description') }}</textarea>
            </div>

            <div class="pt-2">
                <label class="flex items-center space-x-2 cursor-pointer text-xs font-semibold text-[#111827]">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} class="rounded border-gray-300 text-blue-600 focus:ring-blue-500 w-4 h-4">
                    <span>Aktif Olarak Yayınla</span>
                </label>
            </div>

            <div class="pt-4 flex items-center justify-between border-t border-[#E5E7EB]">
                <a href="{{ route('admin.brands.index') }}" class="text-xs font-semibold text-gray-500 hover:text-gray-900">İptal</a>
                <button type="submit" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-xs rounded-lg transition-colors shadow-xs">
                    Markayı Kaydet
                </button>
            </div>
        </form>
    </div>

@endsection
