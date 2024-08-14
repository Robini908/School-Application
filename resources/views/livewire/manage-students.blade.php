<div class="tab-pane fade show active container" id="manage-students" >
    <div class="card">
        <div class="card-body">
            <!-- Dashboard Overview -->
            <div class="container mt-4">
              
                @if (session()->has('message'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('message') }}                        
                    </div>
                @endif
            
                @if (session()->has('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

            <!-- Report Filter Component -->
            @livewire('report-filter')

            <!-- Data Table -->
            <div class="table-responsive mt-4">
                <table id="studentTable" class="table table-hover table-bordered">
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
</div>
