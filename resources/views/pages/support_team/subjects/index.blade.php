@extends('layouts.master')
@section('page_title', 'Manage Subjects')
@section('content')

    <div class="card">
        <div class="card-header header-elements-inline">
            <h6 class="card-title">Manage Subjects</h6>
            {!! Qs::getPanelOptions() !!}
        </div>

        <div class="card-body">
            <livewire:all-subject-management-actions lazy />
        </div>
    </div>
@endsection
