<x-filament-panels::page>
    <form wire:submit.prevent="submit" class="space-y-6">
        {{ $this->form }}
        <div class="flex items-center gap-3">
            <x-filament::button type="submit">
                Save
            </x-filament::button>

            <x-filament::button type="button" color="secondary" wire:click.prevent="$refresh">
                Cancel
            </x-filament::button>
        </div>
    </form>
</x-filament-panels::page>
