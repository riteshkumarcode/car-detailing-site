@extends('layouts.public')

@section('title', 'Book Studio Appointment — The Drive Clinic Jammu')
@section('meta_description', 'Book an appointment at The Drive Clinic Nanak Nagar, Jammu. Select your treatment, car category and real-time bay slot.')

@section('content')
<div class="py-10 sm:py-14 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto space-y-8">
    {{-- Header Banner --}}
    <div class="border-b border-brand-line pb-6">
        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-brand-mint text-brand-teal font-semibold text-[13px] mb-3">
            <span>Online Studio Booking</span>
        </div>
        <h1 class="font-display font-extrabold text-[32px] sm:text-[44px] text-brand-ink tracking-tight">
            Schedule Your Clinic Visit
        </h1>
        <p class="font-body text-[16px] sm:text-[18px] text-brand-muted max-w-2xl mt-1">
            Book your guaranteed bay slot in under 2 minutes. Walk-ins are also welcome.
        </p>
    </div>

    {{-- Livewire Multi-step Stepper Component --}}
    <livewire:public.booking-stepper :initialService="request('service')" />
</div>
@endsection
