@extends('layouts.master')
@section('page_title', 'Student Promotions and Demotions')
@section('content')

    <div class="card  p-3 shadow-lg border rounded" style="background: linear-gradient(135deg, #f8f9fa, #e9ecef);">
        <div class="card-header header-elements-inline">
            <h6 class="card-title">Manage</h6>
            {!! Qs::getPanelOptions() !!}
        </div>

        <div class="card-body">
            <livewire:graduate-students lazy />
        </div>
    </div>

@endsection
