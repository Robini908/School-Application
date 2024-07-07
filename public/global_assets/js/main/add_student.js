
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


// Function to show and hide the tooltip message
$(function() {
    $('[data-toggle="tooltip"]').tooltip();
});

$(document).ready(function() {
    $('#inputNumber').on('input', function() {
        var inputNumber = $(this).val();
        var schoolCode = "SCH"; // Replace with your actual school code
        var currentYear = new Date().getFullYear();

        // Validate input number
        if (!inputNumber || inputNumber <= 0 || isNaN(inputNumber)) {
            displayErrorMessage('Please enter a valid positive number.');
            return;
        }

        // Hide all alerts initially
        $('#suggestionAlert').hide();
        $('#errorAlert').hide();
        $('#successAlert').hide();

        // Format and make AJAX request
        var paddedNumber = inputNumber.toString().padStart(5, '0');
        var admissionNumber = `${schoolCode}/${paddedNumber}/${currentYear}`;

        // Simulated response for testing
        var response = {
            exists: false,
            suggestion: null // Set suggestion to null for no suggestion case
        };


        $.ajax({
            url: '/check-admission-number/' + inputNumber,
            type: 'GET',
            success: function(response) {
                if (response.exists) {
                    $('#suggestionAlert').text(
                            'This number has already been used. Please use ' + response
                            .suggestion + ' instead.')
                        .show();
                    $('#adm_no').val('');
                } else if (response.suggestion) {
                    $('#suggestionAlert').text('Number ' + response.suggestion +
                            ' was skipped. Please use it.')
                        .show();
                    $('#adm_no').val('');
                } else {
                    var paddedNumber = inputNumber.padStart(5, '0');
                    var admissionNumber = `${schoolCode}/${paddedNumber}/${currentYear}`;
                    $('#adm_no').val(admissionNumber);
                    $('#successAlert').text('Admission number generated successfully: ' +
                            admissionNumber)
                        .show();
                }
            },

            error: function(xhr, status, error) {
                var errorMessage = 'An error occurred while checking the admission number.';

                // Check if the server responded with a specific error message
                if (xhr.responseText) {
                    errorMessage = xhr.responseText;
                } else if (status === 'timeout') {
                    errorMessage = 'The request timed out. Please try again later.';
                } else if (status === 'error') {
                    errorMessage =
                        'An error occurred during the request. Please try again.';
                } else if (status === 'abort') {
                    errorMessage = 'The request was aborted. Please try again.';
                } else if (error) {
                    errorMessage = 'An unexpected error occurred: ' + error;
                }

                displayErrorMessage(errorMessage);
                $('#adm_no').val('');
            }
        });

    });

    // Function to display alert messages dynamically
    function displayAlertMessage(type, message) {
        var alertBox = $('#' + type + 'Alert');
        alertBox.html('<strong>' + capitalizeFirstLetter(type) + '!</strong> ' + message)
            .show()
            .delay(5000) // Show message for 5 seconds
            .fadeOut(); // Fade out message
    }

    // Function to display error messages
    function displayErrorMessage(message) {
        displayAlertMessage('error', message)
            .show()
            .delay(5000) // Show message for 5 seconds
            .fadeOut(); // Fade out message
    }

    function displayErrorMessage(message) {
        displayAlertMessage('warning', message);
    }

    // Function to capitalize first letter of a string
    function capitalizeFirstLetter(string) {
        return string.charAt(0).toUpperCase() + string.slice(1);
    }
});
$(document).ready(function() {
    // Function to handle AJAX errors
    function handleAjaxError(xhr, status, error) {
        console.error('AJAX Error:', status, error);
        // Optionally handle errors in UI
    }

    // AJAX request to fetch session from server
    $.ajax({
        url: '/get-session',
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            $('#session').val(response.session);
        },
        error: function(xhr, status, error) {
            handleAjaxError(xhr, status, error);
        }
    });
});