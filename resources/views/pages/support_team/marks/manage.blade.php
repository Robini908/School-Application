@extends('layouts.master')
@section('page_title', 'Manage Marks')
@section('content')

<div class="card shadow-sm mb-4">
    <div class="card-header d-flex justify-content-between align-items-center bg-primary text-white">
        <h6 class="card-title font-weight-bold mb-0">Fill The Form To Manage Marks</h6>
        {!! Qs::getPanelOptions() !!}
    </div>

    <div class="card-body">
        @include('pages.support_team.marks.selector')
    </div>
</div>

<div class="card shadow-sm mb-4">
    <div class="card-header bg-secondary text-white">
        <div class="row">
            <div class="col-md-4">
                <h6 class="card-title mb-0"><strong>Subject:</strong> {{ $m->subject->name }}</h6>
            </div>
            <div class="col-md-4">
                <h6 class="card-title mb-0"><strong>Class:</strong> {{ $m->my_class->name.' '.$m->section->name }}</h6>
            </div>
            <div class="col-md-4">
                <h6 class="card-title mb-0"><strong>Exam:</strong> {{ $m->exam->name.' - '.$m->year }}</h6>
            </div>
        </div>
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>Student Name</th>
                        <th>Subject</th>
                        <th>Marks Obtained</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
            </table>
        </div>
    </div>
</div>

{{-- Marks Manage End --}}
@endsection
