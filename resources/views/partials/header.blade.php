<link href=" {{ asset('assets/css/admin_header.css') }}" rel="stylesheet" type="text/css">

<div id="page-header" class="page-header page-header-light">
    <!-- Breadcrumbs -->
    <div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline d-none d-md-block">


        <div class="header-elements d-flex align-items-center">
            <a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
            <div class="dropdown p-0 ml-3">

            </div>
        </div>
    </div>

    <div class="page-header-content header-elements-md-inline">
        <div class="page-title d-flex align-items-center">
            <h4 class="m-0"><i class="icon-plus-circle2 mr-2"></i> <span class="font-weight-semibold">@yield('page_title')</span></h4>
            <a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
        </div>

        <div class="header-elements d-none d-md-flex align-items-center">
            <a href="{{ Qs::userIsSuperAdmin() ? route('settings') : '' }}" class="btn btn-link btn-float text-default mr-3"><i class="icon-arrow-down7 text-primary"></i> <span class="font-weight-semibold">Current Session: {{ Qs::getSetting('current_session') }}</span></a>
            <!-- Add more header elements here as needed -->
        </div>
    </div>
</div>