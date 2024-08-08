<div class="tab-pane fade " id="admit-student">
    <div class="card container">
        {{--<form id="ajax-reg" method="post" enctype="multipart/form-data" class="wizard-form steps-validation"
            action="{{ route('students.store') }}" data-fouc> --}}
        <form  method="post" enctype="multipart/form-data" class="wizard-form steps-validation"
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
                                placeholder="F-Name" class="form-control" id="first_name">
                            <small class="form-text text-muted text-right"
                                id="count-first-name">0/255</small>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>Middle Name: <span class="text-danger">*</span></label>
                            <input value="{{ old('middle_name') }}" required type="text" name="middle_name"
                                placeholder="M-Name" class="form-control" id="middle_name">
                            <small class="form-text text-muted text-right"
                                id="count-middle-name">0/255</small>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>Last Name: <span class="text-danger">*</span></label>
                            <input value="{{ old('last_name') }}" required type="text" name="last_name"
                                placeholder="L-Name" class="form-control" id="last_name">
                            <small class="form-text text-muted text-right"
                                id="count-last-name">0/255</small>
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
                            <select class="custom-select form-control" id="gender" name="gender" required
                                data-fouc data-placeholder="Choose..">
                                <option value=""></option>
                                <option {{ (old('gender') == 'Male') ? 'selected' : '' }} value="Male">Male
                                </option>
                                <option {{ (old('gender') == 'Female') ? 'selected' : '' }} value="Female">
                                    Female
                                </option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Phone:</label>
                            <input value="{{ old('phone') }}" type="text" placeholder="Phone No." name="phone" class="form-control"
                                placeholder="" id="phone">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Date of Birth:</label>
                            <input name="dob" value="{{ old('dob') }}" type="text"
                                class="form-control date-pick" placeholder="Select Date..." id="dob">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="nal_id">Nationality: <span class="text-danger">*</span></label>
                            <select data-placeholder="Choose..." required name="nal_id" id="nal_id"
                                class="custom-select form-control">
                                <option value=""></option>
                                @foreach($nationals as $nal)
                                <option {{ (old('nal_id') == $nal->id ? 'selected' : '') }}
                                    value="{{ $nal->id }}">
                                    {{ $nal->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="state_id">County/State: <span class="text-danger">*</span></label>
                            <select onchange="getLGA(this.value)" required data-placeholder="Choose.."
                                class="custom-select form-control" name="state_id" id="state_id">
                                <option value=""></option>
                                @foreach($states as $st)
                                <option {{ (old('state_id') == $st->id ? 'selected' : '') }}
                                    value="{{ $st->id }}">
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
                            <input type="text" name="town" placeholder="town"
                                class="form-control"  id="town">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="bg_id">Blood Group:</label>
                            <select class="custom-select form-control" id="bg_id" name="bg_id" data-fouc
                                data-placeholder="Choose..">
                                <option value=""></option>
                                @foreach(App\Models\BloodGroup::all() as $bg)
                                <option {{ (old('bg_id') == $bg->id ? 'selected' : '') }}
                                    value="{{ $bg->id }}">
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
                            <span class="form-text text-muted">Accepted Images: jpeg, png. Max file size
                                2Mb</span>
                        </div>
                    </div>
                </div>
            </fieldset>


            <!-- Step 2: Student Data -->
            <h6 class="card-title">Student Data</h6>
            @csrf
            <fieldset>
                <div class="row">
                    <!-- First Row: Class, Section, Session -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="my_class_id">Class: <span class="text-danger">*</span></label>
                            <select onchange="getClassSections(this.value)" data-placeholder="Choose..."
                                required name="my_class_id" id="my_class_id" class="custom-select">
                                <option value=""></option>
                                @foreach($my_classes as $c)
                                <option {{ (old('my_class_id') == $c->id ? 'selected' : '') }}
                                    value="{{ $c->id }}">
                                    {{ $c->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="section_id">Section: <span class="text-danger">*</span></label>
                            <select data-placeholder="Select Class First" required name="section_id"
                                id="section_id" class="custom-select">
                                <option {{ (old('section_id')) ? 'selected' : '' }}
                                    value="{{ old('section_id') }}">
                                    {{ (old('section_id')) ? 'Selected' : '' }}
                                </option>
                            </select>
                        </div>
                    </div>
                    
                </div>

                <div class="row">
                    <!-- Second Row: Year Admitted, Dormitory, Dormitory Room No -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="year_admitted">Year Admitted: <span
                                    class="text-danger">*</span></label>
                            <select data-placeholder="Choose..." required name="year_admitted"
                                id="year_admitted" class="custom-select">
                                <option value=""></option>
                                @for($y = date('Y', strtotime('- 40 years')); $y <= date('Y'); $y++) <option
                                    {{ (old('year_admitted') == $y) ? 'selected' : '' }} value="{{ $y }}">
                                    {{ $y }}
                                    </option>
                                    @endfor
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="dorm_id">Dormitory: </label>
                            <select data-placeholder="Choose..." name="dorm_id" id="dorm_id"
                                class="custom-select">
                                <option value=""></option>
                                @foreach($dorms as $d)
                                <option {{ (old('dorm_id') == $d->id) ? 'selected' : '' }}
                                    value="{{ $d->id }}">
                                    {{ $d->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>                    
                </div>

                <div class="row">
                    <!-- Third Row: Sport House, Enter Number, Admission Number -->
                    <div class="form-group">
                        <label for="upi_number">UPI Number:</label>
                        <input type="text" name="upi_number" required placeholder="UPI Number"
                            class="form-control" id="upi_number" value="{{ old('upi_number') }}">
                    </div>
                    <div class="col-md-4">                        
                        <div class="form-group">
                            <label for="adm_no">Admission Number:</label>
                            <input type="text" name="adm_no" placeholder="Admission Number"
                                class="form-control"  id="adm_no">
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="kcpe_marks">KCPE Marks:</label>
                            <input type="number" name="kcpe_marks" placeholder="KCPE Marks"
                                class="form-control" id="kcpe_marks" value="{{ old('kcpe_marks') }}">
                        </div>
                       
                    </div>

                    <div class="col-md-4">
                        <div class="message-container">
                            <div class="alert alert-warning alert-dismissible fade show" role="alert"
                                id="suggestionAlert" style="display: none;">
                                <strong>Warning!</strong> <span id="suggestionText"></span>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert"
                                id="errorAlert" style="display: none;">
                                <strong>Error!</strong> <span id="errorText"></span>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="alert alert-success alert-dismissible fade show" role="alert"
                                id="successAlert" style="display: none;">
                                <strong>Success!</strong> <span id="successText"></span>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
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
                            <label for="id_number">Id Number:</label>
                            <input type="text" id="id_number" name="id_number" class="form-control"
                                value="{{ old('id_number') }}" required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="parent_first_name">Parent's First Name:</label>
                            <input type="text" id="parent_first_name" name="parent_first_name"
                                class="form-control" value="{{ old('parent_first_name') }}" required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="parent_middle_name">Parent's Middle Name:</label>
                            <input type="text" id="parent_middle_name" name="parent_middle_name"
                                class="form-control" value="{{ old('parent_middle_name') }}" required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="parent_last_name">Parent's Last Name:</label>
                            <input type="text" id="parent_last_name" name="parent_last_name"
                                class="form-control" value="{{ old('parent_last_name') }}" required>
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
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="parent_email">Parent's Password:</label>
                            <input type="password" id="parent_password" name="parent_password" class="form-control"
                                value="{{ old('parent_password') }}" required>
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
                            <div class="input-group">
                                <input type="password" name="password" id="password" class="form-control"
                                    required data-toggle="tooltip" data-placement="right"
                                    title="Use your admission number as the password. You can later change this in your profile.">
                                <div class="input-group-append">
                                    <span class="input-group-text" id="password-tooltip"
                                        style="cursor: pointer;" data-toggle="tooltip" data-placement="left"
                                        title="Use your admission number as the password. You can later change this in your profile.">
                                        <i class="fas fa-info-circle"></i>
                                    </span>
                                </div>
                            </div>
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
        </form>
    </div>
</div>
