

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
                            <input type="text" class="form-control grade" name="grade[]" required pattern="[A-F]{1,2}">
                            <div class="invalid-feedback"></div>
                        </td>
                        <td>
                            <input type="text" class="form-control remark" name="remark[]" readonly>
                        </td>
                        <td>
                            <input type="text" class="form-control gpa" name="gpa[]" readonly>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <button type="button" id="add_more" class="btn btn-primary">Add More Ranges</button>
        <button type="submit" class="btn btn-success">Submit</button>
    </form>

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
                    <input type="text" class="form-control grade" name="grade[]" required pattern="[A-F]{1,2}">
                    <div class="invalid-feedback"></div>
                </td>
                <td>
                    <input type="text" class="form-control remark" name="remark[]" readonly>
                </td>
                <td>
                    <input type="text" class="form-control gpa" name="gpa[]" readonly>
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
                if (!/^[A-F]{1,2}$/.test(grade)) {
                    this.classList.add('is-invalid');
                    invalidFeedback.textContent = 'Please enter a valid grade (A-F).';
                } else {
                    this.classList.remove('is-invalid');
                    invalidFeedback.textContent = '';
                    const remarkInput = row.querySelector('.remark');
                    const gpaInput = row.querySelector('.gpa');
                    const { remark, gpa } = generateRemarkAndGPA(grade);
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
                case 'A-':
                    remark = 'Excellent';
                    gpa = '4.00';
                    break;
                case 'B+':
                    remark = 'Very Good';
                    gpa = '3.50';
                    break;
                case 'B':
                    remark = 'Good';
                    gpa = '3.00';
                                       break;
                case 'B-':
                    remark = 'Above Average';
                    gpa = '2.75';
                    break;
                case 'C+':
                    remark = 'Average';
                    gpa = '2.50';
                    break;
                case 'C':
                    remark = 'Average';
                    gpa = '2.25';
                    break;
                case 'C-':
                    remark = 'Average';
                    gpa = '2.00';
                    break;
                case 'D+':
                    remark = 'Below Average';
                    gpa = '1.75';
                    break;
                case 'D':
                    remark = 'Poor';
                    gpa = '1.50';
                    break;
                case 'D-':
                    remark = 'Very Poor';
                    gpa = '1.00';
                    break;
                 case 'E':
                    remark = 'Extremely poor';
                    gpa = '0.50';
                    break;
                case 'F':
                    remark = 'Fail';
                    gpa = '0.00';
                    break;
                default:
                    remark = '';
                    gpa = '';
                    break;
            }
            return { remark, gpa };
        }

        // Event listener to generate remark and GPA when grade is changed
        document.addEventListener('input', function(event) {
            if (event.target && event.target.classList.contains('grade')) {
                const gradeInput = event.target;
                const grade = gradeInput.value.toUpperCase();
                const row = gradeInput.closest('tr');
                const remarkInput = row.querySelector('.remark');
                const gpaInput = row.querySelector('.gpa');
                const { remark, gpa } = generateRemarkAndGPA(grade);
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