@extends('layouts.master')
@section('page_title', 'Manage Exam Marks Allocation')
@section('content')
<div class="p-2">
    <a href="{{ route('exams.set') }}" class="btn width-auto btn-primary">Back to Exams</a>
</div>

<div class="card" x-data="{ showAlert: true, showMore: false }">
    <div class="card-header header-elements-inline">
        <h6 class="card-title">Assign Exam Marks</h6>
        {!! Qs::getPanelOptions() !!}
    </div>

    <div class="card-body">
        <!-- Alert for Instructions -->
        <div x-show="showAlert" class="alert alert-info alert-dismissible fade show" role="alert">
            <strong>Important!</strong>
            <span x-show="!showMore">
                Please ensure all marks are allocated correctly before final submission...
                <button x-show="!showMore" @click="showMore = true" class="btn btn-link p-0">View More</button>
            </span>
            <div x-show="showMore" class="mt-2">
                <p>Please ensure that you follow the guidelines:</p>
                <ul class="mb-0">
                    <li>Ensure all subjects are assigned marks for each student in each class to prevent the
                        <strong>missing marks problems.</strong></li><br>
                    <li>Double-check the marks for accuracy.</li>
                    <li>Consult with subject teachers if needed when assigning marks in bulk.</li>
                </ul>
                <button @click="showMore = false" class="btn btn-link p-0">Less</button>
            </div>
            <button type="button" class="close" @click="showAlert = false" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>

        <ul class="nav nav-tabs nav-tabs-highlight p-3" style="margin-bottom: 1rem;">
            <li class="nav-item">
                <a href="#bulk-exam" class="nav-link active" data-toggle="tab">Bulk Marks Allocation</a>
            </li>
            <li class="nav-item">
                <a href="#subject-wise-exam" class="nav-link" data-toggle="tab">Assign Marks Subject-wise</a>
            </li>
            <li class="nav-item">
                <a href="#mark-list-management" class="nav-link" data-toggle="tab">Manage Marks List</a>
            </li>
        </ul>

        <div class="tab-content">
            <!-- Bulk Marks Allocation Tab -->
            <div class="tab-pane fade show active" id="bulk-exam">
                <livewire:assign-batch-marks lazy="on-load" />
            </div>

            <!-- Assign Marks Subject-wise Tab -->
            <div class="tab-pane fade p-1" id="subject-wise-exam">
                <livewire:assign-exams-subjectwise />
            </div>

            <!-- Manage Marks List Tab -->
            <div class="tab-pane fade p-1" id="mark-list-management">
                <livewire:mark-list-management />
            </div>
        </div>
    </div>
</div>


@endsection