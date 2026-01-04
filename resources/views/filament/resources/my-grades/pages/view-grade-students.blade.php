<x-filament-panels::page>
    <div class="space-y-6">
        {{-- Info Card --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h2 class="text-xl font-bold mb-2">{{ $this->record->name }}</h2>
            <p class="text-gray-600 dark:text-gray-400">
                Showing all students enrolled in this grade/class
            </p>
        </div>

        {{-- Students Table --}}
        {{ $this->table }}
    </div>
</x-filament-panels::page>

