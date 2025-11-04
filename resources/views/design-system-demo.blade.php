<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SPPD Design System Demo</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-light min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        
        <!-- Header -->
        <div class="mb-12 text-center">
            <h1 class="text-4xl font-bold bg-gradient-to-r from-primary to-secondary bg-clip-text text-transparent mb-4">
                SPPD Design System
            </h1>
            <p class="text-muted text-lg">Modern Component Library dengan Palet Warna Baru</p>
        </div>

        <!-- Color Palette -->
        <div class="card mb-8">
            <div class="card-header">
                <h2 class="text-2xl font-bold text-primary">🎨 Color Palette</h2>
            </div>
            <div class="card-body">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-6">
                    <div>
                        <div class="h-24 rounded-lg bg-primary mb-3 shadow-lg"></div>
                        <h4 class="font-semibold text-sm">Primary</h4>
                        <p class="text-xs text-muted">#0C2B4E</p>
                    </div>
                    <div>
                        <div class="h-24 rounded-lg bg-secondary mb-3 shadow-lg"></div>
                        <h4 class="font-semibold text-sm">Secondary</h4>
                        <p class="text-xs text-muted">#1A3D64</p>
                    </div>
                    <div>
                        <div class="h-24 rounded-lg bg-accent mb-3 shadow-lg"></div>
                        <h4 class="font-semibold text-sm">Accent</h4>
                        <p class="text-xs text-muted">#1D546C</p>
                    </div>
                    <div>
                        <div class="h-24 rounded-lg bg-light border border-gray-300 mb-3 shadow-lg"></div>
                        <h4 class="font-semibold text-sm">Light</h4>
                        <p class="text-xs text-muted">#F4F4F4</p>
                    </div>
                    <div>
                        <div class="h-24 rounded-lg bg-muted mb-3 shadow-lg"></div>
                        <h4 class="font-semibold text-sm">Muted</h4>
                        <p class="text-xs text-muted">#5D688A</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Buttons -->
        <div class="card mb-8">
            <div class="card-header">
                <h2 class="text-2xl font-bold text-primary">🔘 Buttons</h2>
            </div>
            <div class="card-body">
                <div class="space-y-4">
                    <div>
                        <h4 class="font-semibold mb-3">Primary Buttons</h4>
                        <div class="flex flex-wrap gap-3">
                            <button class="btn-primary">
                                <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                Simpan Data
                            </button>
                            <button class="btn-primary" disabled>
                                Disabled
                            </button>
                        </div>
                    </div>

                    <div>
                        <h4 class="font-semibold mb-3">Secondary Buttons</h4>
                        <div class="flex flex-wrap gap-3">
                            <button class="btn-secondary">
                                <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                                </svg>
                                Kembali
                            </button>
                            <button class="btn-secondary" disabled>
                                Disabled
                            </button>
                        </div>
                    </div>

                    <div>
                        <h4 class="font-semibold mb-3">Accent Buttons</h4>
                        <div class="flex flex-wrap gap-3">
                            <button class="btn-accent">
                                <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                </svg>
                                Refresh Data
                            </button>
                            <button class="btn-accent" disabled>
                                Disabled
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Inputs -->
        <div class="card mb-8">
            <div class="card-header">
                <h2 class="text-2xl font-bold text-primary">📝 Form Inputs</h2>
            </div>
            <div class="card-body">
                <div class="space-y-6">
                    <!-- Normal Input -->
                    <div>
                        <label class="form-label form-label-required">Email</label>
                        <input type="email" class="form-input" placeholder="email@example.com">
                        <p class="form-helper-text">Masukkan email aktif Anda</p>
                    </div>

                    <!-- Error Input -->
                    <div>
                        <label class="form-label form-label-required">Password</label>
                        <input type="password" class="form-input-error" placeholder="Password minimal 8 karakter">
                        <p class="form-error-message">
                            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                            Password minimal 8 karakter
                        </p>
                    </div>

                    <!-- Success Input -->
                    <div>
                        <label class="form-label">Username</label>
                        <input type="text" class="form-input-success" placeholder="john_doe" value="john_doe">
                    </div>

                    <!-- Textarea -->
                    <div>
                        <label class="form-label">Deskripsi</label>
                        <textarea class="form-textarea" placeholder="Tulis deskripsi..."></textarea>
                    </div>

                    <!-- Select -->
                    <div>
                        <label class="form-label form-label-required">Pilih Opsi</label>
                        <select class="form-select">
                            <option value="">-- Pilih Opsi --</option>
                            <option value="1">Opsi 1</option>
                            <option value="2">Opsi 2</option>
                            <option value="3">Opsi 3</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- Badges -->
        <div class="card mb-8">
            <div class="card-header">
                <h2 class="text-2xl font-bold text-primary">🏷️ Badges</h2>
            </div>
            <div class="card-body">
                <div class="flex flex-wrap gap-3">
                    <span class="badge-primary">Primary</span>
                    <span class="badge-success">Success</span>
                    <span class="badge-warning">Warning</span>
                    <span class="badge-danger">Danger</span>
                </div>
            </div>
        </div>

        <!-- Alerts -->
        <div class="card mb-8">
            <div class="card-header">
                <h2 class="text-2xl font-bold text-primary">🔔 Alerts</h2>
            </div>
            <div class="card-body">
                <div class="space-y-4">
                    <div class="alert-success">
                        <strong>Success!</strong> Data berhasil disimpan ke database.
                    </div>
                    <div class="alert-info">
                        <strong>Info:</strong> Sistem akan melakukan maintenance pada jam 02:00 WIB.
                    </div>
                    <div class="alert-warning">
                        <strong>Warning:</strong> Anda memiliki 3 notifikasi yang belum dibaca.
                    </div>
                    <div class="alert-danger">
                        <strong>Error!</strong> Terjadi kesalahan saat menyimpan data.
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabs -->
        <div class="card mb-8">
            <div class="card-header">
                <h2 class="text-2xl font-bold text-primary">🗂️ Tabs</h2>
            </div>
            <div class="border-b border-gray-200 bg-gradient-to-r from-primary/5 to-secondary/10">
                <div class="overflow-x-auto scrollbar-hide">
                    <nav class="tab-nav px-4" x-data="{ activeTab: 'tab1' }">
                        <button type="button" @click="activeTab = 'tab1'" 
                            :class="activeTab === 'tab1' ? 'tab-button-active' : 'tab-button'">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                            </svg>
                            Tab 1
                        </button>
                        <button type="button" @click="activeTab = 'tab2'" 
                            :class="activeTab === 'tab2' ? 'tab-button-active' : 'tab-button'">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                            Tab 2
                        </button>
                        <button type="button" @click="activeTab = 'tab3'" 
                            :class="activeTab === 'tab3' ? 'tab-button-active' : 'tab-button'">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            Tab 3
                        </button>
                    </nav>
                </div>
            </div>
        </div>

        <!-- Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="text-lg font-semibold text-primary">Card dengan Header</h3>
                    <p class="text-sm text-muted">Ini adalah contoh card dengan header</p>
                </div>
                <div class="card-body">
                    <p class="text-gray-600">Konten card di sini. Card component sudah include rounded corners, shadow, dan responsive design.</p>
                </div>
                <div class="card-footer">
                    <div class="flex justify-end gap-3">
                        <button class="btn-secondary">Batal</button>
                        <button class="btn-primary">Simpan</button>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <div class="flex items-start gap-4">
                        <div class="flex-shrink-0">
                            <div class="h-12 w-12 rounded-full bg-gradient-to-r from-primary to-secondary flex items-center justify-center">
                                <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                </svg>
                            </div>
                        </div>
                        <div class="flex-1">
                            <h4 class="font-semibold text-primary mb-1">Card tanpa Header</h4>
                            <p class="text-sm text-muted">Card ini hanya memiliki body, tanpa header dan footer.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</body>
</html>
