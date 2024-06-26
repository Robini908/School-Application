@extends('layouts.master')
@section('page_title', 'Student Details')
@section('content')

<div class="card">
    <div class="card-header header-elements-inline">
        <h6 class="card-title">Student Details</h6>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-4">
                <h5 class="card-title">Personal Information</h5>
                <p><strong>Name:</strong> {{ $student->user->first_name }} {{ $student->user->middle_name }}
                    {{ $student->user->last_name }}</p>
                <p><strong>Email:</strong> {{ $student->user->email }}</p>
                <p><strong>Gender:</strong> {{ $student->user->gender }}</p>
                <p><strong>Phone:</strong> {{ $student->user->phone }}</p>
                <p><strong>Date of Birth:</strong> {{ $student->user->dob }}</p>
                <p><strong>Age:</strong> {{ \Carbon\Carbon::parse($student->user->dob)->age }} years</p>
                <p><strong>Admission Number:</strong> {{ $student->adm_no }}</p>
                <p><strong>Year Admitted:</strong> {{ $student->year_admitted }}</p>
            </div>
            <div class="col-md-4">
                <h5 class="card-title">Parent Information</h5>
                <p><strong>Parent Name:</strong> {{ $student->parent_first_name }} {{ $student->parent_middle_name }}
                    {{ $student->parent_last_name }}</p>
                <p><strong>Parent Phone:</strong> {{ $student->parent_phone }}</p>
                <p><strong>Parent Email:</strong> {{ $student->parent_email }}</p>
            </div>
            <div class="col-md-4">
                <h5 class="card-title">Academic Information</h5>
                <p><strong>Class:</strong> {{ $student->my_class->name }}</p>
                <p><strong>Section:</strong> {{ $student->section->name }}</p>
                <p><strong>House:</strong> {{ $student->house }}</p>
                <p><strong>Dormitory:</strong> {{ $student->dorm->name ?? 'N/A' }}</p>
                <p><strong>Dorm Room No:</strong> {{ $student->dorm_room_no }}</p>
                <p><strong>Session:</strong> {{ $student->session }}</p>
                <p><strong>Graduation Date:</strong> {{ $student->grad_date }}</p>
                <p><strong>Status:</strong> {{ $student->status }}</p>
                @if($student->status === 'disapproved')
                <h5 class="card-title">Disapproval Details</h5>
                <p><strong>Reason:</strong> {{ $student->disapproval_reason }}</p>
                <p><strong>Description:</strong> {{ $student->disapproval_description }}</p>
                @if($student->disapproval_attachment)
                <p><strong>Attachment:</strong> <a href="{{ Storage::url($student->disapproval_attachment) }}"
                        target="_blank">View Attachment</a></p>
                @endif
                @endif
            </div>
        </div>
    </div>
</div>

@endsection