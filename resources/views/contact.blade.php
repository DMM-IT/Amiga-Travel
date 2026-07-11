@extends('layouts.app')

@section('content')
<div class="bg-slate-50 min-h-screen py-12 px-4 sm:px-6 lg:px-8" x-data="{ 
    submitted: false,
    name: '',
    email: '',
    subject: '',
    message: '',
    loading: false,
    submitForm() {
        if (!this.name || !this.email || !this.message) return;
        this.loading = true;
        setTimeout(() => {
            this.loading = false;
            this.submitted = true;
            this.name = '';
            this.email = '';
            this.subject = '';
            this.message = '';
        }, 1500);
    }
}">
    <div class="max-w-6xl mx-auto">
        <!-- Header -->
        <div class="text-center mb-16">
            <span class="text-xs font-semibold uppercase tracking-wider text-emerald-700 bg-emerald-100 px-3 py-1 rounded-full">Contact Us</span>
            <h1 class="mt-4 text-4xl sm:text-5xl font-black text-slate-900 tracking-tight">Get in Touch</h1>
            <p class="mt-4 text-lg text-slate-600 max-w-2xl mx-auto">
                Have questions about routes, ticketing, or custom tour packages? Drop us a message, and our travel specialists will get back to you shortly.
            </p>
        </div>

        <div class="grid lg:grid-cols-3 gap-8 items-stretch">
            <!-- Contact Info Sidebar -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Phone -->
                <div class="bg-white p-8 rounded-[2rem] shadow-md ring-1 ring-slate-100 flex items-start gap-4">
                    <div class="h-12 w-12 bg-pink-100 rounded-2xl flex items-center justify-center text-pink-600 flex-shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.94.725l.548 2.2a1 1 0 01-.321.988l-1.305.98a10.582 10.582 0 004.872 4.872l.98-1.305a1 1 0 01.988-.321l2.2.548a1 1 0 01.725.94V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-slate-900">Phone Numbers</h3>
                        <p class="mt-2 text-sm text-slate-500 font-semibold">Mobile: 0930-928-4278</p>
                        <p class="text-sm text-slate-500 font-semibold">Landline: (043) 738-2989</p>
                    </div>
                </div>

                <!-- Email -->
                <div class="bg-white p-8 rounded-[2rem] shadow-md ring-1 ring-slate-100 flex items-start gap-4">
                    <div class="h-12 w-12 bg-emerald-100 rounded-2xl flex items-center justify-center text-emerald-700 flex-shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-slate-900">Email Addresses</h3>
                        <p class="mt-2 text-sm text-slate-500 font-semibold truncate hover:text-emerald-700">
                            <a href="mailto:agt.salesmarketing1103@gmail.com">agt.salesmarketing1103@gmail.com</a>
                        </p>
                        <p class="text-sm text-slate-500 font-semibold truncate hover:text-emerald-700">
                            <a href="mailto:amigagracia.travelservices@gmail.com">amigagracia.travelservices@gmail.com</a>
                        </p>
                    </div>
                </div>

                <!-- Location -->
                <div class="bg-white p-8 rounded-[2rem] shadow-md ring-1 ring-slate-100 flex items-start gap-4">
                    <div class="h-12 w-12 bg-blue-100 rounded-2xl flex items-center justify-center text-blue-600 flex-shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-slate-900">Office Location</h3>
                        <p class="mt-2 text-sm text-slate-500 leading-relaxed font-semibold">
                            Roxas Drive, Libis, Calapan City,<br>Oriental Mindoro, 5200
                        </p>
                    </div>
                </div>

                <!-- Socials -->
                <div class="bg-white p-8 rounded-[2rem] shadow-md ring-1 ring-slate-100 flex items-start gap-4">
                    <div class="h-12 w-12 bg-purple-100 rounded-2xl flex items-center justify-center text-purple-600 flex-shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-slate-900">Social Media</h3>
                        <p class="mt-2 text-sm text-slate-500 font-semibold hover:text-purple-600">
                            <a href="https://www.facebook.com/profile.php?id=100072122019511" target="_blank">Facebook: Amiga Gracia</a>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Contact Form -->
            <div class="lg:col-span-2 bg-white rounded-[2rem] p-8 sm:p-10 shadow-md ring-1 ring-slate-100 flex flex-col justify-between">
                <div class="relative">
                    <h2 class="text-2xl font-bold text-slate-900 mb-6">Send an Inquiry</h2>

                    <!-- Success Message -->
                    <div x-show="submitted" x-transition class="p-6 bg-emerald-50 rounded-2xl border border-emerald-200 text-emerald-800 mb-6 flex gap-4 items-start">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-emerald-600 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <div>
                            <h3 class="font-bold text-emerald-950">Inquiry Sent Successfully!</h3>
                            <p class="text-xs text-emerald-700 mt-1">Thank you for contacting us. One of our travel consultants will get in touch with you shortly at the email address provided.</p>
                            <button @click="submitted = false" class="mt-4 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs rounded-full transition">Send Another Message</button>
                        </div>
                    </div>

                    <!-- Main Form -->
                    <form @submit.prevent="submitForm()" x-show="!submitted" class="space-y-6">
                        <div class="grid sm:grid-cols-2 gap-6">
                            <div>
                                <label for="name" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Your Name *</label>
                                <input type="text" id="name" x-model="name" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:border-[#216417] text-sm text-slate-800">
                            </div>
                            <div>
                                <label for="email" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Email Address *</label>
                                <input type="email" id="email" x-model="email" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:border-[#216417] text-sm text-slate-800">
                            </div>
                        </div>

                        <div>
                            <label for="subject" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Subject</label>
                            <input type="text" id="subject" x-model="subject" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:border-[#216417] text-sm text-slate-800">
                        </div>

                        <div>
                            <label for="message" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Message *</label>
                            <textarea id="message" x-model="message" rows="5" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:border-[#216417] text-sm text-slate-800 resize-none"></textarea>
                        </div>

                        <div class="flex justify-end">
                            <button type="submit" :disabled="loading" class="px-8 py-3.5 bg-[#216417] hover:bg-green-800 text-white font-bold rounded-full shadow-lg transition flex items-center gap-2 cursor-pointer disabled:opacity-50">
                                <span x-show="!loading">Send Message</span>
                                <span x-show="loading" class="flex items-center gap-2">
                                    <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    Sending...
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Embedded Map Placeholder -->
        <div class="mt-12 bg-white rounded-[2rem] overflow-hidden shadow-md ring-1 ring-slate-100 p-4">
            <h3 class="text-lg font-bold text-slate-900 mb-4 px-4">Find Our Calapan Office</h3>
            <div class="aspect-[21/9] w-full rounded-2xl overflow-hidden bg-slate-100 border border-slate-200 relative flex items-center justify-center">
                <!-- Mock Map Styled Graphic -->
                <div class="absolute inset-0 bg-cover bg-center" style="background-image: url('https://images.unsplash.com/photo-1524661135-423995f22d0b?auto=format&fit=crop&w=1200&q=80'); opacity: 0.15; filter: grayscale(1);"></div>
                <div class="relative z-10 text-center p-6 max-w-md">
                    <div class="mx-auto h-12 w-12 bg-red-100 rounded-full flex items-center justify-center text-red-600 mb-4 animate-bounce shadow-md">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <h4 class="font-bold text-slate-800 text-base">Amiga Gracia Travel Services</h4>
                    <p class="text-xs text-slate-500 mt-1">Roxas Drive, Libis, Calapan City, Oriental Mindoro</p>
                    <p class="text-[10px] text-slate-400 mt-4 uppercase font-bold tracking-wider">Calapan Main Office Location</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
