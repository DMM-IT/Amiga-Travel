@extends('layouts.app')

@section('content')
<div class="bg-slate-50 min-h-screen py-12 px-4 sm:px-6 lg:px-8" x-data="{ openLightbox: false, activeImg: '', activeCaption: '' }">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="text-center mb-16">
            <span class="text-xs font-semibold uppercase tracking-wider text-emerald-700 bg-emerald-100 px-3 py-1 rounded-full">{{ $pageContent['badge'] ?? 'Gallery' }}</span>
            <h1 class="mt-4 text-4xl sm:text-5xl font-black text-slate-900 tracking-tight">{{ $pageContent['title'] ?? 'Explore the Experience' }}</h1>
            <p class="mt-4 text-lg text-slate-600 max-w-2xl mx-auto">
                {{ $pageContent['description'] ?? 'Immerse yourself in the vibrant images showcasing our unique travel packages, destinations, and unforgettable group tours.' }}
            </p>
        </div>

        @php
            $galleryItems = $pageContent['gallery_items'] ?? [
                [
                    'image' => 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=800&q=80',
                    'alt' => 'Boracay Beach',
                    'label' => 'Domestic Package',
                    'title' => 'Boracay Getaways',
                    'description' => 'Crystal waters and premium accommodations.',
                    'caption' => 'Boracay Island Beach - Pristine White Sands & Sunset Sails',
                ],
                [
                    'image' => 'https://images.unsplash.com/photo-1544735716-392fe2489ffa?auto=format&fit=crop&w=800&q=80',
                    'alt' => 'Ferry Cruising',
                    'label' => 'Ferry Transit',
                    'title' => '2GO & Starlite Fleet',
                    'description' => 'Hassle-free sea travel booking partner.',
                    'caption' => 'Ferry Cruising - Fast, Safe & Luxury Transit Experience',
                ],
                [
                    'image' => 'https://images.unsplash.com/photo-1518156677180-95a2893f3e9f?auto=format&fit=crop&w=800&q=80',
                    'alt' => 'El Nido Lagoon',
                    'label' => 'Domestic Package',
                    'title' => 'Palawan Expeditions',
                    'description' => 'Explore the world-famous hidden lagoons.',
                    'caption' => 'El Nido, Palawan - Towering Karsts and Hidden Lagoons',
                ],
                [
                    'image' => 'https://images.unsplash.com/photo-1527631746610-bca00a040d60?auto=format&fit=crop&w=800&q=80',
                    'alt' => 'Group Tour',
                    'label' => 'Group Tour',
                    'title' => 'Educational Apprenticeships',
                    'description' => 'Memories that last a lifetime with friends.',
                    'caption' => 'Group Travels - Joyful Educational and Corporate Tours',
                ],
                [
                    'image' => 'https://images.unsplash.com/photo-1506929562872-bb421503ef21?auto=format&fit=crop&w=800&q=80',
                    'alt' => 'Tropical Island',
                    'label' => 'Custom Tour',
                    'title' => 'Philippine Island Tours',
                    'description' => 'Handpicked scenic natural treasures.',
                    'caption' => 'Tropical Escapes - Customized itineraries across beautiful archipelagos',
                ],
                [
                    'image' => 'https://images.unsplash.com/photo-1488646953014-85cb44e25828?auto=format&fit=crop&w=800&q=80',
                    'alt' => 'Planning Adventure',
                    'label' => 'Services',
                    'title' => 'Adventure Planning',
                    'description' => 'Expert assistance from start to finish.',
                    'caption' => 'Tour Planning - Seamlessly mapped arrangements by travel specialists',
                ],
            ];
        @endphp
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($galleryItems as $item)
                <div class="group relative rounded-[2rem] overflow-hidden shadow-lg ring-1 ring-slate-100 bg-white cursor-pointer transition hover:shadow-xl hover:-translate-y-1 duration-300"
                     @click="openLightbox = true; activeImg = '{{ $item['image'] }}'; activeCaption = '{{ $item['caption'] }}'">
                    <div class="aspect-video w-full overflow-hidden bg-slate-200">
                        <img src="{{ $item['image'] }}" 
                             alt="{{ $item['alt'] }}" 
                             class="w-full h-full object-cover transition duration-500 group-hover:scale-105">
                    </div>
                    <div class="p-6">
                        <span class="text-[10px] font-bold text-emerald-700 uppercase tracking-widest bg-emerald-50 px-2.5 py-1 rounded-full">{{ $item['label'] }}</span>
                        <h3 class="mt-3 font-bold text-slate-900 group-hover:text-emerald-700 transition">{{ $item['title'] }}</h3>
                        <p class="mt-1 text-xs text-slate-500">{{ $item['description'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Lightbox Modal -->
        <template x-if="openLightbox">
            <div class="fixed inset-0 z-50 bg-black/90 backdrop-blur-md flex flex-col items-center justify-center p-4 transition-opacity duration-300"
                 @keydown.escape.window="openLightbox = false">
                <!-- Close Button -->
                <button class="absolute top-4 right-4 p-3 rounded-full bg-white/10 text-white hover:bg-white/20 transition cursor-pointer"
                        @click="openLightbox = false">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>

                <!-- Content Container -->
                <div class="max-w-4xl max-h-[80vh] overflow-hidden flex items-center justify-center p-2">
                    <img :src="activeImg" 
                         :alt="activeCaption" 
                         class="max-w-full max-h-[75vh] rounded-2xl object-contain shadow-2xl">
                </div>

                <!-- Caption -->
                <p class="mt-6 text-white text-center text-sm md:text-base font-medium max-w-xl" x-text="activeCaption"></p>
            </div>
        </template>
    </div>
</div>
@endsection
