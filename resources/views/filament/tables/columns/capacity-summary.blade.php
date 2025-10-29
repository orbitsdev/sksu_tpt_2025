@php
    $data = $column->getCapacityData();
@endphp

@if ($data['capacity'] === 0)
    {{-- 💤 No slots/rooms yet --}}
    <div class="text-sm text-gray-400 italic text-center py-2">
        No capacity data available
    </div>
@else
    {{-- 🧮 Show progress summary --}}
    <div class="text-sm w-full">
        <div class="flex justify-between">
            <span>Occupied: {{ $data['occupied'] }} / {{ $data['capacity'] }}</span>
            <span class="{{ $data['left'] > 0 ? 'text-green-600 font-semibold' : 'text-red-600 font-semibold' }}">
                {{ $data['left'] }} left
            </span>
        </div>

        <div class="w-full bg-gray-200 rounded-full h-2 mt-1 overflow-hidden">
            <div
                class="h-2 rounded-full transition-all duration-300 {{ $data['percent'] < 100 ? 'bg-green-500' : 'bg-red-500' }}"
                style="width: {{ $data['percent'] }}%;">
            </div>
        </div>
    </div>
@endif
