@extends('layouts.master')
@section('page_title', 'Manage Subjects')
@section('content')


<div class="card">
    <div class="card-header header-elements-inline">
        <h6 class="card-title">Manage Subjects</h6>
        {!! Qs::getPanelOptions() !!}
    </div>


    <div class="tab-content">
        <div class="tab-pane show active fade" id="new-subject">
            <div class="row">
                @if (session('success'))
                <div style="color: green;">
                    {{ session('success') }}
                </div>
                @endif

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
                                                <a href="{{ route('subjects.edit', $s->id) }}" class="dropdown-item"><i
                                                        class="icon-pencil"></i> Edit</a>
                                                @endif
                                                {{--Delete--}}
                                                @if(Qs::userIsTeamSA())
                                                <a id="{{ $s->id }}" onclick="confirmDelete(this.id)" href="#"
                                                    class="dropdown-item"><i class="icon-trash"></i> Delete</a>
                                                <form method="post" id="item-delete-{{ $s->id }}"
                                                    action="{{ route('subjects.destroy', $s->id) }}" class="hidden">
                                                    @csrf
                                                    @method('delete')</form>
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


    <script>
    $(document).ready(function() {
        // Initialize Select2
        $('#category').select2({
            placeholder: 'Select Subject',
            width: '100%'
        });

        // Populate input fields based on selected option
        $('#category').on('change', function() {
            const selectedOption = $(this).find('option:selected');
            const subname = selectedOption.val().split(' (')[
                0]; // Use the option's value as the plain subject name
            const subcode = selectedOption.data('code');
            const subabbrev = selectedOption.data('abbrev');

            $('#subname').val(subname);
            $('#subcode').val(subcode);
            $('#subabbrev').val(subabbrev);
        });
    });
    </script>
    @endsection