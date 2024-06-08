@extends('layouts.master')
@section('page_title', 'Manage Subjects')
@section('content')

    <div class="card">
        <div class="card-header header-elements-inline">
            <h6 class="card-title">Manage Subjects</h6>
            {!! Qs::getPanelOptions() !!}
        </div>

        <div class="card-body">
            <ul class="nav nav-tabs nav-tabs-highlight">
                <li class="nav-item"><a href="#new-subject" class="nav-link active" data-toggle="tab">Add Subject</a></li>
                <li class="nav-item"><a href="#subs" class="nav-link " data-toggle="tab">Manage Subject</a></li>
                
            </ul>

            <div class="tab-content">
                <div class="tab-pane show  active fade" id="new-subject">
                    <div class="row">
                        @if (session('success'))
                        <div style="color: green;">
                            {{ session('success') }}
                        </div>
                    @endif
                        <div class="col-md-6">
                            <form class="ajax-store" method="post" action="{{ route('subjects.store') }}">
                                @csrf
                                <div class="form-group row">
                                    <label for="subname" class="col-lg-3 col-form-label font-weight-semibold">Subject Name <span class="text-danger">*</span></label>
                                    <div class="col-lg-9">
                                        <input id="subname" name="subname" value="{{ old('name') }}" required type="text" class="form-control" placeholder="Name of subject">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="subcode" class="col-lg-3 col-form-label font-weight-semibold">Subject Code <span class="text-danger">*</span></label>
                                    <div class="col-lg-9">
                                        <input id="subcode" required name="subcode"  type="text" class="form-control" placeholder="Eg. 232">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="my_class_id" class="col-lg-3 col-form-label font-weight-semibold">Subject Abbreviation: <span class="text-danger">*</span></label>
                                    <div class="col-lg-9">
                                        <input id="subabbrev" required name="subabbrev"  type="text" class="form-control" placeholder="PHY">
                                    </div>
                                </div>

                                <div class="text-right">
                                    <button type="submit" class="btn btn-primary">Add Subject <i class="icon-paperplane ml-2"></i></button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
              
                <div class="tab-pane fade" id="subs">        
                       <table class="table datatable-button-html5-columns">
                        <thead>
                        <tr>
                            <th>S/N</th>
                            <th>Subject Name</th>
                            <th>Subject Code</th>
                            <th>Subject Abbreviation</th>                                
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($subjects as $s)
                            <tr>
                                <td>{{ $s->id }} </td>
                                <td>{{ $s->subject_name }} </td>
                                <td>{{ $s->subject_code }} </td>
                                <td>{{ $s->abbreviation }}</td>                                  
                                <td class="text-center">
                                    <div class="list-icons">
                                        <div class="dropdown">
                                            <a href="#" class="list-icons-item" data-toggle="dropdown">
                                                <i class="icon-menu9"></i>
                                            </a>

                                            <div class="dropdown-menu dropdown-menu-left">
                                                {{--edit--}}
                                                @if(Qs::userIsTeamSA())
                                                    <a href="{{ route('subjects.edit', $s->id) }}" class="dropdown-item"><i class="icon-pencil"></i> Edit</a>
                                                @endif
                                                {{--Delete--}}
                                                @if(Qs::userIsTeamSA())
                                                    <a id="{{ $s->id }}" onclick="confirmDelete(this.id)" href="#" class="dropdown-item"><i class="icon-trash"></i> Delete</a>
                                                    <form method="post" id="item-delete-{{ $s->id }}" action="{{ route('subjects.destroy', $s->id) }}" class="hidden">@csrf @method('delete')</form>
                                                @endif

                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>                

            </div>
        </div>
    </div>

    {{--subject List Ends--}}

@endsection
