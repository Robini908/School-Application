<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta id="csrf-token" name="csrf-token" content="{{ csrf_token() }}">
    <meta name="author" content="CJ Inspired">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.1/css/jquery.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.7.1/css/buttons.dataTables.min.css">
   
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>
<!-- Include Livewire styles and scripts -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>




    <title> @yield('page_title') | {{ config('app.name') }} </title>


    @include('partials.inc_top')
    @livewireStyles
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
   
    @yield('scripts')

    @livewireScripts
</body>

</html>