@extends('layouts.admin')

@section('title', 'Kategoriyi Düzenle | Yusuf Akboğa Yönetim Paneli')
@section('page_title', 'Kategoriyi Düzenle: ' . $category->name)

@section('content')

    <div class="max-w-2xl bg-white border border-[#E5E7EB] rounded-2xl p-6 sm:p-8 shadow-xs space-y-6">
        <form method="POST" action="{{ route('admin.categories.update', $category->id) }}" enctype="multipart/form-data" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Kategori Adı <span class="text-red-500">*</span></label>
                <input type="text" name="name" value="{{ old('name', $category->name) }}" required 
                       class="w-full bg-white border border-[#D1D5DB] rounded-lg px-3.5 py-2.5 text-xs text-[#111827] focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20">
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Slug (URL)</label>
                <input type="text" name="slug" value="{{ old('slug', $category->slug) }}" 
                       class="w-full bg-white border border-[#D1D5DB] rounded-lg px-3.5 py-2.5 text-xs text-[#111827] focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20 font-mono">
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Açıklama</label>
                <textarea name="description" rows="3" class="w-full bg-white border border-[#D1D5DB] rounded-lg p-3 text-xs text-[#111827] focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20">{{ old('description', $category->description) }}</textarea>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Sıralama</label>
                    <input type="number" name="sort_order" value="{{ old('sort_order', $category->sort_order) }}" 
                           class="w-full bg-white border border-[#D1D5DB] rounded-lg px-3.5 py-2 text-xs text-[#111827] focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20">
                </div>

                <div class="flex items-center pt-6">
                    <label class="flex items-center space-x-2 cursor-pointer text-xs font-semibold text-[#111827]">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $category->is_active) ? 'checked' : '' }} class="rounded border-gray-300 text-blue-600 focus:ring-blue-500 w-4 h-4">
                        <span>Aktif Olarak Yayınla</span>
                    </label>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Görseli Değiştir</label>
                @if($category->image)
                    <div class="mb-3">
                        <img src="{{ str_starts_with($category->image, 'http') ? $category->image : asset('storage/' . $category->image) }}" class="w-20 h-20 rounded-xl object-cover border border-[#E5E7EB]">
                    </div>
                @endif
                <input type="file" name="image" accept="image/*" 
                       class="w-full bg-[#F8FAFC] border border-[#D1D5DB] rounded-lg p-2.5 text-xs text-gray-700 file:mr-4 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer">
            </div>

            <div class="pt-4 flex items-center justify-between border-t border-[#E5E7EB]">
                <a href="{{ route('admin.categories.index') }}" class="text-xs font-semibold text-gray-500 hover:text-gray-900">İptal</a>
                <button type="submit" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-xs rounded-lg transition-colors shadow-xs">
                    Değişiklikleri Kaydet
                </button>
            </div>
        </form>
    </div>

@endsection
