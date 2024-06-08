@extends('layouts.master')
@section('page_title', 'Edit Subject - '.$subject->subject_name. ' ('.$subject->subject_name.')')
@section('content')

    <div class="card">
        <div class="card-header header-elements-inline">
            <h6 class="card-title">Edit Subject - {{$subject->subject_name }}</h6>
            {!! Qs::getPanelOptions() !!}
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <form class="ajax-update" method="post" action="{{ route('subjects.update', $subject->id) }}">
                        @csrf @method('PUT')
                        <div class="form-group row">
                            <label class="col-lg-3 col-form-label font-weight-semibold">Subject Name <span class="text-danger">*</span></label>
                            <div class="col-lg-9">
                                <input name="subject_name" value="{{ $subject->subject_name }}" required type="text" class="form-control" placeholder="Name of Subject">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-lg-3 col-form-label font-weight-semibold">Subject Code:</label>
                            <div class="col-lg-9">
                                <input name="subject_code" value="{{ $subject->subject_code }}"  type="text" class="form-control" placeholder="Subject code">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-lg-3 col-form-label font-weight-semibold">Abbreviation:</label>
                            <div class="col-lg-9">
                                <input name="abbreviation" value="{{ $subject->abbreviation }}"  type="text" class="form-control" placeholder="Abbreviation">
                            </div>
                        </div>                      

                        <div class="text-right">
                            <button type="submit" class="btn btn-primary">Update Subject <i class="icon-paperplane ml-2"></i></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    {{--subject Edit Ends--}}

@endsection
