@extends('layouts.master')
@section('page_title', 'Manage Grading System')
@section('content')

<div class="card">
    <div class="card-header header-elements-inline">
        <h6 class="card-title">Manage Grading</h6>
        {!! Qs::getPanelOptions() !!}
    </div>
    <div class="card-body">
        <ul class="nav nav-tabs nav-tabs-highlight">
            <li class="nav-item"><a href="#all-gradings" class="nav-link " data-toggle="tab">Manage Grading</a>
            </li>
            <li class="nav-item"><a href="#new-gradingsystem" class="nav-link active" data-toggle="tab"><i class="icon-plus2"></i> {{ $gradingSystem->name }}</a></li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane fade " id="all-gradings">
                <div class="row">
                    @foreach ($gradingSystems as $grade)
                    <div class="col-md-6 mb-3">
                        <div class="card h-100">
                            <div class="card-body">
                                <h5 class="card-title d-inline-block mr-auto">{{ $grade->name }}</h5>
                                <div class="btn-group float-right">
                                    <a href="{{ route('grading_system.edit', $grade->id) }}" class="btn btn-primary">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <!-- Add other actions here as needed -->
                                </div>
                            </div>
                            <div class="card-body">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Subjects</th>
                                            <th>View</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if($subjects->count() > 0)
                                        @foreach($subjects as $subject)
                                        <tr>
                                            <!-- align text to left -->
                                            <td class=" text-left ">{{$subject->subject_name}}</td>
                                            <!-- align div to right -->
                                            <td class="
                                            d-flex justify-content-end
                                            ">
                                                <div style="width: fit-content;">

                                                    <a class="btn btn-primary" href="{{ route('subject-ranges.show', [$grade->id, $subject->id]) }}">
                                                        View ranges &rarr;
                                                    </a>
                                                </div>

                                            </td>
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

            <div class="tab-pane fade show active" id="new-gradingsystem">

                <form action="{{ route('grading_system.update', $gradingSystem->id) }}" method="POST" id="grading_form">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label for="name"><b>Grading Name:</b> </label>
                        <input type="text" class="form-control" id="name" name="name" required value="{{ $gradingSystem->name }}">
                    </div>

                    <div><button type="submit" class="btn btn-success">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<!-- Modal HTML -->
<div class="modal fade" id="deleteCardModal" tabindex="-1" role="dialog" aria-labelledby="deleteCardModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteCardModalLabel">Confirmation</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this card?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteCard">Delete</button>
            </div>
        </div>
    </div>
</div>
<div x-data="dataComponent()" x-init="console.log(gradingSystems)">
    <div x-text="gradingSystems">

    </div>
</div>
<script src="{{ asset('assets/js/grading_system/index.js') }} "></script>
</div>
</div>
</div>
{{--Class List Ends--}}

@endsection