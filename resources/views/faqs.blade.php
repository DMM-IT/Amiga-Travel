@extends('layouts.app')

@section('content')
<div class="bg-slate-50 min-h-screen py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-3xl mx-auto">
        <!-- Hero Section -->
        <div class="text-center mb-12">
            <div class="flex items-center justify-center gap-3 mb-4">
                <span class="text-xs font-semibold uppercase tracking-wider text-[#ee018d] bg-pink-100 px-3 py-1 rounded-full">Help Center</span>
            </div>
            <h1 class="text-4xl sm:text-5xl font-black text-slate-900 tracking-tight">
                {{ $pageContent['title'] ?? 'Frequently Asked Questions' }}
            </h1>
            <p class="mt-4 text-lg text-slate-600 max-w-2xl mx-auto">
                {{ $pageContent['description'] ?? 'Find answers to common questions about bookings, policies, and our services.' }}
            </p>
        </div>

        <!-- FAQs Accordion -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden" x-data="{ activeAccordion: null }">
            @php
                $faqs = $pageContent['faqs'] ?? [];
            @endphp
            
            @forelse($faqs as $index => $faq)
                <div class="border-b border-slate-100 last:border-b-0">
                    <button 
                        @click="activeAccordion === {{ $index }} ? activeAccordion = null : activeAccordion = {{ $index }}"
                        class="flex justify-between items-center w-full px-6 py-5 text-left focus:outline-none focus-visible:bg-slate-50 transition-colors hover:bg-slate-50 group"
                    >
                        <span class="font-bold text-slate-900 text-lg pr-4 group-hover:text-[#ee018d] transition-colors" :class="{ 'text-[#ee018d]': activeAccordion === {{ $index }} }">
                            {{ $faq['question'] }}
                        </span>
                        <span class="shrink-0 text-slate-400 transition-transform duration-300" :class="{ 'rotate-180 text-[#ee018d]': activeAccordion === {{ $index }} }">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </span>
                    </button>
                    
                    <div 
                        x-show="activeAccordion === {{ $index }}" 
                        x-collapse
                        style="display: none;"
                    >
                        <div class="px-6 pb-6 text-slate-600 leading-relaxed text-base pt-2">
                            {!! nl2br(e($faq['answer'])) !!}
                        </div>
                    </div>
                </div>
            @empty
                <div class="p-8 text-center text-slate-500">
                    No frequently asked questions available at the moment.
                </div>
            @endforelse
        </div>
        
        <div class="mt-12 text-center bg-white rounded-2xl p-8 border border-slate-200 shadow-sm flex flex-col items-center">
            <div class="w-12 h-12 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center mb-4">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
            </div>
            <h3 class="text-xl font-bold text-slate-900 mb-2">Still have questions?</h3>
            <p class="text-slate-600 mb-6">If you couldn't find the answer to your question, our support team is here to help.</p>
            <a href="{{ url('/contact-us') }}" class="inline-flex justify-center items-center rounded-xl bg-[#216417] px-6 py-3 text-sm font-semibold text-white shadow-sm hover:bg-[#1e4c21] transition-colors focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#216417]">
                Contact Support
            </a>
        </div>
    </div>
</div>
@endsection
