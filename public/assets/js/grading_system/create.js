
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
            <td>
                <button type="button" class="btn btn-danger btn-sm delete-range">Delete</button>
            </td>
        `;
                tbody.appendChild(newRow);

                attachEventListeners(newRow);
            });

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
                    const grade = this.value.toUpperCase();
                    this.value = grade;
                    if (!/^[A-F]{1,2}$/.test(grade)) {
                        this.classList.add('is-invalid');
                        invalidFeedback.textContent = 'Please enter a valid grade (A-F).';
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

                row.querySelector('.delete-range').addEventListener('click', function() {
                    row.remove();
                });
            }

            const existingRows = document.querySelectorAll('#grading_ranges tr');
            existingRows.forEach(row => {
                attachEventListeners(row);
            });

            function validateRange(fromInput, toInput) {
                const fromValue = parseInt(fromInput.value);
                const toValue = parseInt(toInput.value);
                const fromFeedback = fromInput.parentNode.querySelector('.invalid-feedback');
                const toFeedback = toInput.parentNode.querySelector('.invalid-feedback');

                if (isNaN(fromValue) || isNaN(toValue) || fromValue < 0 || fromValue > 100 || toValue < 0 || toValue >
                    100) {
                    return;
                }

                if (fromValue >= toValue) { // Changed the condition to disallow equal values
                    fromInput.classList.add('is-invalid');
                    toInput.classList.add('is-invalid');
                    fromFeedback.textContent = 'From value must be less than To value.';
                    toFeedback.textContent = 'To value must be greater than From value.';
                } else {
                    fromInput.classList.remove('is-invalid');
                    toInput.classList.remove('is-invalid');
                    fromFeedback.textContent = '';
                    toFeedback.textContent = '';
                }
            }

            function generateRemarkAndGPA(grade) {
                let remark, gpa;
                switch (grade) {
                    case 'A':
                        remark = 'Magnificent';
                        gpa = '12.00';
                        break;
                    case 'A-':
                        remark = 'Excellent';
                        gpa = '11.00';
                        break;
                    case 'B+':
                        remark = 'Very Good';
                        gpa = '10.00';
                        break;
                    case 'B':
                        remark = 'Good';
                        gpa = '9.00';
                        break;
                    case 'B-':
                        remark = 'Above Average';
                        gpa = '8.00';
                        break;
                    case 'C+':
                        remark = 'Average';
                        gpa = '7.00';
                        break;
                    case 'C':
                        remark = 'Average';
                        gpa = '6.00';
                        break;
                    case 'C-':
                        remark = 'Average';
                        gpa = '5.00';
                        break;
                    case 'D+':
                        remark = 'Below Average';
                        gpa = '4.00';
                        break;
                    case 'D':
                        remark = 'Poor';
                        gpa = '3.00';
                        break;
                    case 'D-':
                        remark = 'Very Poor';
                        gpa = '2.00';
                        break;
                    case 'E':
                        remark = 'Extremely poor';
                        gpa = '1.00';
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