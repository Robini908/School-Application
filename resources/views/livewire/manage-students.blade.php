
    <div class="card mt-4 shadow-sm">
        <div class="card-body">
            <!-- Dashboard Overview -->
            <div class="container-fluid mt-4">

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

                <div >
                    <div class="report-generator">
                        <h3>Generate Multiple Reports</h3>
                        <div class="input-group">
                            <label for="startRow">Start Row:</label>
                            <input type="number" id="startRow" min="1" placeholder="Enter start row">
                        </div>
                        <div class="input-group">
                            <label for="endRow">End Row:</label>
                            <input type="number" id="endRow" min="1" placeholder="Enter end row">
                        </div>
                        <button id="generateReports">Generate Reports</button>
                    </div>
                    
                    <table id="studentTable"  class="display table-responsive table table-hover table-bordered">
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
                                        <img src="{{ asset($student->photo) }}" alt="Student Photo" class="img-thumbnail" style="width: 50px; height: 50px;">
                                    @else
                                        <span class="text-muted">No Image</span>
                                    @endif
                                </td>
                                <td>{{ $student->first_name }} {{ $student->last_name }}</td>
                                <td>{{ $student->gender }}</td>
                                <td>{{ $student->classname }}</td>
                                <td>{{ $student->sectionname }}</td>
                                <td>
                                    <span class="badge bg-{{ $student->status == 'Active' ? 'success' : 'warning' }}">{{ $student->status }}</span>
                                </td>
                                <td>{{ $student->parent_first_name }} {{ $student->parent_last_name }}</td>
                                <td>{{ $student->parent_phone_number }}</td>
                                <td>
                                    <div class="dropdown">
                                        <a href="#" class="btn btn-sm btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                            Actions
                                        </a>
                                        <ul class="dropdown-menu">
                                            <!-- Edit -->
                                            <li><a class="dropdown-item" href="#"><i class="bi bi-pencil"></i> Edit</a></li>
                                            <!-- Delete -->
                                            <li>
                                                <button class="dropdown-item" wire:click="deleteRecord({{ $student->id }})"><i class="bi bi-trash"></i> Delete</button>
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

    @push('scripts')
    <script>
        // Add your JavaScript code here if needed
    </script>
    @endpush

