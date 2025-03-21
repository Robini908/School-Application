@extends('layouts.master')
@section('page_title', 'Manage Classes')
@section('content')

<div class="card col-md-12  p-3 shadow-lg border rounded" style="background: linear-gradient(135deg, #f8f9fa, #e9ecef);">

    <div class="card-header header-elements-inline">
        <h6 class="card-title">Manage Classes</h6>
        {!! Qs::getPanelOptions() !!}
    </div>
    <div class="card-body">

   
        <livewire:parent-child-class lazy />
  
    </div>
   

</div>

{{--Class List Ends--}}

@endsection