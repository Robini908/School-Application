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
            <li class="nav-item"><a href="#subs" class="nav-link" data-toggle="tab">Manage Subject</a></li>
        </ul>

        <div class="tab-content">
            <div class="tab-pane show active fade" id="new-subject">
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
                                <label for="category" class="col-lg-3 col-form-label font-weight-semibold">Select
                                    Subject <span class="text-danger">*</span></label>
                                <div class="col-lg-9">
                                    <select id="category" class="form-control select2" required>
                                        <option value="">Select Subject</option>
                                        <optgroup label="Core Subjects (Compulsory)">
                                            <option value="English" data-abbrev="ENG" data-code="101">English</option>
                                            <option value="Kiswahili" data-abbrev="KIS" data-code="102">Kiswahili
                                            </option>
                                            <option value="Kenya Sign Language" data-abbrev="KSL" data-code="504">Kenya
                                                Sign Language</option>
                                            <option value="Mathematics (121)" data-abbrev="MAT" data-code="121">
                                                Mathematics (121)</option>
                                            <option value="Mathematics (122)" data-abbrev="MAT" data-code="122">
                                                Mathematics (122)</option>
                                        </optgroup>
                                        <optgroup label="Sciences">
                                            <option value="Biology" data-abbrev="BIO" data-code="231">Biology</option>
                                            <option value="Physics" data-abbrev="PHY" data-code="232">Physics</option>
                                            <option value="Chemistry" data-abbrev="CHE" data-code="233">Chemistry
                                            </option>
                                            <option value="Biology for the Blind" data-abbrev="BIO-B" data-code="236">
                                                Biology for the Blind</option>
                                            <option value="General Science" data-abbrev="GEN" data-code="237">General
                                                Science</option>
                                        </optgroup>
                                        <optgroup label="Humanities">
                                            <option value="Geography" data-abbrev="GEO" data-code="312">Geography
                                            </option>
                                            <option value="History and Government" data-abbrev="HIS" data-code="311">
                                                History and Government</option>
                                            <option value="Christian Religious Education" data-abbrev="CRE" data-code="313">Christian Religious Education</option>
                                            <option value="Islamic Religious Education" data-abbrev="IRE" data-code="314">Islamic Religious Education</option>
                                            <option value="Hindu Religious Education" data-abbrev="HRE" data-code="315">
                                                Hindu Religious Education</option>
                                        </optgroup>
                                        <optgroup label="Applied Technical Subjects">
                                            <!-- Add options for Applied Technical Subjects here -->
                                            <!-- Example: <option value="Applied Technical Subject" data-abbrev="XXX" data-code="XXX">Applied Technical Subject</option> -->
                                        </optgroup>
                                        <optgroup label="Languages and Others">
                                            <option value="Business Studies" data-abbrev="BUS" data-code="565">Business
                                                Studies</option>
                                            <option value="French" data-abbrev="FRE" data-code="501">French</option>
                                            <option value="German" data-abbrev="GER" data-code="502">German</option>
                                            <option value="Arabic" data-abbrev="ARA" data-code="503">Arabic</option>
                                            <option value="Kenya Sign Language" data-abbrev="KSL" data-code="504">Kenya
                                                Sign Language</option>
                                            <option value="Music" data-abbrev="MUS" data-code="511">Music</option>
                                        </optgroup>
                                    </select>
                                    @error('category')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="subname" class="col-lg-3 col-form-label font-weight-semibold">Subject Name
                                    <span class="text-danger">*</span></label>
                                <div class="col-lg-9">
                                    <input id="subname" name="subname" value="{{ old('subname') }}" required type="text" class="form-control" placeholder="Name of subject">
                                    @error('subname')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="subcode" class="col-lg-3 col-form-label font-weight-semibold">Subject Code
                                    <span class="text-danger">*</span></label>
                                <div class="col-lg-9">
                                    <input id="subcode" required name="subcode" value="{{ old('subcode') }}" type="text" class="form-control" placeholder="Eg. 232">
                                    @error('subcode')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="subabbrev" class="col-lg-3 col-form-label font-weight-semibold">Subject
                                    Abbreviation <span class="text-danger">*</span></label>
                                <div class="col-lg-9">
                                    <input id="subabbrev" required name="subabbrev" value="{{ old('subabbrev') }}" type="text" class="form-control" placeholder="PHY">
                                    @error('subabbrev')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                    @enderror
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
                                            <form method="post" id="item-delete-{{ $s->id }}" action="{{ route('subjects.destroy', $s->id) }}" class="hidden">@csrf
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