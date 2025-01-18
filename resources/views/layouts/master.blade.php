<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta id="csrf-token" name="csrf-token" content="{{ csrf_token() }}">
    <meta name="author" content="CJ Inspired">

    <title>@yield('page_title') | {{ config('app.name') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @include('partials.inc_top')
    @livewireStyles
    <script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
    <script>
        tinymce.init({
            selector: '#tinyMCE', // ID of the textarea
            plugins: 'advlist autolink lists link image charmap preview anchor pagebreak',
            toolbar: 'undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image',
            height: 300,
        });
    </script>
</head>

<body
    class="{{ in_array(Route::currentRouteName(), ['payments.invoice', 'marks.tabulation', 'marks.show', 'ttr.manage', 'ttr.show']) ? 'sidebar-xs' : '' }}">

    <!-- Top Navigation Bar -->
    @include('partials.top_menu')

    <div class="page-content d-flex">
        <!-- Sidebar -->
        <div class="sidebar sidebar-dark sidebar-main sidebar-expand-md position-sticky"
            style="top: 56px; height: calc(100vh - 56px); overflow-y: auto;">
            @include('partials.menu')
        </div>

        <!-- Content Area -->
        <div class="content-wrapper flex-grow-1" style="overflow-y: auto; height: calc(100vh - 56px);">
            <div class="content">
                {{-- Error Alert Area --}}
                @if ($errors->any())
                    <div class="alert alert-danger border-0 alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
                        @foreach ($errors->all() as $error)
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
    @livewireScripts
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <x-livewire-alert::scripts />
    @filepondScripts
</body>

</html>
