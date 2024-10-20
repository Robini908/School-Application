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
    <!-- Handsontable -->

   <!-- FullCalendar Resource Timeline CSS and JS -->
<link href="https://cdn.jsdelivr.net/npm/@fullcalendar/resource-timeline@6.1.6/index.global.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/@fullcalendar/resource-timeline@6.1.6/index.global.min.js"></script>

<!-- Your existing script -->
<script>
  document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
      initialView: 'resourceTimelineWeek',
      headerToolbar: {
        left: 'prev,next today',
        center: 'title',
        right: 'resourceTimelineDay,resourceTimelineWeek,resourceTimelineMonth'
      },
      resources: [
        { id: 'a', title: 'Room A' },
        { id: 'b', title: 'Room B' },
        { id: 'c', title: 'Room C' }
      ],
      events: [
        { id: '1', resourceId: 'a', title: 'Meeting', start: '2024-10-20T10:00:00', end: '2024-10-20T12:00:00' },
        { id: '2', resourceId: 'b', title: 'Conference', start: '2024-10-21T13:00:00', end: '2024-10-21T15:00:00' }
      ]
    });
    calendar.render();
  });
</script>


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
</body>

</html>