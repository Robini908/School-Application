@extends('layouts.master')
@section('page_title', 'My Dashboard')
@section('content')

@if(Qs::userIsTeamSA())
<div class="row">
    <!-- Total Students Card -->
    <div class="col-sm-6 col-xl-3">
        <div class="card card-body bg-blue-400 has-bg-image" style="height: 100px;">
            <div class="media">
                <div class="media-body">
                    <h3 class="mb-0">{{ $totalStudents }}</h3> <!-- Display total students -->
                    <span class="text-uppercase font-size-xs font-weight-bold">Total Students</span>
                </div>
                <div class="ml-3 align-self-center">
                    <i class="icon-users4 icon-3x opacity-75"></i>
                </div>
            </div>
        </div>
    </div>


    <!-- Total Teachers Card -->
    <div class="col-sm-6 col-xl-3">
        <div class="card card-body bg-danger-400 has-bg-image" style="height: 100px;">
            <div class="media">
                <div class="media-body">
                    <h3 class="mb-0">{{ $users->where('user_type', 'teacher')->count() }}</h3>
                    <span class="text-uppercase font-size-xs">Total Teachers</span>
                </div>

                <div class="ml-3 align-self-center">
                    <i class="icon-users2 icon-3x opacity-75"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Total Administrators Card -->
    <div class="col-sm-6 col-xl-3">
        <div class="card card-body bg-success-400 has-bg-image" style="height: 100px;">
            <div class="media">
                <div class="mr-3 align-self-center">
                    <i class="icon-pointer icon-3x opacity-75"></i>
                </div>

                <div class="media-body text-right">
                    <h3 class="mb-0">{{ $users->where('user_type', 'admin')->count() }}</h3>
                    <span class="text-uppercase font-size-xs">Total Administrators</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Total Parents Card -->
    <div class="col-sm-6 col-xl-3">
        <div class="card card-body bg-indigo-400 has-bg-image" style="height: 100px;">
            <div class="media">
                <div class="mr-3 align-self-center">
                    <i class="icon-user icon-3x opacity-75"></i>
                </div>

                <div class="media-body text-right">
                    <h3 class="mb-0">{{ $users->where('user_type', 'parent')->count() }}</h3>
                    <span class="text-uppercase font-size-xs">Total Parents</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

{{--Events Calendar Begins--}}
<div class="card">
    <div class="card-body">
        <div class="row">
            <!-- Left side with calendar -->
            <div class="col m-1">
                <div class="card bg-light shadow-sm h-100">
                    <!-- Added 'h-100' class to make the card fill the height -->
                    {{--<div class="card-header bg-primary text-white">
                        <h5 class="card-title mb-0">School Events Calendar</h5>
                    </div>--}}
                    <div class="card-body">
                        <div class="fullcalendar-basic">
                            <!-- Your calendar content goes here -->
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right side with visualization -->
            {{--<div class="col-lg-6">
                <div class="card bg-light shadow-sm h-100">
                    <!-- Added 'h-100' class to make the card fill the height -->
                    <div class="card-header bg-success text-white">
                        <h5 class="card-title mb-0">All users(Pie Chart)</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="userPieChart"></canvas>
                    </div>
                </div>
            </div>--}}
        </div>
    </div>
</div>

{{--Events Calendar Ends--}}

{{-- Recent Students Panel Begins --}}
<div class="card">
    {{-- @livewire('manage-students') --}}
</div>

@endsection