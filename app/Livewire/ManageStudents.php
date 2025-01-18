<?php

namespace App\Livewire;

use App\Models\MyClass;
use App\Models\Section;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use App\Models\ParentDetail;
use Livewire\WithPagination;
use App\Models\StudentRecord;
use Livewire\WithFileUploads;
use Maatwebsite\MPDF;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\DisapprovalNotification;
use App\Notifications\StudentExpelled;
use App\Notifications\StudentSuspended;
use App\Helpers\StudentHelper; // Import the helper


class ManageStudents extends Component
{
    use WithPagination;
    use WithFileUploads;
    use LivewireAlert;

    public $selectedStudent;
    public $mystudents;

    public $file; // For file upload
    public $notificationContent; // For rich text editor content

    public $searchTerm = '';
    public $showDeleteModal = false;

    protected $mystudent; // Change to protected
    protected $filePath; // Change to protected
    public $formFilter = '';
    public $isEditingAll = false;
    public $editingFields = [];
    public $sectionFilter = '';
    public $statusFilter = '';
    public $classes = [];

    // Updated suspension-related properties
    public $suspensionReason = ''; // Renamed from expulsionReason
    public $suspensionType = ''; // Renamed from expulsionType

    // Flags for different actions
    public $isEditingStudent = false;
    public $isViewingDetails = false;
    public $isExpellingStudent = false;

    public $transitionYearFilter; // New filter for transition 

    public $isSuspendingStudent = false; // Renamed from isExpellingStudent
    public $currentStep = 1;
    public $suspensionEndDate; // Renamed from expulsionEndDate


    public $isRejectingStudent = false;
    public $noResults = false;
    public $isSendingStudentMail = false;
    public $isViewingHistoryDetails = false;
    public $isApproving = false;

    // Removed/renamed expulsion-related fields
    public $suspensionDuration = null; // Renamed from expulsionDuration
    public $isDeleting = false;
    public $isRejecting = false;
    public $disapprovalReason = ''; // To hold the disapproval reason

    public $forms = [];
    public $isDisapproving = false;
    public $sections = [];
    public $statuses = ['verified', 'unverified'];



    protected $listeners = [
        'refreshStudents' => 'loadStudents',
        'studentSelected' => 'selectStudent'
    ];

    public function mount()
    {
        $this->mystudents = collect(); // Initialize as empty collection

        $this->fetchStudents();
        $this->loadFilterOptions(); // Load filter options if needed
    }


    public function deleteRecord($studentId)
    {
        // Locate the student to be deleted
        $this->selectedStudent = StudentRecord::find($studentId);
        $this->isDeleting = true;

        // Reset other flags if needed
        $this->resetOtherFlags(true);
    }

    public function confirmDelete()
    {
        if ($this->selectedStudent) {
            $fullName = $this->selectedStudent->first_name . ' ' . $this->selectedStudent->middle_name . ' ' . $this->selectedStudent->last_name;
            $admNo = $this->selectedStudent->adm_no;
            $this->selectedStudent->delete();
            $this->alert('success', "Student $fullName (Admission No: $admNo) deleted successfully from the database.");
        }
        $this->cancelDelete();
    }

    public function cancelDelete()
    {
        $this->isDeleting = false;
        $this->selectedStudent = null;
    }


    public function toggleDisapproval()
    {
        $this->isDisapproving = !$this->isDisapproving;
    }

    public function cancelExpel()
    {
        // Reset the necessary properties
        $this->reset();

        // Optionally, you can also use a session message to notify the user
        $this->alert('info', 'Expulsion process cancelled.');
    }

    public function nextStep()
    {
        $this->validate();
        $this->currentStep++;
    }

    public function previousStep()
    {
        $this->currentStep--;
    }


