@extends('layouts.master')
@section('page_title', 'Manage Grades')
@section('content')

<div class="container">
    <h2>Create Grading</h2>
    <!-- add csrf -->
    <form action="{{ route('grading_system.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="name">Grading Name:</label>
            <input type="text" class="form-control" id="name" name="name" required>
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

    <script>
        document.getElementById('add_more').addEventListener('click', function() {
            const div = document.createElement('div');
            div.classList.add('form-group');
            div.innerHTML = `
        <label for="range_from">From:</label>
        <input type="number" class="form-control" id="range_from" name="range_from[]" required>
        <label for="range_to">To:</label>
        <input type="number" class="form-control" id="range_to" name="range_to[]" required>
        <label for="grade">Grade:
        <label for="grade">Grade:</label>
        <input type="text" class="form-control" id="grade" name="grade[]" required>
    `;
            document.getElementById('grading_ranges').appendChild(div);
        });
    </script>
</div>
@endsection