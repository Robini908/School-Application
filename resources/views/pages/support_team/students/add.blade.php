@extends('layouts.master')
@section('page_title', 'Manage and Admit Students')
@section('content')
    <link href=" {{ asset('assets/css/admit_student.css') }}" rel="stylesheet" type="text/css">
    <div class="card">
        <div class="card-header bg-white header-elements-inline">            
            {!! Qs::getPanelOptions() !!}
        </div>
        <div class="card-body">
            <ul class="nav nav-tabs nav-tabs-highlight p-3">                
                <li class="nav-item">
                    <a href="#manage-students" class="nav-link active" data-toggle="tab">Manage Admissions</a>
                </li>
                
                <li class="nav-item">
                    <a href="#admit-student" class="nav-link " data-toggle="tab">Admit New Student</a>
                </li>

                <li class="nav-item">
                    <a href="#bulk-admit" class="nav-link" data-toggle="tab">Bulk Admit</a>
                </li>
            </ul>

            <div class="tab-content mt-3">
            <!-- Manage Students Tab -->            
            @livewire('manage-students')  
            @livewire('admit-student')
                

            <div class="tab-pane fade" id="bulk-admit">
                <div class="card container">
                    <form method="POST" action="" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="bulk_files">Upload Bulk Files:</label>
                            <input type="file" name="bulk_files[]" id="bulk_files" class="form-control-file" multiple>
                            <small class="form-text text-muted">Upload Excel, Word, or PDF files.</small>
                        </div>
                        <button type="submit" class="btn btn-md btn-primary m-1 float-right">Upload Files</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/vue@2/dist/vue.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="{{ asset('global_assets/js/main/add_student.js') }}"></script>
    <script src="{{ asset('global_assets/js/main/manage_admissions.js') }}"></script>
@endsection