    public function sendStudentMail($studentId)
{
    // Find the student based on the ID
    $student = StudentRecord::findOrFail($studentId);

    // Validate the form inputs
    $this->validate([
        'notificationContent' => 'required|string', // Ensure email content is provided
        'file' => 'nullable|mimes:pdf,jpg,png|max:10240', // Allow specific file types up to 10MB
    ]);

    // Store the file if uploaded
    $filePath = null;
    if ($this->file) {
        try {
            $filePath = $this->file->store('email_attachments', 'public');
        } catch (\Exception $e) {
            $this->alert('error', 'Failed to upload file: ' . $e->getMessage());
            return;
        }
    }

    // Prepare email data
    $emailData = [
        'studentName' => "{$student->first_name} {$student->last_name}",
        'parentName'  => $student->parent_detail->parent_first_name ?? '',
        'notificationContent' => $this->notificationContent, // TinyMCE content
        'filePath' => $filePath,
    ];

    // Try sending the email
    try {
        Mail::send('emails.student-notification', $emailData, function ($message) use ($student, $filePath) {
            $message->to($student->email)->subject('Important Notification for Student');

            // Check if parent email exists and cc it
            if (!empty($student->parent_detail->parent_email)) {
                $message->cc($student->parent_detail->parent_email, 'Parent Notification');
            }

            // Attach the file if uploaded
            if ($filePath) {
                $message->attach(storage_path("app/public/{$filePath}"));
            }
        });

        // If email sent successfully, notify user and reset form fields
        $this->alert('success', 'Email sent successfully with attachment.');
        $this->reset(['notificationContent', 'file']); // Reset form fields

    } catch (\Exception $e) {
        // Handle any errors that occur during email sending
        $this->alert('error', 'Failed to send email: ' . $e->getMessage());
    }
}    public function viewStudent($studentId)
    {
        $this->selectedStudent = StudentRecord::findOrFail($studentId);
        $this->isViewingDetails = true;
        $this->resetOtherFlags('isViewingDetails');
    }





    public function fetchStudents()
    {
        // Directly load students from the database
        $this->loadStudents();
    }

    public function getTransitionYears()
    {
        $currentYear = now()->year;
        $startYear = 2020; // You can adjust this start year as needed
        return range($startYear, $currentYear);
    }


    // Inside your Livewire component

    public function loadStudents()
    {
        // Start the query with eager loading of related models
        $query = StudentRecord::with(['my_class', 'section', 'parent_detail'])
            ->orderBy('id', 'desc');

        // Apply filters if necessary
        if (!empty($this->formFilter)) {
            $query->whereHas('my_class', function ($q) {
                $q->where('name', $this->formFilter);
            });
        }

        if (!empty($this->sectionFilter)) {
            $query->whereHas('section', function ($q) {
                $q->where('name', $this->sectionFilter);
            });
        }

        if (!empty($this->statusFilter)) {
            $query->where('status', $this->statusFilter);
        }

        // Apply transition year filter if selected
        if (!empty($this->transitionYearFilter)) {
            // Use the helper to get students for the selected transition year
            $students = StudentHelper::getStudentsByTransitionYear($this->transitionYearFilter);
            // Merge the filtered students with the existing query
            $query->whereIn('id', $students->pluck('id'));
        }

        // Apply search term if provided
        if (!empty($this->searchTerm)) {
            $searchTerm = '%' . $this->searchTerm . '%'; // Add wildcards for partial matching
            $query->where(function ($q) use ($searchTerm) {
                $q->where('parent_id_no', 'LIKE', $searchTerm)
                    ->orWhere('adm_no', 'LIKE', $searchTerm)
                    ->orWhere('year_admitted', 'LIKE', $searchTerm)
                    ->orWhere('kcpe', 'LIKE', $searchTerm)
                    ->orWhere('first_name', 'LIKE', $searchTerm)
                    ->orWhere('middle_name', 'LIKE', $searchTerm)
                    ->orWhere('last_name', 'LIKE', $searchTerm)
                    ->orWhere('email', 'LIKE', $searchTerm)
                    ->orWhere('gender', 'LIKE', $searchTerm)
                    ->orWhere('phone', 'LIKE', $searchTerm)
                    ->orWhere('nationality', 'LIKE', $searchTerm)
                    ->orWhere('state', 'LIKE', $searchTerm)
                    ->orWhere('town', 'LIKE', $searchTerm);
            });
        }

        // Return the paginated results directly in the view
        return $query->paginate(20);
    }
    public function render()
    {
        return view('livewire.manage-students', [
            'students' => $this->loadStudents(), // Pass paginated results directly to the view
            'transitionYears' => $this->getTransitionYears(), // Pass years for the dropdown
        ]);
    }


