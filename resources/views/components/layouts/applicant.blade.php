<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="light">
<head>
    @include('partials.head')
    <script>
        localStorage.setItem('flux.appearance', 'light');
    </script>
</head>

<body class="min-h-screen bg-white dark:bg-zinc-800">
    <flux:header container class="sksu-header border-b border-sksu-green bg-sksu-green dark:border-sksu-green-dark">
        <flux:sidebar.toggle class="lg:hidden text-white" icon="bars-2" inset="left" />

        <a href="{{ route('applicant.dashboard') }}" class="ms-2 me-5 flex items-center space-x-2 rtl:space-x-reverse lg:ms-0">
            <x-app-logo />
        </a>

        <flux:navbar class="-mb-px max-lg:hidden">
            <flux:navbar.item
                icon="home"
                :href="route('applicant.dashboard')"
                :current="request()->routeIs('applicant.dashboard')"
                class="nav-item {{ request()->routeIs('applicant.dashboard') ? 'active-tab' : '' }}">
                <span class="nav-text">{{ __('Dashboard') }}</span>
            </flux:navbar.item>

            @role('student')
            <flux:navbar.item
                icon="document-text"
                :href="route('applicant.applications')"
                :current="request()->routeIs('applicant.applications')"
                class="nav-item {{ request()->routeIs('applicant.applications') ? 'active-tab' : '' }}">
                <span class="nav-text">{{ __('My Applications') }}</span>
            </flux:navbar.item>

            <flux:navbar.item
                icon="academic-cap"
                :href="route('applicant.examinations')"
                :current="request()->routeIs('applicant.examinations')"
                class="nav-item {{ request()->routeIs('applicant.examinations') ? 'active-tab' : '' }}">
                <span class="nav-text">{{ __('Examinations') }}</span>
            </flux:navbar.item>
            @endrole
        </flux:navbar>

        <flux:spacer />

        <!-- Desktop User Menu -->
        <flux:dropdown position="top" align="end">
            <flux:profile class="cursor-pointer" :initials="auth()->user()->initials()" />

            <flux:menu>
                <flux:menu.radio.group>
                    <div class="p-0 text-sm font-normal">
                        <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                            <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                <span class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white">
                                    {{ auth()->user()->initials() }}
                                </span>
                            </span>

                            <div class="grid flex-1 text-start text-sm leading-tight">
                                <span class="truncate font-semibold">{{ auth()->user()->name }}</span>
                                <span class="truncate text-xs">{{ auth()->user()->email }}</span>
                            </div>
                        </div>
                    </div>
                </flux:menu.radio.group>

                <flux:menu.separator />

                <flux:menu.radio.group>
                    <flux:menu.item :href="route('profile.edit')" icon="cog">{{ __('Settings') }}</flux:menu.item>
                </flux:menu.radio.group>

                <flux:menu.separator />

                <form method="POST" action="{{ route('logout') }}" class="w-full">
                    @csrf
                    <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full">
                        {{ __('Log Out') }}
                    </flux:menu.item>
                </form>
            </flux:menu>
        </flux:dropdown>
    </flux:header>

    <!-- Mobile Menu -->
    <flux:sidebar stashable sticky class="lg:hidden border-e border-sksu-green bg-white dark:border-sksu-green-dark dark:bg-zinc-900">
        <flux:sidebar.toggle class="lg:hidden" icon="x-mark" />

        <a href="{{ route('applicant.dashboard') }}" class="ms-1 flex items-center space-x-2 rtl:space-x-reverse">
            <x-app-logo />
        </a>

        <flux:navlist variant="outline">
            <flux:navlist.group :heading="__('Examination')" class="text-sksu-green">
                <flux:navlist.item icon="home" :href="route('applicant.dashboard')" :current="request()->routeIs('applicant.dashboard')" class="hover:bg-sksu-green/10">
                    {{ __('Dashboard') }}
                </flux:navlist.item>

                @role('student')
                <flux:navlist.item icon="document-text" :href="route('applicant.applications')" :current="request()->routeIs('applicant.applications')" class="hover:bg-sksu-green/10">
                    {{ __('My Applications') }}
                </flux:navlist.item>

                <flux:navlist.item icon="academic-cap" :href="route('applicant.examinations')" :current="request()->routeIs('applicant.examinations')" class="hover:bg-sksu-green/10">
                    {{ __('Examinations') }}
                </flux:navlist.item>
                @endrole
            </flux:navlist.group>
        </flux:navlist>

        <flux:spacer />
    </flux:sidebar>

    <flux:main container class="py-8 space-y-6">
        {{ $slot }}
    </flux:main>

    @fluxScripts
</body>

</html>
