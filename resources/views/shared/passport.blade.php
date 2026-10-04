@extends('layouts.public')

@section('title', 'Digital Car Passport — ' . ($vehicle->formatted_plate ?? 'The Drive Clinic'))
@section('meta_description', 'Verified digital service history, health check diagnostics, and ceramic warranty record for ' . ($vehicle->make ?? 'Vehicle') . ' powered by The Drive Clinic.')

@section('content')
<section class="bg-clinical-950 py-12 lg:py-16 min-h-screen text-slate-100">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Passport Verification Banner -->
        <div class="flex flex-col sm:flex-row items-center justify-between gap-4 mb-8 bg-clinical-900/80 backdrop-blur border border-slate-800 p-4 rounded-xl shadow-lg">
            <div class="flex items-center gap-3">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-mono font-semibold bg-emerald-950 text-emerald-400 border border-emerald-800">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    Verified Digital Passport
                </span>
                <span class="text-xs text-slate-400 font-mono">Token: {{ $token }}</span>
            </div>

            <div class="flex items-center gap-3">
                @php
                    $waText = urlencode("Verified Car Passport from The Drive Clinic for {$vehicle->formatted_plate}: " . url()->current());
                @endphp
                <a href="https://wa.me/?text={{ $waText }}" target="_blank" rel="noopener noreferrer"
                   class="inline-flex items-center gap-2 px-3.5 py-1.5 bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-semibold rounded-lg shadow-sm transition">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.711 2.598 2.664-.699c.97.529 1.777.781 2.796.781 3.182 0 5.768-2.587 5.768-5.766 0-3.18-2.586-5.767-5.768-5.767zm7.42 5.766c0 4.092-3.328 7.42-7.42 7.42-1.309 0-2.531-.341-3.601-.937l-4.43 1.162 1.183-4.32c-.675-1.127-1.062-2.441-1.062-3.825 0-4.092 3.328-7.42 7.42-7.42 4.092 0 7.42 3.328 7.42 7.42z"/>
                    </svg>
                    Share Record
                </a>

                <a href="{{ route('book') }}" class="inline-flex items-center gap-1.5 px-3.5 py-1.5 bg-amber-500 hover:bg-amber-400 text-slate-950 text-xs font-bold rounded-lg shadow-sm transition">
                    Book Next Treatment →
                </a>
            </div>
        </div>

        <!-- Reusable Component -->
        <x-passport-view :vehicle="$vehicle" :timeline="$timeline" />

    </div>
</section>
@endsection