    public function getStudentsWithSuspensionInfo()
    {
        return $this->mystudents->map(function ($student) {
            return [
                'id' => $student->id,
                'first_name' => $student->first_name,
                'last_name' => $student->last_name,
                'is_suspended' => (bool) $student->is_suspended, // Updated from is_expelled to is_suspended
                'suspension_type' => $student->is_suspended ? ($student->suspension_type ?? 'N/A') : null, // Updated field
                'my_class' => $student->my_class,
                'section' => $student->section,
                'parent_detail' => $student->parent_detail,
            ];
        });
    }


    public function loadFilterOptions()
    {
        // Fetch distinct forms (my_classes) using the MyClass model
        $this->forms = MyClass::pluck('name')->unique()->toArray();

        // If a form is selected, fetch corresponding sections
        if ($this->formFilter) {
            // Fetch sections corresponding to the selected form using the relationship
            $this->sections = Section::whereHas('my_class', function ($query) {
                $query->where('name', $this->formFilter);
            })
                ->pluck('name')
                ->unique()
                ->toArray();
        } else {
            $this->sections = []; // Reset sections if no form is selected
        }
    }

    

    public function studentExpulsion($studentId)
    {
        // Fetch the student data with related models
        $this->selectedStudent = StudentRecord::with(['my_class', 'section', 'parent_detail'])->find($studentId);

        // Check if student exists
        if (!$this->selectedStudent) {
            $this->alert('error', 'Student not found.'); // Set session error message
            return;
        }

        // Set flags for expulsion process
        $this->isExpellingStudent = true;
    }

    public function studentSuspension($studentId)
    {
        // Fetch the student data with related models
        $this->selectedStudent = StudentRecord::with(['my_class', 'section', 'parent_detail'])->find($studentId);

        // Check if student exists
        if (!$this->selectedStudent) {
            $this->alert('error', 'Student not found.'); // Set session error message
            return;
        }

        // Set flags for suspension process
        $this->isSuspendingStudent = true;

        // Initialize the suspension reason and type
        $this->suspensionReason = ''; // Initialize reason variable
        $this->suspensionType = ''; // Initialize suspension type variable
        $this->suspensionDuration = null; // Initialize suspension duration variable
    }

    public function sendNotificationToGuardians($student)
    {
        // Fetch the parent associated with the student
        $parent = ParentDetail::find($student->parent_id_no);

        // Check if the parent exists
        if ($parent) {
            // Send notification to the parent
            $parent->notify(new StudentSuspended($student)); // Updated notification class
        }
    }

    public function confirmStudentSuspension()
    {
        // Validate the input fields
        $this->validate([
            'suspensionReason' => 'required|string|max:255',
            'suspensionType' => 'required|in:dismissal,withdrawal,permanent_exclusion',
            'suspensionEndDate' => 'nullable|date|after:today', // Validate the end date for temporary suspensions
        ]);

        // Update the student's suspension status
        $this->selectedStudent->is_suspended = true;
        $this->selectedStudent->suspension_reason = $this->suspensionReason;
        $this->selectedStudent->suspended_by = auth()->user()->id;
        $this->selectedStudent->suspension_date = now();
        $this->selectedStudent->suspension_type = $this->suspensionType;

        // Calculate the duration in weeks based on the end date if it's dismissal or withdrawal
        if (in_array($this->suspensionType, ['dismissal', 'withdrawal']) && $this->suspensionEndDate) {
            $endDate = \Carbon\Carbon::parse($this->suspensionEndDate);
            $weeksDifference = now()->diffInWeeks($endDate);

            // Set suspension end date and duration in weeks
            $this->selectedStudent->suspension_end_date = $endDate;
            $this->suspensionDuration = $weeksDifference;
        } else {
            $this->selectedStudent->suspension_end_date = null;
        }

        // Save the student record
        $this->selectedStudent->save();

        // Notify the parent of the suspended student
        $this->sendNotificationToGuardians($this->selectedStudent);

        // Reload the student list
        $this->loadStudents();

        // Reset flags and data
        $this->isSuspendingStudent = false;
        $this->resetSuspension();

        // Set a success message
        $this->alert('success', 'Student suspended successfully.');
    }

