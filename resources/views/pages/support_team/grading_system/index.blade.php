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
            <li class="nav-item"><a href="#all-gradings" class="nav-link active" data-toggle="tab">Manage Grading</a>
            </li>
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
                    @foreach($subjects as $sub)
                    <div class="card p-3 add-more-card">
                        <h5 class="card-title d-inline-block mr-auto"><b>{{ $sub->subject_name }}</b></h5>
                        <button type="button" class="btn btn-danger btn-sm delete-card">Delete Card</button>
                        <div class="table-responsive">
                            <table class="table" id="grading_table">
                                <thead>
                                    <tr>
                                        <th>From</th>
                                        <th>To</th>
                                        <th>Grade</th>
                                        <th>Remark</th>
                                        <th>GPA</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody class="grading-ranges">
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
                                        <td>
                                            <button type="button" class="btn btn-danger btn-sm delete-range">Delete</button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @endforeach
                </form>
            </div>
        </div>
        <div><button type="submit" class="btn btn-success">Submit</button>
            <button type="button" class="btn btn-primary add-more-ranges">Add More Ranges</button>
        </div>
    </div>
</div>


<!-- Modal HTML -->
<div class="modal fade" id="deleteCardModal" tabindex="-1" role="dialog" aria-labelledby="deleteCardModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteCardModalLabel">Confirmation</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this card?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteCard">Delete</button>
            </div>
        </div>
    </div>
</div>



<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Add event listener to the "Add More Ranges" button
        document.querySelectorAll('.add-more-ranges').forEach(button => {
            button.addEventListener('click', function() {
                document.querySelectorAll('.grading-ranges').forEach(tbody => {
                    const newRow = document.createElement('tr');
                    newRow.innerHTML = `
                    <td>
                        <input type="text" class="form-control subject" name="subject[]" required>
                        <div class="invalid-feedback"></div>
                    </td>
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
                    <td>
                        <button type="button" class="btn btn-danger btn-sm delete-range">Delete</button>
                    </td>
                `;
                    tbody.appendChild(newRow);
                    attachEventListeners(newRow);
                });
            });
        });

        // Add event listener to delete the card with modal confirmation
        document.querySelectorAll('.delete-card').forEach(button => {
            button.addEventListener('click', function() {
                // Show the modal
                $('#deleteCardModal').modal('show');

                // Set up event listener for the confirm delete button in the modal
                document.getElementById('confirmDeleteCard').addEventListener('click', function() {
                    // Delete the card
                    const card = button.closest('.card');
                    card.remove();

                    // Hide the modal
                    $('#deleteCardModal').modal('hide');
                });
            });
        });

        function attachEventListeners(row) {
            row.querySelectorAll('.range-from, .range-to, .grade').forEach(input => {
                input.addEventListener('input', function() {
                    validateInput(input);
                });
            });

            row.querySelector('.delete-range').addEventListener('click', function() {
                if (true) {
                    row.remove();
                }
            });
        }

        function validateInput(input) {
            const row = input.closest('tr');
            const rangeFromInput = row.querySelector('.range-from');
            const rangeToInput = row.querySelector('.range-to');
            const gradeInput = row.querySelector('.grade');

            validateRange(rangeFromInput, rangeToInput);
            validateGrade(gradeInput);
        }

        function validateRange(fromInput, toInput) {
            const fromValue = parseInt(fromInput.value);
            const toValue = parseInt(toInput.value);
            const fromFeedback = fromInput.nextElementSibling;
            const toFeedback = toInput.nextElementSibling;

            if (isNaN(fromValue) || fromValue < 0 || fromValue > 100) {
                fromInput.classList.add('is-invalid');
                fromFeedback.textContent = 'Please enter a valid number between 0 and 100.';
            } else {
                fromInput.classList.remove('is-invalid');
                fromFeedback.textContent = '';
            }

            if (isNaN(toValue) || toValue < 0 || toValue > 100) {
                toInput.classList.add('is-invalid');
                toFeedback.textContent = 'Please enter a valid number between 0 and 100.';
            } else {
                toInput.classList.remove('is-invalid');
                toFeedback.textContent = '';
            }

            if (!isNaN(fromValue) && !isNaN(toValue) && fromValue > toValue) {
                fromInput.classList.add('is-invalid');
                toInput.classList.add('is-invalid');
                fromFeedback.textContent = 'From value must be less than or equal to To value.';
                toFeedback.textContent = 'To value must be greater than or equal to From value.';
            } else {
                if (!fromInput.classList.contains('is-invalid')) {
                    fromFeedback.textContent = '';
                }
                if (!toInput.classList.contains('is-invalid')) {
                    toFeedback.textContent = '';
                }
            }
        }

        function validateGrade(gradeInput) {
            const grade = gradeInput.value.toUpperCase();
            const feedback = gradeInput.nextElementSibling;
            gradeInput.value = grade;

            if (!/^[A-F][+-]?$/.test(grade)) {
                gradeInput.classList.add('is-invalid');
                feedback.textContent = 'Please enter a valid grade (A-F, A+, A-, B+, B-, etc.).';
            } else {
                gradeInput.classList.remove('is-invalid');
                feedback.textContent = '';
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
        }

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


        // Add event listener to the "Delete Card" button


    });
</script>
</div>
</div>
</div>

{{--Class List Ends--}}

@endsection