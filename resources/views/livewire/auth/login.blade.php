<div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <!-- Logo & Header -->
        <div class="text-center">
            <div class="mx-auto h-16 w-16 bg-navy rounded-full flex items-center justify-center">
                <svg class="h-10 w-10 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
            </div>
            <h2 class="mt-6 text-3xl font-extrabold text-gray-900">
                Selamat Datang
            </h2>
            <p class="mt-2 text-sm text-gray-600">
                di Aplikasi Surat Tugas & Perjalanan Dinas
            </p>
        </div>

        <!-- Login Form -->
        <div class="bg-white shadow-2xl rounded-2xl overflow-hidden">
            <div class="px-8 py-10">
                <form wire:submit.prevent="login" class="space-y-6">
                    <!-- NIP Input -->
                    <div>
                        <label for="username" class="block text-sm font-medium text-gray-700 mb-2">
                            NIP / Username Semesta
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                                </svg>
                            </div>
                            <input wire:model="username" id="username" type="username" autocomplete="username"
                                class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-navy focus:border-navy transition duration-150 ease-in-out @error('email') border-red-500 @enderror"
                                placeholder="NIP / Username Semesta">
                        </div>
                        @error('username')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password Input -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                            Password
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                            </div>
                            <input wire:model="password" id="password" type="password" autocomplete="current-password"
                                class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-navy focus:border-navy transition duration-150 ease-in-out @error('password') border-red-500 @enderror"
                                placeholder="••••••••">
                        </div>
                        @error('password')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Remember Me -->
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <input wire:model="remember" id="remember" type="checkbox"
                                class="h-4 w-4 text-navy focus:ring-navy border-gray-300 rounded">
                            <label for="remember" class="ml-2 block text-sm text-gray-700">
                                Ingat saya
                            </label>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div>
                        <button type="submit"
                            class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-navy hover:bg-opacity-90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-navy transition duration-150 ease-in-out">
                            <span wire:loading.remove>Masuk</span>
                            <span wire:loading>
                                <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                        stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                    </path>
                                </svg>
                            </span>
                        </button>
                    </div>

                </form>
            </div>
        </div>

        <!-- Back to Home -->
        <div class="text-center">
            <a href="{{ route('home') }}" class="text-sm text-navy hover:text-opacity-80 transition duration-150">
                ← Kembali ke Beranda
            </a>
        </div>
    </div>
</div>
