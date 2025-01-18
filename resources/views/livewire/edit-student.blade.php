<div>
    
    <!-- Success Message -->
    @if (session()->has('message'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('message') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Personal Details Section -->
    <div class="mb-5">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3>Personal Details</h3>
            <button wire:click="$toggle('editPersonalDetails')" class="btn btn-outline-primary btn-sm">
                {{ $editPersonalDetails ? 'Cancel' : 'Edit' }}
            </button>
        </div>
        <div class="p-4">
            @if ($editPersonalDetails)
                <form wire:submit.prevent="save">
                    <div class="row g-4">
                        <div class="col-md-3">
                            <label for="first_name" class="form-label">First Name</label>
                            <input type="text" wire:model="first_name" class="form-control select" id="first_name">
                            @error('first_name') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-md-3">
                            <label for="middle_name" class="form-label">Middle Name</label>
                            <input type="text" wire:model="middle_name" class="form-control select" id="middle_name">
                            @error('middle_name') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-md-3">
                            <label for="last_name" class="form-label">Last Name</label>
                            <input type="text" wire:model="last_name" class="form-control select" id="last_name">
                            @error('last_name') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-md-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" wire:model="email" class="form-control select" id="email">
                            @error('email') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-md-3">
                            <label for="gender" class="form-label">Gender</label>
                            <select wire:model="gender" class="form-select" id="gender">
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                            </select>
                            @error('gender') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-md-3">
                            <label for="phone" class="form-label">Phone</label>
                            <input type="text" wire:model="phone" class="form-control select" id="phone">
                            @error('phone') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-md-3">
                            <label for="dob" class="form-label">Date of Birth</label>
                            <input type="date" wire:model="dob" class="form-control select" id="dob">
                            @error('dob') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-md-3">
                            <label for="nationality" class="form-label">Nationality</label>
                            <select wire:model="nationality" class="form-select" id="nationality">
                                <option value="Kenyan">Kenyan</option>
                                <option value="Other">Other</option>
                            </select>
                            @error('nationality') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-md-3">
                            <label for="state" class="form-label">State</label>
                            <input type="text" wire:model="state" class="form-control select" id="state">
                            @error('state') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-md-3">
                            <label for="town" class="form-label">Town</label>
                            <input type="text" wire:model="town" class="form-control select" id="town">
                            @error('town') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-md-3">
                            <label for="bg_id" class="form-label">Blood Group</label>
                            <select wire:model="bg_id" class="form-select" id="bg_id">
                                <option value="">Select Blood Group</option>
                                @foreach ($bloodGroups as $group)
                                    <option value="{{ $group->id }}">{{ $group->name }}</option>
                                @endforeach
                            </select>
                            @error('bg_id') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>
                        
                        <div class="col-12 mt-4">
                            <button type="submit" class="btn btn-primary me-2">Save Personal Details</button>
                            <button type="button" wire:click="$toggle('editPersonalDetails')" class="btn btn-outline-secondary">Cancel</button>
                        </div>
                    </div>
                </form>
            @else
                <div class="row g-4">
                    <div class="col-md-3"><strong>First Name:</strong> {{ $first_name }}</div>
                    <div class="col-md-3"><strong>Middle Name:</strong> {{ $middle_name }}</div>
                    <div class="col-md-3"><strong>Last Name:</strong> {{ $last_name }}</div>
                    <div class="col-md-3"><strong>Email:</strong> {{ $email }}</div>
                    <div class="col-md-3"><strong>Gender:</strong> {{ $gender }}</div>
                    <div class="col-md-3"><strong>Phone:</strong> {{ $phone }}</div>
                    <div class="col-md-3"><strong>Date of Birth:</strong> {{ $dob }}</div>
                    <div class="col-md-3"><strong>Nationality:</strong> {{ $nationality }}</div>
                    <div class="col-md-3"><strong>State:</strong> {{ $state }}</div>
                    <div class="col-md-3"><strong>Town:</strong> {{ $town }}</div>
                    <div class="col-md-3"><strong>Blood Group:</strong> {{ $bg_id }}</div>
                    
                </div>
            @endif
        </div>
    </div>

    <!-- Academic Details Section -->
    <div class="mb-5">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3>Academic Details</h3>
            <button wire:click="$toggle('editAcademicDetails')" class="btn btn-outline-primary btn-sm">
                {{ $editAcademicDetails ? 'Cancel' : 'Edit' }}
            </button>
        </div>
        <div class="p-4">
            @if ($editAcademicDetails)
                <form wire:submit.prevent="save">
                    <div class="row g-4">
                        <div class="col-md-3">
                            <label for="my_class_id" class="form-label">Class</label>
                            <select wire:model.live="my_class_id" class="form-select" id="my_class_id">
                                <option value="">Select Class</option>
                                @foreach ($myClasses as $class)
                                    <option value="{{ $class->id }}">{{ $class->name }}</option>
                                @endforeach
                            </select>
                            @error('my_class_id') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-md-3">
                            <label for="section_id" class="form-label">Section</label>
                            <select wire:model="section_id.live" class="form-select" id="section_id">
                                <option value="">Select Section</option>
                                @foreach ($sections as $section)
                                    <option value="{{ $section->id }}">{{ $section->name }}</option>
                                @endforeach
                            </select>
                            @error('section_id') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-md-3">
                            <label for="year_admitted" class="form-label">Year Admitted</label>
                            <input type="number" wire:model="year_admitted" class="form-control select" id="year_admitted">
                            @error('year_admitted') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-md-3">
                            <label for="dorm_id" class="form-label">Dormitory</label>
                            <select wire:model="dorm_id" class="form-select" id="dorm_id">
                                <option value="">Select Dormitory</option>
                                @foreach ($dorms as $dorm)
                                    <option value="{{ $dorm->id }}">{{ $dorm->name }}</option>
                                @endforeach
                            </select>
                            @error('dorm_id') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-md-3">
                            <label for="upi_number" class="form-label">UPI Number</label>
                            <input type="text" wire:model="upi_number" class="form-control select" id="upi_number">
                            @error('upi_number') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-md-3">
                            <label for="adm_no" class="form-label">Admission Number</label>
                            <input type="text" wire:model="adm_no" class="form-control select" id="adm_no">
                            @error('adm_no') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-md-3">
                            <label for="kcpe" class="form-label">KCPE Marks</label>
                            <input type="number" wire:model="kcpe" class="form-control select" id="kcpe">
                            @error('kcpe') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-12 mt-4">
                            <button type="submit" class="btn btn-primary me-2">Save Academic Details</button>
                            <button type="button" wire:click="$toggle('editAcademicDetails')" class="btn btn-outline-secondary">Cancel</button>
                        </div>
                    </div>
                </form>
            @else
                <div class="row g-4">
                    <div class="col-md-3"><strong>Class:</strong> {{ $student->myClass->name ?? 'N/A' }}</div>
                    <div class="col-md-3"><strong>Section:</strong> {{ $student->section->name ?? 'N/A' }}</div>
                    <div class="col-md-3"><strong>Year Admitted:</strong> {{ $year_admitted }}</div>
                    <div class="col-md-3"><strong>Dormitory:</strong> {{ $student->dorm->name ?? 'N/A' }}</div>
                    <div class="col-md-3"><strong>UPI Number:</strong> {{ $upi_number }}</div>
                    <div class="col-md-3"><strong>Admission Number:</strong> {{ $adm_no }}</div>
                    <div class="col-md-3"><strong>KCPE Marks:</strong> {{ $kcpe }}</div>
                </div>
            @endif
        </div>
    </div>

    <!-- Parent Details Section -->
    <div class="mb-5">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3>Parent Details</h3>
            <button wire:click="$toggle('editParentDetails')" class="btn btn-outline-primary btn-sm">
                {{ $editParentDetails ? 'Cancel' : 'Edit' }}
            </button>
        </div>
        <div class="p-4">
            @if ($editParentDetails)
                <form wire:submit.prevent="save">
                    <div class="row g-4">
                        <!-- Existing Parent Details Fields -->
                        <div class="col-md-3">
                            <label for="parent_id_no" class="form-label">Parent ID Number</label>
                            <input type="text" wire:model="parent_id_no" class="form-control select" id="parent_id_no">
                            @error('parent_id_no') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-md-3">
                            <label for="parent_first_name" class="form-label">First Name</label>
                            <input type="text" wire:model="parent_first_name" class="form-control select" id="parent_first_name">
                            @error('parent_first_name') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-md-3">
                            <label for="parent_middle_name" class="form-label">Middle Name</label>
                            <input type="text" wire:model="parent_middle_name" class="form-control select" id="parent_middle_name">
                            @error('parent_middle_name') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-md-3">
                            <label for="parent_last_name" class="form-label">Last Name</label>
                            <input type="text" wire:model="parent_last_name" class="form-control select" id="parent_last_name">
                            @error('parent_last_name') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-md-3">
                            <label for="parent_phone" class="form-label">Phone</label>
                            <input type="text" wire:model="parent_phone" class="form-control select" id="parent_phone">
                            @error('parent_phone') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-md-3">
                            <label for="parent_email" class="form-label">Email</label>
                            <input type="email" wire:model="parent_email" class="form-control select" id="parent_email">
                            @error('parent_email') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>
    
                        <!-- Password Update Section -->
                        <div class="col-12 mt-4">
                            <h5>Change Password</h5>
                            <div class="row g-4">
                                @if ($isAdmin)
                                    <!-- Admin: Only show new password and confirmation -->
                                    <div class="col-md-6">
                                        <label for="parent_new_password" class="form-label">New Password</label>
                                        <input type="password" wire:model="parent_new_password" class="form-control" id="parent_new_password">
                                        @error('parent_new_password') <span class="text-danger small">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label for="parent_new_password_confirmation" class="form-label">Confirm New Password</label>
                                        <input type="password" wire:model="parent_new_password_confirmation" class="form-control" id="parent_new_password_confirmation">
                                        @error('parent_new_password_confirmation') <span class="text-danger small">{{ $message }}</span> @enderror
                                    </div>
                                @else
                                    <!-- Parent: Show old password, new password, and confirmation -->
                                    <div class="col-md-4">
                                        <label for="parent_old_password" class="form-label">Old Password</label>
                                        <input type="password" wire:model="parent_old_password" class="form-control" id="parent_old_password">
                                        @error('parent_old_password') <span class="text-danger small">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="col-md-4">
                                        <label for="parent_new_password" class="form-label">New Password</label>
                                        <input type="password" wire:model="parent_new_password" class="form-control" id="parent_new_password">
                                        @error('parent_new_password') <span class="text-danger small">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="col-md-4">
                                        <label for="parent_new_password_confirmation" class="form-label">Confirm New Password</label>
                                        <input type="password" wire:model="parent_new_password_confirmation" class="form-control" id="parent_new_password_confirmation">
                                        @error('parent_new_password_confirmation') <span class="text-danger small">{{ $message }}</span> @enderror
                                    </div>
                                @endif
                            </div>
                        </div>
    
                        <!-- Save Button -->
                        <div class="col-12 mt-4">
                            <button type="submit" class="btn btn-primary me-2">Save Parent Details</button>
                            <button type="button" wire:click="$toggle('editParentDetails')" class="btn btn-outline-secondary">Cancel</button>
                        </div>
                    </div>
                </form>
            @else
                <div class="row g-4">
                    <div class="col-md-3"><strong>Parent ID Number:</strong> {{ $parent_id_no }}</div>
                    <div class="col-md-3"><strong>First Name:</strong> {{ $parent_first_name }}</div>
                    <div class="col-md-3"><strong>Middle Name:</strong> {{ $parent_middle_name }}</div>
                    <div class="col-md-3"><strong>Last Name:</strong> {{ $parent_last_name }}</div>
                    <div class="col-md-3"><strong>Phone:</strong> {{ $parent_phone }}</div>
                    <div class="col-md-3"><strong>Email:</strong> {{ $parent_email }}</div>
                </div>
            @endif
        </div>
    </div>

    <!-- Password Section -->
    <div class="mb-5">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3>Password</h3>
            <button wire:click="$toggle('editPassword')" class="btn btn-outline-primary btn-sm">
                {{ $editPassword ? 'Cancel' : 'Edit' }}
            </button>
        </div>
        <div class="p-4">
            @if ($editPassword)
                <form wire:submit.prevent="savePassword">
                    <div class="row g-4">
                        <!-- Old Password Field (Only for Students) -->
                        @if ($userType === 'student')
                            <div class="col-md-3">
                                <label for="old_password" class="form-label">Old Password</label>
                                <input type="password" wire:model="old_password" class="form-control select" id="old_password" placeholder="Enter old password">
                                @error('old_password') <span class="text-danger small">{{ $message }}</span> @enderror
                            </div>
                        @endif
    
                        <!-- New Password Field -->
                        <div class="col-md-3">
                            <label for="password" class="form-label">New Password</label>
                            <input type="password" wire:model="password" class="form-control select" id="password" placeholder="Enter new password">
                            @error('password') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>
    
                        <!-- Confirm New Password Field -->
                        <div class="col-md-3">
                            <label for="password_confirmation" class="form-label">Confirm Password</label>
                            <input type="password" wire:model="password_confirmation" class="form-control select" id="password_confirmation" placeholder="Confirm new password">
                            @error('password_confirmation') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>
    
                        <!-- Buttons -->
                        <div class="col-12 mt-4">
                            <button type="submit" class="btn btn-primary me-2">Save Password</button>
                            <button type="button" wire:click="$toggle('editPassword')" class="btn btn-outline-secondary">Cancel</button>
                        </div>
                    </div>
                </form>
            @else
                <p class="mb-0">Password is hidden for security reasons. Click "Edit" to update the password.</p>
            @endif
        </div>
    </div>
</div>