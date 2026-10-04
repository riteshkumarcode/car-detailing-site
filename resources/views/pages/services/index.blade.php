@extends('layouts.public')

@section('title', 'Services & Pricing — The Drive Clinic Jammu')
@section('meta_description', 'Explore transparent pricing for foam car wash, machine paint correction, ceramic coating and interior deep cleaning in Jammu.')

@section('content')
<div class="py-12 sm:py-16 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto space-y-12" x-data="{
    carType: 'hatchback',
    activeCategory: 'all',
}">
    {{-- Header Banner --}}
    <div class="border-b border-line pb-8">
        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-mint text-teal font-semibold text-[13px] mb-3">
            <span>The Drive Clinic</span>
            <span>•</span>
            <span>Service Catalog</span>
        </div>
        <h1 class="font-display font-extrabold text-[36px] sm:text-[48px] text-ink tracking-tight">
            Services & Vehicle Pricing
        </h1>
        <p class="font-body text-[18px] text-muted max-w-2xl mt-2">
            Select your vehicle category to see exact prices. Every service is performed with dedicated clean tools and 2-bucket swirl-free methods.
        </p>

        {{-- Controls Bar: Car Type Switcher & Category Filters --}}
        <div class="mt-8 flex flex-col md:flex-row md:items-center justify-between gap-6 pt-6 border-t border-line/60">
            {{-- Vehicle Type Switcher (Alpine) --}}
            <div class="flex items-center gap-2 bg-mist p-1.5 rounded-[10px] border border-line w-full sm:w-auto">
                <span class="text-[13px] font-bold text-muted px-2.5 uppercase tracking-wider hidden sm:inline">Vehicle Type:</span>
                <button
                    type="button"
                    @click="carType = 'hatchback'"
                    :class="carType === 'hatchback' ? 'bg-white text-ink shadow-sm font-bold border-line' : 'text-muted hover:text-ink font-semibold'"
                    class="px-4 py-2 rounded-[8px] text-[15px] transition-all flex-1 sm:flex-initial text-center border border-transparent"
                >
                    Hatchback
                </button>
                <button
                    type="button"
                    @click="carType = 'sedan'"
                    :class="carType === 'sedan' ? 'bg-white text-ink shadow-sm font-bold border-line' : 'text-muted hover:text-ink font-semibold'"
                    class="px-4 py-2 rounded-[8px] text-[15px] transition-all flex-1 sm:flex-initial text-center border border-transparent"
                >
                    Sedan
                </button>
                <button
                    type="button"
                    @click="carType = 'suv'"
                    :class="carType === 'suv' ? 'bg-white text-ink shadow-sm font-bold border-line' : 'text-muted hover:text-ink font-semibold'"
                    class="px-4 py-2 rounded-[8px] text-[15px] transition-all flex-1 sm:flex-initial text-center border border-transparent"
                >
                    SUV / 4x4
                </button>
            </div>

            {{-- Category Filter Chips --}}
            <div class="flex flex-wrap items-center gap-2">
                <button
                    type="button"
                    @click="activeCategory = 'all'"
                    :class="activeCategory === 'all' ? 'bg-teal text-white font-bold' : 'bg-white text-muted border-line hover:text-ink font-semibold'"
                    class="px-3.5 py-1.5 rounded-full text-[14px] border transition-all"
                >
                    All Services
                </button>
                @foreach($categories as $category)
                    <button
                        type="button"
                        @click="activeCategory = '{{ $category->slug }}'"
                        :class="activeCategory === '{{ $category->slug }}' ? 'bg-teal text-white font-bold' : 'bg-white text-muted border-line hover:text-ink font-semibold'"
                        class="px-3.5 py-1.5 rounded-full text-[14px] border transition-all"
                    >
                        {{ $category->name }}
                    </button>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Services Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @foreach($allServices as $service)
            <div
                x-show="activeCategory === 'all' || activeCategory === '{{ $service->category->slug ?? '' }}'"
                x-transition
                class="card-panel bg-white shadow-card flex flex-col justify-between space-y-6 hover:border-teal transition-all group"
            >
                <div class="space-y-4">
                    <div class="flex items-center justify-between gap-2">
                        <span class="text-[12px] font-bold uppercase tracking-wider px-2.5 py-0.5 rounded-full bg-mint text-teal">
                            {{ $service->category->name ?? 'Service' }}
                        </span>
                        <span class="text-[13px] text-muted font-semibold">
                            ⏱ {{ $service->duration_minutes }} mins
                        </span>
                    </div>

                    <div>
                        <h3 class="font-display font-extrabold text-[22px] text-ink group-hover:text-teal transition-colors">
                            {{ $service->name }}
                        </h3>
                        <p class="text-[15px] text-muted leading-relaxed mt-2">
                            {{ $service->short_description }}
                        </p>
                    </div>

                    {{-- Dynamic Price Display by Vehicle Type --}}
                    <div class="bg-mist p-4 rounded-[10px] border border-line/60">
                        <div class="flex items-baseline justify-between">
                            <span class="text-[13px] font-bold text-muted uppercase tracking-wider">
                                Price (<span x-text="carType.toUpperCase()"></span>)
                            </span>
                            <span class="font-display font-extrabold text-[26px] text-ink">
                                <span x-show="carType === 'hatchback'">₹{{ number_format($service->price_hatchback ?? 0) }}</span>
                                <span x-show="carType === 'sedan'">₹{{ number_format($service->price_sedan ?? 0) }}</span>
                                <span x-show="carType === 'suv'">₹{{ number_format($service->price_suv ?? 0) }}</span>
                            </span>
                        </div>
                        <div class="flex items-center justify-between text-[11px] text-muted/80 mt-1 border-t border-line/40 pt-1">
                            <span>Hatch: ₹{{ number_format($service->price_hatchback ?? 0) }}</span>
                            <span>Sedan: ₹{{ number_format($service->price_sedan ?? 0) }}</span>
                            <span>SUV: ₹{{ number_format($service->price_suv ?? 0) }}</span>
                        </div>
                    </div>

                    {{-- What's Included Checklist --}}
                    <div class="space-y-2">
                        <span class="text-[12px] font-bold uppercase tracking-wider text-muted block">What's Included:</span>
                        <ul class="space-y-1.5 text-[14px] text-ink">
                            @foreach(array_slice($service->whats_included ?? [], 0, 4) as $item)
                                <li class="flex items-start gap-2">
                                    <span class="text-teal font-bold shrink-0">✓</span>
                                    <span>{{ $item }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="pt-4 border-t border-line/60 flex items-center gap-3">
                    <x-button variant="primary" size="sm" class="flex-1" :href="route('book', ['service' => $service->slug])">
                        Book now
                    </x-button>
                    <x-button variant="secondary" size="sm" :href="route('services.show', $service->slug)">
                        Details
                    </x-button>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
