@extends('layouts.admin')

@section('title', 'Marka Yönetimi | Yusuf Akboğa Yönetim Paneli')
@section('page_title', 'Marka Yönetimi')

@section('content')

    <div class="space-y-6">
        
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <p class="text-xs text-gray-500">Mağazadaki ayakkabı markalarını yönetin (Nike, Adidas, Puma, New Balance vb.).</p>
            <a href="{{ route('admin.brands.create') }}" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-xs rounded-lg transition-all shadow-xs flex items-center space-x-1.5 self-start sm:self-auto">
                <i class="fa-solid fa-plus text-[11px]"></i>
                <span>Yeni Marka</span>
            </a>
        </div>

        <div class="bg-white border border-[#E5E7EB] rounded-2xl shadow-xs overflow-hidden">
            <table class="w-full text-left text-xs sm:text-[13px]">
                <thead class="bg-[#F9FAFB] text-gray-500 font-semibold border-b border-[#E5E7EB] text-[11px] uppercase tracking-wider">
                    <tr>
                        <th class="py-3.5 px-4">Marka Adı</th>
                        <th class="py-3.5 px-4">Slug</th>
                        <th class="py-3.5 px-4">Ürün Sayısı</th>
                        <th class="py-3.5 px-4">Durum</th>
                        <th class="py-3.5 px-4 text-right">İşlemler</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#F3F4F6]">
                    @forelse($brands as $brand)
                        <tr class="hover:bg-[#F8FAFC] transition-colors">
                            <td class="py-3.5 px-4 font-bold text-[#111827] text-sm">
                                {{ $brand->name }}
                            </td>
                            <td class="py-3.5 px-4 font-mono text-gray-400 text-[11px]">
                                /urunler?brand={{ $brand->slug }}
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="px-2.5 py-1 rounded-md bg-blue-50 text-blue-700 font-bold border border-blue-100 text-xs">
                                    {{ $brand->products_count }} Ürün
                                </span>
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="px-2.5 py-1 rounded-full {{ $brand->is_active ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-red-50 text-red-700 border border-red-200' }} text-[11px] font-bold">
                                    {{ $brand->is_active ? 'Aktif' : 'Pasif' }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-right space-x-1.5">
                                <a href="{{ route('admin.brands.edit', $brand->id) }}" class="p-2 bg-blue-50 hover:bg-blue-100 text-blue-600 rounded-lg transition-colors inline-block" title="Düzenle">
                                    <i class="fa-solid fa-pen-to-square text-xs"></i>
                                </a>
                                <form method="POST" action="{{ route('admin.brands.destroy', $brand->id) }}" class="inline-block" onsubmit="return confirm('Bu markayı silmek istediğinize emin misiniz?');">
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
                            <td colspan="5" class="py-10 text-center text-gray-400">Marka bulunamadı.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>

@endsection
