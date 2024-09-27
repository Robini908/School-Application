@extends('layouts.master')
@section('page_title', 'Manage Exams')
@section('content')

<div class="card">
    <div class="card-header header-elements-inline">
        <h6 class="card-title">Manage Exams</h6>
        {!! Qs::getPanelOptions() !!}
    </div>

    <div class="card-body">
        <ul class="nav nav-tabs nav-tabs-highlight">
            <li class="nav-item"><a href="#all-exams" class="nav-link active" data-toggle="tab">Manage Exam</a></li>
            <li class="nav-item"><a href="#new-exam" class="nav-link" data-toggle="tab"><i class="icon-plus2"></i> Add Exam</a></li>
        </ul>

        <div class="tab-content">
            <div class="tab-pane fade show active" id="all-exams">
                <table class="table datatable-button-html5-columns">
                    <thead>
                        <tr>
                            <th>S/N</th>
                            <th>Name</th>
                            <th>Term</th>
                            <th>Session</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($exams as $ex)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $ex->name }}</td>
                            <td>{{ 'Term '.$ex->term }}</td>
                            <td>{{ $ex->year }}</td>
                            <td class="text-center">
                                <div class="list-icons">
                                    <div class="dropdown">
                                        <a href="#" class="list-icons-item" data-toggle="dropdown">
                                            <i class="icon-menu9"></i>
                                        </a>

                                        <div class="dropdown-menu dropdown-menu-left">
                                            

                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="tab-pane fade" id="new-exam">
                <div class="row">
                    <div class="col-md-12">
                        <div class="alert alert-info border-0 alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>

                            <span>You are creating an Exam for the Current Session <strong>{{ Qs::getSetting('current_session') }}</strong></span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <form method="post" action="{{ route('exams.store') }}">
                            @csrf
                            <div class="form-group row">
                                <label class="col-lg-3 col-form-label font-weight-semibold">Name <span class="text-danger">*</span></label>
                                <div class="col-lg-9">
                                    <input name="name" value="{{ old('name') }}" required type="text" class="form-control" placeholder="Name of Exam">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="term" class="col-lg-3 col-form-label font-weight-semibold">Term</label>
                                <div class="col-lg-9">
                                    <select data-placeholder="Select Teacher" class="form-control select-search" name="term" id="term">
                                        <option {{ old('term') == 1 ? 'selected' : '' }} value="1">First Term</option>
                                        <option {{ old('term') == 2 ? 'selected' : '' }} value="2">Second Term</option>
                                        <option {{ old('term') == 3 ? 'selected' : '' }} value="3">Third Term</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="term" class="col-lg-3 col-form-label font-weight-semibold">Grading System</label>
                                <div class="col-lg-9">
                                    <!-- make width full -->
                                    <select class="form-control select-search w-100 " name="grading_system_id" id="grading_system_id">
                                        @foreach ($gradingSystems as $system)
                                        <option value="{{ $system->id }}">
                                            {{ $system->name }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>


                            <div class="form-group row w-full">
                                <label for="term" class="col-lg-3 col-form-label font-weight-semibold">Select Classes</label>
                                <div class="col-lg-9 w-full">
                                    <!-- make width full -->
                                    <select class="form-control select-search w-100" name="classes[]" id="classes" multiple style="height: fit-content;">
                                        @foreach ($classes_ as $class_)
                                        <option value="{{ $class_->id }}" selected>
                                            {{ $class_->name }}
                                        </option>
                                        @endforeach
                                    </select>

                                </div>
                            </div>





                            <div class="text-right">
                                <button type="submit" class="btn btn-primary">Submit form <i class="icon-paperplane ml-2"></i></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{--Class List Ends--}}

@endsection