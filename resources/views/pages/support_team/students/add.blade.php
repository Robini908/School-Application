@extends('layouts.master')
@section('page_title', 'Admit Student')
@section('content')

<link href=" {{ asset('assets/css/admit_student.css') }}" rel="stylesheet" type="text/css">
<div class="card">
    <div class="card-header bg-white header-elements-inline">
        <h6 class="card-title">Please fill the form below to admit a new student</h6>
        {!! Qs::getPanelOptions() !!}
    </div>

    <form id="ajax-reg" method="post" enctype="multipart/form-data" class="wizard-form steps-validation"
        action="{{ route('students.store') }}" data-fouc>
        @csrf
        <h6>Personal Data</h6>
        <fieldset>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Full Name: <span class="text-danger">*</span></label>
                        <input value="{{ old('name') }}" required type="text" name="name" placeholder="Full Name"
                            class="form-control" id="name">
                        <small class="form-text text-muted text-right" id="count-name">0/255</small>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label>Address: <span class="text-danger">*</span></label>
                        <input value="{{ old('address') }}" class="form-control" placeholder="Address" name="address"
                            type="text" required id="address">
                        <small class="form-text text-muted text-right" id="count-address">0/255</small>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Email address: </label>
                        <input type="email" value="{{ old('email') }}" name="email" class="form-control"
                            placeholder="Email Address" id="email">
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label for="gender">Gender: <span class="text-danger">*</span></label>
                        <select class="select form-control" id="gender" name="gender" required data-fouc
                            data-placeholder="Choose..">
                            <option value=""></option>
                            <option {{ (old('gender') == 'Male') ? 'selected' : '' }} value="Male">Male</option>
                            <option {{ (old('gender') == 'Female') ? 'selected' : '' }} value="Female">Female</option>
                        </select>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label>Phone:</label>
                        <input value="{{ old('phone') }}" type="text" name="phone" class="form-control" placeholder=""
                            id="phone">
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label>Telephone:</label>
                        <input value="{{ old('phone2') }}" type="text" name="phone2" class="form-control" placeholder=""
                            id="phone2">
                    </div>
                </div>
            </div>

            <div class="row">
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
                            class="select-search form-control">
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
                    <label for="state_id">County: <span class="text-danger">*</span></label>
                    <select onchange="getLGA(this.value)" required data-placeholder="Choose.."
                        class="select-search form-control" name="state_id" id="state_id">
                        <option value=""></option>
                        @foreach($states as $st)
                        <option {{ (old('state_id') == $st->id ? 'selected' : '') }} value="{{ $st->id }}">
                            {{ $st->name }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label for="lga_id">Town: <span class="text-danger">*</span></label>
                    {{-- <select required data-placeholder="Select State First" class="select-search form-control" name="lga_id" id="lga_id">
                        <option value=""></option>
                    </select>--}}
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="bg_id">Blood Group: </label>
                        <select class="select form-control" id="bg_id" name="bg_id" data-fouc
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

        <h6>Student Data</h6>
        <fieldset>
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="my_class_id">Class: <span class="text-danger">*</span></label>
                        <select onchange="getClassSections(this.value)" data-placeholder="Choose..." required
                            name="my_class_id" id="my_class_id" class="select-search form-control">
                            <option value=""></option>
                            @foreach($my_classes as $c)
                            <option {{ (old('my_class_id') == $c->id ? 'selected' : '') }} value="{{ $c->id }}">
                                {{ $c->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="section_id">Section: <span class="text-danger">*</span></label>
                        <select data-placeholder="Select Class First" required name="section_id" id="section_id"
                            class="select-search form-control">
                            <option {{ (old('section_id')) ? 'selected' : '' }} value="{{ old('section_id') }}">
                                {{ (old('section_id')) ? 'Selected' : '' }}
                            </option>
                        </select>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label for="my_parent_id">Parent: </label>
                        <select data-placeholder="Choose..." name="my_parent_id" id="my_parent_id"
                            class="select-search form-control">
                            <option value=""></option>
                            @foreach($parents as $p)
                            <option {{ (old('my_parent_id') == Qs::hash($p->id)) ? 'selected' : '' }}
                                value="{{ Qs::hash($p->id) }}">{{ $p->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label for="year_admitted">Year Admitted: <span class="text-danger">*</span></label>
                        <select data-placeholder="Choose..." required name="year_admitted" id="year_admitted"
                            class="select-search form-control">
                            <option value=""></option>
                            @for($y = date('Y', strtotime('- 10 years')); $y <= date('Y'); $y++) <option
                                {{ (old('year_admitted') == $y) ? 'selected' : '' }} value="{{ $y }}">{{ $y }}</option>
                                @endfor
                        </select>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-3">
                    <label for="dorm_id">Dormitory: </label>
                    <select data-placeholder="Choose..." name="dorm_id" id="dorm_id" class="select-search form-control">
                        <option value=""></option>
                        @foreach($dorms as $d)
                        <option {{ (old('dorm_id') == $d->id) ? 'selected' : '' }} value="{{ $d->id }}">{{ $d->name }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label>Dormitory Room No:</label>
                        <input type="text" name="dorm_room_no" placeholder="Dormitory Room No" class="form-control"
                            value="{{ old('dorm_room_no') }}">
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label>Sport House:</label>
                        <input type="text" name="house" placeholder="Sport House" class="form-control"
                            value="{{ old('house') }}">
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label>Admission Number:</label>
                        <input type="text" name="adm_no" placeholder="Admission Number" class="form-control" id="adm_no"
                            readonly>
                    </div>
                </div>
            </div>

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
                    </div>
                </div>
            </div>
        </fieldset>
    </form>
</div>
@endsection

@section('scripts')
<script>
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
    $('#password').on('input', function() {
        const strength = checkPasswordStrength($(this).val());
        $('#password-strength').text(strength);
    });

    $('#confirm_password').on('input', function() {
        const password = $('#password').val();
        const confirmPassword = $(this).val();
        if (password !== confirmPassword) {
            $(this).addClass('is-invalid');
        } else {
            $(this).removeClass('is-invalid');
        }
    });

    function checkPasswordStrength(password) {
        const strongPattern = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;
        if (strongPattern.test(password)) {
            return 'Strong';
        }
        return 'Weak';
    }

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

    // Fetch towns based on county selection
    $('#state_id').change(function() {
        const stateId = $(this).val();
        if (stateId) {
            $.ajax({
                url: '/get-towns/' + stateId,
                type: 'GET',
                success: function(data) {
                    $('#lga_id').html(data);
                }
            });
        }
    });

    // Function to generate admission number
    function generateAdmissionNumber() {
        let sequentialNumber = padDigits(getNextSequentialNumber(), 5);
        let admissionNumber = getSchoolCode() + '/' + getCurrentAdmissionYear() + '/' + sequentialNumber;
        return admissionNumber;
    }

    // Function to generate next sequential number
    function getNextSequentialNumber() {
        // You can implement your logic to fetch the latest sequential number from the database
        // For now, let's assume it's stored in a global variable
        if (typeof sequentialNumber === 'undefined') {
            sequentialNumber = 1;
        } else {
            sequentialNumber++;
        }
        return sequentialNumber;
    }

    // Function to pad digits with leading zeros
    function padDigits(number, digits) {
        return Array(Math.max(digits - String(number).length + 1, 0)).join(0) + number;
    }

    // Function to get the school code
    function getSchoolCode() {
        return 'SCC'; // Example school code, replace it with your actual logic
    }

    // Function to get the current admission year
    function getCurrentAdmissionYear() {
        return new Date().getFullYear();
    }

    // Set the generated admission number to the input field
    $('#adm_no').val(generateAdmissionNumber());
});
</script>
@endsection