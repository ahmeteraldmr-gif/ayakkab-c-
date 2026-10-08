@extends('layouts.admin')

@section('title', 'Markayı Düzenle | VELORA Yönetim Paneli')
@section('page_title')
    Markayı Düzenle: {{ $brand->name }}
@endsection

@section('content')

    <div class="max-w-2xl bg-white border border-[#E5E7EB] rounded-xl p-6 sm:p-8 shadow-sm space-y-6">
        <form method="POST" action="{{ route('admin.brands.update', $brand->id) }}" class="space-y-5">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-2">Marka Adı <span class="text-rose-500">*</span></label>
                <input type="text" name="name" value="{{ old('name', $brand->name) }}" required 
                       class="w-full bg-white border border-[#D1D5DB] rounded-lg px-4 py-2.5 text-sm text-slate-900 focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/10 transition-all">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-2">Slug (URL)</label>
                <input type="text" name="slug" value="{{ old('slug', $brand->slug) }}" 
                       class="w-full bg-white border border-[#D1D5DB] rounded-lg px-4 py-2.5 text-sm text-slate-900 focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/10 font-mono transition-all">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-2">Açıklama</label>
                <textarea name="description" rows="3" class="w-full bg-white border border-[#D1D5DB] rounded-lg p-3 text-sm text-slate-900 focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/10 transition-all">{{ old('description', $brand->description) }}</textarea>
            </div>

            <div class="pt-2">
                <label class="flex items-center space-x-2.5 cursor-pointer text-sm font-medium text-slate-700">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $brand->is_active) ? 'checked' : '' }} class="rounded border-[#D1D5DB] text-blue-600 focus:ring-blue-500 w-4 h-4">
                    <span>Aktif Olarak Yayınla</span>
                </label>
            </div>

            <div class="pt-5 flex items-center justify-between border-t border-[#E5E7EB]">
                <a href="{{ route('admin.brands.index') }}" class="text-sm font-medium text-slate-600 hover:text-slate-900 transition-colors">İptal</a>
                <button type="submit" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-medium text-sm rounded-lg shadow-sm transition-all flex items-center space-x-2">
                    <i class="fa-solid fa-check text-xs"></i>
                    <span>Değişiklikleri Kaydet</span>
                </button>
            </div>
        </form>
    </div>

@endsection
