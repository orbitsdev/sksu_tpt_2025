

    <div class="bg-white dark:bg-gray-900">

        {{-- 🧭 Slot Header --}}
        <div class="mb-6">
            <h2 class="text-xl font-bold text-gray-900 dark:text-white">
                {{ $record->examination->title ?? 'Unknown Exam' }}
            </h2>
            <p class="text-sm text-gray-600 dark:text-gray-400">
                Campus: <strong>{{ $record->campus->name ?? 'N/A' }}</strong><br>
                Building: <strong>{{ $record->building_name ?? 'N/A' }}</strong><br>
                Date: <strong>{{ \Carbon\Carbon::parse($record->date_of_exam)->format('F d, Y') }}</strong>
            </p>
        </div>

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

        {{-- 🧮 Footer summary --}}
        <div class="mt-6 text-right text-sm text-gray-700 dark:text-gray-300">
            <span>Total Capacity:</span>
            <strong>{{ $record->rooms->sum('capacity') }}</strong> |
            <span>Occupied:</span>
            <strong>{{ $record->rooms->sum('occupied') }}</strong> |
            <span>Available:</span>
            <strong>{{ $record->rooms->sum('capacity') - $record->rooms->sum('occupied') }}</strong>
        </div>

    </div>

