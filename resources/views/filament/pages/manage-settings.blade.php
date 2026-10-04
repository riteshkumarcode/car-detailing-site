<x-filament-panels::page>
    <form wire:submit="save" class="space-y-6">
        {{ $this->form }}

        <div class="flex items-center gap-4">
            <x-filament::button type="submit" size="lg" color="primary">
                Save All Settings
            </x-filament::button>
        </div>
    </form>
</x-filament-panels::page>
