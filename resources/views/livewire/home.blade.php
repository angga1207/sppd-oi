<div class="min-h-screen">
    <!-- Navigation -->
    <nav class="bg-white shadow-sm sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <div class="flex-shrink-0 flex items-center">
                        <div class="h-10 w-10 bg-navy rounded-lg flex items-center justify-center">
                            <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <h1 class="ml-3 text-xl font-bold text-navy">SPPD</h1>
                    </div>
                </div>
                <div class="flex items-center">
                    <a href="{{ route('login') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-navy hover:bg-opacity-90 transition duration-150">
                        Masuk
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="bg-gradient-to-br from-navy via-blue-light to-cream py-20 sm:py-32">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h1 class="text-4xl sm:text-5xl md:text-6xl font-extrabold text-white mb-6">
                    Sistem Informasi
                    <span class="block text-cream">Surat Perintah Perjalanan Dinas</span>
                </h1>
                <p class="mt-3 max-w-md mx-auto text-base sm:text-lg md:mt-5 md:text-xl md:max-w-3xl text-white text-opacity-90">
                    Kelola SPPD dengan mudah, cepat, dan terorganisir. Digitalisasi proses administrasi perjalanan dinas Anda.
                </p>
                <div class="mt-10 flex justify-center gap-4">
                    <a href="{{ route('login') }}" class="inline-flex items-center px-8 py-3 border border-transparent text-base font-medium rounded-lg text-navy bg-white hover:bg-gray-50 transition duration-150 shadow-lg">
                        Mulai Sekarang
                        <svg class="ml-2 -mr-1 w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl sm:text-4xl font-extrabold text-gray-900">
                    Fitur Unggulan
                </h2>
                <p class="mt-4 text-xl text-gray-600">
                    Semua yang Anda butuhkan untuk mengelola SPPD
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Feature 1 -->
                <div class="bg-cream rounded-2xl p-8 shadow-lg hover:shadow-xl transition duration-300 border-t-4 border-navy">
                    <div class="h-12 w-12 bg-navy rounded-lg flex items-center justify-center mb-4">
                        <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Pembuatan SPPD Digital</h3>
                    <p class="text-gray-600">Buat surat perintah perjalanan dinas secara digital dengan formulir yang mudah dan terstruktur.</p>
                </div>

                <!-- Feature 2 -->
                <div class="bg-cream rounded-2xl p-8 shadow-lg hover:shadow-xl transition duration-300 border-t-4 border-blue-light">
                    <div class="h-12 w-12 bg-blue-light rounded-lg flex items-center justify-center mb-4">
                        <svg class="h-6 w-6 text-navy" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Proses Persetujuan</h3>
                    <p class="text-gray-600">Sistem persetujuan bertingkat yang efisien untuk mempercepat proses approval SPPD.</p>
                </div>

                <!-- Feature 3 -->
                <div class="bg-cream rounded-2xl p-8 shadow-lg hover:shadow-xl transition duration-300 border-t-4 border-navy">
                    <div class="h-12 w-12 bg-navy rounded-lg flex items-center justify-center mb-4">
                        <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Manajemen Pegawai</h3>
                    <p class="text-gray-600">Kelola data pegawai dan instansi dengan sistematis dan terorganisir.</p>
                </div>

                <!-- Feature 4 -->
                <div class="bg-cream rounded-2xl p-8 shadow-lg hover:shadow-xl transition duration-300 border-t-4 border-blue-light">
                    <div class="h-12 w-12 bg-blue-light rounded-lg flex items-center justify-center mb-4">
                        <svg class="h-6 w-6 text-navy" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Laporan & Dashboard</h3>
                    <p class="text-gray-600">Dapatkan insight lengkap dengan dashboard interaktif dan laporan yang detail.</p>
                </div>

                <!-- Feature 5 -->
                <div class="bg-cream rounded-2xl p-8 shadow-lg hover:shadow-xl transition duration-300 border-t-4 border-navy">
                    <div class="h-12 w-12 bg-navy rounded-lg flex items-center justify-center mb-4">
                        <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Keamanan Data</h3>
                    <p class="text-gray-600">Data Anda terlindungi dengan sistem keamanan yang terpercaya dan enkripsi.</p>
                </div>

                <!-- Feature 6 -->
                <div class="bg-cream rounded-2xl p-8 shadow-lg hover:shadow-xl transition duration-300 border-t-4 border-blue-light">
                    <div class="h-12 w-12 bg-blue-light rounded-lg flex items-center justify-center mb-4">
                        <svg class="h-6 w-6 text-navy" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Responsive Design</h3>
                    <p class="text-gray-600">Akses dari perangkat apapun - desktop, tablet, atau smartphone dengan tampilan optimal.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="bg-gradient-to-r from-navy to-blue-light py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl sm:text-4xl font-extrabold text-white mb-4">
                Siap untuk memulai?
            </h2>
            <p class="text-xl text-white text-opacity-90 mb-8">
                Mulai kelola SPPD Anda dengan lebih efisien hari ini
            </p>
            <a href="{{ route('login') }}" class="inline-flex items-center px-8 py-3 border-2 border-white text-base font-medium rounded-lg text-white hover:bg-white hover:text-navy transition duration-150">
                Login Sekarang
            </a>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div>
                    <div class="flex items-center mb-4">
                        <div class="h-10 w-10 bg-navy rounded-lg flex items-center justify-center">
                            <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <h3 class="ml-3 text-xl font-bold">SPPD</h3>
                    </div>
                    <p class="text-gray-400">Sistem Informasi Surat Perintah Perjalanan Dinas yang modern dan efisien.</p>
                </div>
                <div>
                    <h4 class="text-lg font-semibold mb-4">Menu</h4>
                    <ul class="space-y-2">
                        <li><a href="{{ route('home') }}" class="text-gray-400 hover:text-white transition">Beranda</a></li>
                        <li><a href="{{ route('login') }}" class="text-gray-400 hover:text-white transition">Login</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-lg font-semibold mb-4">Kontak</h4>
                    <p class="text-gray-400">Email: info@sppd.com</p>
                    <p class="text-gray-400">Telp: (021) 1234-5678</p>
                </div>
            </div>
            <div class="mt-8 pt-8 border-t border-gray-800 text-center text-gray-400">
                <p>&copy; {{ date('Y') }} SPPD. All rights reserved.</p>
            </div>
        </div>
    </footer>
</div>
