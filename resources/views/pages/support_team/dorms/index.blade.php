@extends('layouts.master')
@section('page_title', 'Manage Dorms')
@section('content')

    <div class="card p-3 shadow-lg border rounded" style="background: linear-gradient(135deg, #f8f9fa, #e9ecef);">
        {{-- <div class="card-header header-elements-inline">
            <h2 class="card-title">Manage Dorms</h2>
            {!! Qs::getPanelOptions() !!}
        </div> --}}

        <div class="card-body">
            @livewire('manage-dorms')
        </div>
    </div>

    {{-- Dorm List Ends --}}

@endsection
