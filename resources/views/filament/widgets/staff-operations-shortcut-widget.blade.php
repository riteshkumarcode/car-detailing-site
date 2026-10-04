<x-filament-widgets::widget>
    <x-filament::section>
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h3 class="text-base font-bold text-gray-950 dark:text-white flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
                    Live Studio Operations Floor
                </h3>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                    Open the mobile-first fast staff console for plate search, bay dispatch, ramp diagnostics, and one-tap billing.
                </p>
            </div>

            <div class="flex flex-wrap items-center gap-3">
                <a href="{{ route('staff.home') }}" target="_blank"
                   class="inline-flex items-center gap-2 px-4 py-2 bg-amber-500 hover:bg-amber-400 text-gray-950 font-bold text-xs rounded-xl shadow-sm transition">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                    <span>Launch Staff Job Board ↗</span>
                </a>

                <a href="{{ route('styleguide') }}" target="_blank"
                   class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-gray-100 hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 font-semibold text-xs rounded-xl transition">
                    Brand Styleguide ↗
                </a>

                <a href="{{ route('home') }}" target="_blank"
                   class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-gray-100 hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 font-semibold text-xs rounded-xl transition">
                    Public Website ↗
                </a>
            </div>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
