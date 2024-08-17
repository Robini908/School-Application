<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta id="csrf-token" name="csrf-token" content="{{ csrf_token() }}">
    <meta name="author" content="CJ Inspired">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.0.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.7.1/css/buttons.dataTables.min.css">

    <title> @yield('page_title') | {{ config('app.name') }} </title>


    @include('partials.inc_top')
    @livewireStyles

    <style>
        /* Custom styles for the dropdown menu */
        .actions-dropdown {
            position: relative;
            display: inline-block;
        }

        .actions-dropdown .breadcrumb-icon {
            cursor: pointer;
            padding: 5px 10px;
            border: none;
            background: #e9ecef;
            border-radius: 4px;
        }

        .actions-dropdown .dropdown-menu {
            display: none;
            position: absolute;
            left: 0;
            top: 100%;
            background: #fff;
            border: 1px solid #ddd;
            z-index: 1000;
            padding: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .actions-dropdown .dropdown-menu a {
            display: block;
            padding: 5px;
            text-decoration: none;
            color: #007bff;
        }

        .actions-dropdown .dropdown-menu a:hover {
            background: #f8f9fa;
        }

        #modal {
            display: none;
            position: absolute;
            z-index: 2000;
            width: 300px;
            background: white;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        /* Container for layout */
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 1rem;
        }

        /* Flex container for side-by-side layout */
        .flex {
            display: flex;
            gap: 1rem;
            /* Adds space between cards */
        }

        /* Card styling for both report-generator and advanced-filter */
        .report-generator,
        .advanced-filter {
            flex: 1;
            /* Ensures both cards take up equal space */
            background-color: #ffffff;
            padding: 1rem;
            border-radius: 0.5rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 1rem;
        }

        /* Styling for report generator */
        .report-generator {
            background-color: #f9f9f9;
            border: 1px solid #ddd;
        }

        /* Styling for advanced filter */
        .advanced-filter {
            background-color: #ffffff;
            border: 1px solid #ddd;
        }

        /* Input group styling */
        .input-group {
            margin-bottom: 1rem;
        }

        /* Label styling inside input group */
        .input-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
        }

        /* Input styling inside input group */
        .input-group input,
        .input-group select {
            width: 100%;
            padding: 0.5rem;
            border: 1px solid #ccc;
            border-radius: 0.25rem;
        }

        /* Button styling */
        #generateReports,
        #applyFilter {
            background-color: #3b82f6;
            color: white;
            padding: 0.5rem 1rem;
            border: none;
            border-radius: 0.25rem;
            cursor: pointer;
            font-weight: 600;
        }

        /* Button hover effect */
        #generateReports:hover,
        #applyFilter:hover {
            background-color: #2563eb;
        }

        /* Additional Tailwind CSS classes */
        .text-lg {
            font-size: 1.125rem;
        }

        .font-bold {
            font-weight: 700;
        }

        .mb-4 {
            margin-bottom: 1rem;
        }

        .block {
            display: block;
        }

        .w-full {
            width: 100%;
        }

        .border {
            border: 1px solid #d1d5db;
        }

        .border-gray-300 {
            border-color: #d1d5db;
        }

        .shadow-sm {
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
        }

        .bg-blue-500 {
            background-color: #3b82f6;
        }

        .text-white {
            color: #ffffff;
        }

        .hover\:bg-blue-600:hover {
            background-color: #2563eb;
        }

        .bg-green-500 {
            background-color: #10b981;
        }

        .hover\:bg-green-600:hover {
            background-color: #059669;
        }
    </style>


</head>

<body
    class="{{ in_array(Route::currentRouteName(), ['payments.invoice', 'marks.tabulation', 'marks.show', 'ttr.manage', 'ttr.show']) ? 'sidebar-xs' : '' }}">
    @include('partials.top_menu')
    <div class="page-content">
        @include('partials.menu')
        <div class="content-wrapper">

           {{-- @include('partials.header')--}}

            {{-- Include Notify Component --}}
            @include('notify::components.notify')

            <div class="content">
                @if(session('flash_success'))
                <div class="alert alert-success">
                    {{ session('flash_success') }}
                </div>
                @endif

                @if(session('flash_danger'))
                <div class="alert alert-danger">
                    {{ session('flash_danger') }}
                </div>
                @endif

                @if(session('pop_error'))
                <div class="alert alert-warning">
                    {{ session('pop_error') }}
                </div>
                @endif

                {{--Error Alert Area--}}
                @if($errors->any())
                <div class="alert alert-danger border-0 alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>

                    @foreach($errors->all() as $er)
                    <span><i class="icon-arrow-right5"></i> {{ $er }}</span> <br>
                    @endforeach

                </div>
                @endif
                <div id="ajax-alert" style="display: none"></div>

                @yield('content')

            </div>


        </div>
    </div>
    <x-notify::notify />
    @include('partials.inc_bottom')
    @livewireScripts
    @yield('scripts')
</body>

</html>