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
            <li class="nav-item"><a href="#all-gradings" class="nav-link active" data-toggle="tab">Manage Grading</a>
            </li>
            <li class="nav-item"><a href="#new-gradingsystem" class="nav-link" data-toggle="tab"><i class="icon-plus2"></i> Create New Grading</a></li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane fade show active" id="all-gradings">
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
                                            <!-- <th>Grade</th> -->
                                            <th>Ranges</th>
                                            <th>View</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if($subjects->count() > 0)
                                        @foreach($subjects as $subject)
                                        <tr>
                                            <td>{{$subject->subject_name}}</td>


                                            <td>4</td>

                                            <td>
                                                <div>

                                                    <a class="btn btn-primary" href="{{ route('subject-ranges.show', [$grade->id, $subject->id]) }}">
                                                        View &rarr;
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

            <div class="tab-pane fade" id="new-gradingsystem">

                <form action="{{ route('grading_system.store') }}" method="POST" id="grading_form">
                    @csrf
                    <div class="form-group">
                        <label for="name"><b>Grading Name:</b> </label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <!-- @foreach($subjects as $sub)
                    <div class="card p-3 add-more-card">
                        <h5 class="card-title d-inline-block mr-auto"><b>{{ $sub->subject_name }}</b></h5>
                        <button type="button" class="btn btn-danger btn-sm delete-card">Delete Card</button>
                        <div class="table-responsive">
                            <table class="table" id="grading_table">
                                <thead>
                                    <tr>
                                        <th>From</th>
                                        <th>To</th>
                                        <th>Grade</th>
                                        <th>Remark</th>
                                        <th>GPA</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody class="grading-ranges">
                                    <tr>

                                        <td>
                                            <input type="number" class="form-control range-from" name="range_from[]" required min="0" max="100">
                                            <div class="invalid-feedback"></div>
                                        </td>
                                        <td>
                                            <input type="number" class="form-control range-to" name="range_to[]" required min="0" max="100">
                                            <div class="invalid-feedback"></div>
                                        </td>
                                        <td>
                                            <input type="text" class="form-control grade" name="grade[]" required>
                                            <div class="invalid-feedback"></div>
                                        </td>
                                        <td>
                                            <input type="text" class="form-control remark" name="remark[]">
                                        </td>
                                        <td>
                                            <input type="text" class="form-control gpa" name="gpa[]">
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-danger btn-sm delete-range">Remove</button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @endforeach -->
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