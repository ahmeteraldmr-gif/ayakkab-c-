@extends('layouts.admin')

@section('title', 'Gelişmiş Satış ve Mağaza Raporları | Yönetim Paneli')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-[#111827]">Satış ve Gelir Raporları</h1>
            <p class="text-xs text-[#6B7280] mt-1">Dönemsel satış performansı, iptal/iade oranları ve en çok satan ayakkabı modelleri.</p>
        </div>

        <a href="{{ route('admin.reports.export-csv', request()->query()) }}" class="px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold transition-colors shadow-sm flex items-center space-x-2">
            <i class="fa-solid fa-file-csv text-sm"></i>
            <span>CSV Olarak Dışa Aktar</span>
        </a>
    </div>

    <!-- Filter Card -->
    <div class="bg-white rounded-2xl border border-gray-200 p-5 shadow-sm">
        <form method="GET" action="{{ route('admin.reports.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3">
            <div>
                <label class="block text-[11px] font-bold text-gray-500 uppercase mb-1">Başlangıç Tarihi</label>
                <input type="date" name="start_date" value="{{ $startDate }}" class="w-full text-xs bg-gray-50 border border-gray-200 rounded-xl px-3 py-2 focus:outline-none focus:border-blue-600">
            </div>

            <div>
                <label class="block text-[11px] font-bold text-gray-500 uppercase mb-1">Bitiş Tarihi</label>
                <input type="date" name="end_date" value="{{ $endDate }}" class="w-full text-xs bg-gray-50 border border-gray-200 rounded-xl px-3 py-2 focus:outline-none focus:border-blue-600">
            </div>

            <div>
                <label class="block text-[11px] font-bold text-gray-500 uppercase mb-1">Kategori</label>
                <select name="category_id" class="w-full text-xs bg-gray-50 border border-gray-200 rounded-xl px-3 py-2 focus:outline-none focus:border-blue-600">
                    <option value="">Tüm Kategoriler</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-[11px] font-bold text-gray-500 uppercase mb-1">Marka</label>
                <select name="brand_id" class="w-full text-xs bg-gray-50 border border-gray-200 rounded-xl px-3 py-2 focus:outline-none focus:border-blue-600">
                    <option value="">Tüm Markalar</option>
                    @foreach($brands as $b)
                        <option value="{{ $b->id }}" {{ request('brand_id') == $b->id ? 'selected' : '' }}>{{ $b->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex items-end space-x-2">
                <button type="submit" class="w-full py-2 bg-[#2563EB] hover:bg-[#1D4ED8] text-white rounded-xl text-xs font-bold transition-colors shadow-sm">
                    <i class="fa-solid fa-filter mr-1"></i> Filtrele
                </button>
                <a href="{{ route('admin.reports.index') }}" class="px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-600 rounded-xl text-xs font-bold transition-colors" title="Sıfırla">
                    <i class="fa-solid fa-rotate-right"></i>
                </a>
            </div>
        </form>
    </div>

    <!-- Summary Metrics -->
    <div class="grid grid-cols-2 lg:grid-cols-6 gap-4">
        <div class="bg-white rounded-2xl border border-gray-200 p-5 shadow-sm">
            <span class="text-[11px] font-bold text-gray-500 uppercase block">Toplam Ciro</span>
            <div class="text-xl font-extrabold text-blue-600 mt-1">{{ number_format($metrics['total_revenue'], 2, ',', '.') }} TL</div>
        </div>

        <div class="bg-white rounded-2xl border border-gray-200 p-5 shadow-sm">
            <span class="text-[11px] font-bold text-gray-500 uppercase block">Tamamlanan Satış</span>
            <div class="text-xl font-extrabold text-emerald-600 mt-1">{{ $metrics['delivered_orders'] }} Sipariş</div>
        </div>

        <div class="bg-white rounded-2xl border border-gray-200 p-5 shadow-sm">
            <span class="text-[11px] font-bold text-gray-500 uppercase block">Toplam Sipariş</span>
            <div class="text-xl font-extrabold text-gray-900 mt-1">{{ $metrics['total_orders'] }}</div>
        </div>

        <div class="bg-white rounded-2xl border border-gray-200 p-5 shadow-sm">
            <span class="text-[11px] font-bold text-gray-500 uppercase block">Satılan Ayakkabı</span>
            <div class="text-xl font-extrabold text-indigo-600 mt-1">{{ $metrics['units_sold'] }} Adet</div>
        </div>

        <div class="bg-white rounded-2xl border border-gray-200 p-5 shadow-sm">
            <span class="text-[11px] font-bold text-gray-500 uppercase block">İptal Oranı</span>
            <div class="text-xl font-extrabold text-rose-600 mt-1">%{{ $metrics['cancellation_rate'] }}</div>
        </div>

        <div class="bg-white rounded-2xl border border-gray-200 p-5 shadow-sm">
            <span class="text-[11px] font-bold text-gray-500 uppercase block">İade Oranı</span>
            <div class="text-xl font-extrabold text-amber-600 mt-1">%{{ $metrics['return_rate'] }}</div>
        </div>
    </div>

    <!-- Chart & Top Products Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Revenue Chart -->
        <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-200 p-6 shadow-sm space-y-4">
            <div class="flex items-center justify-between border-b border-gray-100 pb-3">
                <h3 class="font-bold text-sm text-gray-900 flex items-center">
                    <i class="fa-solid fa-chart-area text-blue-600 mr-2"></i> Günlük Gelir Trendi (TL)
                </h3>
            </div>
            <div class="h-72">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>

        <!-- Top Selling Products -->
        <div class="bg-white rounded-2xl border border-gray-200 p-6 shadow-sm space-y-4">
            <h3 class="font-bold text-sm text-gray-900 border-b border-gray-100 pb-3 flex items-center">
                <i class="fa-solid fa-trophy text-amber-500 mr-2"></i> Çok Satan Modeller
            </h3>

            @if($topSellingProducts->isEmpty())
                <p class="text-xs text-gray-500 text-center py-10">Döneme ait satış verisi bulunamadı.</p>
            @else
                <div class="divide-y divide-gray-100 text-xs">
                    @foreach($topSellingProducts as $top)
                        <div class="py-2.5 flex items-center justify-between gap-3">
                            <div class="overflow-hidden">
                                <h4 class="font-bold text-gray-900 truncate">{{ $top->product_name }}</h4>
                                <span class="text-gray-500 text-[11px]">{{ $top->total_qty }} Adet Satıldı</span>
                            </div>
                            <span class="font-bold text-blue-600 text-right">{{ number_format($top->total_revenue, 2, ',', '.') }} TL</span>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('revenueChart').getContext('2d');
        const labels = {!! json_encode($chartLabels) !!};
        const data = {!! json_encode($chartValues) !!};

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Günlük Ciro (TL)',
                    data: data,
                    borderColor: '#2563EB',
                    backgroundColor: 'rgba(37, 99, 235, 0.08)',
                    fill: true,
                    tension: 0.35,
                    borderWidth: 2.5,
                    pointBackgroundColor: '#2563EB',
                    pointRadius: 3,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: '#F1F5F9' },
                        ticks: {
                            callback: function(value) {
                                return value.toLocaleString('tr-TR') + ' TL';
                            }
                        }
                    },
                    x: {
                        grid: { display: false }
                    }
                }
            }
        });
    });
</script>
@endsection
