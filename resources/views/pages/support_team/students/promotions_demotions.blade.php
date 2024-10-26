@extends('layouts.master')
@section('page_title', 'Student Promotions and Demotions')
@section('content')

<div class="card">
    <div class="card-header header-elements-inline">
        <h6 class="card-title">Manage</h6>
        {!! Qs::getPanelOptions() !!}
    </div>

    <div class="card-body">
        @livewire('manage_promotions')
        {{-- <livewire:manage_promotions lazy /> --}}
    </div>
</div>

@endsection