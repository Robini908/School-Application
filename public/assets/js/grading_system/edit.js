
    // document.getElementById('add_more').addEventListener('click', function() {
    //         const tbody = document.getElementById('grading_ranges');
    //         const newRow = document.createElement('tr');
    //         newRow.innerHTML = `
    //             <td></td>
    //             <td></td>
    //             <td>
    //                 <input type="number" class="form-control range-from" name="range_from[]" required min="0" max="100">
    //                 <div class="invalid-feedback"></div>
    //             </td>
    //             <td>
    //                 <input type="number" class="form-control range-to" name="range_to[]" required min="0" max="100">
    //                 <div class="invalid-feedback"></div>
    //             </td>
    //             // <td>
    //                 <input type="text" class="form-control grade" name="grade[]" required>
    //                 <div class="invalid-feedback"></div>
    //             </td>
    //             <td>
    //                 <input type="text" class="form-control remark" name="remark[]" placeholder="e.g., Excellent">
    //             </td>
    //             <td>
    //                 <input type="text" class="form-control gpa" name="gpa[]" placeholder="e.g., 4.0">
    //             </td>
    //         `;
    //         tbody.appendChild(newRow);
    
    //         // Attach event listeners for new inputs
    //         attachEventListeners(newRow);
    //     });
    
    //     // Function to attach event listeners for input fields
    //     function attachEventListeners(row) {
    //         const rangeFromInput = row.querySelector('.range-from');
    //         const rangeToInput = row.querySelector('.range-to');
    //         const gradeInput = row.querySelector('.grade');
    //         const invalidFeedbacks = row.querySelectorAll('.invalid-feedback');
    
    //         rangeFromInput.addEventListener('input', function() {
    //             const invalidFeedback = invalidFeedbacks[0];
    //             const fromValue = parseInt(this.value);
    //             if (isNaN(fromValue) || fromValue < 0 || fromValue > 100) {
    //                 this.classList.add('is-invalid');
    //                 invalidFeedback.textContent = 'Please enter a valid number between 0 and 100.';
    //             } else {
    //                 this.classList.remove('is-invalid');
    //                 invalidFeedback.textContent = '';
    //             }
    //             validateRange(this, rangeToInput);
    //         });
    
    //         rangeToInput.addEventListener('input', function() {
    //             const invalidFeedback = invalidFeedbacks[1];
    //             const toValue = parseInt(this.value);
    //             if (isNaN(toValue) || toValue < 0 || toValue > 100) {
    //                 this.classList.add('is-invalid');
    //                 invalidFeedback.textContent = 'Please enter a valid number between 0 and 100.';
    //             } else {
    //                 this.classList.remove('is-invalid');
    //                 invalidFeedback.textContent = '';
    //             }
    //             validateRange(rangeFromInput, this);
    //         });
    
    //         gradeInput.addEventListener('input', function() {
    //             const invalidFeedback = invalidFeedbacks[2];
    //             const grade = this.value.toUpperCase();
    //             this.value = grade; 
    //             if (!/^[A-F][+-]?$/.test(grade)) {
    //                 this.classList.add('is-invalid');
    //                 invalidFeedback.textContent = 'Please enter a valid grade (A-F, A+, A-, B+, B-, etc.).';
    //             } else {
    //                 this.classList.remove('is-invalid');
    //                 invalidFeedback.textContent = '';
    //             }
    //         });
    //     }
    
    //     // Attach event listeners for existing inputs
    //     const existingRows = document.querySelectorAll('#grading_ranges tr');
    //     existingRows.forEach(row => {
    //         attachEventListeners(row);
    //     });
    
    //     // Validate range inputs
    //     function validateRange(fromInput, toInput) {
    //         const fromValue = parseInt(fromInput.value);
    //         const toValue = parseInt(toInput.value);
    //         const fromFeedback = fromInput.parentNode.querySelector('.invalid-feedback');
    //         const toFeedback = toInput.parentNode.querySelector('.invalid-feedback');
    
    //         if (isNaN(fromValue) || isNaN(toValue) || fromValue < 0 || fromValue > 100 || toValue < 0 || toValue > 100) {
    //             return;
    //         }
    
    //         if (fromValue > toValue) {
    //             fromInput.classList.add('is-invalid');
    //             toInput.classList.add('is-invalid');
    //             fromFeedback.textContent = 'From value must be less than or equal to To value.';
    //             toFeedback.textContent = 'To value must be greater than or equal to From value.';
    //         } else {
    //             fromInput.classList.remove('is-invalid');
    //             toInput.classList.remove('is-invalid');
    //             fromFeedback.textContent = '';
    //             toFeedback.textContent = '';
    //         }
    //     }
    
   