

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
                        @endphp
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50">
                            <td class="px-4 py-3 text-sm font-medium text-gray-900 dark:text-white">
                                {{ $room->room_number }}
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-400">
                                {{ $room->capacity }}
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-400">
                                {{ $room->occupied }}
                            </td>
                            <td class="px-4 py-3 text-sm font-semibold text-gray-900 dark:text-white">
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

        {{-- 🧮 Summary Stats --}}
        <div class="rounded-lg border border-gray-200 bg-gray-50/50 p-4 dark:border-white/10 dark:bg-gray-800/50">
            <div class="grid grid-cols-3 gap-6 text-center">
                <div>
                    <div class="text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">Total Capacity</div>
                    <div class="mt-1 text-2xl font-semibold text-gray-900 dark:text-white">
                        {{ $record->rooms->sum('capacity') }}
                    </div>
                </div>
                <div>
                    <div class="text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">Occupied</div>
                    <div class="mt-1 text-2xl font-semibold text-gray-900 dark:text-white">
                        {{ $record->rooms->sum('occupied') }}
                    </div>
                </div>
                <div>
                    <div class="text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">Available</div>
                    <div class="mt-1 text-2xl font-semibold text-gray-900 dark:text-white">
                        {{ max($record->rooms->sum('capacity') - $record->rooms->sum('occupied'), 0) }}
                    </div>
                </div>
            </div>
        </div>

    </div>

