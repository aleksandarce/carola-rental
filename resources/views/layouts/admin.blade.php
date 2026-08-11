<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ $title ?? 'Admin' }} - {{ config('app.name', 'Carola') }}</title>

    <link rel="icon" href="{{ asset('carola/assets/images/favicon.png') }}" type="image/png">
    <link rel="apple-touch-icon" href="{{ asset('carola/assets/images/favicon.png') }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body x-data="{ sidebarToggle: false }" class="h-full bg-gray-50 dark:bg-gray-900">

    <div class="flex h-full">

        {{-- Sidebar --}}
        <aside
            :class="sidebarToggle ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
            class="fixed left-0 top-0 z-9999 flex h-screen w-[260px] flex-col overflow-y-auto border-r border-gray-200 bg-white px-5 transition-transform duration-300 dark:border-gray-800 dark:bg-gray-900 lg:static"
        >
            <div class="flex items-center pt-8 pb-7">
                <a href="{{ route('admin.dashboard') }}" class="text-lg font-bold text-gray-800 dark:text-white/90">
                    Carola Admin
                </a>
            </div>

            <nav class="flex flex-col gap-1">
                <a
                    href="{{ route('admin.dashboard') }}"
                    @class([
                        'rounded-lg px-3 py-2 text-theme-sm font-medium',
                        'bg-gray-100 text-gray-900 dark:bg-white/[0.03] dark:text-white' => request()->routeIs('admin.dashboard'),
                        'text-gray-600 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-white/[0.03]' => ! request()->routeIs('admin.dashboard'),
                    ])
                >
                    Dashboard
                </a>

                <a
                    href="{{ route('admin.cars.index') }}"
                    @class([
                        'rounded-lg px-3 py-2 text-theme-sm font-medium',
                        'bg-gray-100 text-gray-900 dark:bg-white/[0.03] dark:text-white' => request()->routeIs('admin.cars.*'),
                        'text-gray-600 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-white/[0.03]' => ! request()->routeIs('admin.cars.*'),
                    ])
                >
                    Cars
                </a>

                <a
                    href="{{ route('admin.bookings.index') }}"
                    @class([
                        'rounded-lg px-3 py-2 text-theme-sm font-medium',
                        'bg-gray-100 text-gray-900 dark:bg-white/[0.03] dark:text-white' => request()->routeIs('admin.bookings.*'),
                        'text-gray-600 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-white/[0.03]' => ! request()->routeIs('admin.bookings.*'),
                    ])
                >
                    Bookings
                </a>

                <a
                    href="{{ route('admin.locations.index') }}"
                    @class([
                        'rounded-lg px-3 py-2 text-theme-sm font-medium',
                        'bg-gray-100 text-gray-900 dark:bg-white/[0.03] dark:text-white' => request()->routeIs('admin.locations.*'),
                        'text-gray-600 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-white/[0.03]' => ! request()->routeIs('admin.locations.*'),
                    ])
                >
                    Locations
                </a>

                <a
                    href="{{ route('admin.insurance-settings.index') }}"
                    @class([
                        'rounded-lg px-3 py-2 text-theme-sm font-medium',
                        'bg-gray-100 text-gray-900 dark:bg-white/[0.03] dark:text-white' => request()->routeIs('admin.insurance-settings.*'),
                        'text-gray-600 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-white/[0.03]' => ! request()->routeIs('admin.insurance-settings.*'),
                    ])
                >
                    Insurance
                </a>
            </nav>
        </aside>

        {{-- Mobile sidebar overlay --}}
        <div
            x-show="sidebarToggle"
            @click="sidebarToggle = false"
            class="fixed inset-0 z-40 bg-gray-900/50 lg:hidden"
            style="display: none;"
        ></div>

        <div class="flex w-full flex-1 flex-col">

            {{-- Header --}}
            <header class="sticky top-0 z-50 flex w-full border-b border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900">
                <div class="flex w-full items-center justify-between px-4 py-4 lg:px-6">
                    <button
                        class="flex h-10 w-10 items-center justify-center rounded-lg border border-gray-200 text-gray-500 dark:border-gray-800 dark:text-gray-400 lg:hidden"
                        @click="sidebarToggle = !sidebarToggle"
                    >
                        <svg class="fill-current" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M2.5 5a.75.75 0 01.75-.75h13.5a.75.75 0 010 1.5H3.25A.75.75 0 012.5 5zm0 5a.75.75 0 01.75-.75h13.5a.75.75 0 010 1.5H3.25A.75.75 0 012.5 10zm.75 4.25a.75.75 0 000 1.5h13.5a.75.75 0 000-1.5H3.25z" fill="" />
                        </svg>
                    </button>

                    <h1 class="hidden text-lg font-semibold text-gray-800 dark:text-white/90 lg:block">
                        {{ $title ?? 'Admin' }}
                    </h1>

                    <div class="relative" x-data="{ dropdownOpen: false }" @click.outside="dropdownOpen = false">
                        <button
                            class="flex items-center gap-2 text-theme-sm font-medium text-gray-700 dark:text-gray-400"
                            @click="dropdownOpen = !dropdownOpen"
                        >
                            {{ auth()->user()->name }}
                            <svg
                                class="size-4 fill-current transition-transform duration-200"
                                :class="dropdownOpen ? 'rotate-180' : ''"
                                viewBox="0 0 20 20"
                                fill="none"
                                xmlns="http://www.w3.org/2000/svg"
                            >
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M5.23 7.21a.75.75 0 0 1 1.06.02L10 11.168l3.71-3.938a.75.75 0 1 1 1.08 1.04l-4.25 4.5a.75.75 0 0 1-1.08 0l-4.25-4.5a.75.75 0 0 1 .02-1.06Z" />
                            </svg>
                        </button>

                        <div
                            x-show="dropdownOpen"
                            class="absolute right-0 mt-2 w-48 rounded-lg border border-gray-200 bg-white p-2 shadow-theme-lg dark:border-gray-800 dark:bg-gray-900"
                            style="display: none;"
                        >
                            <a href="{{ route('profile.edit') }}" class="block w-full rounded-lg px-3 py-2 text-left text-theme-sm text-gray-700 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-white/[0.03]">
                                Settings
                            </a>

                            <div class="my-1 border-t border-gray-200 dark:border-gray-800"></div>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full rounded-lg px-3 py-2 text-left text-theme-sm text-gray-700 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-white/[0.03]">
                                    Sign out
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </header>

            <main class="p-4 lg:p-6">{{ $slot }}</main>
        </div>
    </div>

</body>
</html>
