@php
    $announcementActive = \App\Models\Setting::get('website.announcement_bar_active', true);
    $announcementText = \App\Models\Setting::get('website.announcement_bar_text', 'Grand Opening in Nanak Nagar, Jammu! Get a Free Digital Car Health Check.');
    $announcementLink = \App\Models\Setting::get('website.announcement_bar_link', '/free-car-health-check');
    $phone = \App\Models\Setting::get('business.phone', '+91 94191 00000');
    $cleanPhone = preg_replace('/[^0-9+]/', '', $phone);
@endphp

<header x-data="{ mobileOpen: false }" class="sticky top-0 z-40 w-full transition-shadow duration-300 bg-white" data-motion="header">
    {{-- Top Announcement Bar --}}
    @if($announcementActive && $announcementText)
        <div class="bg-teal-deep text-white text-[13px] sm:text-[14px] font-medium py-2 px-4 text-center select-none border-b border-white/10">
            <div class="max-w-7xl mx-auto flex items-center justify-center gap-2">
                <span>{{ $announcementText }}</span>
                @if($announcementLink)
                    <a href="{{ url($announcementLink) }}" class="underline decoration-amber underline-offset-4 font-semibold text-amber hover:text-amber-300 ml-1">
                        Learn more
                    </a>
                @endif
            </div>
        </div>
    @endif

    {{-- Main Navbar --}}
    <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-[76px] flex items-center justify-between border-b border-line/60">
        {{-- Logo --}}
        <div class="flex items-center gap-8">
            <x-logo variant="light" :height="42" />
        </div>

        {{-- Desktop Navigation Links --}}
        <div class="hidden lg:flex items-center gap-7 text-[16px] font-semibold text-ink">
            <a href="{{ route('services.index') }}" class="hover:text-teal transition-colors py-1 relative group">
                Services
                <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-amber transition-all duration-200 group-hover:w-full"></span>
            </a>
            <a href="{{ route('health-check.form') }}" class="hover:text-teal transition-colors py-1 relative group">
                Health Check
                <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-amber transition-all duration-200 group-hover:w-full"></span>
            </a>
            <a href="{{ route('drive-club') }}" class="hover:text-teal transition-colors py-1 relative group">
                Drive Club
                <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-amber transition-all duration-200 group-hover:w-full"></span>
            </a>
            <a href="{{ route('gallery') }}" class="hover:text-teal transition-colors py-1 relative group">
                Gallery
                <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-amber transition-all duration-200 group-hover:w-full"></span>
            </a>
            <a href="{{ route('about') }}" class="hover:text-teal transition-colors py-1 relative group">
                About
                <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-amber transition-all duration-200 group-hover:w-full"></span>
            </a>
            <a href="{{ route('contact') }}" class="hover:text-teal transition-colors py-1 relative group">
                Contact
                <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-amber transition-all duration-200 group-hover:w-full"></span>
            </a>
        </div>

        {{-- Right Actions --}}
        <div class="hidden lg:flex items-center gap-3">
            <a href="tel:{{ $cleanPhone }}" class="inline-flex items-center gap-2 px-3.5 py-2 text-[15px] font-bold text-ink hover:text-teal transition-colors" aria-label="Call The Drive Clinic">
                <svg class="w-4 h-4 text-teal stroke-current stroke-[2]" fill="none" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 0 0 2.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 0 1-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 0 0-1.091-.852H4.5A2.25 2.25 0 0 0 2.25 4.5v2.25Z" />
                </svg>
                <span>Call</span>
            </a>
            <x-button variant="primary" size="sm" :href="route('book')">
                Book your car
            </x-button>
        </div>

        {{-- Mobile Hamburger Button --}}
        <div class="flex items-center gap-2 lg:hidden">
            <x-button variant="primary" size="sm" :href="route('book')" class="text-[14px] px-3.5 min-h-[40px]">
                Book
            </x-button>
            <button
                type="button"
                @click="mobileOpen = !mobileOpen"
                class="p-2.5 rounded-[8px] text-ink hover:bg-mist transition-colors focus:outline-none focus:ring-2 focus:ring-amber"
                aria-label="Toggle navigation menu"
            >
                <svg x-show="!mobileOpen" class="w-6 h-6 stroke-current stroke-2" fill="none" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                </svg>
                <svg x-show="mobileOpen" class="w-6 h-6 stroke-current stroke-2" fill="none" viewBox="0 0 24 24" style="display: none;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    </nav>

    {{-- Mobile Menu Dropdown --}}
    <div
        x-show="mobileOpen"
        x-collapse
        class="lg:hidden bg-white border-b border-line px-6 py-6 shadow-lg space-y-4"
        style="display: none;"
    >
        <div class="flex flex-col space-y-3 font-semibold text-[17px] text-ink">
            <a href="{{ route('services.index') }}" @click="mobileOpen = false" class="py-2 hover:text-teal">Services & Prices</a>
            <a href="{{ route('health-check.form') }}" @click="mobileOpen = false" class="py-2 hover:text-teal">Free Car Health Check</a>
            <a href="{{ route('drive-club') }}" @click="mobileOpen = false" class="py-2 hover:text-teal">Drive Club Memberships</a>
            <a href="{{ route('gallery') }}" @click="mobileOpen = false" class="py-2 hover:text-teal">Before & After Gallery</a>
            <a href="{{ route('about') }}" @click="mobileOpen = false" class="py-2 hover:text-teal">About The Clinic</a>
            <a href="{{ route('contact') }}" @click="mobileOpen = false" class="py-2 hover:text-teal">Location & Contact</a>
        </div>

        <div class="pt-4 border-t border-line flex flex-col gap-3">
            <a href="tel:{{ $cleanPhone }}" class="btn-secondary w-full justify-center">
                Call {{ $phone }}
            </a>
            <x-button variant="primary" :href="route('book')" class="w-full justify-center">
                Book your car
            </x-button>
        </div>
    </div>
</header>
