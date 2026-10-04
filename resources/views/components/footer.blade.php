@php
    $phone = \App\Models\Setting::get('business.phone', '+91 94191 00000');
    $cleanPhone = preg_replace('/[^0-9+]/', '', $phone);
    $address = \App\Models\Setting::get('business.address', 'Nanak Nagar, Jammu, J&K 180004');
    $email = \App\Models\Setting::get('business.email', 'contact@thedriveclinic.in');
@endphp

<footer class="bg-teal-deep text-white pt-16 pb-12 border-t border-white/10" data-motion="footer">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Top Grid: 3 Columns --}}
        <div class="grid grid-cols-1 md:grid-cols-12 gap-10 lg:gap-12 pb-14 border-b border-white/15">
            {{-- Column 1: Brand & Visit --}}
            <div class="md:col-span-5 space-y-5">
                <x-logo variant="dark" :height="44" />
                <p class="text-white/80 font-body text-[16px] max-w-sm leading-relaxed">
                    Your Car's Healthcare Centre in Jammu. Diagnostic foam washes, paint restoration, ceramic protection and complete vehicle health tracking.
                </p>
                <div class="space-y-2.5 text-[15px] text-white/90 pt-2">
                    <p class="flex items-start gap-2.5">
                        <svg class="w-5 h-5 text-amber shrink-0 mt-0.5 stroke-current stroke-[2]" fill="none" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                        </svg>
                        <span>{{ $address }}</span>
                    </p>
                    <p class="flex items-center gap-2.5">
                        <svg class="w-5 h-5 text-amber shrink-0 stroke-current stroke-[2]" fill="none" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 0 0 2.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 0 1-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 0 0-1.091-.852H4.5A2.25 2.25 0 0 0 2.25 4.5v2.25Z" />
                        </svg>
                        <a href="tel:{{ $cleanPhone }}" class="hover:text-amber transition-colors underline decoration-white/30">{{ $phone }}</a>
                    </p>
                    <p class="flex items-center gap-2.5">
                        <svg class="w-5 h-5 text-amber shrink-0 stroke-current stroke-[2]" fill="none" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>
                        <span>Mon–Fri: 9:00 AM – 7:00 PM | Sat–Sun: 9:00 AM – 8:00 PM</span>
                    </p>
                </div>
            </div>

            {{-- Column 2: Services --}}
            <div class="md:col-span-3 space-y-4">
                <h3 class="font-display font-extrabold text-[18px] text-white tracking-wide uppercase text-amber">
                    Services
                </h3>
                <ul class="space-y-2.5 text-[16px] text-white/80 font-medium">
                    <li><a href="{{ route('services.index') }}" class="hover:text-white hover:underline transition-colors">Foam Wash & Decontamination</a></li>
                    <li><a href="{{ route('services.index') }}" class="hover:text-white hover:underline transition-colors">Interior Deep Sanitization</a></li>
                    <li><a href="{{ route('services.index') }}" class="hover:text-white hover:underline transition-colors">Paint Correction & Polish</a></li>
                    <li><a href="{{ route('services.index') }}" class="hover:text-white hover:underline transition-colors">Ceramic Coating Protection</a></li>
                    <li><a href="{{ route('health-check.form') }}" class="hover:text-amber text-amber font-semibold transition-colors">Free Car Health Check</a></li>
                    <li><a href="{{ route('drive-club') }}" class="hover:text-white hover:underline transition-colors">Drive Club Memberships</a></li>
                </ul>
            </div>

            {{-- Column 3: The Clinic & Trust --}}
            <div class="md:col-span-4 space-y-4">
                <h3 class="font-display font-extrabold text-[18px] text-white tracking-wide uppercase text-amber">
                    The Clinic
                </h3>
                <ul class="space-y-2.5 text-[16px] text-white/80 font-medium">
                    <li><a href="{{ route('about') }}" class="hover:text-white hover:underline transition-colors">About Our Healthcare Philosophy</a></li>
                    <li><a href="{{ route('gallery') }}" class="hover:text-white hover:underline transition-colors">Before & After Results</a></li>
                    <li><a href="{{ route('contact') }}" class="hover:text-white hover:underline transition-colors">Studio Location & Map</a></li>
                    <li><a href="{{ route('faq') }}" class="hover:text-white hover:underline transition-colors">Frequently Asked Questions</a></li>
                    <li><a href="{{ route('privacy') }}" class="hover:text-white hover:underline transition-colors">Privacy Policy (DPDP Act)</a></li>
                    <li><a href="{{ route('terms') }}" class="hover:text-white hover:underline transition-colors">Terms of Service</a></li>
                    <li class="pt-2"><a href="{{ url('/admin') }}" class="text-[14px] text-white/50 hover:text-white transition-colors">Staff & Manager Portal</a></li>
                </ul>
            </div>
        </div>

        {{-- Bottom Copyright Bar --}}
        <div class="pt-8 flex flex-col sm:flex-row items-center justify-between text-[14px] text-white/60 gap-4">
            <p>© {{ date('Y') }} The Drive Clinic. All rights reserved. Nanak Nagar, Jammu, J&K.</p>
            <p class="text-center sm:text-right">
                Built with precision for Jammu's premier car care studio.
            </p>
        </div>
    </div>
</footer>
