@php
    $data = $column->getCapacityData();
@endphp

<div class="text-sm">
    <div class="flex justify-between">
        <span>Occupied: {{ $data['occupied'] }} / {{ $data['capacity'] }}</span>
        <span class="{{ $data['left'] > 0 ? 'text-green-600' : 'text-red-600' }}">
            {{ $data['left'] }}  left
        </span>
    </div>

    <div class="w-full bg-gray-200 rounded-full h-2 mt-1">
        <div
            class="h-2 rounded-full {{ $data['percent'] < 100 ? 'bg-green-500' : 'bg-red-500' }}"
            style="width: {{ $data['percent'] }}%">
        </div>
    </div>
</div>
