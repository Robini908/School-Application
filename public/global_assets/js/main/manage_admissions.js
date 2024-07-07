// Sample data structure for students

// Initialize students with default status as pending
students.forEach(student => {
    student.status = 'pending';
    student.actionDone = false;
});

// Function to render students based on filter and search query
function renderStudents(filterStatus = '', searchQuery = '') {
    let tbody = document.getElementById('student-list');
    tbody.innerHTML = ''; // Clear previous content

    students.forEach(student => {
        // Check if the student matches the current filter and search query
        if ((filterStatus === '' || student.status === filterStatus) &&
            (searchQuery === '' || student.name.toLowerCase().includes(searchQuery.toLowerCase()))) {
            let row = document.createElement('tr');
            row.innerHTML = `
                <td>${student.name}</td>
                <td>${student.email}</td>
                <td>${student.gender}</td>
                <td>${student.class}</td>
                <td>${student.section}</td>
                <td>${student.status}</td>
                <td>
                    ${student.status === 'pending' ?
                        `<button class="btn btn-sm btn-success btn-mark-approved" data-id="${student.id}" ${student.actionDone ? 'disabled' : ''} data-toggle="tooltip" data-placement="top" title="Click to mark as Approved">Mark as Approved</button>`
                        : ''}
                    ${student.status === 'approved' ?
                        `<button class="btn btn-sm btn-danger btn-mark-disapproved" data-id="${student.id}" data-toggle="modal" data-target="#disapproveModal">Disapprove</button>`
                        : ''}
                    ${student.status === 'approved' || student.status === 'pending' ?
                        `<button class="btn btn-sm btn-warning btn-mark-pending" data-id="${student.id}" ${student.actionDone ? 'disabled' : ''} data-toggle="tooltip" data-placement="top" title="Click to mark as Pending">Mark as Pending</button>`
                        : ''}
                </td>
            `;
            tbody.appendChild(row);
        }
    });

    // Initialize tooltips
    $('[data-toggle="tooltip"]').tooltip();

    updateSubmissionCounts(); // Update submission counts after rendering
}

// Function to update submission counts dynamically
function updateSubmissionCounts() {
    let totalSubmissions = students.length;
    let approvedSubmissions = students.filter(student => student.status === 'approved').length;
    let pendingSubmissions = students.filter(student => student.status === 'pending').length;
    let disapprovedSubmissions = students.filter(student => student.status === 'disapproved').length;

    document.getElementById('total-submissions').textContent = totalSubmissions;
    document.getElementById('approved-submissions').textContent = approvedSubmissions;
    document.getElementById('pending-submissions').textContent = pendingSubmissions;
    document.getElementById('disapproved-submissions').textContent = disapprovedSubmissions;
}

// Function to toggle approval status (mark as approved or pending)
function toggleApproval(studentId, actionType) {
    let student = students.find(s => s.id === studentId);
    if (!student) {
        displayError("Student not found!");
        return;
    }

    if (student.actionDone) {
        displayError("Action already performed for this student.");
        return;
    }

    // Perform the action (toggle approval or pending)
    student.status = actionType === 'approved' ? 'approved' : 'pending';
    student.actionDone = true;

    // Update the UI
    renderStudents(getFilterStatus(), getSearchQuery());

    // Update submission counts after action is performed
    updateSubmissionCounts();
}

// Function to disapprove admission
function disapproveAdmission(studentId, reason) {
    let student = students.find(s => s.id === studentId);
    if (!student) {
        displayError("Student not found!");
        return;
    }

    // Perform the disapproval action
    student.status = 'disapproved';
    student.actionDone = true;

    // Close the modal
    $('#disapproveModal').modal('hide');

    // Update the UI
    renderStudents(getFilterStatus(), getSearchQuery());

    // Update submission counts after action is performed
    updateSubmissionCounts();
}

// Event listener for marking a student as approved or pending
document.addEventListener('click', function(event) {
    if (event.target.classList.contains('btn-mark-approved')) {
        let studentId = parseInt(event.target.getAttribute('data-id'));
        toggleApproval(studentId, 'approved');
        disableOtherButtons(studentId);
    } else if (event.target.classList.contains('btn-mark-pending')) {
        let studentId = parseInt(event.target.getAttribute('data-id'));
        toggleApproval(studentId, 'pending');
        disableOtherButtons(studentId);
    }
});

// Event listener for disapproving admission (show modal)
document.addEventListener('click', function(event) {
    if (event.target.classList.contains('btn-mark-disapproved')) {
        let studentId = parseInt(event.target.getAttribute('data-id'));
        // Show the disapprove modal
        $('#disapproveModal').modal('show');

        // Save disapproval on modal save button click
        document.getElementById('btn-disapprove').addEventListener('click', function() {
            let reason = document.getElementById('disapproval-reason').value;
            if (reason.trim() === '') {
                displayError("Please provide a reason for disapproval.");
                return;
            }
            disapproveAdmission(studentId, reason);
        });
    }
});

// Function to disable other action buttons once one is clicked
function disableOtherButtons(studentId) {
    students.forEach(student => {
        if (student.id !== studentId) {
            student.actionDone = true; // Mark action as done for other students
        }
    });

    renderStudents(getFilterStatus(), getSearchQuery());
}

// Function to display error messages
function displayError(message) {
    // Display error message to the user (e.g., alert, toast, etc.)
    console.error(message); // Log error to console for debugging
}

// Event listener for search input
document.getElementById('search-student').addEventListener('input', function() {
    renderStudents(getFilterStatus(), this.value.trim());
});

// Event listener for filter status change
document.getElementById('filter-status').addEventListener('change', function() {
    renderStudents(this.value, document.getElementById('search-student').value.trim());
});

// Function to get the current filter status
function getFilterStatus() {
    return document.getElementById('filter-status').value;
}

// Function to get the current search query
function getSearchQuery() {
    return document.getElementById('search-student').value.trim();
}

// Initial render on page load
renderStudents();
