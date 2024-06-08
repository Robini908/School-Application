@extends('layouts.master')
@section('page_title', 'Manage Grading System')
@section('content')

<div class="card">
    <div class="card-header header-elements-inline">
        <h6 class="card-title">Manage Grading</h6>
        {!! Qs::getPanelOptions() !!}
    </div>
    <div class="card-body">
        <ul class="nav nav-tabs nav-tabs-highlight">
            <li class="nav-item"><a href="#all-gradings" class="nav-link active" data-toggle="tab">Manage Grading</a></li>
            <li class="nav-item"><a href="#new-gradingsystem" class="nav-link" data-toggle="tab"><i class="icon-plus2"></i> Create New Grading</a></li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane fade show active" id="all-gradings">
                <div class="row">
                    @foreach ($gradingSystems as $grade)
                    <div class="col-md-6 mb-3">
                        <div class="card h-100">
                            <div class="card-body">
                                <h5 class="card-title d-inline-block mr-auto">{{ $grade->name }}</h5>
                                <div class="btn-group float-right">
                                    <a href="{{ route('grading_system.edit', $grade->id) }}" class="btn btn-primary">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <!-- Add other actions here as needed -->
                                </div>
                            </div>
                            <div class="card-body">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Ranges</th>
                                            <th>Grade</th>
                                            <th>Remark</th>
                                            <th>GPA</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if($grade->gradingRanges->count() > 0)
                                        @foreach($grade->gradingRanges as $range)
                                        <tr>
                                            <td>{{$range->range_from}} - {{$range->range_to}}</td>
                                            <td>{{ $range->grade }}</td>
                                            <td>{{ $range->remark }}</td>
                                            <td>{{ $range->gpa }}</td>
                                        </tr>
                                        @endforeach
                                        @else
                                        <tr>
                                            <td colspan="4">No ranges defined</td>
                                        </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="tab-pane fade" id="new-gradingsystem">

                <form action="{{ route('grading_system.store') }}" method="POST" id="grading_form">
                    @csrf
                    <div class="form-group">
                        <label for="name"><b>Grading Name:</b> </label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="table-responsive">
                        <table class="table" id="grading_table">
                            <thead>
                                <tr>
                                    <th>From</th>
                                    <th>To</th>
                                    <th>Grade</th>
                                    <th>Remark</th>
                                    <th>GPA</th>
                                </tr>
                            </thead>
                            <tbody id="grading_ranges">
                                <tr>
                                    <td>
                                        <input type="number" class="form-control range-from" name="range_from[]" required min="0" max="100">
                                        <div class="invalid-feedback"></div>
                                    </td>
                                    <td>
                                        <input type="number" class="form-control range-to" name="range_to[]" required min="0" max="100">
                                        <div class="invalid-feedback"></div>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control grade" name="grade[]" required>
                                        <div class="invalid-feedback"></div>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control remark" name="remark[]">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control gpa" name="gpa[]">
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <button type="button" id="add_more" class="btn btn-primary">Add More Ranges</button>
                    <button type="submit" class="btn btn-success">Submit</button>
                </form>
            </div>
        </div>
        <script>
            document.getElementById('add_more').addEventListener('click', function() {
                const tbody = document.getElementById('grading_ranges');
                const newRow = document.createElement('tr');
                newRow.innerHTML = `
                <td>
                    <input type="number" class="form-control range-from" name="range_from[]" required min="0" max="100">
                    <div class="invalid-feedback"></div>
                </td>
                <td>
                    <input type="number" class="form-control range-to" name="range_to[]" required min="0" max="100">
                    <div class="invalid-feedback"></div>
                </td>
                <td>
                    <input type="text" class="form-control grade" name="grade[]" required>
                    <div class="invalid-feedback"></div>
                </td>
                <td>
                    <input type="text" class="form-control remark" name="remark[]" >
                </td>
                <td>
                    <input type="text" class="form-control gpa" name="gpa[]" >
                </td>
            `;
                tbody.appendChild(newRow);

                // Attach event listeners for new inputs
                attachEventListeners(newRow);
            });

            // Function to attach event listeners for input fields
            function attachEventListeners(row) {
                const rangeFromInput = row.querySelector('.range-from');
                const rangeToInput = row.querySelector('.range-to');
                const gradeInput = row.querySelector('.grade');
                const invalidFeedbacks = row.querySelectorAll('.invalid-feedback');

                rangeFromInput.addEventListener('input', function() {
                    const invalidFeedback = invalidFeedbacks[0];
                    const fromValue = parseInt(this.value);
                    if (isNaN(fromValue) || fromValue < 0 || fromValue > 100) {
                        this.classList.add('is-invalid');
                        invalidFeedback.textContent = 'Please enter a valid number between 0 and 100.';
                    } else {
                        this.classList.remove('is-invalid');
                        invalidFeedback.textContent = '';
                    }
                    validateRange(this, rangeToInput);
                });

                rangeToInput.addEventListener('input', function() {
                    const invalidFeedback = invalidFeedbacks[1];
                    const toValue = parseInt(this.value);
                    if (isNaN(toValue) || toValue < 0 || toValue > 100) {
                        this.classList.add('is-invalid');
                        invalidFeedback.textContent = 'Please enter a valid number between 0 and 100.';
                    } else {
                        this.classList.remove('is-invalid');
                        invalidFeedback.textContent = '';
                    }
                    validateRange(rangeFromInput, this);
                });

                gradeInput.addEventListener('input', function() {
                    const invalidFeedback = invalidFeedbacks[2];
                    const grade = this.value.toUpperCase(); // Convert to uppercase
                    this.value = grade; // Update the input value
                    if (!/^[A-F][+-]?$/.test(grade)) {
                        this.classList.add('is-invalid');
                        invalidFeedback.textContent = 'Please enter a valid grade (A-F, A+, A-, B+, B-, etc.).';
                    } else {
                        this.classList.remove('is-invalid');
                        invalidFeedback.textContent = '';
                        const remarkInput = row.querySelector('.remark');
                        const gpaInput = row.querySelector('.gpa');
                        const {
                            remark,
                            gpa
                        } = generateRemarkAndGPA(grade);
                        remarkInput.value = remark;
                        gpaInput.value = gpa;
                    }
                });

            }

            // Attach event listeners for existing inputs
            const existingRows = document.querySelectorAll('#grading_ranges tr');
            existingRows.forEach(row => {
                attachEventListeners(row);
            });

            // Validate range inputs
            function validateRange(fromInput, toInput) {
                const fromValue = parseInt(fromInput.value);
                const toValue = parseInt(toInput.value);
                const fromFeedback = fromInput.parentNode.querySelector('.invalid-feedback');
                const toFeedback = toInput.parentNode.querySelector('.invalid-feedback');

                if (isNaN(fromValue) || isNaN(toValue) || fromValue < 0 || fromValue > 100 || toValue < 0 || toValue > 100) {
                    return;
                }

                if (fromValue > toValue) {
                    fromInput.classList.add('is-invalid');
                    toInput.classList.add('is-invalid');
                    fromFeedback.textContent = 'From value must be less than or equal to To value.';
                    toFeedback.textContent = 'To value must be greater than or equal to From value.';
                } else {
                    fromInput.classList.remove('is-invalid');
                    toInput.classList.remove('is-invalid');
                    fromFeedback.textContent = '';
                    toFeedback.textContent = '';
                }
            }

            // Auto-generate remark and GPA based on grade
            function generateRemarkAndGPA(grade) {
                let remark, gpa;
                switch (grade) {
                    case 'A':
                        remark = 'Outstanding Performance';
                        gpa = '12.00';
                        break;
                    case 'A-':
                        remark = 'Excellent Achievement';
                        gpa = '11.00';
                        break;
                    case 'B+':
                        remark = 'Very Good Effort';
                        gpa = '10.00';
                        break;
                    case 'B':
                        remark = 'Good Job';
                        gpa = '9.00';
                        break;
                    case 'B-':
                        remark = 'Above Average Performance';
                        gpa = '8.00';
                        break;
                    case 'C+':
                        remark = 'Fairly Good';
                        gpa = '7.00';
                        break;
                    case 'C':
                        remark = 'Satisfactory';
                        gpa = '6.00';
                        break;
                    case 'C-':
                        remark = 'Just Average';
                        gpa = '5.00';
                        break;
                    case 'D+':
                        remark = 'Below Average Work';
                        gpa = '4.00';
                        break;
                    case 'D':
                        remark = 'Needs Improvement';
                        gpa = '3.00';
                        break;
                    case 'D-':
                        remark = 'Subpar Effort';
                        gpa = '2.00';
                        break;
                    case 'E':
                        remark = 'Insufficient';
                        gpa = '1.00';
                        break;
                    case 'F':
                        remark = 'Failure';
                        gpa = '0.00';
                        break;
                    default:
                        remark = 'Invalid Grade';
                        gpa = '';
                        break;
                }
                return {
                    remark,
                    gpa
                };
            }


            // Event listener to generate remark and GPA when grade is changed
            document.addEventListener('input', function(event) {
                if (event.target && event.target.classList.contains('grade')) {
                    const gradeInput = event.target;
                    const grade = gradeInput.value.toUpperCase();
                    const row = gradeInput.closest('tr');
                    const remarkInput = row.querySelector('.remark');
                    const gpaInput = row.querySelector('.gpa');
                    const {
                        remark,
                        gpa
                    } = generateRemarkAndGPA(grade);
                    remarkInput.value = remark;
                    gpaInput.value = gpa;
                }
            });

            // Form submission handler
            document.getElementById('grading_form').addEventListener('submit', function(event) {
                const invalidInputs = document.querySelectorAll('.is-invalid');
                if (invalidInputs.length > 0) {
                    event.preventDefault();
                    invalidInputs[0].focus();
                }
            });
        </script>
    </div>
</div>
</div>

{{--Class List Ends--}}

@endsection