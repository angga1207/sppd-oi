<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Dashboard</h1>
            <p class="mt-2 text-sm text-gray-600">Selamat datang di Sistem Informasi SPPD</p>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Total SPPD Card -->
            <div class="bg-gradient-to-br from-navy to-blue-light rounded-xl shadow-lg p-6 text-white transform hover:scale-105 transition-transform duration-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium opacity-90">Total SPPD</p>
                        <p class="text-3xl font-bold mt-2">{{ $stats['total_sppd'] }}</p>
                        <p class="text-xs opacity-75 mt-2">Semua periode</p>
                    </div>
                    <div class="bg-white bg-opacity-20 rounded-full p-4">
                        <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Pending SPPD Card -->
            <div class="bg-gradient-to-br from-yellow-400 to-orange-500 rounded-xl shadow-lg p-6 text-white transform hover:scale-105 transition-transform duration-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium opacity-90">Menunggu Persetujuan</p>
                        <p class="text-3xl font-bold mt-2">{{ $stats['pending_sppd'] }}</p>
                        <p class="text-xs opacity-75 mt-2">Perlu ditindaklanjuti</p>
                    </div>
                    <div class="bg-white bg-opacity-20 rounded-full p-4">
                        <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Approved SPPD Card -->
            <div class="bg-gradient-to-br from-green-400 to-emerald-600 rounded-xl shadow-lg p-6 text-white transform hover:scale-105 transition-transform duration-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium opacity-90">Disetujui</p>
                        <p class="text-3xl font-bold mt-2">{{ $stats['approved_sppd'] }}</p>
                        <p class="text-xs opacity-75 mt-2">{{ $stats['approval_rate'] }}% dari total</p>
                    </div>
                    <div class="bg-white bg-opacity-20 rounded-full p-4">
                        <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Completed SPPD Card -->
            <div class="bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl shadow-lg p-6 text-white transform hover:scale-105 transition-transform duration-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium opacity-90">Selesai</p>
                        <p class="text-3xl font-bold mt-2">{{ $stats['completed_sppd'] }}</p>
                        <p class="text-xs opacity-75 mt-2">{{ $stats['completion_rate'] }}% tingkat penyelesaian</p>
                    </div>
                    <div class="bg-white bg-opacity-20 rounded-full p-4">
                        <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Secondary Stats -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <!-- Rejected SPPD -->
            <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-red-500">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-red-100 rounded-full p-3">
                        <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Ditolak</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['rejected_sppd'] }}</p>
                    </div>
                </div>
            </div>

            <!-- Total Employees -->
            <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-navy">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-blue-100 rounded-full p-3">
                        <svg class="h-6 w-6 text-navy" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Total Pegawai</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['total_employees'] }}</p>
                    </div>
                </div>
            </div>

            <!-- Total Instances -->
            <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-blue-light">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-blue-100 rounded-full p-3">
                        <svg class="h-6 w-6 text-blue-light" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Total Instansi</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['total_instances'] }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts and Tables Row -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Monthly Trend Chart -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Trend SPPD 6 Bulan Terakhir</h3>
                @if($monthlySppd->count() > 0)
                    <div class="space-y-3">
                        @php
                            $maxValue = $monthlySppd->max('total') ?: 1;
                        @endphp
                        @foreach($monthlySppd as $data)
                            @php
                                $percentage = ($data->total / $maxValue) * 100;
                                $monthName = \Carbon\Carbon::parse($data->month)->format('M Y');
                            @endphp
                            <div>
                                <div class="flex items-center justify-between mb-1">
                                    <span class="text-sm font-medium text-gray-700">{{ $monthName }}</span>
                                    <span class="text-sm font-bold text-navy">{{ $data->total }}</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-3 overflow-hidden">
                                    <div class="bg-gradient-to-r from-navy to-blue-light h-3 rounded-full transition-all duration-500" style="width: {{ $percentage }}%"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8 text-gray-500">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                        <p class="mt-2">Belum ada data</p>
                    </div>
                @endif
            </div>

            <!-- SPPD by Instance -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">SPPD per Instansi</h3>
                @if($sppdByInstance->count() > 0)
                    <div class="space-y-3 max-h-80 overflow-y-auto">
                        @php
                            $maxInstanceValue = $sppdByInstance->max('total') ?: 1;
                        @endphp
                        @foreach($sppdByInstance as $data)
                            @php
                                $percentage = ($data['total'] / $maxInstanceValue) * 100;
                            @endphp
                            <div>
                                <div class="flex items-center justify-between mb-1">
                                    <span class="text-sm font-medium text-gray-700 truncate pr-2">{{ $data['instance_name'] }}</span>
                                    <span class="text-sm font-bold text-navy">{{ $data['total'] }}</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-3 overflow-hidden">
                                    <div class="bg-gradient-to-r from-green-400 to-emerald-600 h-3 rounded-full transition-all duration-500" style="width: {{ $percentage }}%"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8 text-gray-500">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                        <p class="mt-2">Belum ada data</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Recent SPPD Table -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">SPPD Terbaru</h3>
                        <p class="text-sm text-gray-600 mt-1">10 SPPD terakhir yang dibuat</p>
                    </div>
                    <a href="{{ route('admin.sppd.index') }}" class="text-navy hover:text-blue-light font-medium text-sm transition-colors">
                        Lihat Semua →
                    </a>
                </div>
            </div>

            <div class="overflow-x-auto">
                @if($recentSppd->count() > 0)
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-cream">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">No. SPPD</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Pegawai</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Instansi</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Tgl Berangkat</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($recentSppd as $sppd)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ $sppd->nomor_sppd ?? 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $sppd->employeeExecutor->nama_lengkap ?? 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900">
                                        <span class="truncate block max-w-xs">{{ $sppd->instance->name ?? 'N/A' }}</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $sppd->tanggal_berangkat ? \Carbon\Carbon::parse($sppd->tanggal_berangkat)->format('d M Y') : 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @php
                                            $statusConfig = [
                                                'pending' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-800', 'label' => 'Pending'],
                                                'approved' => ['bg' => 'bg-green-100', 'text' => 'text-green-800', 'label' => 'Disetujui'],
                                                'rejected' => ['bg' => 'bg-red-100', 'text' => 'text-red-800', 'label' => 'Ditolak'],
                                                'completed' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-800', 'label' => 'Selesai'],
                                            ];
                                            $status = $statusConfig[$sppd->status] ?? ['bg' => 'bg-gray-100', 'text' => 'text-gray-800', 'label' => ucfirst($sppd->status)];
                                        @endphp
                                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $status['bg'] }} {{ $status['text'] }}">
                                            {{ $status['label'] }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <a href="{{ route('admin.sppd.edit', $sppd->id) }}" class="text-navy hover:text-blue-light font-medium transition-colors">
                                            Detail →
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="text-center py-12">
                        <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <p class="mt-4 text-gray-500">Belum ada SPPD yang dibuat</p>
                        <a href="{{ route('admin.sppd.create') }}" class="mt-4 inline-flex items-center px-4 py-2 border border-transparent rounded-lg text-sm font-medium text-white bg-navy hover:bg-opacity-90 transition-colors">
                            Buat SPPD Baru
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
