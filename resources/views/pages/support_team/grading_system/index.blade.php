@extends('layouts.master')
@section('page_title', 'Manage Grades')
@section('content')

<div class="container">
    <h2>Create Grading</h2>
    <form action="{{ route('grading_system.index') }}" method="POST">

        <div class="form-group">
            <label for="grading_name">Grading Name:</label>
            <input type="text" class="form-control" id="grading_name" name="grading_name" required>
        </div>
        <div class="form-group">
            <label for="grading_ranges">Grading Ranges:</label>
            <div id="grading_ranges">
                <div class="form-group">
                    <label for="range_from">From:</label>
                    <input type="number" class="form-control" id="range_from" name="range_from[]" required>
                    <label for="range_to">To:</label>
                    <input type="number" class="form-control" id="range_to" name="range_to[]" required>
                    <label for="grade">Grade:</label>
                    <input type="text" class="form-control" id="grade" name="grade[]" required>
                </div>
            </div>
            <button type="button" id="add_more" class="btn btn-primary">Add More Ranges</button>
        </div>
        <button type="submit" class="btn btn-success">Submit</button>
    </form>
</div>
@endsection

