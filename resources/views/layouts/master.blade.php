<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta id="csrf-token" name="csrf-token" content="{{ csrf_token() }}">
    <meta name="author" content="CJ Inspired">

    <title>@yield('page_title') | {{ config('app.name') }}</title>

    <!-- Font Awesome -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js" defer></script>
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

    @livewireStyles
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @include('partials.inc_top')
</head>

<body
    class="{{ in_array(Route::currentRouteName(), ['payments.invoice', 'marks.tabulation', 'marks.show', 'ttr.manage', 'ttr.show']) ? 'sidebar-xs' : '' }}">
    
    @include('partials.top_menu')
    <div class="page-content">
        @include('partials.menu')
        <div class="content-wrapper">
            @include('notify::components.notify')

            <div class="content">
                {{-- Error Alert Area --}}
                @if($errors->any())
                <div class="alert alert-danger border-0 alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
                    @foreach($errors->all() as $error)
                    <span><i class="icon-arrow-right5"></i> {{ $error }}</span><br>
                    @endforeach
                </div>
                @endif
                <div id="ajax-alert" style="display: none"></div>
                @yield('content')
            </div>
        </div>
    </div>

    @yield('scripts')
    @stack('scripts')
    @include('partials.inc_bottom')
    {{-- @livewire('wire-elements-modal') --}}
    @livewireScripts
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <x-livewire-alert::scripts />
    {{-- <x-toaster-hub /> --}}
</body>

</html>