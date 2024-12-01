@extends('layouts.master')
@section('page_title', 'Manage Grading System')
@section('content')

<div class="card">
    <div class="card-body">
        <ul class="nav nav-tabs nav-tabs-highlight p-3" style="margin-bottom: 1rem;">
            <!-- Added margin-bottom -->
            <li class="nav-item">
                <a href="#grading-management" class="nav-link active" data-toggle="tab">Grading Systems</a>
            </li>
            <li class="nav-item">
                <a href="#grading-range" class="nav-link" data-toggle="tab">Grading Ranges</a>
            </li>

            <li class="nav-item">
                <a href="#meangrade-range" class="nav-link" data-toggle="tab">MeanGrade Ranges</a>
            </li>
        </ul>

        <div class="tab-content">
            <!-- Grading Management Tab -->
            <div class="tab-pane fade show active" id="grading-management">

                <livewire:grading-management lazy />
            </div>
            <div class="tab-pane fade p-1" id="grading-range">
                <livewire:grading-range-manager />
            </div>

            <div class="tab-pane fade p-1" id="meangrade-range">
                <livewire:grade-manager lazy />
            </div>
        </div>
    </div>

</div>

@endsection