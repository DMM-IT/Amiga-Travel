<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="theme-color" content="#216417">
        <link rel="manifest" href="/manifest.json">

        <title>{{ config('app.name', 'Amiga Gracia Travel Service') }}</title>

        <link rel="icon" href="{{ asset('images/amiga-logo-transparent.png') }}" type="image/png">

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles

        {{-- Flatpickr (global) --}}
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
        <script src="https://cdn.jsdelivr.net/npm/flatpickr" defer></script>
        <style>
            .flatpickr-calendar { font-family: inherit; border-radius: 1rem; border: 1px solid #e2e8f0; box-shadow: 0 10px 30px -5px rgba(0,0,0,.15); overflow: hidden; }
            .flatpickr-calendar.arrowTop:before,.flatpickr-calendar.arrowTop:after { display:none; }
            .flatpickr-months { background:#3b82f6; border-radius:1rem 1rem 0 0; padding:4px 0; }
            .flatpickr-month,.flatpickr-current-month .cur-month,.flatpickr-current-month .cur-year { color:#fff; fill:#fff; font-weight:600; }
            .flatpickr-prev-month,.flatpickr-next-month { color:#fff; fill:#fff; padding:8px 14px; }
            .flatpickr-prev-month:hover,.flatpickr-next-month:hover { background:rgba(255,255,255,.2); border-radius:8px; }
            .flatpickr-weekdays { background:#eff6ff; }
            span.flatpickr-weekday { color:#3b82f6; font-weight:700; font-size:.68rem; text-transform:uppercase; }
            .flatpickr-day { border-radius:.6rem; font-size:.85rem; color:#1e293b; height:34px; line-height:34px; }
            .flatpickr-day:hover { background:#dbeafe; border-color:transparent; }
            .flatpickr-day.today { border-color:#3b82f6; color:#3b82f6; font-weight:700; }
            .flatpickr-day.today:hover { background:#dbeafe; }
            .flatpickr-day.selected,.flatpickr-day.selected:hover { background:#3b82f6; border-color:#3b82f6; color:#fff; font-weight:700; }
            .flatpickr-day.prevMonthDay,.flatpickr-day.nextMonthDay { color:#cbd5e1; }
            .flatpickr-day.flatpickr-disabled { color:#e2e8f0; }
            .flatpickr-innerContainer { padding:6px 8px 8px; }
        </style>
    </head>
    <body class="bg-slate-50 text-slate-900 min-h-screen flex flex-col">
        <header class="bg-[#216417] text-white sticky top-0 z-50 shadow-md">
            <div class="max-w-full mx-auto px-3 sm:px-4 lg:px-5">
                <div class="flex items-center justify-between h-20">
                    <div class="flex items-center gap-2">
                        <a href="{{ url('/') }}" class="flex items-center gap-2">
                            <img src="{{ data_get($headerData, 'logo') ? asset('storage/' . data_get($headerData, 'logo')) : asset('images/amiga-logo.jpg') }}" alt="{{ data_get($headerData, 'company_name', 'Amiga Gracia') }}" class="h-16 w-auto rounded bg-white p-1">
                        </a>
                    </div>
                    <nav class="hidden md:flex flex-1 justify-end space-x-6 font-medium">
                        <a href="{{ url('/') }}" class="border-b-2 {{ request()->is('/') ? 'text-[#ee018d] border-[#ee018d]' : 'text-white border-transparent hover:text-[#ee018d] hover:border-[#ee018d]' }} pb-1 transition">Home</a>
                        <a href="{{ url('/about') }}" class="border-b-2 {{ request()->is('about') ? 'text-[#ee018d] border-[#ee018d]' : 'text-white border-transparent hover:text-[#ee018d] hover:border-[#ee018d]' }} pb-1 transition">About</a>
                        <a href="{{ url('/schedules') }}" class="border-b-2 {{ request()->is('schedules') ? 'text-[#ee018d] border-[#ee018d]' : 'text-white border-transparent hover:text-[#ee018d] hover:border-[#ee018d]' }} pb-1 transition">Schedules</a>
                        <div x-data="{ open: false }" class="relative" @mouseenter="open = true" @mouseleave="open = false">
                            <button class="border-b-2 {{ request()->is('services') || request()->is('tour-package') ? 'text-[#ee018d] border-[#ee018d]' : 'text-white border-transparent hover:text-[#ee018d] hover:border-[#ee018d]' }} pb-1 transition flex items-center gap-1">
                                Discover
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                            </button>
                            <div x-show="open" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100" x-transition:leave-end="transform opacity-0 scale-95" class="absolute left-1/2 -translate-x-1/2 mt-2 w-48 rounded-xl shadow-lg bg-[#216417] ring-1 ring-black ring-opacity-5 focus:outline-none overflow-hidden" style="display: none;">
                                <div class="py-1">
                                    <a href="{{ url('/services') }}" class="block px-4 py-2.5 text-sm font-medium {{ request()->is('services') ? 'bg-[#ee018d] text-white' : 'text-white hover:bg-[#ee018d] hover:text-white' }}">Services</a>
                                    <a href="{{ url('/tour-package') }}" class="block px-4 py-2.5 text-sm font-medium {{ request()->is('tour-package') ? 'bg-[#ee018d] text-white' : 'text-white hover:bg-[#ee018d] hover:text-white' }}">Tour Package</a>
                                </div>
                            </div>
                        </div>
                        <div x-data="{ open: false }" class="relative" @mouseenter="open = true" @mouseleave="open = false">
                            <button class="border-b-2 {{ request()->is('contact-us') || request()->is('faqs') ? 'text-[#ee018d] border-[#ee018d]' : 'text-white border-transparent hover:text-[#ee018d] hover:border-[#ee018d]' }} pb-1 transition flex items-center gap-1">
                                Get Help
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                            </button>
                            <div x-show="open" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100" x-transition:leave-end="transform opacity-0 scale-95" class="absolute left-1/2 -translate-x-1/2 mt-2 w-48 rounded-xl shadow-lg bg-[#216417] ring-1 ring-black ring-opacity-5 focus:outline-none overflow-hidden" style="display: none;">
                                <div class="py-1">
                                    <a href="{{ url('/contact-us') }}" class="block px-4 py-2.5 text-sm font-medium {{ request()->is('contact-us') ? 'bg-[#ee018d] text-white' : 'text-white hover:bg-[#ee018d] hover:text-white' }}">Contact Us</a>
                                    <a href="{{ url('/faqs') }}" class="block px-4 py-2.5 text-sm font-medium {{ request()->is('faqs') ? 'bg-[#ee018d] text-white' : 'text-white hover:bg-[#ee018d] hover:text-white' }}">FAQs</a>
                                </div>
                            </div>
                        </div>
                        <a href="{{ url('/download') }}" class="border-b-2 {{ request()->is('download') ? 'text-[#ee018d] border-[#ee018d]' : 'text-white border-transparent hover:text-[#ee018d] hover:border-[#ee018d]' }} pb-1 transition">Download App</a>
                    </nav>

                    <div class="flex items-center gap-4">
                        <div class="hidden xl:flex items-center gap-6 text-sm text-white/90">
                            @if(!empty($headerData['phone']))
                                <a href="tel:{{ $headerData['phone'] }}" class="hover:text-[#ee018d]">{{ $headerData['phone'] }}</a>
                            @endif
                            @if(!empty($headerData['email']))
                                <a href="mailto:{{ $headerData['email'] }}" class="hover:text-[#ee018d]">{{ $headerData['email'] }}</a>
                            @endif
                        </div>
                        <button id="mobile-menu-button" aria-expanded="false" aria-label="Toggle navigation" class="inline-flex items-center justify-center rounded-lg border border-white/20 p-2 text-white hover:bg-white/10 md:hidden focus:outline-none focus:ring-2 focus:ring-white/50">
                            <svg id="menu-open-icon" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                            <svg id="menu-close-icon" class="hidden h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <div id="mobile-menu" class="md:hidden hidden bg-[#1e4c21] border-t border-white/10">
                <div class="max-w-full mx-auto px-4 py-4 space-y-3">
                    <a href="{{ url('/') }}" class="block rounded-xl px-4 py-3 {{ request()->is('/') ? 'bg-white/10 text-[#ee018d]' : 'text-white hover:bg-white/10' }}">Home</a>
                    <a href="{{ url('/about') }}" class="block rounded-xl px-4 py-3 {{ request()->is('about') ? 'bg-white/10 text-[#ee018d]' : 'text-white hover:bg-white/10' }}">About</a>
                    <a href="{{ url('/schedules') }}" class="block rounded-xl px-4 py-3 {{ request()->is('schedules') ? 'bg-white/10 text-[#ee018d]' : 'text-white hover:bg-white/10' }}">Schedules</a>
                    <div x-data="{ open: false }">
                        <button @click="open = !open" class="w-full flex justify-between items-center rounded-xl px-4 py-3 font-medium {{ request()->is('services') || request()->is('tour-package') ? 'bg-white/10 text-[#ee018d]' : 'text-white hover:bg-white/10' }}">
                            Discover
                            <svg :class="{'rotate-180': open}" class="w-4 h-4 transition-transform duration-200" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <div x-show="open" style="display: none;" class="pl-4 pr-2 py-2 space-y-2 border-l border-white/20 ml-2 mt-1">
                            <a href="{{ url('/services') }}" class="block rounded-lg px-4 py-2 text-sm font-medium {{ request()->is('services') ? 'bg-white/10 text-[#ee018d]' : 'text-white/80 hover:bg-white/10 hover:text-white' }}">Services</a>
                            <a href="{{ url('/tour-package') }}" class="block rounded-lg px-4 py-2 text-sm font-medium {{ request()->is('tour-package') ? 'bg-white/10 text-[#ee018d]' : 'text-white/80 hover:bg-white/10 hover:text-white' }}">Tour Package</a>
                        </div>
                    </div>
                    <div x-data="{ open: false }">
                        <button @click="open = !open" class="w-full flex justify-between items-center rounded-xl px-4 py-3 font-medium {{ request()->is('contact-us') || request()->is('faqs') ? 'bg-white/10 text-[#ee018d]' : 'text-white hover:bg-white/10' }}">
                            Get Help
                            <svg :class="{'rotate-180': open}" class="w-4 h-4 transition-transform duration-200" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <div x-show="open" style="display: none;" class="pl-4 pr-2 py-2 space-y-2 border-l border-white/20 ml-2 mt-1">
                            <a href="{{ url('/contact-us') }}" class="block rounded-lg px-4 py-2 text-sm font-medium {{ request()->is('contact-us') ? 'bg-white/10 text-[#ee018d]' : 'text-white/80 hover:bg-white/10 hover:text-white' }}">Contact Us</a>
                            <a href="{{ url('/faqs') }}" class="block rounded-lg px-4 py-2 text-sm font-medium {{ request()->is('faqs') ? 'bg-white/10 text-[#ee018d]' : 'text-white/80 hover:bg-white/10 hover:text-white' }}">FAQs</a>
                        </div>
                    </div>
                    <a href="{{ url('/download') }}" class="block rounded-xl px-4 py-3 {{ request()->is('download') ? 'bg-white/10 text-[#ee018d]' : 'text-white hover:bg-white/10' }}">Download App</a>
                    <div class="border-t border-white/10 pt-3">
                        @if(!empty($headerData['phone']))
                            <a href="tel:{{ $headerData['phone'] }}" class="block text-sm text-white/90 hover:text-white">Call us: {{ $headerData['phone'] }}</a>
                        @endif
                        @if(!empty($headerData['email']))
                            <a href="mailto:{{ $headerData['email'] }}" class="block text-sm text-white/90 hover:text-white">Email: {{ $headerData['email'] }}</a>
                        @endif
                    </div>
                </div>
            </div>
        </header>

        <main class="flex-grow">
            @yield('content')
        </main>

        <footer class="relative overflow-hidden bg-[#0e2709] text-white pt-16 pb-8 mt-12">
            <div class="w-full px-4 sm:px-6 lg:px-8 relative z-10">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-8 pb-12 border-b border-white/10">
                    <!-- Column 1: Logo & Tagline -->
                    <div class="space-y-4">
                        <div class="flex items-center gap-3">
                            <img src="{{ data_get($headerData, 'logo') ? asset('storage/' . data_get($headerData, 'logo')) : asset('images/amiga-logo-transparent.png') }}" alt="{{ data_get($headerData, 'company_name', 'Amiga Gracia') }}" class="h-14 w-auto">
                        </div>

                        <!-- Social Icons -->
                        <div class="flex gap-4 pt-2">
                            @forelse($footerData['social_links'] ?? [] as $social)
                                <a href="{{ $social['url'] ?? '#' }}" target="_blank" class="h-10 w-10 rounded-full bg-white/10 hover:bg-emerald-500 flex items-center justify-center transition text-white" aria-label="{{ $social['platform'] ?? 'Social' }}">
                                    <span class="text-sm font-bold">{{ strtoupper(substr($social['platform'] ?? 'SM', 0, 2)) }}</span>
                                </a>
                            @empty
                                <a href="https://www.facebook.com/profile.php?id=100072122019511" target="_blank" class="h-10 w-10 rounded-full bg-white/10 hover:bg-emerald-500 flex items-center justify-center transition text-white">
                                    <svg class="h-5 w-5 fill-current" viewBox="0 0 24 24">
                                        <path d="M22 12c0-5.52-4.48-10-10-10S2 6.48 2 12c0 4.84 3.44 8.87 8 9.8V15H8v-3h2V9.5C10 7.57 11.57 6 13.5 6H16v3h-2c-.55 0-1 .45-1 1V12h3v3h-3v6.8c4.56-.93 8-4.96 8-9.8z"/>
                                    </svg>
                                </a>
                                <a href="https://www.tiktok.com/@amigagracia?_r=1" target="_blank" class="h-10 w-10 rounded-full bg-white/10 hover:bg-emerald-500 flex items-center justify-center transition text-white">
                                    <svg class="h-5 w-5 fill-current" viewBox="0 0 24 24">
                                        <path d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.17-2.86-.74-3.94-1.74-.22-.2-.43-.42-.62-.65v7.17c.02 1.36-.26 2.74-.91 3.97-.8 1.48-2.2 2.63-3.82 3.1-1.61.47-3.36.33-4.9-.38-1.54-.7-2.79-2.02-3.38-3.63-.59-1.61-.53-3.44.18-5 1-2.2 3.32-3.75 5.75-3.64.09 0 .17.02.26.03V10.7c-1.43-.07-2.91.43-3.9 1.48-.99 1.05-1.41 2.58-1.15 4.02.26 1.44 1.22 2.68 2.53 3.3 1.31.62 2.87.58 4.14-.14 1.27-.72 2.05-2.09 2.08-3.56v-15.8z"/>
                                    </svg>
                                </a>
                            @endforelse
                        </div>
                    </div>

                    <!-- Column 2: Sitemap -->
                    <div class="space-y-4">
                        <h5 class="text-sm font-bold uppercase tracking-wider text-emerald-400">Sitemap</h5>
                        <ul class="space-y-2 text-sm text-slate-300 font-medium">
                            <li><a href="{{ url('/') }}" class="hover:text-emerald-300 transition">Home</a></li>
                            <li><a href="{{ url('/about') }}" class="hover:text-emerald-300 transition">About</a></li>
                            <li><a href="{{ url('/schedules') }}" class="hover:text-emerald-300 transition">Schedules</a></li>
                            <li><a href="{{ url('/services') }}" class="hover:text-emerald-300 transition">Services</a></li>
                            <li><a href="{{ url('/tour-package') }}" class="hover:text-emerald-300 transition">Tour Packages</a></li>
                            <li><a href="{{ url('/faqs') }}" class="hover:text-emerald-300 transition">Frequently Asked Questions</a></li>
                            <li><a href="{{ url('/contact-us') }}" class="hover:text-emerald-300 transition">Contact Us</a></li>
                            <li><a href="{{ url('/download') }}" class="hover:text-emerald-300 transition">Download App</a></li>
                        </ul>
                    </div>

                    <!-- Column 3: Transit Services -->
                    <div class="space-y-4">
                        <h5 class="text-sm font-bold uppercase tracking-wider text-emerald-400">Transit</h5>
                        <ul class="space-y-2 text-sm text-slate-300 font-medium">
                            @forelse($footerData['transit_links'] ?? [] as $transit)
                                <li><a href="{{ $transit['url'] }}" class="hover:text-emerald-300 transition">{{ $transit['label'] }}</a></li>
                            @empty
                                <li><a href="{{ url('/book/new') }}" class="hover:text-emerald-300 transition">2GO Travel</a></li>
                                <li><a href="{{ url('/book/new') }}" class="hover:text-emerald-300 transition">Starlite Ferries Inc.</a></li>
                                <li><a href="{{ url('/book/new') }}" class="hover:text-emerald-300 transition">Airline Ticketing</a></li>
                            @endforelse
                        </ul>
                    </div>

                    <!-- Column 4: Support -->
                    <div class="space-y-4">
                        <h5 class="text-sm font-bold uppercase tracking-wider text-emerald-400">Support</h5>
                        <ul class="space-y-2 text-sm text-slate-300 font-medium">
                            <li><a href="{{ url('/contact-us') }}" class="hover:text-emerald-300 transition">Contact Us</a></li>
                            <li><a href="{{ url('/faqs') }}" class="hover:text-emerald-300 transition">Frequently Asked Questions</a></li>
                        </ul>
                    </div>

                    <!-- Column 5: Contact details -->
                    <div class="space-y-4">
                        <h5 class="text-sm font-bold uppercase tracking-wider text-emerald-400">Contact Info</h5>
                        <ul class="space-y-3 text-sm text-slate-300 font-medium">
                            <li class="flex gap-2 items-center">
                                <span class="font-semibold text-emerald-400">Mobile:</span>
                                <span>{{ data_get($footerData, 'phone', '0930-928-4278') }}</span>
                            </li>
                            <li class="flex gap-2 items-center">
                                <span class="font-semibold text-emerald-400">Landline:</span>
                                <span>{{ data_get($footerData, 'landline', '(043) 738-2989') }}</span>
                            </li>
                            <li class="flex flex-wrap gap-1 items-center">
                                <span class="font-semibold text-emerald-400">Email:</span>
                                <span class="hover:text-emerald-300 break-all"><a href="mailto:{{ data_get($footerData, 'email', 'agtsreservation@amigagracia.com') }}">{{ data_get($footerData, 'email', 'agtsreservation@amigagracia.com') }}</a></span>
                            </li>
                            <li class="text-sm leading-relaxed pt-2 text-slate-400 font-medium">
                                {{ data_get($footerData, 'address', 'Roxas Drive, Libis, Calapan City, Oriental Mindoro, 5200') }}
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Bottom bar -->
                <div class="pt-8 flex flex-col md:flex-row items-center justify-between gap-4 text-sm text-slate-400 relative z-10">
                    <div class="space-y-1 text-center md:text-left">
                        <p>&copy; 2017 – {{ date('Y') }} {{ $headerData['company_name'] ?? 'Amiga Gracia Travel Services' }}. All rights reserved.</p>
                        <p class="text-slate-500">Developed by Aries King N. Nieto and Drew M. Macaraig</p>
                    </div>
                    <div class="flex flex-wrap gap-6 items-center justify-center md:justify-end">
                        <a href="{{ url('/download') }}" class="hover:text-emerald-300 transition">Download App</a>
                        <a href="{{ url('/contact-us') }}" class="hover:text-emerald-300 transition">Support</a>
                        @if(!empty($footerData['app_version']))
                            <span class="text-slate-500">App version {{ $footerData['app_version'] }}</span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Watermark Background Text: AMIGA GRACIA -->
            <div class="absolute bottom-[-3.5rem] left-1/2 -translate-x-1/2 w-full text-center select-none pointer-events-none opacity-[0.03] z-0">
                <span class="text-[12vw] font-black uppercase tracking-widest whitespace-nowrap text-white">AMIGA GRACIA</span>
            </div>
        </footer>
        @livewireScripts
        @stack('scripts')
        <script>
            if ('serviceWorker' in navigator) {
                window.addEventListener('load', function () {
                    navigator.serviceWorker.register('/sw.js').catch(function (error) {
                        console.warn('Service worker registration failed:', error);
                    });
                });
            }
        </script>
    </body>
</html>
