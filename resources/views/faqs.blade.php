@extends('layouts.app')

@section('content')
<div class="bg-transparent min-h-screen py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-3xl mx-auto">
        <!-- Hero Section -->
        <div class="text-center mb-16 relative ws-sbtn-container">
            @if(auth('admin')->check()) <button type="button" @click.prevent="$dispatch('open-editor', { section: 'hero' })" class="ws-sbtn absolute top-0 right-0"></button> @endif
            <span class="text-xs font-semibold uppercase tracking-wider text-emerald-700 bg-emerald-100 px-3 py-1 rounded-full">{{ data_get($pageContent, 'badge', 'FAQs') }}</span>
            <h1 class="mt-4 text-4xl sm:text-5xl font-black text-slate-900 tracking-tight">{{ data_get($pageContent, 'title', 'Frequently Asked Questions') }}</h1>
            <p class="mt-4 text-lg text-black font-semibold max-w-2xl mx-auto">
                {{ data_get($pageContent, 'description', 'Find quick answers to common questions about our ticketing, bookings, and other travel services.') }}
            </p>
        </div>

        <!-- FAQs Accordion -->
        <div class="max-w-3xl mx-auto space-y-4 relative ws-sbtn-container">
            @if(auth('admin')->check()) <button type="button" @click.prevent="$dispatch('open-editor', { section: 'faqs_list' })" class="ws-sbtn absolute -top-4 -right-4 z-10"></button> @endif
            @forelse(data_get($pageContent, 'faqs_list', []) as $index => $faq)
                <div x-data="{ expanded: false }" class="bg-white/85 backdrop-blur-md rounded-2xl shadow-sm ring-1 ring-slate-100 overflow-hidden transition-all duration-200" :class="expanded ? 'ring-[#216417] shadow-md' : 'hover:shadow-md'">
                    <button @click="expanded = !expanded" class="w-full px-6 py-5 flex items-center justify-between gap-4 text-left focus:outline-none">
                        <span class="font-bold text-slate-900" :class="expanded ? 'text-[#216417]' : ''">{{ data_get($faq, 'question') }}</span>
                        <div class="flex-shrink-0 h-8 w-8 rounded-full flex items-center justify-center transition-colors duration-200" :class="expanded ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-500'">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 transform transition-transform duration-200" :class="expanded ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                    </button>
                    <div x-show="expanded" x-collapse>
                        <div class="px-6 pb-5 text-slate-600 text-sm leading-relaxed">
                            {!! nl2br(e(data_get($faq, 'answer'))) !!}
                        </div>
                    </div>
                </div>
            @empty
                <!-- Empty State -->
                <div class="text-center py-12 bg-white/50 backdrop-blur-md rounded-3xl border border-slate-100">
                    <div class="h-16 w-16 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-slate-900">{{ data_get($pageContent, 'empty_title', 'No FAQs Yet') }}</h3>
                    <p class="text-sm text-slate-500 mt-1">{{ data_get($pageContent, 'empty_desc', 'Check back later for answers to common questions.') }}</p>
                </div>
            @endforelse
        </div>

        <!-- Contact CTA -->
        <div class="mt-20 bg-gradient-to-br from-[#216417] to-[#14400e] rounded-[2rem] p-8 sm:p-12 text-center text-white shadow-xl flex flex-col items-center justify-center relative ws-sbtn-container">
            @if(auth('admin')->check()) <button type="button" @click.prevent="$dispatch('open-editor', { section: 'cta' })" class="ws-sbtn absolute top-2 right-2"></button> @endif
            <div class="h-16 w-16 bg-emerald-500/20 rounded-full flex items-center justify-center mb-6">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-emerald-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z" />
                </svg>
            </div>
            <h2 class="text-2xl sm:text-3xl font-black mb-4">{{ data_get($pageContent, 'cta_title', 'Still have questions?') }}</h2>
            <p class="text-emerald-100 max-w-xl mx-auto mb-8">
                {{ data_get($pageContent, 'cta_desc', 'If you couldn\'t find the answer you were looking for, our team is ready to help you directly.') }}
            </p>
            <a href="{{ url('/contact-us') }}" class="inline-flex items-center gap-2 px-8 py-4 bg-white text-emerald-900 font-bold rounded-full shadow-lg hover:bg-emerald-50 transition cursor-pointer">
                {{ data_get($pageContent, 'cta_btn', 'Contact Us Directly') }}
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                </svg>
            </a>
        </div>
    </div>
</div>
@endsection
