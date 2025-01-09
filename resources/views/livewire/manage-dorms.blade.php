<div>
    <div class="row justify-content-center">
        <div class="col-md-12">
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-1">
                @if (
                    !$isCreating &&
                        !$isEditing &&
                        !$isAssigningDormMaster &&
                        !$isViewingDormMasters &&
                        !$isAddingStudents &&
                        !$showOccupancyCard &&
                        !$showStudentsList)
                    <div>
                        <h1 class="font-weight-bold text-secondary mb-2">Manage Dorms</h1>
                        <p class="text-muted md-col-6">
                            Welcome to the Dorm Management section! Here, you can create, edit, and manage dormitories,
                            assign dorm masters, and track occupancy.
                            Use the buttons below to get started.
                        </p>
                    </div>
                @endif
                @if ($isCreating || $isEditing || $isAssigningDormMaster || $isViewingDormMasters)
                    {{-- <button class="btn btn-outline-secondary" wire:click="resetForm">
                        <i class="fas fa-arrow-left"></i>
                    </button> --}}
                @else
                    @if (
                        !$isAddingStudents &&
                            !$isAssigningDormMaster &&
                            !$isViewingDormMasters &&
                            !$isAddingStudents &&
                            !$showOccupancyCard &&
                            !$showStudentsList)
                        @if ($dorms->count() > 0)
                            <button class="btn btn-primary" wire:click="create">
                                <i class="fas fa-plus"></i> New
                            </button>
                        @endif
                    @endif

                @endif
            </div>

            <!-- Flash Messages -->
            @if (session()->has('message'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('message') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Dorms Table or No Dorms Message -->
            @if (
                !$isCreating &&
                    !$isEditing &&
                    !$isAssigningDormMaster &&
                    !$isViewingDormMasters &&
                    !$isAddingStudents &&
                    !$showOccupancyCard &&
                    !$showStudentsList)
                @if ($dorms->count() > 0)

                    <div>
                        <table class="table table-bordered" style="width: 100%; table-layout: auto;">
                            <thead class="thead-light">
                                <tr>
                                    <th>Name</th>
                                    <th>Capacity</th>
                                    <th>Occupancy ({{ date('Y') }})</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($dorms as $dorm)
                                    <tr>
                                        <td class="align-middle">{{ $dorm->name }}</td>
                                        <td class="align-middle">{{ $dorm->capacity }}</td>
                                        <td class="align-middle">
                                            <!-- Clickable link to view students for the current year -->
                                            <a href="#"
                                                wire:click="setSelectedDormIdAndViewStudents({{ $dorm->id }}, '{{ date('Y') }}')"
                                                class="btn btn-link font-weight-bold">
                                                {{ $this->getCurrentYearOccupancy($dorm->id) }} / {{ $dorm->capacity }}
                                            </a>
                                        </td>
                                        <td class="text-center">
                                            <div class="list-icons">
                                                <div class="dropdown">
                                                    <a href="#" class="list-icons-item" data-toggle="dropdown">
                                                        <i class="icon-menu9"></i>
                                                    </a>
                                                    <div class="dropdown-menu dropdown-menu-left">
                                                        <!-- Edit Button -->
                                                        <button wire:click="editDorm({{ $dorm->id }})"
                                                            class="dropdown-item btn btn-warning">
                                                            <i class="fas fa-edit"></i> Edit
                                                        </button>

                                                        <!-- Delete Button -->
                                                        <button wire:click="deleteDorm({{ $dorm->id }})"
                                                            class="dropdown-item btn btn-danger">
                                                            <i class="fas fa-trash"></i> Delete
                                                        </button>

                                                        <!-- Assign Dorm Master Button -->
                                                        <button wire:click="assignDormMaster({{ $dorm->id }})"
                                                            class="dropdown-item btn btn-info">
                                                            <i class="fas fa-user-plus"></i> Assign Master
                                                        </button>

                                                        <!-- View Dorm Masters Button -->
                                                        <button wire:click="viewDormMasters({{ $dorm->id }})"
                                                            class="dropdown-item btn btn-secondary">
                                                            <i class="fas fa-eye"></i> View Masters
                                                        </button>

                                                        <!-- Add Students Button -->
                                                        <button wire:click="addStudents({{ $dorm->id }})"
                                                            class="dropdown-item btn btn-success">
                                                            <i class="fas fa-user-plus"></i> Add Students
                                                        </button>

                                                        <!-- View Occupancy Button -->
                                                        <button wire:click="viewOccupancy({{ $dorm->id }})"
                                                            class="dropdown-item btn btn-primary">
                                                            <i class="fas fa-chart-line"></i> View Occupancy
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <!-- No Dorms Message -->
                    <div>
                        <div class="card-body">
                            <i class="fas fa-building fa-4x text-muted mb-4"></i>
                            <h3 class="h4 text-muted">No Dorms Found</h3>
                            <p class="text-muted">You haven't added any dorms yet. Click the button below to get
                                started.</p>
                            <button class="btn btn-primary" wire:click="create">
                                <i class="fas fa-plus"></i> Add Dorm
                            </button>
                        </div>
                    </div>
                @endif
            @endif

            @if ($showOccupancyCard && !$showStudentsList)
                <div class="mt-2">
                    <div class="d-flex justify-content-between align-items-center text-secondary display-3">
                        <h5 class="card-title mb-0">Occupancy Over the Years - {{ $dormName }}</h5>
                        <button wire:click="closeOccupancyCard" class="btn btn-sm btn-light float-right">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <div class="card-body">
                        {{-- <livewire:occupancy-chart :dormId="$selectedDormId" /> --}}

                        @if (count($occupancyData) > 0)
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Year</th>
                                        <th>Occupancy</th>
                                        <th>Progress</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($occupancyData as $data)
                                        @php
                                            $occupancyPercentage = ($data->occupancy / $this->dormCapacity) * 100;
                                        @endphp
                                        <tr>
                                            <td>{{ $data->year }}</td>
                                            <td>
                                                <a href="#" wire:click="viewStudents('{{ $data->year }}')"
                                                    class="btn btn-link font-weight-bold">
                                                    {{ $data->occupancy }} / {{ $this->dormCapacity }}
                                                </a>
                                            </td>
                                            <td>
                                                <div class="progress"
                                                    style="height: 20px; border-radius: 10px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);">
                                                    <div class="progress-bar 
                                                           @if ($occupancyPercentage >= 90) bg-danger
                                                           @elseif ($occupancyPercentage >= 50) bg-warning
                                                           @else bg-success @endif"
                                                        role="progressbar"
                                                        style="width: {{ $occupancyPercentage }}%; border-radius: 10px;"
                                                        aria-valuenow="{{ $data->occupancy }}" aria-valuemin="0"
                                                        aria-valuemax="{{ $this->dormCapacity }}">
                                                    </div>
                                                </div>
                                                <span
                                                    class="text-success font-weight-bold">{{ round($occupancyPercentage, 2) }}%</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <div class="text-center py-3">
                                <i class="fas fa-chart-line fa-3x text-muted mb-3"></i>
                                <h4 class="text-muted">No Occupancy Data Found</h4>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            @if ($showStudentsList)
                <div class="mt-2">
                    <div class="d-flex justify-content-between align-items-center text-secondary font-weight-bold">
                        <h5 class="card-title mb-0">Students in {{ $dormName }} ({{ $selectedYear }})</h5>
                        <button wire:click="closeStudentsList" class="btn btn-sm btn-light float-right">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <div class="card-body">
                        @if (count($studentsInDorm) > 0)
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>SN</th>
                                        <th>Name</th>
                                        <th>Class</th>
                                        <th>Section</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($studentsInDorm as $student)
                                        <tr>
                                            <td>{{ $loop->index + 1 }}</td>
                                            <td>{{ $student->first_name }} {{ $student->last_name }}</td>
                                            <td>{{ $student->class_name }}</td>
                                            <td>{{ $student->section_name }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <div class="text-center py-3">
                                <i class="fas fa-users-slash fa-3x text-muted mb-3"></i>
                                <h4 class="text-muted">No Students Found</h4>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Dorm Creation/Edit Form -->
            @if ($isCreating || $isEditing)
                <div>
                    <div class="card-body">
                        <h2 class="h4 mb-4">{{ $isEditing ? 'Edit Dorm' : 'Create Dorm' }}</h2>
                        <form wire:submit.prevent="saveDorm">
                            <!-- Name Field -->
                            <div class="form-group mb-3">
                                <label for="name" class="form-label">Name</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                    id="name" wire:model="name" placeholder="Enter dorm name">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Capacity Field -->
                            <div class="form-group mb-3">
                                <label for="capacity" class="form-label">Capacity</label>
                                <input type="number" class="form-control @error('capacity') is-invalid @enderror"
                                    id="capacity" wire:model="capacity" placeholder="Enter dorm capacity">
                                @error('capacity')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Description Field -->
                            <div class="form-group mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" id="description" wire:model="description"
                                    placeholder="Enter dorm description" rows="3"></textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="">
                                <!-- Submit Button -->
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> {{ $isEditing ? 'Update' : 'Save' }}
                                </button>
                                <button class="btn btn-danger" wire:click="resetForm">
                                    Cancel
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            @endif

            @if ($isAddingStudents)
                <div>
                    <div class="card-header  text-secondary display-1">
                        <h5 class="card-title mb-0">Add Students to Dorm</h5>
                    </div>
                    <div class="card-body">
                        <!-- Search and Filters -->
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <input type="text" wire:model.live="searchQuery" class="form-control"
                                    placeholder="Search students...">
                            </div>
                            <div class="col-md-4">
                                <select wire:model.live="selectedClass" class="form-control">
                                    <option value="">Select Class</option>
                                    @foreach ($classes as $class)
                                        <option value="{{ $class->id }}">{{ $class->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <select wire:model.live="selectedSection" class="form-control">
                                    <option value="">Select Section</option>
                                    @foreach ($sections as $section)
                                        <option value="{{ $section->id }}">{{ $section->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Students List -->
                        <div class="table-responsive" style="max-height: 500px; overflow-y: auto;">
                            @if ($loadedStudents->isEmpty())
                                <!-- No Results Message -->
                                <div class="text-center py-5">
                                    <i class="fas fa-users-slash fa-3x text-muted mb-3"></i>
                                    <h4 class="text-muted">No Students Found</h4>
                                    <p class="text-muted">Try adjusting your filters or search terms.</p>
                                </div>
                            @else
                                <!-- Students Table -->
                                <table class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th>
                                                <button wire:click="selectAll" class="btn btn-sm btn-primary">
                                                    Select All
                                                </button>
                                                <span wire:click="clearSelection" class="btn btn-link">
                                                    <i class="fas fa-times-circle"></i>
                                                </span>
                                            </th>
                                            <th>Admission No</th>
                                            <th>Name</th>
                                            <th>Class</th>
                                            <th>Section</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($loadedStudents as $student)
                                            <tr>
                                                <td>
                                                    <input type="checkbox" wire:model="selectedStudents"
                                                        value="{{ $student->id }}">
                                                </td>
                                                <td>{{ $student->adm_no }}</td>
                                                <td>{{ $student->first_name }} {{ $student->last_name }}</td>
                                                <td>{{ $student->my_class->name }}</td>
                                                <td>{{ $student->section->name }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @endif
                        </div>

                        <!-- Loading Indicator -->
                        @if ($isLoading)
                            <div class="text-center mt-3">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="sr-only">Loading...</span>
                                </div>
                            </div>
                        @endif

                        <!-- Load More Button -->
                        @if (!$loadedStudents->isEmpty() && $loadedStudents->count() < $totalStudents)
                            <div class="text-center mt-3">
                                <button wire:click="loadMore" class="btn btn-primary">
                                    Load More
                                </button>
                            </div>
                        @endif
                    </div>
                    <div class="card-footer">
                        <!-- Year Selection (Conditional) -->
                        @if (!empty($selectedStudents))
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <select wire:model="year" class="form-control">
                                        <option value="">Select Year</option>
                                        @foreach ($this->getYearsRange() as $year)
                                            <option value="{{ $year }}">{{ $year }}</option>
                                        @endforeach
                                    </select>
                                    @error('year')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        @endif

                        <!-- Action Buttons -->
                        <button wire:click="assignStudentsToDorm" class="btn btn-success">
                            <i class="fas fa-save"></i> Assign Selected Students
                        </button>
                        <button wire:click="cancelAddingStudents" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Cancel
                        </button>

                    </div>
                </div>
            @endif
            @if ($isAssigningDormMaster)
                <div>
                    <div class="card-body">
                        <h2 class="h4 mb-4">Assign Dorm Master</h2>
                        <form wire:submit.prevent="saveDormMaster">
                            <!-- Teacher Field -->
                            <div class="form-group mb-3">
                                <label for="teacherId" class="form-label">Teacher</label>
                                <select class="form-control @error('teacherId') is-invalid @enderror" id="teacherId"
                                    wire:model="teacherId">
                                    <option value="">Select a Teacher</option>
                                    @foreach ($teachers as $teacher)
                                        <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                                    @endforeach
                                </select>
                                @error('teacherId')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Session Field -->
                            <div class="form-group mb-3">
                                <label for="session" class="form-label">Session</label>
                                <select class="form-control @error('session') is-invalid @enderror" id="session"
                                    wire:model="session">
                                    <option value="">Select a Session</option>
                                    @foreach ($this->getYearsRange() as $year)
                                        <option value="{{ $year }}">{{ $year }}</option>
                                    @endforeach
                                </select>
                                @error('session')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Submit Button -->


                            <div class="">
                                <!-- Submit Button -->
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-user-plus"></i> Assign Dorm Master
                                </button>
                                <button class="btn btn-danger" wire:click="resetForm">
                                    Cancel
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            @endif


            <!-- View Dorm Masters Card -->
            @if ($isViewingDormMasters)
                @if ($dormMasters->count() > 0)
                    <div>
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-1">

                                <h2 class="h4 mb-4">Dorm Masters for {{ $dormName }}</h2>
                                <button class="btn btn-danger" wire:click="resetForm">
                                    <i class="fa fa-close"></i>
                                </button>
                            </div>
                            <table class="table table-hover">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Teacher name</th>
                                        <th>Code</th>
                                        <th>Session</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($dormMasters as $teacher)
                                        <tr>
                                            <td>{{ $teacher->name }}</td>
                                            <td>{{ $teacher->code }}</td>
                                            <td>{{ $teacher->pivot->session }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @else
                    <div class="p-5 text-center shadow-lg border rounded"
                        style="background: linear-gradient(135deg, #f8f9fa, #e9ecef);">
                        <div class="card-body">
                            <i class="fas fa-building fa-4x text-muted mb-4"></i>
                            <h3 class="h4 text-muted">Dorm masters not assigned</h3>
                            <p class="text-muted">You haven't added any dorm masters yet.</p>

                        </div>
                    </div>
                @endif
            @endif
        </div>
    </div>
</div>
@script
    <script>
        // Get the students list element
        const studentsList = document.querySelector('.table-responsive');

        if (studentsList) {
            // Add a scroll event listener to the students list
            studentsList.addEventListener('scroll', function() {
                // Check if the user has scrolled to the bottom of the list
                if (studentsList.scrollTop + studentsList.clientHeight >= studentsList.scrollHeight - 10) {
                    // Trigger the Livewire `loadMore` method
                    @this.loadMore();
                }
            });
        }

        const occupancyData = @json($occupancyData);

        // Calculate metrics
        const totalOccupancy = occupancyData.reduce((sum, data) => sum + data.occupancy, 0);
        const averageOccupancy = (totalOccupancy / occupancyData.length).toFixed(2);
        const maxOccupancy = Math.max(...occupancyData.map(data => data.occupancy));

        // Animate counters
        animateCounter('totalOccupancyCounter', totalOccupancy);
        animateCounter('averageOccupancyCounter', averageOccupancy);
        animateCounter('maxOccupancyCounter', maxOccupancy);

        function animateCounter(elementId, targetValue) {
            let current = 0;
            const increment = targetValue / 100;
            const counterElement = document.getElementById(elementId);

            const interval = setInterval(() => {
                current += increment;
                if (current >= targetValue) {
                    clearInterval(interval);
                    current = targetValue;
                }
                counterElement.textContent = Math.round(current);
            }, 10);
        }
    </script>
@endscript
