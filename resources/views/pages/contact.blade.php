@extends('layouts.public')

@section('title', 'Contact Studio & Location — The Drive Clinic Jammu')
@section('meta_description', 'Contact The Drive Clinic in Nanak Nagar, Jammu. View opening hours, WhatsApp contact, telephone, and studio directions.')

@section('content')
<div class="py-12 sm:py-16 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto space-y-16">
    <div class="max-w-3xl mx-auto text-center space-y-4">
        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-mint text-teal font-semibold text-[13px]">
            <span>Get in Touch</span>
        </div>
        <h1 class="font-display font-extrabold text-[36px] sm:text-[50px] text-ink tracking-tight leading-[1.05]">
            Contact Our Studio
        </h1>
        <p class="font-body text-[18px] sm:text-[20px] text-muted leading-relaxed">
            Reach out by phone, WhatsApp, or drop by our studio in Nanak Nagar, Jammu.
        </p>
    </div>

    {{-- 3 Contact Channel Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 max-w-5xl mx-auto">
        {{-- Card 1: WhatsApp --}}
        <a href="https://wa.me/{{ $businessInfo['whatsapp'] }}" target="_blank" class="card-panel bg-white p-6 text-center space-y-3 hover:border-teal transition-all group shadow-card">
            <div class="w-12 h-12 rounded-full bg-[#25D366]/15 text-[#25D366] font-bold flex items-center justify-center mx-auto group-hover:scale-110 transition-transform">
                <svg class="w-6 h-6 fill-current" viewBox="0 0 24 24">
                    <path d="M12.004 2C6.48 2 2 6.48 2 12.004c0 1.944.56 3.76 1.53 5.305L2.08 22l4.834-1.42a9.96 9.96 0 0 0 5.09 1.428h.004c5.524 0 10.004-4.48 10.004-10.004C22.012 6.48 17.528 2 12.004 2Zm5.84 14.18c-.244.688-1.42 1.32-1.956 1.396-.51.072-1.168.104-3.79-0.984-2.82-1.168-4.63-4.04-4.77-4.228-.14-.188-1.134-1.51-1.134-2.88 0-1.37.718-2.044.974-2.324.254-.28.56-.35.748-.35.188 0 .376.002.54.01.176.008.41-.068.642.488.244.588.83 2.024.904 2.172.072.15.122.326.022.524-.098.2-.148.326-.296.5-.148.174-.31.39-.444.524-.148.15-.302.312-.13.608.172.296.764 1.26 1.638 2.04 1.124 1.002 2.072 1.314 2.368 1.462.296.15.47.126.642-.072.174-.2.744-.864.944-1.16.2-.298.398-.248.67-.148.272.098 1.728.816 2.024.964.296.15.494.224.568.35.074.124.074.724-.17 1.412Z"/>
                </svg>
            </div>
            <h3 class="font-display font-bold text-[20px] text-ink">WhatsApp Studio</h3>
            <p class="text-[14px] text-muted">Instant replies for appointments, estimates and photos.</p>
            <span class="text-teal font-bold text-[15px] block pt-2">Chat with Studio</span>
        </a>

        {{-- Card 2: Phone Call --}}
        <a href="tel:{{ preg_replace('/[^0-9+]/', '', $businessInfo['phone']) }}" class="card-panel bg-white p-6 text-center space-y-3 hover:border-teal transition-all group shadow-card">
            <div class="w-12 h-12 rounded-full bg-mint text-teal font-bold flex items-center justify-center mx-auto group-hover:scale-110 transition-transform">
                <svg class="w-6 h-6 stroke-current stroke-[2]" fill="none" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 0 0 2.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 0 1-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 0 0-1.091-.852H4.5A2.25 2.25 0 0 0 2.25 4.5v2.25Z" />
                </svg>
            </div>
            <h3 class="font-display font-bold text-[20px] text-ink">Call Directly</h3>
            <p class="text-[14px] text-muted">{{ $businessInfo['phone'] }}</p>
            <span class="text-teal font-bold text-[15px] block pt-2">Call Studio</span>
        </a>

        {{-- Card 3: Visit In Person --}}
        <a href="https://maps.google.com/?q=Nanak+Nagar+Jammu" target="_blank" class="card-panel bg-white p-6 text-center space-y-3 hover:border-teal transition-all group shadow-card">
            <div class="w-12 h-12 rounded-full bg-amber-50 text-amber font-bold flex items-center justify-center mx-auto group-hover:scale-110 transition-transform">
                <svg class="w-6 h-6 stroke-current stroke-[2]" fill="none" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                </svg>
            </div>
            <h3 class="font-display font-bold text-[20px] text-ink">Visit Studio</h3>
            <p class="text-[14px] text-muted">{{ $businessInfo['address'] }}</p>
            <span class="text-teal font-bold text-[15px] block pt-2">Get Directions</span>
        </a>
    </div>

    {{-- Contact Form & Studio Hours Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 max-w-5xl mx-auto items-start">
        {{-- Contact Form --}}
        <div class="lg:col-span-7 card-panel bg-white p-6 sm:p-8 shadow-card space-y-6">
            <h3 class="font-display font-bold text-[22px] text-ink border-b border-line pb-3">Send a Direct Message</h3>

            @if(session('success'))
                <div class="p-4 rounded-[8px] bg-mint text-teal font-semibold text-[15px] border border-teal/20">
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('contact.submit') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label for="name" class="block font-semibold text-[14px] text-ink mb-1">Your Full Name *</label>
                    <input type="text" id="name" name="name" required value="{{ old('name') }}" placeholder="e.g. Rahul Sharma" class="w-full h-11 px-3.5 rounded-[8px] border border-line focus:border-teal focus:ring-1 focus:ring-teal outline-none font-body text-[15px]">
                    @error('name') <span class="text-red-600 text-[13px]">{{ $message }}</span> @enderror
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="mobile" class="block font-semibold text-[14px] text-ink mb-1">Mobile / WhatsApp *</label>
                        <input type="tel" id="mobile" name="mobile" required value="{{ old('mobile') }}" placeholder="10-digit number" class="w-full h-11 px-3.5 rounded-[8px] border border-line focus:border-teal focus:ring-1 focus:ring-teal outline-none font-body text-[15px]">
                        @error('mobile') <span class="text-red-600 text-[13px]">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label for="email" class="block font-semibold text-[14px] text-ink mb-1">Email Address</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="optional" class="w-full h-11 px-3.5 rounded-[8px] border border-line focus:border-teal focus:ring-1 focus:ring-teal outline-none font-body text-[15px]">
                        @error('email') <span class="text-red-600 text-[13px]">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div>
                    <label for="subject" class="block font-semibold text-[14px] text-ink mb-1">Inquiry Topic</label>
                    <input type="text" id="subject" name="subject" value="{{ old('subject') }}" placeholder="e.g. Paint Correction for Fortuner" class="w-full h-11 px-3.5 rounded-[8px] border border-line focus:border-teal focus:ring-1 focus:ring-teal outline-none font-body text-[15px]">
                </div>

                <div>
                    <label for="message" class="block font-semibold text-[14px] text-ink mb-1">Your Message *</label>
                    <textarea id="message" name="message" rows="4" required placeholder="Tell us how we can help your vehicle..." class="w-full p-3.5 rounded-[8px] border border-line focus:border-teal focus:ring-1 focus:ring-teal outline-none font-body text-[15px]">{{ old('message') }}</textarea>
                    @error('message') <span class="text-red-600 text-[13px]">{{ $message }}</span> @enderror
                </div>

                <x-button variant="primary" size="lg" type="submit" class="w-full justify-center">
                    Send Studio Message
                </x-button>
            </form>
        </div>

        {{-- Studio Hours & Capacity Panel --}}
        <div class="lg:col-span-5 space-y-6">
            <div class="card-panel bg-white p-6 sm:p-7 shadow-card space-y-4">
                <h3 class="font-display font-bold text-[20px] text-ink border-b border-line pb-2">Operating Hours</h3>
                <div class="space-y-2 text-[15px]">
                    <div class="flex items-center justify-between py-1 border-b border-line/40">
                        <span class="font-medium text-ink">Monday – Friday</span>
                        <span class="font-bold text-teal">9:00 AM – 7:00 PM</span>
                    </div>
                    <div class="flex items-center justify-between py-1 border-b border-line/40">
                        <span class="font-medium text-ink">Saturday</span>
                        <span class="font-bold text-teal">9:00 AM – 8:00 PM</span>
                    </div>
                    <div class="flex items-center justify-between py-1">
                        <span class="font-medium text-ink">Sunday</span>
                        <span class="font-bold text-teal">9:00 AM – 8:00 PM</span>
                    </div>
                </div>
                <p class="text-[12px] text-muted pt-2 border-t border-line/60">
                    3–4 Dedicated bays in Nanak Nagar for fast turnaround.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
