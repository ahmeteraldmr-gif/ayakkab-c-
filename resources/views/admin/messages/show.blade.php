@extends('layouts.admin')

@section('title', 'Mesaj Detayı | VELORA Yönetim Paneli')
@section('page_title', 'Mesaj Detayı')

@section('content')

    <div class="space-y-6">
        <a href="{{ route('admin.messages.index') }}" class="text-xs font-semibold text-gray-500 hover:text-blue-600 flex items-center transition-colors">
            <i class="fa-solid fa-arrow-left mr-2"></i> Tüm Mesajlara Dön
        </a>

        <div class="max-w-3xl bg-white border border-[#E5E7EB] rounded-2xl p-6 sm:p-8 shadow-xs space-y-6">
            <div class="flex items-center justify-between border-b border-[#E5E7EB] pb-4">
                <div>
                    <h3 class="font-sans font-bold text-lg text-[#111827]">{{ $message->subject ?? 'Genel İletişim' }}</h3>
                    <p class="text-xs text-gray-400 mt-0.5">Gönderim Tarihi: {{ $message->created_at->format('d.m.Y H:i:s') }}</p>
                </div>

                <form method="POST" action="{{ route('admin.messages.destroy', $message->id) }}" onsubmit="return confirm('Bu mesajı silmek istediğinize emin misiniz?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-3 py-1.5 bg-red-50 hover:bg-red-100 text-red-600 rounded-lg text-xs font-semibold transition-colors">
                        <i class="fa-solid fa-trash mr-1 text-[11px]"></i> Mesajı Sil
                    </button>
                </form>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 bg-[#F8FAFC] border border-[#E2E8F0] p-4 rounded-xl text-xs">
                <div>
                    <span class="text-gray-400 block font-medium">Gönderen Ad Soyad:</span>
                    <strong class="text-[#111827] text-sm block mt-0.5">{{ $message->name }}</strong>
                </div>
                <div>
                    <span class="text-gray-400 block font-medium">İletişim (Telefon / E-posta):</span>
                    <strong class="text-blue-600 text-sm block mt-0.5">{{ $message->email_or_phone }}</strong>
                </div>
            </div>

            <div class="space-y-2">
                <span class="text-xs font-bold text-gray-500 uppercase tracking-wider block">Mesaj İçeriği:</span>
                <div class="p-5 rounded-xl bg-[#F8FAFC] border border-[#E2E8F0] text-gray-800 text-sm leading-relaxed whitespace-pre-line">
                    {{ $message->message }}
                </div>
            </div>

            <!-- Quick WhatsApp reply button if contains digits -->
            @php
                $digits = preg_replace('/[^0-9]/', '', $message->email_or_phone);
            @endphp
            @if(strlen($digits) >= 10)
                <div class="pt-4 border-t border-[#E5E7EB]">
                    <a href="https://wa.me/{{ strlen($digits) == 10 ? '90' . $digits : $digits }}?text={{ urlencode('Merhaba ' . $message->name . ', VELORA web sitemiz üzerinden ilettiğiniz mesajınızla ilgili yazıyoruz.') }}" 
                       target="_blank"
                       class="inline-flex items-center px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-xs font-semibold transition-colors shadow-xs">
                        <i class="fa-brands fa-whatsapp mr-2 text-base"></i> WhatsApp Üzerinden Yanıtla
                    </a>
                </div>
            @endif
        </div>
    </div>

@endsection
