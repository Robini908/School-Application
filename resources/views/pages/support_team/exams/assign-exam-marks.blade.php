@extends('layouts.master')

@section('page_title', 'Manage Exams')

@section('content')
    <div class="p-2">
        <a href="{{ route('exams.set') }}" class="btn width-auto btn-primary">Back to Exams</a>
    </div>

    <!-- Include Livewire Component and pass the examId -->
    <div class="card">
        <div class="card-header header-elements-inline">
            <h6 class="card-title">Assign Exam Marks</h6>
            {!! Qs::getPanelOptions() !!}
        </div>
    @livewire('assign-batch-marks')
    </div>
@endsection
