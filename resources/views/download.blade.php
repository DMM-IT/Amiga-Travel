@extends('layouts.app')

@section('content')
<div class="bg-slate-50 min-h-screen">

    @php
        $downloadSteps = $pageContent['download_steps'] ?? [
            [
                'number' => '1',
                'title' => 'Download APK',
                'description' => 'Tap the "Download Android APK" button to download the installation file to your device.',
                'icon' => 'M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4',
                'icon_color' => '#216417',
                'bg_color' => '#eaf5e8',
            ],
            [
                'number' => '2',
                'title' => 'Allow Install',
                'description' => 'Open the downloaded file and allow installation from unknown sources if your device prompts you.',
                'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
                'icon_color' => '#ee018d',
                'bg_color' => '#fce7f3',
            ],
            [
                'number' => '3',
                'title' => 'You\'re All Set!',
                'description' => 'The app icon appears on your home screen. Open it to start booking trips and earning points!',
                'icon' => 'M13 10V3L4 14h7v7l9-11h-7z',
                'icon_color' => '#216417',
                'bg_color' => '#eaf5e8',
            ],
        ];
        $downloadFeatures = $pageContent['download_features'] ?? [
            [
                'title' => 'Lightning Fast',
                'description' => 'Loads instantly, even on slow connections.',
                'icon' => 'M13 10V3L4 14h7v7l9-11h-7z',
                'bg_color' => '#eaf5e8',
                'icon_color' => '#216417',
            ],
            [
                'title' => 'Home Screen Icon',
                'description' => 'Quick access from your phone\'s home screen.',
                'icon' => 'M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z',
                'bg_color' => '#fce7f3',
                'icon_color' => '#ee018d',
            ],
            [
                'title' => 'Secure & Private',
                'description' => 'Your data stays safe with HTTPS encryption.',
                'icon' => 'M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z',
                'bg_color' => '#eaf5e8',
                'icon_color' => '#216417',
            ],
            [
                'title' => 'Always Updated',
                'description' => 'Automatically gets the latest features.',
                'icon' => 'M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15',
                'bg_color' => '#eff6ff',
                'icon_color' => '#2563eb',
            ],
        ];
    @endphp

    @php
        $pubspecPath = base_path('flutter_app/pubspec.yaml');
        $version = '1.0.0+1';
        if (file_exists($pubspecPath)) {
            $content = file_get_contents($pubspecPath);
            if (preg_match('/^version:\s*(.+)$/m', $content, $matches)) {
                $version = trim($matches[1]);
            }
        }
        
        $apkPath = public_path('downloads/amiga-travel.apk');
        $size = file_exists($apkPath) ? round(filesize($apkPath) / 1048576, 1) . ' MB' : '17.6 MB';
    @endphp

    <!-- Hero Section -->
    <div class="relative overflow-hidden" style="background: linear-gradient(135deg, #216417 0%, #14400e 60%, #0a2d06 100%);">
        <div class="absolute inset-0 opacity-10">
            <div class="absolute top-10 left-10 w-72 h-72 bg-white/10 rounded-full blur-3xl"></div>
            <div class="absolute bottom-10 right-10 w-96 h-96 bg-[#ee018d]/10 rounded-full blur-3xl"></div>
        </div>
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-20 sm:py-28 relative z-10">
            <div class="flex flex-col lg:flex-row items-center gap-12">
                <!-- Left: Text Content -->
                <div class="flex-1 text-center lg:text-left">
                    <div class="flex flex-col lg:flex-row items-center lg:items-start gap-6 mb-6">
                        <img src="{{ asset('images/app-icon-original.png') }}" alt="Amiga Gracia" class="h-20 w-20 rounded-2xl shadow-xl border border-white/20 bg-white object-contain">
                        <div class="flex flex-col items-center lg:items-start">
                            <span class="inline-flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-emerald-400 bg-white/10 backdrop-blur-sm px-4 py-1.5 rounded-full border border-white/20 mb-3">
                                <svg class="h-3.5 w-3.5" fill="currentColor" viewBox="0 0 20 20"><path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838l-3.598 1.543A3.002 3.002 0 007 13a3 3 0 00-2 5.236V18a1 1 0 001 1h8a1 1 0 001-1v-.764A3.001 3.001 0 0013 13a3.002 3.002 0 00-.244-1.18l2.85-1.22a1 1 0 000-1.84l-5.212-2.68zM7 14a1 1 0 100 2 1 1 0 000-2zm6 0a1 1 0 100 2 1 1 0 000-2z"/></svg>
                                Android APK
                            </span>
                            <h1 class="text-4xl sm:text-5xl font-black text-white tracking-tight leading-tight">
                                {!! $pageContent['title'] ?? 'Get the <span class="text-emerald-400">Amiga Gracia</span> App' !!}
                            </h1>
                        </div>
                    </div>
                    <p class="mt-6 text-base sm:text-lg text-white/80 max-w-lg mx-auto lg:mx-0 leading-relaxed">
                        {{ $pageContent['description'] ?? 'Book ferry tickets, flights, and tour packages right from your phone. Download our compiled Android APK for a fast, hassle-free booking experience.' }}
                    </p>

                    <!-- Install Button (PWA) & APK Download -->
                    <div class="mt-8 flex flex-col sm:flex-row items-center gap-4 lg:justify-start justify-center">
                        <!-- Direct Flutter APK Download Link -->
                        <a href="{{ asset('downloads/amiga-travel.apk') }}"
                           class="group inline-flex items-center gap-3 px-8 py-4 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-base rounded-2xl shadow-lg shadow-emerald-900/30 hover:shadow-xl hover:shadow-emerald-900/40 transition-all duration-300 hover:-translate-y-0.5 border border-transparent"
                           download
                        >
                            <svg class="h-6 w-6 group-hover:scale-110 transition-transform fill-current text-white" viewBox="0 0 24 24">
                                <path d="M6 18c0 .55.45 1 1 1h1v3.5c0 .83.67 1.5 1.5 1.5s1.5-.67 1.5-1.5V19h2v3.5c0 .83.67 1.5 1.5 1.5s1.5-.67 1.5-1.5V19h1c.55 0 1-.45 1-1V8H6v10zM11.1 5.6a.49.49 0 00-.23-.65l-1.3-.75a.51.51 0 00-.69.18.49.49 0 00.18.69l1.3.75c.08.05.17.08.26.08.17 0 .34-.09.43-.25zM12.9 5.6a.49.49 0 00.43.25c.09 0 .18-.03.26-.08l1.3-.75a.49.49 0 00.18-.69.51.51 0 00-.69-.18l-1.3.75a.49.49 0 00-.23.65zM12 5a3 3 0 013 3H9a3 3 0 013-3zM19.5 8c-.83 0-1.5.67-1.5 1.5v6c0 .83.67 1.5 1.5 1.5s1.5-.67 1.5-1.5v-6c0-.83-.67-1.5-1.5-1.5zM4.5 8C3.67 8 3 8.67 3 9.5v6c0 .83.67 1.5 1.5 1.5S6 16.33 6 15.5v-6C6 8.67 5.33 8 4.5 8z"/>
                            </svg>
                            Download Android APK
                        </a>
                    </div>

                    <!-- App Info Grid -->
                    <div class="mt-8 pt-8 border-t border-white/10 grid grid-cols-2 sm:grid-cols-4 gap-4 text-white/70 max-w-lg mx-auto lg:mx-0 text-left">
                        <div>
                            <p class="text-[10px] uppercase font-bold tracking-wider text-emerald-400">Version</p>
                            <p class="text-sm font-semibold text-white mt-0.5">{{ $version }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] uppercase font-bold tracking-wider text-emerald-400">File Size</p>
                            <p class="text-sm font-semibold text-white mt-0.5">{{ $size }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] uppercase font-bold tracking-wider text-emerald-400">Requires</p>
                            <p class="text-sm font-semibold text-white mt-0.5">Android 8.0+</p>
                        </div>
                        <div>
                            <p class="text-[10px] uppercase font-bold tracking-wider text-emerald-400">Verified</p>
                            <p class="text-sm font-semibold text-white mt-0.5 flex items-center gap-1">
                                <svg class="h-3.5 w-3.5 text-emerald-400 fill-current" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                Safe
                            </p>
                        </div>
                    </div>

                    <div class="mt-5 flex items-center gap-6 text-white/50 text-xs font-medium lg:justify-start justify-center">
                        <span class="flex items-center gap-1.5">
                            <svg class="h-3.5 w-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/></svg>
                            Secure
                        </span>
                        <span class="flex items-center gap-1.5">
                            <svg class="h-3.5 w-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M3 5a2 2 0 012-2h10a2 2 0 012 2v8a2 2 0 01-2 2h-2.22l.123.489.804.805A1 1 0 0113 18H7a1 1 0 01-.707-1.707l.804-.804L7.22 15H5a2 2 0 01-2-2V5zm5.771 7H5V5h10v7H8.771z" clip-rule="evenodd"/></svg>
                            Works Offline
                        </span>
                        <span class="flex items-center gap-1.5">
                            <svg class="h-3.5 w-3.5" fill="currentColor" viewBox="0 0 20 20"><path d="M13 7H7v6h6V7z"/><path fill-rule="evenodd" d="M7 2a1 1 0 012 0v1h2V2a1 1 0 112 0v1h2a2 2 0 012 2v2h1a1 1 0 110 2h-1v2h1a1 1 0 110 2h-1v2a2 2 0 01-2 2h-2v1a1 1 0 11-2 0v-1H9v1a1 1 0 11-2 0v-1H5a2 2 0 01-2-2v-2H2a1 1 0 110-2h1V9H2a1 1 0 010-2h1V5a2 2 0 012-2h2V2zM5 5h10v10H5V5z" clip-rule="evenodd"/></svg>
                            Lightweight
                        </span>
                    </div>
                </div>

                <!-- Right: Phone Mockup -->
                <div class="flex-shrink-0 relative">
                    <div class="w-64 sm:w-72 h-[500px] sm:h-[560px] rounded-[3rem] border-[6px] border-white/20 bg-white/5 backdrop-blur-md shadow-2xl overflow-hidden relative">
                        <!-- Phone Notch -->
                        <div class="absolute top-0 left-1/2 -translate-x-1/2 w-28 h-6 bg-black/40 rounded-b-2xl z-20"></div>
                        <!-- Phone Screen Content -->
                        <div class="absolute inset-0 flex flex-col items-center justify-center p-6 text-center">
                            <img src="{{ asset('images/app-icon-original.png') }}" alt="Amiga Gracia App" class="h-24 w-24 rounded-3xl mb-4 drop-shadow-xl border-2 border-white/25 bg-white object-contain">
                            <h3 class="text-white font-extrabold text-xl tracking-wide">Amiga Gracia</h3>
                            <p class="text-white/60 text-xs mt-1">Travel Services</p>
                            <div class="mt-6 w-full space-y-3">
                                <div class="bg-white/10 rounded-xl p-3 flex items-center gap-3">
                                    <div class="h-8 w-8 rounded-lg bg-emerald-500/20 flex items-center justify-center">
                                        <svg class="h-4 w-4 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                    </div>
                                    <span class="text-white/80 text-xs font-medium">Book Ferry Tickets</span>
                                </div>
                                <div class="bg-white/10 rounded-xl p-3 flex items-center gap-3">
                                    <div class="h-8 w-8 rounded-lg bg-emerald-500/20 flex items-center justify-center">
                                        <svg class="h-4 w-4 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                                    </div>
                                    <span class="text-white/80 text-xs font-medium">Flight Bookings</span>
                                </div>
                                <div class="bg-white/10 rounded-xl p-3 flex items-center gap-3">
                                    <div class="h-8 w-8 rounded-lg bg-emerald-500/20 flex items-center justify-center">
                                        <svg class="h-4 w-4 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    </div>
                                    <span class="text-white/80 text-xs font-medium">Tour Packages</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Glow behind phone -->
                    <div class="absolute -inset-8 bg-emerald-500/10 rounded-full blur-3xl -z-10"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- APK Benefits Section -->
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="bg-white rounded-[2rem] shadow-sm border border-slate-100 p-8 sm:p-12">
            <div class="text-center mb-10">
                <span class="text-xs font-semibold uppercase tracking-wider px-3 py-1 rounded-full" style="color: #ee018d; background: #fce7f3;">Exclusive App Benefits</span>
                <h2 class="mt-4 text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">Why download the app?</h2>
            </div>
            <div class="grid sm:grid-cols-2 gap-8 lg:gap-12">
                <div class="flex gap-4">
                    <div class="h-14 w-14 shrink-0 rounded-2xl bg-[#eaf5e8] text-[#216417] flex items-center justify-center shadow-sm">
                        <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-slate-900 text-lg">Gracia Point System</h3>
                        <p class="text-sm text-slate-500 mt-2 leading-relaxed">Earn points on every booking made through the app. Redeem them for discounts on your future trips!</p>
                    </div>
                </div>
                <div class="flex gap-4">
                    <div class="h-14 w-14 shrink-0 rounded-2xl bg-[#fce7f3] text-[#ee018d] flex items-center justify-center shadow-sm">
                        <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" /></svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-slate-900 text-lg">Exclusive Vouchers</h3>
                        <p class="text-sm text-slate-500 mt-2 leading-relaxed">Get access to app-only promotions, seasonal vouchers, and special partner discounts.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- How to Install Section -->
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="text-center mb-12">
            <span class="text-xs font-semibold uppercase tracking-wider px-3 py-1 rounded-full" style="color: #216417; background: #eaf5e8;">{{ data_get($pageContent, 'how_it_works_label', 'Installation Guide') }}</span>
            <h2 class="mt-4 text-3xl sm:text-4xl font-black text-slate-900 tracking-tight">{{ data_get($pageContent, 'how_it_works_title', 'Install in 3 Easy Steps') }}</h2>
            <p class="mt-3 text-slate-500 max-w-lg mx-auto">{{ data_get($pageContent, 'how_it_works_description', 'Follow these simple steps to install the APK on your Android device.') }}</p>
        </div>

        <div class="grid sm:grid-cols-3 gap-8">
            @foreach($downloadSteps as $step)
                <div class="relative bg-white rounded-[2rem] p-8 shadow-md ring-1 ring-slate-100 text-center group hover:shadow-lg transition">
                    <div class="absolute -top-4 left-1/2 -translate-x-1/2 h-8 w-8 rounded-full font-black text-sm flex items-center justify-center text-white shadow-md" style="background: {{ data_get($step, 'icon_color') }};">{{ data_get($step, 'number') }}</div>
                    <div class="h-16 w-16 mx-auto rounded-2xl flex items-center justify-center mb-5 group-hover:scale-105 transition" style="background: {{ data_get($step, 'bg_color') }};">
                        <svg class="h-8 w-8" style="color: {{ data_get($step, 'icon_color') }};" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="{{ data_get($step, 'icon') }}" />
                        </svg>
                    </div>
                    <h3 class="font-bold text-slate-900 text-lg">{{ data_get($step, 'title') }}</h3>
                    <p class="text-sm text-slate-500 mt-2 leading-relaxed">{{ data_get($step, 'description') }}</p>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Bottom CTA -->
    <div class="text-center pb-16">
        <p class="text-sm text-slate-500">
            Need help?
            <a href="{{ url('/contact-us') }}" class="text-[#ee018d] font-semibold hover:underline">Contact our team</a>
            or visit our office at Roxas Drive, Libis, Calapan City.
        </p>
    </div>
</div>

<!-- PWA Install Script -->
<script>
    function pwaInstall() {
        return {
            deferredPrompt: null,
            canInstall: false,
            isInstalled: false,

            init() {
                // Check if already installed
                if (window.matchMedia('(display-mode: standalone)').matches || window.navigator.standalone === true) {
                    this.isInstalled = true;
                }

                // Listen for beforeinstallprompt
                window.addEventListener('beforeinstallprompt', (e) => {
                    e.preventDefault();
                    this.deferredPrompt = e;
                    this.canInstall = true;
                });

                // Listen for successful install
                window.addEventListener('appinstalled', () => {
                    this.canInstall = false;
                    this.isInstalled = true;
                    this.deferredPrompt = null;
                });
            },

            async install() {
                if (!this.deferredPrompt) return;
                this.deferredPrompt.prompt();
                const { outcome } = await this.deferredPrompt.userChoice;
                if (outcome === 'accepted') {
                    this.canInstall = false;
                    this.isInstalled = true;
                }
                this.deferredPrompt = null;
            }
        };
    }
</script>
@endsection
