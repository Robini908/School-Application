@extends('layouts.master')
@section('page_title', 'Manage Classes')
@section('content')

<div class="card">
    <div class="card-header header-elements-inline">
        <h6 class="card-title">Manage Classes</h6>
        {!! Qs::getPanelOptions() !!}
    </div>

    
@livewire('class-management')
   
</div>

{{--Class List Ends--}}

@endsection