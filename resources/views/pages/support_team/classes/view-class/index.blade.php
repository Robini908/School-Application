@extends('layouts.master')
@section('page_title', 'View class')
@section('content')

<link href=" {{ asset('assets/css/view_class.css') }}" rel="stylesheet" type="text/css">

<div class="card">
    <div class="card-header header-elements-inline">
        <h6 class="card-title">View Class</h6>
        {!! Qs::getPanelOptions() !!}
    </div>

    <div class="card-body">
        <div class="tab-content">
            <div class="tab-pane fade show active" id="class">
                <table class="table datatable-button-html5-columns">
                    <thead>
                    <tr>
                        <th>S/N</th>
                        <th>Stream</th>
                        <th>Class Teacher</th>
                        
                    </tr>
                    </thead>
                    <tbody>
                    {{-- @foreach() --}}
                        <tr>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                        </tr>
                    {{-- @endforeach --}}
                    </tbody>
                </table>
            </div>
        </div>
    </div>


</div>

@endSection