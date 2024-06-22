@extends('layouts.master')
@section('page_title', 'Manage Grading System')
@section('content')
<link href=" {{ asset('assets/css/edit_grading.css') }}" rel="stylesheet" type="text/css">
<!-- DataTables CSS -->



<div class="card">
    <div class="card-header header-elements-inline" style="background-color: floralwhite;">
        <h6 class="card-title">{{$gradingSystem->name}}</h6>
        {!! Qs::getPanelOptions() !!}
    </div>

    <div class="card-body">
        <ul class="nav nav-tabs nav-tabs-highlight">
            <li class="nav-item"><a href="#all-gradings" class="nav-link" data-toggle="tab">{{ $subject->subject_name }}
                    Grading</a></li>
            <li class="nav-item"><a href="#editting_grading_system" class="nav-link active" data-toggle="tab"><i class="icon-plus2"></i> Edit Grading</a></li>
        </ul>

        <div class="tab-content">
            <div class="tab-pane fade" id="all-gradings">
                <div class="card subject-card">
                    <bold>
                        <h5>{{ $subject->subject_name }} Grading</h5>
                    </bold>
                    <div class="card-body custom-scrollbar">
                        <table class="table data-table">
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

            <div class="tab-pane fade show active" id="editting_grading_system">
                <form action="{{ route('subject-ranges.edit', [$gradingSystem->id, $subject->id]) }}" method="POST" id="grading_form">
                    @csrf
                    @method('put')
                    <div class="table-responsive">
                        <table class="table custom-table">

                            <thead>
                                <tr>
                                    <th>From</th>
                                    <th>To</th>
                                    <th>Grade</th>
                                    <th>Remark</th>
                                    <th>Points</th>
                                </tr>
                            </thead>
                            <tbody id="grading_ranges">
                                @if ($ranges->count() == 0)
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
                                </tr>
                                @else
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
                                        <input type="text" class="form-control grade" name="grade[]" required value="{{ $range->grade }}">
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
                                @endif
                            </tbody>
                        </table>
                    </div>
                    <button id="add_more" type="button" class="btn btn-primary add-more-ranges">Add More Ranges</button>
                    <button type="submit" class="btn btn-success">Submit</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('assets/js/grading_system/edit.js') }}"></script>
<script>
    // Initialize Swiper
    var swiper = new Swiper('.swiper-container', {
        loop: true,
        pagination: {
            el: '.swiper-pagination',
            clickable: true,
        },
    });
</script>

@endsection