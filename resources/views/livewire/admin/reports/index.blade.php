<div class="min-h-screen bg-light py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <h2 class="text-3xl font-bold bg-gradient-to-r from-primary to-secondary bg-clip-text text-transparent">
                Laporan SPT & SPPD
            </h2>
            <p class="mt-2 text-sm text-muted">Analisis dan statistik Surat Perintah Tugas dan SPPD</p>
        </div>

        <!-- Report Type Selector -->
        <div class="card mb-6">
            <div class="card-body">
                <div class="flex flex-col sm:flex-row gap-4 items-start sm:items-center justify-between">
                    <div class="flex-1 w-full sm:w-auto">
                        <label class="form-label mb-2">Jenis Laporan</label>
                        <div class="inline-flex rounded-lg border border-gray-200 p-1 bg-white w-full sm:w-auto">
                            <button wire:click="changeReportType('spt')"
                                class="flex-1 sm:flex-initial px-6 py-2.5 rounded-md text-sm font-medium transition-all duration-200 {{ $reportType === 'spt' ? 'bg-gradient-to-r from-primary to-secondary text-white shadow-md' : 'text-gray-600 hover:text-gray-900' }}">
                                <svg class="w-5 h-5 inline-block mr-2" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                Surat Perintah Tugas
                            </button>
                            <button wire:click="changeReportType('sppd')"
                                class="flex-1 sm:flex-initial px-6 py-2.5 rounded-md text-sm font-medium transition-all duration-200 {{ $reportType === 'sppd' ? 'bg-gradient-to-r from-primary to-secondary text-white shadow-md' : 'text-gray-600 hover:text-gray-900' }}">
                                <svg class="w-5 h-5 inline-block mr-2" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                                SPPD
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters Card -->
        <div class="card mb-6" x-data="{ showFilters: true }">
            <div class="card-header">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-primary flex items-center gap-2">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                        </svg>
                        Filter Laporan
                    </h3>
                    <button @click="showFilters = !showFilters"
                        class="btn-primary inline-flex items-center justify-center text-sm px-3 py-1">
                        <span x-show="!showFilters">Tampilkan Filter</span>
                        <span x-show="showFilters">Sembunyikan Filter</span>
                    </button>
                </div>
            </div>
            <div x-show="showFilters" x-cloak class="card-body">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <!-- Start Date -->
                    <div>
                        <label class="form-label">
                            <svg class="h-4 w-4 inline mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            Tanggal Mulai
                        </label>
                        <input wire:model="startDate" type="date" class="form-input">
                    </div>

                    <!-- End Date -->
                    <div>
                        <label class="form-label">
                            <svg class="h-4 w-4 inline mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            Tanggal Akhir
                        </label>
                        <input wire:model="endDate" type="date" class="form-input">
                    </div>

                    <!-- Instance Filter -->
                    @if (auth()->user()->instance_id == null)
                        <div wire:ignore>
                            <label class="form-label">Perangkat Daerah</label>
                            <select id="instanceFilterReport" class="form-select select2-filter" style="width: 100%">
                                <option value="">Semua Perangkat Daerah</option>
                                @foreach ($instances as $instance)
                                    <option value="{{ $instance['id'] }}">{{ $instance['name'] }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif

                    <!-- Status Filter -->
                    <div wire:ignore>
                        <label class="form-label">Status</label>
                        <select id="statusFilterReport" class="form-select select2-filter" style="width: 100%">
                            <option value="">Semua Status</option>
                            <option value="draft">Draft</option>
                            <option value="sent">Terkirim</option>
                            <option value="approved">Ditandatangani</option>
                            <option value="rejected">Ditolak</option>
                        </select>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row gap-3 mt-6">
                    <button wire:click="generateReport"
                        class="btn-primary inline-flex items-center justify-center flex-1 sm:flex-initial">
                        <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                        Tampilkan Laporan
                    </button>
                    <button wire:click="resetFilters"
                        class="btn-secondary inline-flex items-center justify-center flex-1 sm:flex-initial">
                        <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        Reset Filter
                    </button>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        @if (!empty($statistics))
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                <!-- Total Card -->
                <div
                    class="bg-gradient-to-br from-navy to-blue-light rounded-xl shadow-lg p-6 text-white transform hover:scale-105 transition-transform duration-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium opacity-90">Total
                                {{ $reportType === 'spt' ? 'SPT' : 'SPPD' }}
                            </p>
                            <p class="text-3xl font-bold mt-2">{{ number_format($statistics['total']) }}</p>
                            <p class="text-xs opacity-75 mt-2">Periode yang dipilih</p>
                        </div>
                        <div class="bg-white bg-opacity-20 rounded-full p-4">
                            <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Draft Card -->
                <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-gray-500">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-gray-100 rounded-full p-3">
                            <x-heroicon-o-hand-raised class="w-6 h-6 text-gray-600" />
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Draft</p>
                            <p class="text-2xl font-bold text-gray-900">{{ number_format($statistics['draft']) }}</p>
                        </div>
                    </div>
                </div>

                <!-- Sent Card -->
                <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-orange-500">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-orange-100 rounded-full p-3">
                            <x-heroicon-o-clock class="w-6 h-6 text-orange-600" />
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Menunggu Tanda Tangan</p>
                            <p class="text-2xl font-bold text-gray-900">{{ number_format($statistics['sent']) }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Approved Card -->
                <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-green-500">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-green-100 rounded-full p-3">
                            <x-heroicon-o-finger-print class="w-6 h-6 text-green-600" />
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Ditandatangani</p>
                            <p class="text-2xl font-bold text-gray-900">{{ number_format($statistics['approved']) }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Completed/Additional Card -->
                @if ($reportType === 'spt')
                    <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-blue-500">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-blue-100 rounded-full p-3">
                                <svg class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600">Total SPPD</p>
                                <p class="text-2xl font-bold text-gray-900">
                                    {{ number_format($statistics['total_sppd'] ?? 0) }}</p>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-purple-500">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-purple-100 rounded-full p-3">
                                <svg class="h-6 w-6 text-purple-600" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600">Total Biaya</p>
                                <p class="text-xl font-bold text-gray-900">
                                    Rp {{ number_format($statistics['total_biaya'] ?? 0, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Charts Row -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                <!-- Status Distribution -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="text-lg font-semibold text-primary">Distribusi Status</h3>
                    </div>
                    <div class="card-body">
                        <canvas id="statusChart" height="300"></canvas>
                    </div>
                </div>

                <!-- Monthly Trend -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="text-lg font-semibold text-primary">Trend Bulanan</h3>
                    </div>
                    <div class="card-body">
                        @if (!empty($statistics['by_month']) && count($statistics['by_month']) > 0)
                            <div class="space-y-3">
                                @php
                                    $maxValue = collect($statistics['by_month'])->max('count') ?: 1;
                                @endphp
                                @foreach ($statistics['by_month'] as $data)
                                    @php
                                        $percentage = ($data['count'] / $maxValue) * 100;
                                    @endphp
                                    <div>
                                        <div class="flex items-center justify-between mb-1">
                                            <span
                                                class="text-sm font-medium text-gray-700">{{ $data['month'] }}</span>
                                            <span class="text-sm font-bold text-navy">{{ $data['count'] }}</span>
                                        </div>
                                        <div class="w-full bg-gray-200 rounded-full h-3 overflow-hidden">
                                            <div class="bg-gradient-to-r from-navy to-blue-light h-3 rounded-full transition-all duration-500"
                                                style="width: {{ $percentage }}%"></div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8 text-gray-500">
                                <p>Tidak ada data untuk periode yang dipilih</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- By Instance -->
            <div class="card mb-8">
                <div class="card-header">
                    <h3 class="text-lg font-semibold text-primary">Berdasarkan Perangkat Daerah</h3>
                </div>
                <div class="card-body">
                    @if (!empty($statistics['by_instance']) && count($statistics['by_instance']) > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            No</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Perangkat Daerah</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Jumlah</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Persentase</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach ($statistics['by_instance'] as $index => $item)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $index + 1 }}</td>
                                            <td class="px-6 py-4 text-sm text-gray-900">{{ $item['name'] }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                {{ number_format($item['count']) }}
                                                {{ $reportType === 'spt' ? 'SPT' : 'SPPD' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ number_format(($item['count'] / $statistics['total']) * 100, 1) }}%
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-8 text-gray-500">
                            <p>Tidak ada data untuk periode yang dipilih</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Export Actions -->
            {{-- <div class="card">
                <div class="card-body">
                    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Export Laporan</h3>
                            <p class="text-sm text-muted mt-1">Unduh laporan dalam format Excel atau PDF</p>
                        </div>
                        <div class="flex gap-3">
                            <button wire:click="$set('exportFormat', 'excel')" wire:then="exportReport"
                                class="btn-success inline-flex items-center justify-center">
                                <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                Export Excel
                            </button>
                            <button wire:click="$set('exportFormat', 'pdf')" wire:then="exportReport"
                                class="btn-danger inline-flex items-center justify-center">
                                <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                </svg>
                                Export PDF
                            </button>
                        </div>
                    </div>
                </div>
            </div> --}}
        @else
            <!-- Empty State -->
            <div class="card">
                <div class="card-body">
                    <div class="text-center py-12">
                        <div class="mx-auto h-24 w-24 text-muted mb-4">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">Belum Ada Laporan</h3>
                        <p class="text-sm text-muted mb-6">
                            Silakan pilih filter dan klik "Tampilkan Laporan" untuk melihat statistik
                        </p>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
        let statusChart = null;

        function initializeSelect2() {
            $('#instanceFilterReport').select2({
                placeholder: 'Semua Perangkat Daerah',
                allowClear: true,
                width: '100%'
            }).on('change', function(e) {
                @this.set('instanceFilter', $(this).val());
            });

            $('#statusFilterReport').select2({
                placeholder: 'Semua Status',
                allowClear: true,
                width: '100%',
                minimumResultsForSearch: -1
            }).on('change', function(e) {
                @this.set('statusFilter', $(this).val());
            });
        }

        function renderCharts() {
            // Get fresh data from Livewire component
            const chartData = @this.chartData;
            console.log('Rendering chart with data:', chartData);

            if (chartData && chartData.status && chartData.status.data) {
                const ctx = document.getElementById('statusChart');
                if (ctx) {
                    // Destroy existing chart if it exists
                    if (statusChart) {
                        statusChart.destroy();
                        statusChart = null;
                    }

                    // Only create chart if there's data
                    const hasData = chartData.status.data.some(value => value > 0);
                    if (hasData) {
                        statusChart = new Chart(ctx, {
                            type: 'doughnut',
                            data: {
                                labels: chartData.status.labels,
                                datasets: [{
                                    data: chartData.status.data,
                                    backgroundColor: [
                                        '#9CA3AF', // Draft - Gray
                                        '#F59E0B', // Sent - Orange
                                        '#10B981', // Approved - Green
                                        '#EF4444', // Rejected - Red
                                        '#3B82F6', // Completed - Blue
                                    ],
                                    borderWidth: 2,
                                    borderColor: '#ffffff'
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: {
                                    legend: {
                                        position: 'bottom',
                                        labels: {
                                            padding: 15,
                                            font: {
                                                size: 12
                                            }
                                        }
                                    },
                                    tooltip: {
                                        callbacks: {
                                            label: function(context) {
                                                let label = context.label || '';
                                                if (label) {
                                                    label += ': ';
                                                }
                                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                                const percentage = total > 0 ? Math.round((context.parsed / total) * 100) : 0;
                                                label += context.parsed + ' (' + percentage + '%)';
                                                return label;
                                            }
                                        }
                                    }
                                }
                            }
                        });
                        console.log('Chart created successfully');
                    } else {
                        console.log('No data to display in chart');
                    }
                } else {
                    console.log('Canvas element not found');
                }
            } else {
                console.log('Chart data not available or invalid');
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            initializeSelect2();

            // Render charts when statistics are loaded on initial page load
            @if (!empty($statistics) && !empty($chartData))
                setTimeout(() => renderCharts(), 100);
            @endif
        });

        // Listen for Livewire navigation finished
        document.addEventListener('livewire:navigated', function() {
            initializeSelect2();
            setTimeout(() => renderCharts(), 100);
        });

        // Listen for Livewire updates - this is the key for reactive updates
        Livewire.hook('morph.updated', () => {
            console.log('Livewire morph updated, re-rendering chart...');
            setTimeout(() => {
                initializeSelect2();
                renderCharts();
            }, 150);
        });

        // Listen for custom events
        window.addEventListener('filtersReset', event => {
            $('#instanceFilterReport').val('').trigger('change');
            $('#statusFilterReport').val('').trigger('change');
            if (statusChart) {
                statusChart.destroy();
                statusChart = null;
            }
        });

        window.addEventListener('reportGenerated', event => {
            console.log('Report generated event received');
            setTimeout(() => {
                renderCharts();
            }, 200);
        });
    </script>
@endpush
