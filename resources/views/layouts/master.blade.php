<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-gray-100">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'MBUKU ERP') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    @stack('styles')
    
</head>

<body class="h-full font-sans antialiased" x-data="{ sidebarOpen: false }">
    <livewire:toasts />

    <div class="min-h-full">
        <!-- Include sidebar -->
        @include('partials.sidebar')

        <!-- Main content -->
        <div class="lg:pl-64 flex flex-col flex-1">
            <!-- Top nav -->
            @include('partials.header')

            <!-- Page content -->
            <main class="flex-1">
                <!-- Page header -->
                <div class="bg-white shadow">
                    <div class="px-4 sm:px-6 lg:px-8 py-4">
                        <div class="md:flex md:items-center md:justify-between">
                            <div class="flex-1 min-w-0">
                                <!-- Page title goes here -->
                                @yield('page_title')
                            </div>
                            <div class="mt-4 flex md:mt-0 md:ml-4">
                                <!-- Action buttons go here -->
                                @yield('page_action_buttons')
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Alert section -->
                @if($errors->any())
                    <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8 mt-4">
                        <div class="rounded-md bg-red-50 p-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-red-800">
                                        There were {{ count($errors) }} errors with your submission
                                    </h3>
                                    <div class="mt-2 text-sm text-red-700">
                                        <ul role="list" class="list-disc pl-5 space-y-1">
                                            @foreach($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Flash messages -->
                @include('partials.flash')

                <!-- Main content area -->
                <div class="py-6">
                    <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8">
                        @yield('content')
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- Scripts -->
    @livewireScripts
    @stack('scripts')
</body>
</html>
