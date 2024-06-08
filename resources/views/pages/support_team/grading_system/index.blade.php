@extends('layouts.master')
@section('page_title', 'Manage Grades')
@section('content')

<div class="container">
    <h2>Create Grading</h2>

    <div class="row mb-3">
        <div class="col-md-6">
            <a href="{{ route('grading_system.create') }}" class="btn btn-success">
                <i class="fas fa-plus-circle"></i> Create Grading System
            </a>
        </div>
    </div>

    <div class="row">
        @foreach ($gradingSystems as $grade)
        <div class="col-md-4 mb-3">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title d-inline-block mr-auto">{{ $grade->name }}</h5>
                    <div class="btn-group float-right">
                        <a href="{{ route('grading_system.edit', $grade->id) }}" class="btn btn-primary">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <!-- Add other actions here as needed -->
                        <button type="button" class="btn btn-danger">
                            <i class="fas fa-trash"></i> Delete
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Ranges</th>
                                <th>Grade</th>
                                <th>Remark</th>
                                <th>GPA</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($grade->gradingRanges->count() > 0)
                            @foreach($grade->gradingRanges as $range)
                            <tr>
                                <td>{{$range->range_from}} - {{$range->range_to}}</td>
                                <td>{{ $range->grade }}</td>
                                <td>{{ $range->remark }}</td>
                                <td>{{ $range->gpa }}</td>
                            </tr>
                            @endforeach
                            @else
                            <tr>
                                <td colspan="4">No ranges defined</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

@endsection
