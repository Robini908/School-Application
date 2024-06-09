@extends('layouts.master')
@section('page_title', 'Manage Grades')
@section('content')

<div class="container mt-4">
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h6 class="card-title font-weight-bold mb-0">Create Grading</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('grading_system.update', $gradingSystem->id) }}" method="post">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label for="name">Grading Name:</label>
                    <input type="text" class="form-control" id="name" name="name" required value="{{ $gradingSystem->name }}">
                </div>
                <div class="form-group">
                    <label for="grading_ranges">Grading Ranges:</label>
                    <div id="grading_ranges">
                        @foreach ($ranges as $range)
                        <div class="form-group border rounded p-3 mb-3">
                            <div class="row">
                                <div class="col-12 col-md-3 mb-3">
                                    <label for="range_from">From:</label>
                                    <input type="number" class="form-control" id="range_from" name="range_from[]" required value="{{ $range->range_from }}">
                                </div>
                                <div class="col-12 col-md-3 mb-3">
                                    <label for="range_to">To:</label>
                                    <input type="number" class="form-control" id="range_to" name="range_to[]" required value="{{ $range->range_to }}">
                                </div>
                                <div class="col-12 col-md-3 mb-3">
                                    <label for="grade">Grade:</label>
                                    <input type="text" class="form-control" id="grade" name="grade[]" required value="{{ $range->grade }}">
                                </div>
                                <div class="col-12 col-md-3 mb-3">
                                    <label for="gpa">GPA:</label>
                                    <input type="number" step="0.01" class="form-control" id="gpa" name="gpa[]" required value="{{ $range->gpa }}">
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <button type="button" id="add_more" class="btn btn-primary mt-2">Add More Ranges</button>
                </div>
                <button type="submit" class="btn btn-success mt-2">Submit</button>
            </form>
        </div>
    </div>
</div>

<script>
    document.getElementById('add_more').addEventListener('click', function() {
        const div = document.createElement('div');
        div.classList.add('form-group', 'border', 'rounded', 'p-3', 'mb-3');
        div.innerHTML = `
            <div class="row">
                <div class="col-12 col-md-3 mb-3">
                    <label for="range_from">From:</label>
                    <input type="number" class="form-control" id="range_from" name="range_from[]" required>
                </div>
                <div class="col-12 col-md-3 mb-3">
                    <label for="range_to">To:</label>
                    <input type="number" class="form-control" id="range_to" name="range_to[]" required>
                </div>
                <div class="col-12 col-md-3 mb-3">
                    <label for="grade">Grade:</label>
                    <input type="text" class="form-control" id="grade" name="grade[]" required>
                </div>
                <div class="col-12 col-md-3 mb-3">
                    <label for="gpa">GPA:</label>
                    <input type="number" step="0.01" class="form-control" id="gpa" name="gpa[]" required>
                </div>
            </div>
        `;
        document.getElementById('grading_ranges').appendChild(div);
    });
</script>
@endsection
