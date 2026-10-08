@extends('layouts.admin')

@section('title', 'Kategori Yönetimi | VELORA Yönetim Paneli')
@section('page_title', 'Kategori Yönetimi')

@section('content')

    <div class="space-y-6">
        
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <p class="text-xs text-gray-500">Mağazadaki ayakkabı kategorilerini listeleyin, ekleyin veya düzenleyin.</p>
            <a href="{{ route('admin.categories.create') }}" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-xs rounded-lg transition-all shadow-xs flex items-center space-x-1.5 self-start sm:self-auto">
                <i class="fa-solid fa-plus text-[11px]"></i>
                <span>Yeni Kategori</span>
            </a>
        </div>

        <div class="bg-white border border-[#E5E7EB] rounded-2xl shadow-xs overflow-hidden">
            <table class="w-full text-left text-xs sm:text-[13px]">
                <thead class="bg-[#F9FAFB] text-gray-500 font-semibold border-b border-[#E5E7EB] text-[11px] uppercase tracking-wider">
                    <tr>
                        <th class="py-3.5 px-4">Görsel</th>
                        <th class="py-3.5 px-4">Kategori Adı</th>
                        <th class="py-3.5 px-4">Slug (URL)</th>
                        <th class="py-3.5 px-4">Ürün Sayısı</th>
                        <th class="py-3.5 px-4">Sıra</th>
                        <th class="py-3.5 px-4">Durum</th>
                        <th class="py-3.5 px-4 text-right">İşlemler</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#F3F4F6]">
                    @forelse($categories as $cat)
                        <tr class="hover:bg-[#F8FAFC] transition-colors">
                            <td class="py-3 px-4 w-16">
                                <img src="{{ $cat->image ? (str_starts_with($cat->image, 'http') ? $cat->image : asset('storage/' . $cat->image)) : 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=200' }}" class="w-12 h-12 rounded-xl object-cover border border-[#E5E7EB]">
                            </td>
                            <td class="py-3 px-4 font-bold text-[#111827] text-sm">
                                {{ $cat->name }}
                            </td>
                            <td class="py-3 px-4 font-mono text-gray-400 text-[11px]">
                                /urunler?category={{ $cat->slug }}
                            </td>
                            <td class="py-3 px-4">
                                <span class="px-2.5 py-1 rounded-md bg-blue-50 text-blue-700 font-bold border border-blue-100 text-xs">
                                    {{ $cat->products_count }} Ürün
                                </span>
                            </td>
                            <td class="py-3 px-4 text-gray-600">
                                {{ $cat->sort_order }}
                            </td>
                            <td class="py-3 px-4">
                                <span class="px-2.5 py-1 rounded-full {{ $cat->is_active ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-red-50 text-red-700 border border-red-200' }} text-[11px] font-bold">
                                    {{ $cat->is_active ? 'Aktif' : 'Pasif' }}
                                </span>
                            </td>
                            <td class="py-3 px-4 text-right space-x-1.5">
                                <a href="{{ route('admin.categories.edit', $cat->id) }}" class="p-2 bg-blue-50 hover:bg-blue-100 text-blue-600 rounded-lg transition-colors inline-block" title="Düzenle">
                                    <i class="fa-solid fa-pen-to-square text-xs"></i>
                                </a>
                                <form method="POST" action="{{ route('admin.categories.destroy', $cat->id) }}" class="inline-block" onsubmit="return confirm('Bu kategoriyi silmek istediğinize emin misiniz?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 bg-red-50 hover:bg-red-100 text-red-600 rounded-lg transition-colors" title="Sil">
                                        <i class="fa-solid fa-trash text-xs"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-10 text-center text-gray-400">Kategori bulunamadı.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>

@endsection
