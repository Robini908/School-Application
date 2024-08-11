@extends('layouts.master')

@section('page_title', 'Student Profile')

@section('content')
<div class="container my-4">
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if ($student)
    <div class="card shadow-lg">
        <div class="row no-gutters">
            <div class="col-md-4 bg-light d-flex justify-content-center align-items-center">
                @if ($student->photo)
                    <img src="{{ asset($student->photo) }}" alt="Student Photo" class="img-fluid rounded-start">
                @else
                    <div class="text-center">
                        <span class="text-muted">No Image</span>
                    </div>
                @endif
            </div>
            <div class="col-md-8 p-4">
                <h3 class="card-title">{{ $student->first_name }} {{ $student->last_name }}</h3>
                <p><strong>Admission No:</strong> {{ $student->adm_no }}</p>
                <p><strong>Gender:</strong> {{ $student->gender }}</p>
                <p><strong>Class:</strong> {{ $student->classname }}</p>
                <p><strong>Section:</strong> {{ $student->sectionname }}</p>
                <p><strong>Status:</strong> 
                    <span class="badge {{ $student->status == 'Active' ? 'badge-success' : 'badge-warning' }}">
                        {{ $student->status }}
                    </span>
                </p>
                <p><strong>Parent Name:</strong> {{ $student->parent_first_name }} {{ $student->parent_last_name }}</p>
                <p><strong>Parent Contact:</strong> {{ $student->parent_phone_number }}</p>
            </div>
        </div>
    </div>
    @else
    @livewire('not-found')
    @endif
</div>
@endsection
