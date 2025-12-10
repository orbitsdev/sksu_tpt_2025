<x-filament-panels::page>
    <div class="space-y-6">
        {{-- Account Information Card --}}
        <x-filament::section>
            <x-slot name="heading">
                <div class="flex items-center gap-2">
                    <x-filament::icon
                        icon="heroicon-o-user-circle"
                        class="h-5 w-5"
                    />
                    Account Information
                </div>
            </x-slot>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <x-filament::section>
                        <div class="space-y-4">
                            <div>
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Full Name</p>
                                <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $record->name }}</p>
                            </div>

                            <div>
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Email</p>
                                <div class="mt-1 flex items-center gap-2">
                                    <x-filament::icon
                                        icon="heroicon-o-envelope"
                                        class="h-4 w-4 text-gray-400"
                                    />
                                    <p class="text-sm text-gray-900 dark:text-white">{{ $record->email }}</p>
                                </div>
                            </div>

                            <div>
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Campus</p>
                                <p class="mt-1 text-sm text-gray-900 dark:text-white">
                                    {{ $record->campus?->name ?? 'N/A' }}
                                </p>
                            </div>
                        </div>
                    </x-filament::section>
                </div>

                <div>
                    <x-filament::section>
                        <div class="space-y-4">
                            <div>
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Role(s)</p>
                                <div class="mt-1 flex flex-wrap gap-2">
                                    @forelse($record->roles as $role)
                                        <x-filament::badge>
                                            {{ $role->name }}
                                        </x-filament::badge>
                                    @empty
                                        <p class="text-sm text-gray-500">No roles assigned</p>
                                    @endforelse
                                </div>
                            </div>

                            <div>
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Registered</p>
                                <p class="mt-1 text-sm text-gray-900 dark:text-white">
                                    {{ $record->created_at->format('M d, Y h:i A') }}
                                </p>
                            </div>

                            <div>
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Last Updated</p>
                                <p class="mt-1 text-sm text-gray-900 dark:text-white">
                                    {{ $record->updated_at->format('M d, Y h:i A') }}
                                </p>
                            </div>
                        </div>
                    </x-filament::section>
                </div>
            </div>
        </x-filament::section>

        {{-- Personal Information Card --}}
        @if($record->personalInformation)
        <x-filament::section collapsible>
            <x-slot name="heading">
                <div class="flex items-center gap-2">
                    <x-filament::icon
                        icon="heroicon-o-user"
                        class="h-5 w-5"
                    />
                    Personal Information
                </div>
            </x-slot>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">First Name</p>
                    <p class="mt-1 text-sm text-gray-900 dark:text-white">
                        {{ $record->personalInformation->first_name ?? 'N/A' }}
                    </p>
                </div>

                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Middle Name</p>
                    <p class="mt-1 text-sm text-gray-900 dark:text-white">
                        {{ $record->personalInformation->middle_name ?? 'N/A' }}
                    </p>
                </div>

                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Last Name</p>
                    <p class="mt-1 text-sm text-gray-900 dark:text-white">
                        {{ $record->personalInformation->last_name ?? 'N/A' }}
                    </p>
                </div>

                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Suffix</p>
                    <p class="mt-1 text-sm text-gray-900 dark:text-white">
                        {{ $record->personalInformation->suffix ?? 'N/A' }}
                    </p>
                </div>

                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Sex</p>
                    <div class="mt-1">
                        @if($record->personalInformation->sex)
                            <x-filament::badge
                                :color="$record->personalInformation->sex === 'Male' ? 'info' : 'success'"
                            >
                                {{ $record->personalInformation->sex }}
                            </x-filament::badge>
                        @else
                            <p class="text-sm text-gray-500">N/A</p>
                        @endif
                    </div>
                </div>

                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Birth Date</p>
                    <p class="mt-1 text-sm text-gray-900 dark:text-white">
                        {{ $record->personalInformation->birth_date ? $record->personalInformation->birth_date->format('M d, Y') : 'N/A' }}
                    </p>
                </div>

                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Contact Email</p>
                    <div class="mt-1 flex items-center gap-2">
                        @if($record->personalInformation->email)
                            <x-filament::icon
                                icon="heroicon-o-envelope"
                                class="h-4 w-4 text-gray-400"
                            />
                            <p class="text-sm text-gray-900 dark:text-white">
                                {{ $record->personalInformation->email }}
                            </p>
                        @else
                            <p class="text-sm text-gray-500">N/A</p>
                        @endif
                    </div>
                </div>

                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Contact Number</p>
                    <div class="mt-1 flex items-center gap-2">
                        @if($record->personalInformation->contact_number)
                            <x-filament::icon
                                icon="heroicon-o-phone"
                                class="h-4 w-4 text-gray-400"
                            />
                            <p class="text-sm text-gray-900 dark:text-white">
                                {{ $record->personalInformation->contact_number }}
                            </p>
                        @else
                            <p class="text-sm text-gray-500">N/A</p>
                        @endif
                    </div>
                </div>
            </div>
        </x-filament::section>
        @else
        <x-filament::section>
            <div class="text-center py-12">
                <x-filament::icon
                    icon="heroicon-o-information-circle"
                    class="mx-auto h-12 w-12 text-gray-400"
                />
                <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">No Personal Information</h3>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Personal information has not been added for this user yet.
                </p>
            </div>
        </x-filament::section>
        @endif
    </div>
</x-filament-panels::page>
