@extends('layouts.master')
@section('page_title', 'Admit Student')
@section('content')
<link href="{{ asset('assets/css/admit_student.css') }}" rel="stylesheet" type="text/css">
<div class="card">
    <div class="card-header bg-white header-elements-inline">
        <h6 class="card-title">Please fill the form below to admit a new student</h6>
        {!! Qs::getPanelOptions() !!}
    </div>

    <form id="ajax-reg" method="post" enctype="multipart/form-data" class="wizard-form steps-validation"
        action="{{ route('students.store') }}" data-fouc>
        @csrf

        <!-- Step 1: Personal Data -->
        <h6>Personal Data</h6>
        <fieldset>
            <div class="row">
                <div class="col-md-2">
                    <div class="form-group">
                        <label>First Name: <span class="text-danger">*</span></label>
                        <input value="{{ old('first_name') }}" required type="text" name="first_name"
                            placeholder="First Name" class="form-control" id="first_name">
                        <small class="form-text text-muted text-right" id="count-first-name">0/255</small>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label>Middle Name: <span class="text-danger">*</span></label>
                        <input value="{{ old('middle_name') }}" required type="text" name="middle_name"
                            placeholder="Middle Name" class="form-control" id="middle_name">
                        <small class="form-text text-muted text-right" id="count-middle-name">0/255</small>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label>Last Name: <span class="text-danger">*</span></label>
                        <input value="{{ old('last_name') }}" required type="text" name="last_name"
                            placeholder="Last Name" class="form-control" id="last_name">
                        <small class="form-text text-muted text-right" id="count-last-name">0/255</small>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Email address:</label>
                        <input type="email" value="{{ old('email') }}" name="email" class="form-control"
                            placeholder="Email Address" id="email">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="gender">Gender: <span class="text-danger">*</span></label>
                        <select class="custom-select form-control" id="gender" name="gender" required data-fouc
                            data-placeholder="Choose..">
                            <option value=""></option>
                            <option {{ (old('gender') == 'Male') ? 'selected' : '' }} value="Male">Male</option>
                            <option {{ (old('gender') == 'Female') ? 'selected' : '' }} value="Female">Female</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Phone:</label>
                        <input value="{{ old('phone') }}" type="text" name="phone" class="form-control" placeholder=""
                            id="phone">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Date of Birth:</label>
                        <input name="dob" value="{{ old('dob') }}" type="text" class="form-control date-pick"
                            placeholder="Select Date..." id="dob">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="nal_id">Nationality: <span class="text-danger">*</span></label>
                        <select data-placeholder="Choose..." required name="nal_id" id="nal_id"
                            class="custom-select form-control">
                            <option value=""></option>
                            @foreach($nationals as $nal)
                            <option {{ (old('nal_id') == $nal->id ? 'selected' : '') }} value="{{ $nal->id }}">
                                {{ $nal->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="state_id">County: <span class="text-danger">*</span></label>
                        <select onchange="getLGA(this.value)" required data-placeholder="Choose.."
                            class="custom-select form-control" name="state_id" id="state_id">
                            <option value=""></option>
                            @foreach($states as $st)
                            <option {{ (old('state_id') == $st->id ? 'selected' : '') }} value="{{ $st->id }}">
                                {{ $st->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="lga_id">Town: <span class="text-danger">*</span></label>
                        <select required data-placeholder="Select State First" class="custom-select form-control"
                            name="lga_id" id="lga_id">
                            <option value=""></option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="bg_id">Blood Group:</label>
                        <select class="custom-select form-control" id="bg_id" name="bg_id" data-fouc
                            data-placeholder="Choose..">
                            <option value=""></option>
                            @foreach(App\Models\BloodGroup::all() as $bg)
                            <option {{ (old('bg_id') == $bg->id ? 'selected' : '') }} value="{{ $bg->id }}">
                                {{ $bg->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="d-block">Upload Passport Photo:</label>
                        <input value="{{ old('photo') }}" accept="image/*" type="file" name="photo"
                            class="form-input-styled" data-fouc>
                        <span class="form-text text-muted">Accepted Images: jpeg, png. Max file size 2Mb</span>
                    </div>
                </div>
            </div>
        </fieldset>


        <!-- Step 2: Student Data -->
        <h6>Student Data</h6>
        <fieldset>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="my_class_id">Class: <span class="text-danger">*</span></label>
                        <select onchange="getClassSections(this.value)" data-placeholder="Choose..." required
                            name="my_class_id" id="my_class_id" class="custom-select">
                            <option value=""></option>
                            @foreach($my_classes as $c)
                            <option {{ (old('my_class_id') == $c->id ? 'selected' : '') }} value="{{ $c->id }}">
                                {{ $c->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="section_id">Stream: <span class="text-danger">*</span></label>
                        <select data-placeholder="Select Class First" required name="section_id" id="section_id"
                            class="custom-select">
                            <option {{ (old('section_id')) ? 'selected' : '' }} value="{{ old('section_id') }}">
                                {{ (old('section_id')) ? 'Selected' : '' }}
                            </option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="year_admitted">Year Admitted: <span class="text-danger">*</span></label>
                        <select data-placeholder="Choose..." required name="year_admitted" id="year_admitted"
                            class="custom-select">
                            <option value=""></option>
                            @for($y = date('Y', strtotime('- 10 years')); $y <= date('Y'); $y++) <option
                                {{ (old('year_admitted') == $y) ? 'selected' : '' }} value="{{ $y }}">{{ $y }}</option>
                                @endfor
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="dorm_id">Dormitory: </label>
                        <select data-placeholder="Choose..." name="dorm_id" id="dorm_id" class="custom-select">
                            <option value=""></option>
                            @foreach($dorms as $d)
                            <option {{ (old('dorm_id') == $d->id) ? 'selected' : '' }} value="{{ $d->id }}">
                                {{ $d->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="dorm_room_no">Dormitory Room No:</label>
                        <input type="text" name="dorm_room_no" placeholder="Dormitory Room No" class="form-control"
                            value="{{ old('dorm_room_no') }}">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="house">Sport House:</label>
                        <input type="text" name="house" placeholder="Sport House" class="form-control"
                            value="{{ old('house') }}">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="adm_no">Admission Number:</label>
                        <input type="text" name="adm_no" placeholder="Admission Number" class="form-control" required
                            value="{{ old('adm_no') }}" id="adm_no">
                    </div>
                </div>
            </div>
        </fieldset>

        <!-- Step 3: Parent Details -->
        <h6>Parent Details</h6>
        <fieldset>
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="parent_first_name">Parent's First Name:</label>
                        <input type="text" id="parent_first_name" name="parent_first_name" class="form-control"
                            value="{{ old('parent_first_name') }}" required>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="parent_middle_name">Parent's Middle Name:</label>
                        <input type="text" id="parent_middle_name" name="parent_middle_name" class="form-control"
                            value="{{ old('parent_middle_name') }}" required>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="parent_last_name">Parent's Last Name:</label>
                        <input type="text" id="parent_last_name" name="parent_last_name" class="form-control"
                            value="{{ old('parent_last_name') }}" required>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="nin">National Identification Number:</label>
                        <input type="text" id="nin" name="nin" class="form-control" value="{{ old('nin') }}" required>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="parent_phone">Parent's Phone Number:</label>
                        <input type="text" id="parent_phone" name="parent_phone" class="form-control"
                            value="{{ old('parent_phone') }}" required>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="parent_email">Parent's Email:</label>
                        <input type="email" id="parent_email" name="parent_email" class="form-control"
                            value="{{ old('parent_email') }}" required>
                    </div>
                </div>
            </div>
        </fieldset>

        <h6>Password</h6>
        <fieldset>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Password: <span class="text-danger">*</span></label>
                        <input type="password" name="password" id="password" class="form-control" required>
                        <small class="form-text text-muted">Password strength: <span
                                id="password-strength"></span></small>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Confirm Password: <span class="text-danger">*</span></label>
                        <input type="password" name="password_confirmation" id="password_confirmation"
                            class="form-control" required>
                        <small class="form-text text-muted" id="password-match"></small>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="show-passwords">
                        <label class="form-check-label" for="show-passwords">Show Passwords</label>
                    </div>
                </div>
            </div>
        </fieldset>

        <!-- Step 4: Password -->

        <!-- Submit Button -->
    </form>
</div>
@endsection




@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const passwordInput = document.getElementById('password');
    const passwordConfirmationInput = document.getElementById('password_confirmation');
    const passwordStrengthDisplay = document.getElementById('password-strength');
    const passwordMatchDisplay = document.getElementById('password-match');
    const showPasswordsCheckbox = document.getElementById('show-passwords');

    passwordInput.addEventListener('input', function() {
        const password = passwordInput.value;
        const result = zxcvbn(password);

        let strengthText;
        switch (result.score) {
            case 0:
                strengthText = 'Very Weak';
                passwordStrengthDisplay.className = 'text-danger';
                break;
            case 1:
                strengthText = 'Weak';
                passwordStrengthDisplay.className = 'text-danger';
                break;
            case 2:
                strengthText = 'Fair';
                passwordStrengthDisplay.className = 'text-warning';
                break;
            case 3:
                strengthText = 'Strong';
                passwordStrengthDisplay.className = 'text-success';
                break;
            case 4:
                strengthText = 'Very Strong';
                passwordStrengthDisplay.className = 'text-success';
                break;
            default:
                strengthText = 'Unknown';
                passwordStrengthDisplay.className = 'text-muted';
        }

        passwordStrengthDisplay.textContent = `${strengthText} (${result.score}/4)`;
    });

    passwordInput.addEventListener('input', validatePasswords);
    passwordConfirmationInput.addEventListener('input', validatePasswords);

    showPasswordsCheckbox.addEventListener('change', function() {
        const type = showPasswordsCheckbox.checked ? 'text' : 'password';
        passwordInput.type = type;
        passwordConfirmationInput.type = type;
    });

    function validatePasswords() {
        if (passwordInput.value === passwordConfirmationInput.value) {
            passwordConfirmationInput.classList.remove('is-invalid');
            passwordConfirmationInput.classList.add('is-valid');
            passwordMatchDisplay.textContent = 'Passwords match';
            passwordMatchDisplay.classList.remove('text-danger');
            passwordMatchDisplay.classList.add('text-success');
        } else {
            passwordConfirmationInput.classList.remove('is-valid');
            passwordConfirmationInput.classList.add('is-invalid');
            passwordMatchDisplay.textContent = 'Passwords do not match';
            passwordMatchDisplay.classList.remove('text-success');
            passwordMatchDisplay.classList.add('text-danger');
        }
    }
});


document.addEventListener('DOMContentLoaded', function() {
    const formInputs = document.querySelectorAll('.form-control');
    const dropdowns = document.querySelectorAll('.select-search');

    // Function to handle input events for form inputs
    const handleInput = function() {
        const errorMessage = this.parentNode.querySelector('.invalid-feedback');
        if (this.value.trim() === '') {
            errorMessage.style.display = 'block'; // Show error message
        } else {
            errorMessage.style.display = 'none'; // Hide error message
        }
    };

    // Add event listeners for form inputs
    formInputs.forEach(input => {
        input.addEventListener('input', handleInput);
    });

    // Add event listeners for dropdowns
    dropdowns.forEach(dropdown => {
        dropdown.addEventListener('change', handleInput);
    });
});


$(document).ready(function() {
    // Real-time character counter for text inputs
    $('#name, #address').on('input', function() {
        const maxLength = 255;
        const length = $(this).val().length;
        $(this).next('small').text(`${length}/${maxLength}`);
    });

    // Real-time email validation
    $('#email').on('input', function() {
        const emailPattern = /^[a-zA-Z0-9_.+-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-.]+$/;
        if (!emailPattern.test($(this).val())) {
            $(this).addClass('is-invalid');
        } else {
            $(this).removeClass('is-invalid');
        }
    });

    // Real-time phone validation
    $('#phone, #phone2').on('input', function() {
        const phonePattern = /^[0-9]{10,15}$/;
        if (!phonePattern.test($(this).val())) {
            $(this).addClass('is-invalid');
        } else {
            $(this).removeClass('is-invalid');
        }
    });

    // Date of Birth Picker
    $('.date-pick').datepicker({
        autoclose: true,
        format: 'yyyy-mm-dd'
    });

    // Password strength checker

    // Fetch sections based on class selection
    $('#my_class_id').change(function() {
        const classId = $(this).val();
        if (classId) {
            $.ajax({
                url: '/get-sections/' + classId,
                type: 'GET',
                success: function(data) {
                    $('#section_id').html(data);
                }
            });
        }
    });


});

$(document).ready(function() {
    // Example: Enhance select dropdown with select2 library
    $('.custom-select').select2({
        theme: 'bootstrap4', // Adjust theme as per your setup
        placeholder: 'Select an option',
        width: '100%', // Adjust width as needed
        allowClear: true, // Option to clear selection
    });

    // Example: Handle dynamic changes or events
    $('#my_class_id').change(function() {
        var classId = $(this).val();
        // Perform actions based on selected classId, such as fetching related data for section_id dropdown
        // Example AJAX call or other logic here
    });
});
</script>
@endsection