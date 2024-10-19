<div>
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
                                <td>{{ $ex['name'] }}</td>
                                <td>{{ 'Term '.$ex['term'] }}</td>
                                <td>{{ $ex['year'] }}</td>
                                <td class="text-center">
                                    <div class="list-icons">
                                        <div class="dropdown">
                                            <a href="#" class="list-icons-item" data-toggle="dropdown">
                                                <i class="icon-menu9"></i>
                                            </a>

                                            <div class="dropdown-menu dropdown-menu-left">
                                                @if(Qs::userIsTeamSA())
                                                {{-- Edit --}}
                                                <a href="#" wire:click="editExam({{ $ex['id'] }})" class="dropdown-item"><i class="icon-pencil"></i> Edit</a>
                                                @endif
                                                @if(Qs::userIsSuperAdmin())
                                                {{-- Delete --}}
                                                <a href="#" wire:click="deleteExam({{ $ex['id'] }})" class="dropdown-item"><i class="icon-trash"></i> Delete</a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <!-- Pagination links removed -->
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
                            <form wire:submit="{{ $editingExamId ? 'updateExam' : 'addExam' }}">
                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label font-weight-semibold">Name <span class="text-danger">*</span></label>
                                    <div class="col-lg-9">
                                        <input wire:model.live="name" type="text" class="form-control" placeholder="Name of Exam">
                                        @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="term" class="col-lg-3 col-form-label font-weight-semibold">Term</label>
                                    <div class="col-lg-9">
                                        <select wire:model.live="term" class="form-control select-search" id="term">
                                            <option value="1">First Term</option>
                                            <option value="2">Second Term</option>
                                            <option value="3">Third Term</option>
                                        </select>
                                        @error('term') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="grading_system_id" class="col-lg-3 col-form-label font-weight-semibold">Grading System</label>
                                    <div class="col-lg-9">
                                        <select wire:model.live="grading_system_id" class="form-control select-search w-100" id="grading_system_id">
                                            @foreach ($gradingSystems as $system)
                                            <option value="{{ $system->id }}">{{ $system->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('grading_system_id') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="classes" class="col-lg-3 col-form-label font-weight-semibold">Select Classes</label>
                                    <div class="col-lg-9">
                                        <select wire:model.live="selectedClasses" class="form-control select-search w-100" id="classes" multiple style="height: fit-content;">
                                            @foreach ($classes as $class_)
                                            <option value="{{ $class_->id }}">{{ $class_->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('selectedClasses') <span class="text-danger">{{ $message }}</span> @enderror
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
</div>