    public function resetSuspension()
    {
        $this->selectedStudent = null;
        $this->isSuspendingStudent = false;
        $this->suspensionReason = '';
        $this->suspensionType = '';
        $this->suspensionDuration = null;
    }


    // Suspend Student
    public function suspendStudent($studentId)
    {
        // Logic to suspend student
        $this->selectedStudent = StudentRecord::find($studentId);
        $this->isSuspendingStudent = true;
        // Reset other flags
        $this->resetOtherFlags('isSuspendingStudent');
    }

    // View Student History


    // Approve Student
    public function approveStudent($studentId)
    {
        // Fetch the student data with related models
        $this->selectedStudent = StudentRecord::with(['my_class', 'section', 'parent_detail'])->find($studentId);

        // Check if student exists
        if (!$this->selectedStudent) {
            $this->alert('error', 'Student not found.'); // Set session error message
            return;
        }

        // Set flags for approval process
        $this->isApproving = true;

        // Verify student information (you can customize these checks as needed)
        if (empty($this->selectedStudent->first_name) || empty($this->selectedStudent->last_name) || empty($this->selectedStudent->parent_id_no)) {
            $this->alert('error', 'Please ensure all required student information is filled out.', [
                'position' => 'top',
                'showConfirmButton' => true,
                'confirmButtonText' => 'OK',

                'reverseButtons' => true,
                'timer' => 30000,
                'toast' => false,
            ]);
            return;
        }
    }


    public function confirmApproval()
    {
        if (!$this->selectedStudent) {
            $this->alert('error', 'No student selected for approval.');
            return;
        }

        // Proceed with approving the student
        $this->selectedStudent->status = 'Approved';
        $this->selectedStudent->save();

        // Notify user of successful approval
        $this->alert('success', 'Student approved successfully.');

        // Refresh students list
        $this->loadStudents();

        // Reset approval flag
        $this->isApproving = false;
    }



    // Method to cancel approval and record the reason
    public function cancelApproval()
    {
        // Ensure a selected student exists
        if (!$this->selectedStudent) {
            $this->alert('error', 'No student selected for disapproval.');
            return;
        }

        // Ensure that the reason is provided
        if (empty($this->disapprovalReason)) {
            $this->alert('error', 'Please provide a reason for disapproval.');
            return;
        }

        // Update student status and reason for disapproval
        $this->selectedStudent->status = 'Disapproved';
        $this->selectedStudent->disapproval_reason = $this->disapprovalReason; // Assuming there's a `disapproval_reason` column in the DB
        $this->selectedStudent->save();

        // Send email notification
        $this->sendDisapprovalNotification($this->selectedStudent);

        // Reset flags and input fields
        $this->disapprovalReason = '';
        $this->isApproving = false;

        // Notify the user
        $this->alert('success', 'Student disapproved successfully.');

        // Refresh student list
        $this->loadStudents();
    }

    // Method to send an email notification to the student or parent
    public function sendDisapprovalNotification($student)
    {
        $email = $student->email ?? $student->parent_detail->email; // Assuming parent_detail has an email
        if ($email) {
            Mail::to($email)->send(new DisapprovalNotification($student, $this->disapprovalReason));
        }
    }

    public function updated($propertyName)
    {
        // Re-load students when a filter is updated
        if (in_array($propertyName, ['formFilter', 'sectionFilter', 'statusFilter'])) {
            $this->loadStudents();
            $this->loadFilterOptions();
        }
    }

