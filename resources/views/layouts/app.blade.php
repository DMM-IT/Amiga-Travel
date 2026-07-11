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
    </head>
    <body class="bg-slate-50 text-slate-900 min-h-screen flex flex-col">
        <header class="bg-[#216417] text-white sticky top-0 z-50 shadow-md">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-20">
                    <div class="flex items-center gap-3">
                        <a href="{{ url('/') }}" class="flex items-center gap-3">
                            <img src="{{ asset('images/amiga-logo.jpg') }}" alt="Amiga Gracia" class="h-12 w-auto rounded bg-white p-1">
                            <span class="font-bold text-xl uppercase tracking-wider hidden sm:block">Amiga Gracia</span>
                        </a>
                    </div>
                    <nav class="hidden md:flex space-x-8 font-medium">
                        <a href="{{ url('/') }}" class="border-b-2 {{ request()->is('/') ? 'text-[#ee018d] border-[#ee018d]' : 'text-white border-transparent hover:text-[#ee018d] hover:border-[#ee018d]' }} pb-1 transition">Home</a>
                        <a href="{{ url('/about') }}" class="border-b-2 {{ request()->is('about') ? 'text-[#ee018d] border-[#ee018d]' : 'text-white border-transparent hover:text-[#ee018d] hover:border-[#ee018d]' }} pb-1 transition">About</a>
                        <a href="{{ url('/gallery') }}" class="border-b-2 {{ request()->is('gallery') ? 'text-[#ee018d] border-[#ee018d]' : 'text-white border-transparent hover:text-[#ee018d] hover:border-[#ee018d]' }} pb-1 transition">Gallery</a>
                        <a href="{{ url('/services') }}" class="border-b-2 {{ request()->is('services') ? 'text-[#ee018d] border-[#ee018d]' : 'text-white border-transparent hover:text-[#ee018d] hover:border-[#ee018d]' }} pb-1 transition">Services</a>
                        <a href="{{ url('/tour-package') }}" class="border-b-2 {{ request()->is('tour-package') ? 'text-[#ee018d] border-[#ee018d]' : 'text-white border-transparent hover:text-[#ee018d] hover:border-[#ee018d]' }} pb-1 transition">Tour Package</a>
                        <a href="{{ url('/contact-us') }}" class="border-b-2 {{ request()->is('contact-us') ? 'text-[#ee018d] border-[#ee018d]' : 'text-white border-transparent hover:text-[#ee018d] hover:border-[#ee018d]' }} pb-1 transition">Contact Us</a>
                        <a href="{{ url('/download') }}" class="border-b-2 {{ request()->is('download') ? 'text-[#ee018d] border-[#ee018d]' : 'text-white border-transparent hover:text-[#ee018d] hover:border-[#ee018d]' }} pb-1 transition">Download</a>
                    </nav>
                </div>
            </div>
        </header>

        <main class="flex-grow">
            @yield('content')
        </main>

        <footer class="relative overflow-hidden bg-[#0e2709] text-white pt-16 pb-8 mt-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
                <div class="grid grid-cols-2 md:grid-cols-6 gap-8 pb-12 border-b border-white/10">
                    <!-- Column 1: Logo & Tagline -->
                    <div class="col-span-2 md:col-span-2 space-y-4">
                        <div class="flex items-center gap-3">
                            <img src="{{ asset('images/amiga-logo-transparent.png') }}" alt="Amiga Gracia" class="h-14 w-auto">
                            <div>
                                <h4 class="font-extrabold text-xl tracking-wider text-emerald-400">Amiga Gracia</h4>
                                <p class="text-xs text-emerald-100/70">Travel Services</p>
                            </div>
                        </div>
                        <p class="text-xs text-slate-300 leading-relaxed max-w-xs">
                            Kay Amiga, Hassle Free Ka! Offering first-class sea transit, air booking, and custom tours.
                        </p>
                        <!-- Social Icons -->
                        <div class="flex gap-4 pt-2">
                            <a href="https://www.facebook.com/profile.php?id=100072122019511" target="_blank" class="h-8 w-8 rounded-full bg-white/10 hover:bg-emerald-500 flex items-center justify-center transition text-white">
                                <!-- Facebook Icon -->
                                <svg class="h-4 w-4 fill-current" viewBox="0 0 24 24">
                                    <path d="M22 12c0-5.52-4.48-10-10-10S2 6.48 2 12c0 4.84 3.44 8.87 8 9.8V15H8v-3h2V9.5C10 7.57 11.57 6 13.5 6H16v3h-2c-.55 0-1 .45-1 1V12h3v3h-3v6.8c4.56-.93 8-4.96 8-9.8z"/>
                                </svg>
                            </a>
                            <a href="https://www.tiktok.com/@amigagracia?_r=1" target="_blank" class="h-8 w-8 rounded-full bg-white/10 hover:bg-emerald-500 flex items-center justify-center transition text-white">
                                <!-- Tiktok Icon -->
                                <svg class="h-4 w-4 fill-current" viewBox="0 0 24 24">
                                    <path d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.17-2.86-.74-3.94-1.74-.22-.2-.43-.42-.62-.65v7.17c.02 1.36-.26 2.74-.91 3.97-.8 1.48-2.2 2.63-3.82 3.1-1.61.47-3.36.33-4.9-.38-1.54-.7-2.79-2.02-3.38-3.63-.59-1.61-.53-3.44.18-5 1-2.2 3.32-3.75 5.75-3.64.09 0 .17.02.26.03V10.7c-1.43-.07-2.91.43-3.9 1.48-.99 1.05-1.41 2.58-1.15 4.02.26 1.44 1.22 2.68 2.53 3.3 1.31.62 2.87.58 4.14-.14 1.27-.72 2.05-2.09 2.08-3.56v-15.8z"/>
                                </svg>
                            </a>
                        </div>
                    </div>

                    <!-- Column 2: Sitemap -->
                    <div class="space-y-4">
                        <h5 class="text-xs font-bold uppercase tracking-wider text-emerald-400">Sitemap</h5>
                        <ul class="space-y-2 text-xs text-slate-300 font-medium">
                            <li><a href="{{ url('/') }}" class="hover:text-emerald-300 transition">Home</a></li>
                            <li><a href="{{ url('/about') }}" class="hover:text-emerald-300 transition">About</a></li>
                            <li><a href="{{ url('/gallery') }}" class="hover:text-emerald-300 transition">Gallery</a></li>
                            <li><a href="{{ url('/services') }}" class="hover:text-emerald-300 transition">Services</a></li>
                            <li><a href="{{ url('/tour-package') }}" class="hover:text-emerald-300 transition">Tour Packages</a></li>
                            <li><a href="{{ url('/contact-us') }}" class="hover:text-emerald-300 transition">Contact Us</a></li>
                        </ul>
                    </div>

                    <!-- Column 3: Transit Services -->
                    <div class="space-y-4">
                        <h5 class="text-xs font-bold uppercase tracking-wider text-emerald-400">Transit</h5>
                        <ul class="space-y-2 text-xs text-slate-300 font-medium">
                            <li><a href="{{ url('/book/new') }}" class="hover:text-emerald-300 transition">2GO Travel</a></li>
                            <li><a href="{{ url('/book/new') }}" class="hover:text-emerald-300 transition">Starlite Ferry</a></li>
                            <li><a href="{{ url('/book/new') }}" class="hover:text-emerald-300 transition">Supercat Fastcraft</a></li>
                            <li><a href="{{ url('/book/new') }}" class="hover:text-emerald-300 transition">Airline Ticketing</a></li>
                        </ul>
                    </div>

                    <!-- Column 4: Contact details -->
                    <div class="space-y-4 col-span-2 md:col-span-2">
                        <h5 class="text-xs font-bold uppercase tracking-wider text-emerald-400">Contact Info</h5>
                        <ul class="space-y-2 text-xs text-slate-300 font-medium">
                            <li class="flex gap-2">
                                <span class="font-semibold text-emerald-400">Mobile:</span>
                                <span>0930-928-4278</span>
                            </li>
                            <li class="flex gap-2">
                                <span class="font-semibold text-emerald-400">Landline:</span>
                                <span>(043) 738-2989</span>
                            </li>
                            <li class="flex flex-wrap gap-1">
                                <span class="font-semibold text-emerald-400 block">Email:</span>
                                <span class="hover:text-emerald-300 break-all"><a href="mailto:agt.salesmarketing1103@gmail.com">agt.salesmarketing1103@gmail.com</a></span>
                            </li>
                            <li class="text-[11px] leading-relaxed pt-2 text-slate-400 font-medium">
                                Roxas Drive, Libis, Calapan City, Oriental Mindoro, 5200
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Bottom bar -->
                <div class="pt-8 flex flex-col md:flex-row items-center justify-between gap-4 text-xs text-slate-400 relative z-10">
                    <p>&copy; 2017 – {{ date('Y') }} Amiga Gracia Travel Services. All rights reserved.</p>
                    <div class="flex gap-6">
                        <a href="{{ url('/download') }}" class="hover:text-emerald-300 transition">Downloads</a>
                        <a href="{{ url('/contact-us') }}" class="hover:text-emerald-300 transition">Support</a>
                    </div>
                </div>
            </div>

            <!-- Watermark Background Text: AMIGA GRACIA -->
            <div class="absolute bottom-[-3.5rem] left-1/2 -translate-x-1/2 w-full text-center select-none pointer-events-none opacity-[0.03] z-0">
                <span class="text-[12vw] font-black uppercase tracking-widest whitespace-nowrap text-white">AMIGA GRACIA</span>
            </div>
        </footer>
        @livewireScripts
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
