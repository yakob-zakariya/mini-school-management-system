<x-filament-panels::page>
    <div class="space-y-6">
        {{-- Student Info Card --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold">{{ $this->student->name }}</h2>
                    <p class="text-gray-600 dark:text-gray-400 mt-1">
                        {{ $this->grade->name }} • {{ $this->student->email }}
                    </p>
                </div>
                <div class="text-right">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Student ID</p>
                    <p class="text-lg font-semibold">#{{ $this->student->id }}</p>
                </div>
            </div>
        </div>

        {{-- Marks Form --}}
        <form wire:submit="save">
            {{ $this->schema }}

            <div class="mt-6 flex gap-3">
                @foreach ($this->getSchemaActions() as $action)
                    {{ $action }}
                @endforeach
            </div>
        </form>
    </div>
</x-filament-panels::page>

