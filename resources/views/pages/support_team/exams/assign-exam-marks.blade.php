@extends('layouts.master')

@section('page_title', 'Manage Exams')

@section('content')
<div class="card">
    <div class="p-2">
        <a href="{{ route('exams.set') }}" class="btn width-auto btn-primary">Back to Exams</a>
    </div>
  
    <h1 class="p-3">Assign Marks for Exam</h1>

    <!-- Include Livewire Component and pass the examId -->
    @livewire('assign-batch-marks')
</div>
@endsection
