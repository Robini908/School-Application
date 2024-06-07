@extends('layouts.master')
@section('page_title', 'Manage Grades')
@section('content')

<div class="container">
    <h2>Create Grading</h2>

    <table class="table">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Name</th>
                <th scope="col">Ranges</th>
                <th scope="col">View</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($gradingSystems as $grade)
            <tr>
                <th scope="row">{{$grade->id}}</th>
                <td>{{
                    $grade->name }}</td>
                <td>{{
                    $grade->gradingRanges->count() }}</td>
                <td>
                    <a href="{{ route('grading_system.edit', $grade->id) }}" class="btn btn-primary">Edit
                        &rarr;
                    </a>
                </td>
            </tr>
            @endforeach

        </tbody>
    </table><!-- add csrf -->

</div>
@endsection