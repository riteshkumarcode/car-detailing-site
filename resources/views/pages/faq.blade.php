@extends('layouts.public')

@section('title', 'Frequently Asked Questions — The Drive Clinic Jammu')
@section('meta_description', 'Everything you need to know about our foam car wash, ceramic coating, paint correction, and health check diagnostics in Jammu.')

@section('content')
<div class="py-12 sm:py-16 px-4 sm:px-6 lg:px-8 max-w-5xl mx-auto space-y-12" x-data="{
    activeTab: 'all',
    searchQuery: '',
    openFaq: null,
}">
    <div class="text-center space-y-4 max-w-3xl mx-auto">
        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-mint text-teal font-semibold text-[13px]">
            <span>Knowledge Base</span>
        </div>
        <h1 class="font-display font-extrabold text-[36px] sm:text-[48px] text-ink tracking-tight">
            Frequently Asked Questions
        </h1>
        <p class="font-body text-[18px] text-muted max-w-xl mx-auto">
            Find quick answers to common questions about our wash methods, pricing, and health check diagnosis.
        </p>

        {{-- Search Input --}}
        <div class="pt-4 max-w-md mx-auto">
            <input
                type="text"
                x-model="searchQuery"
                placeholder="Search questions (e.g. ceramic, swirls, walk-in)..."
                class="w-full px-4 py-3 rounded-[10px] border border-line bg-white text-ink text-[16px] shadow-sm focus:outline-none focus:ring-2 focus:ring-amber"
            >
        </div>
    </div>

    {{-- Category Tabs --}}
    <div class="flex flex-wrap items-center justify-center gap-2 border-b border-line pb-6">
        @foreach($categories as $key => $label)
            <button
                type="button"
                @click="activeTab = '{{ $key }}'"
                :class="activeTab === '{{ $key }}' ? 'bg-teal text-white font-bold' : 'bg-white text-muted border-line hover:text-ink font-semibold'"
                class="px-4 py-2 rounded-full text-[14px] border transition-all"
            >
                {{ $label }}
            </button>
        @endforeach
    </div>

    {{-- Accordion List --}}
    <div class="space-y-4">
        @foreach($faqs as $faq)
            <div
                x-show="(activeTab === 'all' || activeTab === '{{ $faq->category }}') && ('{{ strtolower($faq->question) }}'.includes(searchQuery.toLowerCase()) || '{{ strtolower($faq->answer) }}'.includes(searchQuery.toLowerCase()))"
                class="card-panel bg-white p-0 overflow-hidden shadow-xs border border-line"
            >
                <button
                    type="button"
                    @click="openFaq = openFaq === {{ $faq->id }} ? null : {{ $faq->id }}"
                    class="w-full text-left p-5 sm:p-6 flex items-center justify-between gap-4 font-display font-bold text-[17px] sm:text-[19px] text-ink hover:text-teal transition-colors"
                >
                    <span>{{ $faq->question }}</span>
                    <span class="text-teal font-bold text-[22px] leading-none shrink-0" x-text="openFaq === {{ $faq->id }} ? '−' : '+'"></span>
                </button>
                <div
                    x-show="openFaq === {{ $faq->id }}"
                    x-collapse
                    class="px-5 sm:px-6 pb-6 text-[16px] text-muted leading-relaxed border-t border-line/40 pt-4"
                    style="display: none;"
                >
                    {{ $faq->answer }}
                </div>
            </div>
        @endforeach
    </div>

    {{-- Support Footer --}}
    <div class="card-panel bg-paper p-8 text-center space-y-4">
        <h3 class="font-display font-bold text-[20px] text-ink">Have a question not answered here?</h3>
        <p class="text-[15px] text-muted max-w-md mx-auto">Our studio manager in Nanak Nagar is happy to guide you on any specific vehicle care question.</p>
        <div class="pt-2 flex justify-center gap-4">
            <x-button variant="primary" :href="'https://wa.me/919419100000'" target="_blank">
                Ask on WhatsApp
            </x-button>
            <x-button variant="secondary" :href="route('contact')">
                Contact Form
            </x-button>
        </div>
    </div>
</div>
@endsection
