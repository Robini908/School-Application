@extends('layouts.master')
@section('page_title', 'Manage Grading System')
@section('content')
<link href=" {{ asset('assets/css/edit_grading.css') }}" rel="stylesheet" type="text/css">

<div class="card">
    <div class="card-header header-elements-inline">
        <h6 class="card-title">{{$gradingSystem->name}}</h6>
        {!! Qs::getPanelOptions() !!}
    </div>

    <div class="card-body">
        <ul class="nav nav-tabs nav-tabs-highlight">
            <li class="nav-item"><a href="#all-gradings" class="nav-link " data-toggle="tab">Manage {{$subject->subject_name}} Grading</a></li>
            <li class="nav-item"><a href="#editting_grading_system" class="nav-link active" data-toggle="tab"><i class="icon-plus2"></i> Edit Grading</a></li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane fade " id="all-gradings">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div class="card h-100">
                            <div class="card-body">

                            </div>
                            <div class="card-body">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Ranges</th>
                                            <th>Grade</th>
                                            <th>Remark</th>
                                            <th>Points</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        @foreach($ranges as $range)
                                        <tr>
                                            <td>{{$range->range_from}} - {{$range->range_to}}</td>
                                            <td>{{ $range->grade }}</td>
                                            <td>{{ $range->remark }}</td>
                                            <td>{{ $range->gpa }}</td>
                                        </tr>
                                        @endforeach

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <div class="tab-pane fade show active" id="editting_grading_system">

                <form action="{{ route('grading_system.update', $gradingSystem->id) }}" method="POST" id="grading_form">
                    @csrf
                    @method('put')

                    <div class="table-responsive">
                        <table class="table" id="grading_table">
                            <thead>
                                <tr>
                                    <th>From</th>
                                    <th>To</th>
                                    <th>Grade</th>
                                    <th>Remark</th>
                                    <th>GPA</th>
                                </tr>
                            </thead>
                            <tbody id="grading_ranges">
                                @foreach($ranges as $range)
                                <tr>
                                    <td>
                                        <input type="number" class="form-control range-from" name="range_from[]" required min="0" max="100" value="{{ $range->range_from }}">
                                        <div class="invalid-feedback"></div>
                                    </td>
                                    <td>
                                        <input type="number" class="form-control range-to" name="range_to[]" required min="0" max="100" value="{{ $range->range_to }}">
                                        <div class="invalid-feedback"></div>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control grade" name="grade[]" required value="{{
                                            $range->grade}}">
                                        <div class="invalid-feedback"></div>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control remark" name="remark[]" value="{{ $range->remark }}">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control gpa" name="gpa[]" value="{{ $range->gpa }}">
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#deleteModal">Delete
                    </button>
                    <button type="submit" class="btn btn-success">Submit</button>
                    <button type="button" class="btn btn-primary add-more-ranges">Add More Ranges</button>
            </div>
        </div>
        </form>
    </div>
</div>
<script src="{{ asset('assets/js/grading_system/edit.js') }} "></script>
</div>
</div>
</div>
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content border-0 shadow-lg" style="background-color: #f8f9fa; border-radius: 10px;">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="exampleModalLabel"><i class="bi bi-trash-fill mr-2"></i> Confirm Deletion
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center">
                <p><span class="display-1 text-danger">&#128465;</span></p>
                <p class="lead">Are you sure you want to delete this grading system?</p>
                <p class="lead">This action cannot be undone.</p>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-outline-danger btn-lg rounded-pill" data-dismiss="modal"><i class="bi bi-x-circle-fill mr-1"></i> Cancel</button>

            </div>
        </div>
    </div>
</div>


{{--Class List Ends--}}

@endsection