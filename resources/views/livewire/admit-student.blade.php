<div class="card mt-4 col-12 p-3 shadow-lg border rounded" style="background: linear-gradient(135deg, #f8f9fa, #e9ecef);">
    <form wire:submit.prevent="submit" class="wizard-form steps-validation" enctype="multipart/form-data">
        <!-- Step 1: Personal Data -->
        <div x-show="$wire.currentStep === 1">
            <h6>Personal Data</h6>
            <div class="row">
                <!-- First Name -->
                <div class="col-md-2">
                    <div class="form-group">
                        <label>First Name: <span class="text-danger">*</span></label>
                        <input wire:model="first_name" type="text" placeholder="F-Name" class="form-control">
                        @error('first_name')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <!-- Middle Name -->
                <div class="col-md-2">
                    <div class="form-group">
                        <label>Middle Name: <span class="text-danger">*</span></label>
                        <input wire:model="middle_name" type="text" placeholder="M-Name" class="form-control">
                        @error('middle_name')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <!-- Last Name -->
                <div class="col-md-2">
                    <div class="form-group">
                        <label>Last Name: <span class="text-danger">*</span></label>
                        <input wire:model="last_name" type="text" placeholder="L-Name" class="form-control">
                        @error('last_name')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <!-- Email -->
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Email address:</label>
                        <input wire:model="email" type="email" placeholder="Email Address" class="form-control">
                        @error('email')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <!-- Gender -->
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="gender">Gender: <span class="text-danger">*</span></label>
                        <select wire:model="gender" class="custom-select form-control" required>
                            <option value="">Choose..</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                        </select>
                        @error('gender')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Phone -->
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Phone:</label>
                        <input wire:model="phone" type="text" placeholder="Phone No." class="form-control">
                        @error('phone')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <!-- Date of Birth -->
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Date of Birth:</label>
                        <input wire:model="dob" type="date" class="form-control">
                        @error('dob')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <!-- Nationality -->
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="nationality">Nationality: <span class="text-danger">*</span></label>
                        <input wire:model="nationality" type="text" placeholder="Enter Nationality"
                            class="form-control" required>
                        @error('nationality')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <!-- County/State -->
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="state">County/State: <span class="text-danger">*</span></label>
                        <input wire:model="state" type="text" placeholder="Enter County/State" class="form-control"
                            required>
                        @error('state')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <!-- Town -->
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="town">Town: <span class="text-danger">*</span></label>
                        <input wire:model="town" type="text" placeholder="Enter Town" class="form-control" required>
                        @error('town')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <!-- Blood Group -->
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="bg_id">Blood Group:</label>
                        <select wire:model="bg_id" class="custom-select form-control">
                            <option value="">Choose..</option>
                            @foreach ($bloodGroups as $bg)
                                <option value="{{ $bg->id }}">{{ $bg->name }}</option>
                            @endforeach
                        </select>
                        @error('bg_id')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <!-- Passport Photo -->
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="d-block">Upload Passport Photo:</label>
                        <input wire:model="photo" type="file" class="form-control">
                        @error('photo')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>
            <button type="button" wire:click="nextStep" class="btn btn-primary">Next</button>
        </div>

        <!-- Step 2: Student Data -->
        <div x-show="$wire.currentStep === 2">
            <h6>Student Data</h6>
            <div class="row">
                <!-- Class -->
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="my_class_id">Class: <span class="text-danger">*</span></label>
                        <select wire:model.live="my_class_id" class="custom-select form-control" required>
                            <option value="">Choose..</option>
                            @foreach ($myClasses as $class)
                                <option value="{{ $class->id }}">{{ $class->name }}</option>
                            @endforeach
                        </select>
                        @error('my_class_id')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <!-- Section -->
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="section_id">Section: <span class="text-danger">*</span></label>
                        <select wire:model.live="section_id" class="custom-select form-control" required>
                            <option value="">Select Class First</option>
                            @foreach ($sections as $section)
                                <option value="{{ $section->id }}">{{ $section->name }}</option>
                            @endforeach
                        </select>
                        @error('section_id')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <!-- Year Admitted -->
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="year_admitted">Year Admitted: <span class="text-danger">*</span></label>
                        <select wire:model="year_admitted" class="custom-select form-control" required>
                            <option value="">Choose..</option>
                            @for ($y = date('Y', strtotime('- 40 years')); $y <= date('Y'); $y++)
                                <option value="{{ $y }}">{{ $y }}</option>
                            @endfor
                        </select>
                        @error('year_admitted')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Dormitory -->
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="dorm_id">Dormitory:</label>
                        <select wire:model="dorm_id" class="custom-select form-control">
                            <option value="">Choose..</option>
                            @foreach ($dorms as $dorm)
                                <option value="{{ $dorm->id }}">{{ $dorm->name }}</option>
                            @endforeach
                        </select>
                        @error('dorm_id')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <!-- UPI Number -->
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="upi_number">UPI Number: <span class="text-danger">*</span></label>
                        <input wire:model="upi_number" type="text" placeholder="UPI Number" class="form-control">
                        @error('upi_number')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <!-- Admission Number -->
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="adm_no">Admission Number:</label>
                        <input wire:model="adm_no" type="text" placeholder="Admission Number"
                            class="form-control">
                        @error('adm_no')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- KCPE Marks -->
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="kcpe">KCPE Marks:</label>
                        <input wire:model="kcpe" type="number" placeholder="KCPE Marks" class="form-control">
                        @error('kcpe')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>
            <button type="button" wire:click="previousStep" class="btn btn-secondary">Previous</button>
            <button type="button" wire:click="nextStep" class="btn btn-primary">Next</button>
        </div>

        <!-- Step 3: Parent Details -->
        <div x-show="$wire.currentStep === 3">
            <h6>Parent Details</h6>
            <div class="row">
                <!-- Parent ID Number -->
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="parent_id_no">National ID Number: <span class="text-danger">*</span></label>
                        <input wire:model="parent_id_no" type="text" class="form-control">
                        @error('parent_id_no')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <!-- Parent First Name -->
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="parent_first_name">First Name: <span class="text-danger">*</span></label>
                        <input wire:model="parent_first_name" type="text" class="form-control">
                        @error('parent_first_name')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <!-- Parent Middle Name -->
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="parent_middle_name">Middle Name:</label>
                        <input wire:model="parent_middle_name" type="text" class="form-control">
                        @error('parent_middle_name')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <!-- Parent Last Name -->
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="parent_last_name">Last Name: <span class="text-danger">*</span></label>
                        <input wire:model="parent_last_name" type="text" class="form-control">
                        @error('parent_last_name')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Parent Phone -->
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="parent_phone">Phone Number: <span class="text-danger">*</span></label>
                        <input wire:model="parent_phone" type="text" class="form-control">
                        @error('parent_phone')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <!-- Parent Email -->
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="parent_email">Email: <span class="text-danger">*</span></label>
                        <input wire:model="parent_email" type="email" class="form-control">
                        @error('parent_email')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <!-- Parent Password -->
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="parent_password">Password: <span class="text-danger">*</span></label>
                        <input wire:model="parent_password" type="password" class="form-control">
                        @error('parent_password')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>
            <button type="button" wire:click="previousStep" class="btn btn-secondary">Previous</button>
            <button type="button" wire:click="nextStep" class="btn btn-primary">Next</button>
        </div>

        <!-- Step 4: Password -->
        <div x-show="$wire.currentStep === 4">
            <h6>Password</h6>
            <div class="row">
                <!-- Password -->
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Password: <span class="text-danger">*</span></label>
                        <input wire:model="password" type="password" class="form-control">
                        @error('password')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <!-- Confirm Password -->
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Confirm Password: <span class="text-danger">*</span></label>
                        <input wire:model="password_confirmation" type="password" class="form-control">
                        @error('password_confirmation')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>
            <button type="button" wire:click="previousStep" class="btn btn-secondary">Previous</button>
            <button type="submit" class="btn btn-success">Submit</button>
        </div>
    </form>
</div>