    public function resetFilters()
    {
        $this->formFilter = '';
        $this->sectionFilter = '';
        $this->statusFilter = '';
        $this->loadStudents();
        $this->loadFilterOptions();
    }

    public function closeAction()
    {
        // Reset all flags to false to return to the default student table
        $this->isEditingStudent = false;
        $this->isViewingDetails = false;
        $this->isDeleting = false;
        $this->isSuspendingStudent = false;
        $this->isViewingHistoryDetails = false;
        $this->isApproving = false;
        $this->isRejectingStudent = false;
        $this->isSendingStudentMail = false;
        $this->isExpellingStudent = false;
        $this->isViewingDetails = false;
        $this->isEditingAll = false;
        $this->selectedStudent = null;
    }


    public function removeFilter($filterName)
    {
        if ($filterName === 'formFilter') {
            $this->formFilter = '';
        } elseif ($filterName === 'sectionFilter') {
            $this->sectionFilter = '';
        } elseif ($filterName === 'statusFilter') {
            $this->statusFilter = '';
        }

        $this->resetPage(); // Reset pagination if using it
    }


    public function editStudent($studentId)
    {
        $this->selectedStudent = StudentRecord::findOrFail($studentId); // Retrieve the student record
        $this->isEditingStudent = true; // Set editing flag
        $this->resetOtherFlags('isEditingStudent'); // Reset any other flags if necessary
    }

    public function saveStudent()
    {
        $this->validate([
            'selectedStudent.parent_id_no' => 'nullable|string|max:255',
            'selectedStudent.my_class_id' => 'required|integer|exists:my_classes,id', // Assuming my_classes table exists
            'selectedStudent.section_id' => 'required|integer|exists:sections,id', // Assuming sections table exists
            'selectedStudent.adm_no' => 'nullable|string|max:30|unique:student_records,adm_no,' . $this->selectedStudent->id,
            'selectedStudent.dorm_id' => 'nullable|integer|exists:dorms,id', // Assuming dorms table exists
            'selectedStudent.year_admitted' => 'nullable|string|max:4',
            'selectedStudent.kcpe' => 'required|string',
            'selectedStudent.first_name' => 'required|string|max:255',
            'selectedStudent.middle_name' => 'nullable|string|max:255',
            'selectedStudent.last_name' => 'required|string|max:255',
            'selectedStudent.email' => 'nullable|email|max:255',
            'selectedStudent.gender' => 'required|string|in:male,female,other', // Specify allowed genders
            'selectedStudent.phone' => 'nullable|string|max:15',
            'selectedStudent.dob' => 'nullable|date',
            'selectedStudent.nal_id' => 'nullable|integer',
            'selectedStudent.state_id' => 'nullable|integer',
            'selectedStudent.lga_id' => 'nullable|integer',
            'selectedStudent.town' => 'nullable|string|max:255',
            'selectedStudent.bg_id' => 'nullable|integer',
            'selectedStudent.photo' => 'nullable|string|max:255',
            'selectedStudent.status' => 'nullable|string|max:255',
            'selectedStudent.student_password' => 'nullable|string|max:255',
        ]);

        // Save the changes
        $this->selectedStudent->save();

        // Optionally reset the editing flag
        $this->isEditingStudent = false;

        // Flash a success message
        $this->alert('success', 'Student record updated successfully!');
    }




    // Add other action methods like viewStudent, studentExpulsion, suspendStudent, etc.



    // Helper method to reset all flags except the current one
    protected function resetOtherFlags($currentFlag)
    {
        $flags = [
            'isEditingStudent',
            'isViewingDetails',
            'isExpellingStudent',
            'isSuspendingStudent',
            'isViewingHistoryDetails',
            'isApproving',
            'isRejecting',
            'showDeleteModal',
            // Add more flags as needed for other actions
        ];

        foreach ($flags as $flag) {
            if ($flag !== $currentFlag) {
                $this->$flag = false;
            }
        }
    }
}
