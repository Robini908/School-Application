@extends('layouts.master')
@section('page_title', 'Manage Grades')
@section('content')

<div class="container-xl">
    <div class="row justify-content-center">
        <div class="col-md-11"> <!-- Increased to 95% of the container width -->
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h2 class="card-title">Edit Grading System</h2>
                </div>
                <div class="card-body">
                    <form action="{{ route('grading_system.update', $gradingSystem->id) }}" method="put">
                        @csrf
                        <div class="form-group">
                            <label for="name">Grading Name:</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" required value="{{ old('name', $gradingSystem->name) }}">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Display existing ranges -->
                        <div class="form-group">
                            <label for="grading_ranges">Grading Ranges:</label>
                            <div id="grading_ranges">
                                @foreach ($ranges as $range)
                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <label for="range_from">From:</label>
                                        <input type="number" class="form-control" id="range_from" name="range_from[]" required value="{{ $range->range_from }}">
                                    </div>
                                    <div class="col-md-4">
                                        <label for="range_to">To:</label>
                                        <input type="number" class="form-control" id="range_to" name="range_to[]" required value="{{ $range->range_to }}">
                                    </div>
                                    <div class="col-md-4">
                                        <label for="grade">Grade:</label>
                                        <input type="text" class="form-control" id="grade" name="grade[]" required value="{{ $range->grade }}">
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
