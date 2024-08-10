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

    <title> @yield('page_title') | {{ config('app.name') }} </title>


    @include('partials.inc_top')
    @livewireStyles

    <style>
        .actions-dropdown {
            cursor: pointer;
            padding: 5px;
            border: 1px solid #ddd;
            border-radius: 3px;
            position: relative;
            display: inline-block;
        }
        .actions-dropdown .breadcrumb-icon {
            font-size: 16px;
            font-weight: bold;
        }
        .dropdown-menu {
            display: none;
            position: absolute;
            background-color: #fff;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            border-radius: 3px;
            right: 0;
            top: 100%;
            z-index: 1000;
            min-width: 150px;
        }
        .dropdown-menu a {
            display: block;
            padding: 8px 12px;
            text-decoration: none;
            color: #333;
        }
        .dropdown-menu a:hover {
            background-color: #f1f1f1;
        }
        .breadcrumb-icon::after {
            content: '▼';
            font-size: 12px;
            margin-left: 5px;
        }
        .dataTables_wrapper .dt-buttons {
            margin-bottom: 10px;
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgb(0,0,0);
            background-color: rgba(0,0,0,0.4);
            padding-top: 60px;
        }

        .modal-content {
            background-color: #fefefe;
            margin: 5% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            position: relative;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            position: absolute;
            top: 10px;
            right: 20px;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }

        #reportContent {
            margin-top: 10px; 
            padding:5px;
            border: 1px solid green;
            border-radius: 0px;
            background-color:#fff;
            /* Add margin to avoid overlap with close button */
        }

        #printReportBtn {
            display: block;
            margin: 20px auto;
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            float: left;
        }

        #printReportBtn:hover {
            background-color: #45a049;
        }
    </style>

</head>

<body
    class="{{ in_array(Route::currentRouteName(), ['payments.invoice', 'marks.tabulation', 'marks.show', 'ttr.manage', 'ttr.show']) ? 'sidebar-xs' : '' }}">
    @include('partials.top_menu')
    <div class="page-content">
        @include('partials.menu')
        <div class="content-wrapper">

            @include('partials.header')

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