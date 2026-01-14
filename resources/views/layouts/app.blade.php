<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        @livewireStyles
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100 flex">
            
<aside class="w-64 bg-white border-r border-gray-200 min-h-screen flex flex-col">

    <!-- Logo -->
    <div class="flex p-6 items-center">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-application-mark class="block h-9 w-auto" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-4 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')">
                        {{ config('app.name') }}
                    </x-nav-link>
                </div>
            </div>

    <!-- Navigation -->
    <nav class="flex-1 px-3 py-4 space-y-6 overflow-y-auto">

        <x-side-link href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')">
            Dashboard
        </x-side-link>

        <!-- Banking -->
        <div>
            <p class="px-4 mb-2 text-xs font-semibold text-gray-400 uppercase tracking-wider">
                Banking
            </p>

            <x-side-link href="{{ route('banks') }}" :active="request()->routeIs('banks')">
                Banks List
            </x-side-link>

            <x-side-link href="{{ route('daily-balances') }}" :active="request()->routeIs('daily-balances')">
                Daily Balances
            </x-side-link>
        </div>

        <!-- API Providers -->
        <div>
            <p class="px-4 mb-2 text-xs font-semibold text-gray-400 uppercase tracking-wider">
                API Providers
            </p>

            <x-side-link href="{{ route('api-providers') }}" :active="request()->routeIs('api-providers')">
                API Providers List
            </x-side-link>

            <x-side-link href="{{ route('api-reports') }}" :active="request()->routeIs('api-reports')">
             Daily API Reports
            </x-side-link>

            <x-side-link href="{{ route('api-recharge-stats') }}" :active="request()->routeIs('api-recharge-stats')">
                Daily Recharge Stats
            </x-side-link>
        </div>

        <!-- Gateways -->
        <div>
            <p class="px-4 mb-2 text-xs font-semibold text-gray-400 uppercase tracking-wider">
                Gateways
            </p>

            <x-side-link href="{{ route('gateways') }}" :active="request()->routeIs('gateways')">
                Gateway List
            </x-side-link>

            <x-side-link href="{{ route('gateway-reports') }}" :active="request()->routeIs('gateway-reports')">
                Daily Reports
            </x-side-link>
        </div>

        <!-- Expenses -->
        <div>
            <p class="px-4 mb-2 text-xs font-semibold text-gray-400 uppercase tracking-wider">
                Expenses
            </p>

            <x-side-link href="{{ route('expense-categories') }}" :active="request()->routeIs('expense-categories')">
                Categories
            </x-side-link>

            <x-side-link href="{{ route('expenses') }}" :active="request()->routeIs('expenses')">
                Expense Entry
            </x-side-link>
        </div>

    </nav>
</aside>

            <div class="flex-1 flex flex-col">
                
                @livewire('navigation-menu')

                @if (isset($header))
                    <header class="bg-white shadow">
                        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                            {{ $header }}
                        </div>
                    </header>
                @endif

                <main class="flex-1 ">
                    {{ $slot }}
                </main>
            </div>
        </div>

        @stack('modals')
        @livewireScripts
    </body>
</html>