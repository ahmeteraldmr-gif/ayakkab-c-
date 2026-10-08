@extends('layouts.admin')

@section('title', 'İletişim Mesajları | VELORA Yönetim Paneli')
@section('page_title', 'İletişim Mesajları')

@section('content')

    <div class="space-y-6">
        
        <p class="text-xs text-gray-500">Müşterilerden iletişim formu üzerinden gelen mesajlar ve talepler.</p>

        <div class="bg-white border border-[#E5E7EB] rounded-2xl shadow-xs overflow-hidden">
            <table class="w-full text-left text-xs sm:text-[13px]">
                <thead class="bg-[#F9FAFB] text-gray-500 font-semibold border-b border-[#E5E7EB] text-[11px] uppercase tracking-wider">
                    <tr>
                        <th class="py-3.5 px-4">Gönderen</th>
                        <th class="py-3.5 px-4">İletişim</th>
                        <th class="py-3.5 px-4">Konu</th>
                        <th class="py-3.5 px-4">Mesaj Özeti</th>
                        <th class="py-3.5 px-4">Durum</th>
                        <th class="py-3.5 px-4">Tarih</th>
                        <th class="py-3.5 px-4 text-right">İşlemler</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#F3F4F6]">
                    @forelse($messages as $msg)
                        <tr class="hover:bg-[#F8FAFC] transition-colors {{ !$msg->is_read ? 'bg-blue-50/40' : '' }}">
                            <td class="py-3.5 px-4 font-bold text-[#111827]">
                                {{ $msg->name }}
                            </td>
                            <td class="py-3.5 px-4 text-gray-600">
                                {{ $msg->email_or_phone }}
                            </td>
                            <td class="py-3.5 px-4 text-blue-600 font-medium">
                                {{ $msg->subject ?? 'Genel İletişim' }}
                            </td>
                            <td class="py-3.5 px-4 text-gray-500 truncate max-w-xs">
                                {{ Str::limit($msg->message, 45) }}
                            </td>
                            <td class="py-3.5 px-4">
                                @if(!$msg->is_read)
                                    <span class="px-2.5 py-0.5 rounded-full bg-blue-50 text-blue-700 border border-blue-200 text-[11px] font-bold">
                                        Yeni / Okunmadı
                                    </span>
                                @else
                                    <span class="px-2.5 py-0.5 rounded-full bg-gray-100 text-gray-500 text-[11px]">
                                        Okundu
                                    </span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-gray-400 text-xs">
                                {{ $msg->created_at->format('d.m.Y H:i') }}
                            </td>
                            <td class="py-3.5 px-4 text-right space-x-1.5">
                                <a href="{{ route('admin.messages.show', $msg->id) }}" class="p-2 bg-blue-50 hover:bg-blue-100 text-blue-600 rounded-lg transition-colors inline-block" title="Mesajı Oku">
                                    <i class="fa-solid fa-envelope-open-text text-xs"></i>
                                </a>
                                <form method="POST" action="{{ route('admin.messages.destroy', $msg->id) }}" class="inline-block" onsubmit="return confirm('Bu mesajı silmek istediğinize emin misiniz?');">
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
                            <td colspan="7" class="py-10 text-center text-gray-400">Mesaj bulunamadı.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="p-4 border-t border-[#E5E7EB] bg-[#F9FAFB]">
                {{ $messages->links('pagination::tailwind') }}
            </div>
        </div>

    </div>

@endsection
