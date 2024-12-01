@extends('layouts.master')
@section('page_title', 'Manage Classes')
@section('content')

<div class="card  p-3 shadow-lg border rounded" style="background: linear-gradient(135deg, #f8f9fa, #e9ecef);">

    <div class="card-header header-elements-inline">
        <h6 class="card-title">Manage Classes</h6>
        {!! Qs::getPanelOptions() !!}
    </div>

    

<livewire:class-management lazy/>
   
</div>

{{--Class List Ends--}}

@endsection