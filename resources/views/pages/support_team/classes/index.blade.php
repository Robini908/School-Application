@extends('layouts.master')
@section('page_title', 'Manage Classes')
@section('content')

<div class="card">
    <div class="card-header header-elements-inline">
        <h6 class="card-title">Manage Classes</h6>
        {!! Qs::getPanelOptions() !!}
    </div>

    <div class="card-body">
@livewire('class-management')
    </div>
</div>

{{--Class List Ends--}}

@endsection