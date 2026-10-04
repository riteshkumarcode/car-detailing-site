@extends('layouts.public')

@section('title', 'Drive Club Plans — Staff Operations')

@section('content')
<div class="min-h-screen bg-mist py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto space-y-6">
        <div class="flex items-center justify-between">
            <a href="{{ route('staff.home') }}" class="text-xs font-bold text-teal hover:underline flex items-center gap-1">
                ← Back to Staff Operations
            </a>
        </div>

        <livewire:staff.membership-plan-manager />
    </div>
</div>
@endsection
