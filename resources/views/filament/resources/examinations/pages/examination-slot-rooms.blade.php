

    <div class="space-y-4">

        {{-- 🧱 Table --}}
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-300 dark:divide-white/15">
                <thead class="bg-gray-50 dark:bg-gray-800">
                    <tr>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-900 dark:text-white">
                            Room
                        </th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-900 dark:text-white">
                            Capacity
                        </th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-900 dark:text-white">
                            Occupied
                        </th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-900 dark:text-white">
                            Available
                        </th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-200 bg-white dark:divide-white/10 dark:bg-gray-900">
                    @forelse ($record->rooms as $room)
                        @php
                            $available = max($room->capacity - $room->occupied, 0);
                            $color = $available > 0 ? 'text-green-600' : 'text-red-600';
                        @endphp
                        <tr>
                            <td class="px-4 py-3 text-sm text-gray-800 dark:text-gray-200 font-medium">
                                {{ $room->room_number }}
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300">
                                {{ $room->capacity }}
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300">
                                {{ $room->occupied }}
                            </td>
                            <td class="px-4 py-3 text-sm font-semibold {{ $color }}">
                                {{ $available }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-6 text-center text-sm text-gray-500 dark:text-gray-400">
                                No rooms found for this slot.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- 🧮 Summary Cards --}}
        <div class="grid grid-cols-3 gap-4">
            <div class="rounded-lg bg-blue-50 dark:bg-blue-900/20 p-4 text-center">
                <div class="text-sm font-medium text-blue-600 dark:text-blue-400">Total Capacity</div>
                <div class="mt-1 text-2xl font-bold text-blue-700 dark:text-blue-300">
                    {{ $record->rooms->sum('capacity') }}
                </div>
            </div>
            <div class="rounded-lg bg-orange-50 dark:bg-orange-900/20 p-4 text-center">
                <div class="text-sm font-medium text-orange-600 dark:text-orange-400">Occupied</div>
                <div class="mt-1 text-2xl font-bold text-orange-700 dark:text-orange-300">
                    {{ $record->rooms->sum('occupied') }}
                </div>
            </div>
            <div class="rounded-lg bg-green-50 dark:bg-green-900/20 p-4 text-center">
                <div class="text-sm font-medium text-green-600 dark:text-green-400">Available</div>
                <div class="mt-1 text-2xl font-bold text-green-700 dark:text-green-300">
                    {{ max($record->rooms->sum('capacity') - $record->rooms->sum('occupied'), 0) }}
                </div>
            </div>
        </div>

    </div>

