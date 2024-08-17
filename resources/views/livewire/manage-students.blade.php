<!--<div class="card mt-4 shadow-sm" x-data="{ showModal: false, selectedStudentId: null }">-->
<div class="tab-pane fade show active container-fluid" id="manage-students" >
    <div class="card-body">
        <div class="card container-fluid m-2" style="width:98%;">
            <div class="row g-3 align-items-center m-1">
              <div class="col-auto">
                <label for="officeFilter" class="form-label">Form: </label>
              </div>
              <div class="col-auto">
                <select id="form" class="form-control p-1">
                    <option value="">Select Form...</option>
                    <option value="">All</option>
                    <option value="Form 1">Form 1</option>
                    <option value="Form 2">Form 2 </option>
                    <option value="Form 3">Form 3 </option>
                    <option value="Form 4">Form 4 </option>
                </select>
              </div> 
              <div class="col-auto">
                <label  class="col-form-label">Stream:</label>
              </div>
              <div class="col-auto">
                <select id="section" class="form-control p-1">
                    <option value="">Select Stream...</option>
                    <option value="">All</option>
                    <option value="Diamond">Diamond</option>
                    <option value="Silver">Silver</option>                        
                </select>
              </div>   
              <div class="col-auto">
                <label  class="col-form-label">Status:</label>
              </div>
              <div class="col-auto">
                <select id="status" class="form-control p-1">
                    <option value="">Select Status...</option>
                    <option value="">All</option>
                    <option value="verified">Verified</option>
                    <option value="unverified">Unverified</option>                        
                </select>
              </div>               
            </div>
            
            <div class="row g-3 align-items-center m-1">
                         
            </div>
        </div>
        <!-- Dashboard Overview -->
        <div class=" container-fluid m-auto m-1 p-1 mt-3">
            <!-- Display success and error messages -->
            @if (session()->has('message'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('message') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif

            @if (session()->has('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif

            <!-- Report Filter Component -->
           {{--   @livewire('report-filter')--}}

            <!-- Data Table -->
            
                <table id="studentTable" class="table table-responsive table-hover table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th>Admission</th>
                            <th>Student Photo</th>
                            <th>Name</th>
                            <th>Gender</th>
                            <th>Class</th>
                            <th>Section</th>
                            <th>Status</th>
                            <th>Parent Name</th>
                            <th>Parent Contact</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($mystudents as $student)
                        <tr>
                            <td>{{ $student->adm_no }}</td>
                            <td>
                                @if ($student->photo)
                                <img src="{{ asset($student->photo) }}" alt="Student Photo" class="img-thumbnail"
                                    style="width: 50px; height: 50px;">
                                @else
                                <span class="text-muted">No Image</span>
                                @endif
                            </td>
                            <!-- Make the student name clickable and redirect to student info page -->
                            <td>
                       
                                <a href="{{ route('student.info', ['id' => $student->id]) }}">
                                    {{ $student->first_name }} {{ $student->last_name }}
                                </a>
                            </td>
                            <td>{{ $student->gender }}</td>
                            <td>{{ $student->classname }}</td>
                            <td>{{ $student->sectionname }}</td>
                            <td>
                                <span class="badge bg-{{ $student->status == 'Active' ? 'success' : 'warning' }}">
                                    {{ $student->status }}
                                </span>
                            </td>
                            <td>{{ $student->parent_first_name }} {{ $student->parent_last_name }}</td>
                            <td>{{ $student->parent_phone_number }}</td>
                            <td>
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle"
                                        type="button" id="actionsDropdown{{ $student->id }}" data-bs-toggle="dropdown" aria-expanded="false">
                                        Actions
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="actionsDropdown{{ $student->id }}">
                                        <!-- Edit -->
                                        <li>
                                            <a class="dropdown-item" href="#">
                                                <i class="bi bi-pencil"></i> Edit
                                            </a>
                                        </li>
                                        <!-- Delete -->
                                        <li>
                                            <button class="dropdown-item" wire:click="deleteRecord({{ $student->id }})">
                                                <i class="bi bi-trash"></i> Delete
                                            </button>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
           
        </div>
    </div>
</div>
