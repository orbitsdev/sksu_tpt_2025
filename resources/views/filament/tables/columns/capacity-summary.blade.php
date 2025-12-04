@php
    $data = $column->getCapacityData();
@endphp

@if ($data['capacity'] === 0)
    {{-- 💤 No slots/rooms yet --}}
    <div class="text-sm text-gray-400 italic text-center py-3">
        No capacity data available
    </div>
@else
    {{-- 🧮 Show progress summary --}}
    <div class="text-sm w-full space-y-2 py-1 px-10">
        <div class="flex justify-between items-center gap-3">
            <span class="text-gray-700 dark:text-gray-300">
                <span class="font-medium">Occupied:</span> {{ $data['occupied'] }} / {{ $data['capacity'] }}
            </span>
            <span class="px-2 py-1 rounded-md text-xs font-semibold {{ $data['left'] > 0 ? 'bg-green-50 text-green-700 dark:bg-green-900/20 dark:text-green-400' : 'bg-red-50 text-red-700 dark:bg-red-900/20 dark:text-red-400' }}">
                {{ $data['left'] }} left
            </span>
        </div>

        <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2.5 overflow-hidden">
            <div
                class="h-2.5 rounded-full transition-all duration-300 {{ $data['percent'] < 100 ? 'bg-green-500' : 'bg-red-500' }}"
                style="width: {{ min($data['percent'], 100) }}%;">
            </div>
        </div>

        <div class="text-xs text-gray-500 dark:text-gray-400 text-right">
            {{ $data['percent'] }}% capacity used
        </div>
    </div>
@endif
