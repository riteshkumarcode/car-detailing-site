@extends('layouts.public')

@section('title', 'Before & After Detailing Results — The Drive Clinic Jammu')
@section('meta_description', 'View real paint correction, ceramic coating and interior steam cleaning transformations from our Nanak Nagar studio.')

@section('content')
<div class="py-12 sm:py-16 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto space-y-12" x-data="{
    activeArea: 'all',
}">
    {{-- Header Banner --}}
    <div class="border-b border-line pb-8">
        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-mint text-teal font-semibold text-[13px] mb-3">
            <span>Studio Proof & Transformations</span>
        </div>
        <h1 class="font-display font-extrabold text-[36px] sm:text-[48px] text-ink tracking-tight">
            Before & After Clinic Gallery
        </h1>
        <p class="font-body text-[18px] text-muted max-w-2xl mt-2">
            Drag the interactive slider on each comparison to see how our diagnostic compounding, steam extraction, and ceramic coatings restore vehicles.
        </p>

        {{-- Area Filter Chips --}}
        <div class="mt-8 flex flex-wrap items-center gap-2">
            <button
                type="button"
                @click="activeArea = 'all'"
                :class="activeArea === 'all' ? 'bg-teal text-white font-bold' : 'bg-white text-muted border-line hover:text-ink font-semibold'"
                class="px-4 py-2 rounded-full text-[14px] border transition-all"
            >
                All Areas
            </button>
            <button
                type="button"
                @click="activeArea = 'paint'"
                :class="activeArea === 'paint' ? 'bg-teal text-white font-bold' : 'bg-white text-muted border-line hover:text-ink font-semibold'"
                class="px-4 py-2 rounded-full text-[14px] border transition-all"
            >
                Paint Correction & Swirls
            </button>
            <button
                type="button"
                @click="activeArea = 'interior'"
                :class="activeArea === 'interior' ? 'bg-teal text-white font-bold' : 'bg-white text-muted border-line hover:text-ink font-semibold'"
                class="px-4 py-2 rounded-full text-[14px] border transition-all"
            >
                Interior Deep Clean
            </button>
            <button
                type="button"
                @click="activeArea = 'wheels'"
                :class="activeArea === 'wheels' ? 'bg-teal text-white font-bold' : 'bg-white text-muted border-line hover:text-ink font-semibold'"
                class="px-4 py-2 rounded-full text-[14px] border transition-all"
            >
                Wheels & Brake Dust
            </button>
            <button
                type="button"
                @click="activeArea = 'glass'"
                :class="activeArea === 'glass' ? 'bg-teal text-white font-bold' : 'bg-white text-muted border-line hover:text-ink font-semibold'"
                class="px-4 py-2 rounded-full text-[14px] border transition-all"
            >
                Glass & Water Spots
            </button>
        </div>
    </div>

    {{-- Gallery Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        @foreach($galleryItems as $item)
            <div
                x-show="activeArea === 'all' || activeArea === '{{ $item->area }}'"
                x-transition
            >
                <x-before-after-slider
                    :title="$item->title"
                    :carModel="$item->car_model"
                    :problem="$item->problem_description"
                    :service="$item->service_name"
                    :beforeImage="$item->before_image_path"
                    :afterImage="$item->after_image_path"
                />
            </div>
        @endforeach
    </div>
</div>
@endsection
