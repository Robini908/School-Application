<?php

namespace App\Http\Livewire;

use App\Models\MyClass;
use App\Models\Section;
use Livewire\Component;
use App\Models\ParentDetail;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use App\Models\StudentRecord;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\DisapprovalNotification;
use App\Notifications\StudentExpelled;


class ManageStudents extends Component
{
    use WithPagination;
    use WithFileUploads;

    public $selectedStudent;
    // protected $mystudents;
    public $mystudents;

    public $file; // For file upload
    public $notificationContent; // For rich text editor content



    public $showDeleteModal = false;
    protected $mystudent; // Change to protected
    protected $filePath; // Change to protected
    public $formFilter = '';
    public $sectionFilter = '';
    public $statusFilter = '';
    public $classes = [];
    public $expulsionReason = '';
    public $expulsionType = '';

    // Flags for different actions
    public $isEditingStudent = false;
    public $isViewingDetails = false;

    public $isExpellingStudent = false;
    public $currentStep = 1;
    public $expulsionEndDate;

    public $isRejectingStudent = false;
    public $isSuspendingStudent = false;
    public $noResults = false;
    public $isSendingStudentMail = false;
    public $isViewingHistoryDetails = false;
    public $isApproving = false;
    public $expulsionDuration = null;
    public $isDeleting = false;
    public $isRejecting = false;
    public $disapprovalReason = ''; // To hold the disapproval reason

    public $forms = [];
    public $isDisapproving = false;
    public $sections = [];
    public $statuses = ['Active', 'Inactive'];

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

    public function toggleDisapproval()
    {
        $this->isDisapproving = !$this->isDisapproving;
    }

    public function cancelExpel()
    {
        // Reset the necessary properties
        $this->reset();

        // Optionally, you can also use a session message to notify the user
        session()->flash('info', 'Expulsion process cancelled.');
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

        // Validate the uploaded file
        $this->validate([
            'file' => 'nullable|mimes:pdf,jpg,png|max:10240', // Allow specific file types up to 10MB
        ]);

        // Store the file if uploaded
        $filePath = $this->file ? $this->file->store('email_attachments', 'public') : null;

        // Prepare email data
        $emailData = [
            'studentName' => "{$student->first_name} {$student->last_name}",
            'parentName'  => $student->parent_detail->parent_first_name ?? '',
            'notificationContent' => $this->notificationContent,
            'filePath' => $filePath,
        ];

        // Try sending the email
        try {
            Mail::send('emails.student-notification', $emailData, function ($message) use ($student, $filePath) {
                $message->to($student->email)->subject('Important Notification for Student');
                if ($student->parent_detail->parent_email) {
                    $message->cc($student->parent_detail->parent_email)->subject('Important Notification for Parent');
                }
                if ($filePath) {
                    $message->attach(storage_path("app/public/{$filePath}"));
                }
            });
            $this->isSendingStudentMail = false;
            // Notify the user of success
            session()->flash('success', 'Email sent successfully with attachment.');
        } catch (\Exception $e) {
            // Handle any errors that occur during email sending
            session()->flash('error', 'Failed to send email: ' . $e->getMessage());
        }
    }

    public function isSendingStudentMail($studentId)
    {
        // View student details and set flags
        $this->selectedStudent = StudentRecord::find($studentId);
        $this->isSendingStudentMail = true;
        $this->resetOtherFlags('isSendingStudentMail');
    }





    public function fetchStudents()
    {
        // Directly load students from the database
        $this->loadStudents();
    }

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

        // Execute the query and get the results
        $this->mystudents = $query->get(); // Fetch all student records

        // Check if no results found
        $this->noResults = $this->mystudents->isEmpty();
    }

    public function getStudentsWithExpulsionInfo()
    {
        return $this->mystudents->map(function ($student) {
            return [
                'id' => $student->id,
                'first_name' => $student->first_name,
                'last_name' => $student->last_name,
                'is_expelled' => (bool) $student->is_expelled,
                'expulsion_type' => $student->is_expelled ? ($student->expulsion_type ?? 'N/A') : null,
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

    // View Student Details
    public function viewStudent($studentId)
    {
        // Logic to view student details
        $this->selectedStudent = StudentRecord::find($studentId);
        $this->isViewingDetails = true;
        // Reset other flags
        $this->resetOtherFlags('isViewingDetails');
    }

    // Expel Student
    public function studentExpulsion($studentId)
    {
        // Fetch the student data with related models
        $this->selectedStudent = StudentRecord::with(['my_class', 'section', 'parent_detail'])->find($studentId);

        // Check if student exists
        if (!$this->selectedStudent) {
            session()->flash('error', 'Student not found.'); // Set session error message
            return;
        }

        // Set flags for expulsion process
        $this->isExpellingStudent = true;

        // Initialize the expulsion reason and type
        $this->expulsionReason = ''; // Initialize reason variable
        $this->expulsionType = ''; // Initialize expulsion type variable
        $this->expulsionDuration = null; // Initialize expulsion duration variable
    }

    public function sendNotificationToGuardians($student)
    {
        // Fetch the parent associated with the student
        $parent = ParentDetail::find($student->parent_id_no);

        // Check if the parent exists
        if ($parent) {
            // Send notification to the parent
            $parent->notify(new StudentExpelled($student));
        }
    }

    public function expelStudent()
    {
        // Validate the input fields
        $this->validate([
            'expulsionReason' => 'required|string|max:255',
            'expulsionType' => 'required|in:dismissal,withdrawal,permanent_exclusion',
            'expulsionEndDate' => 'nullable|date|after:today', // Validate the end date for temporary expulsions
        ]);

        // Update the student's expulsion status
        $this->selectedStudent->is_expelled = true;
        $this->selectedStudent->expulsion_reason = $this->expulsionReason;
        $this->selectedStudent->expelled_by = auth()->user()->id;
        $this->selectedStudent->expulsion_date = now();
        $this->selectedStudent->expulsion_type = $this->expulsionType;

        // Calculate the duration in weeks based on the end date if it's dismissal or withdrawal
        if (in_array($this->expulsionType, ['dismissal', 'withdrawal']) && $this->expulsionEndDate) {
            // Calculate the difference in weeks between now and the expulsion end date
            $endDate = \Carbon\Carbon::parse($this->expulsionEndDate);
            $weeksDifference = now()->diffInWeeks($endDate); // Get the difference in weeks

            // Set expulsion end date and duration in weeks
            $this->selectedStudent->expulsion_end_date = $endDate; // Set the end date
            $this->expulsionDuration = $weeksDifference; // Store the calculated weeks (for reference, if needed)
        } else {
            // For permanent exclusion, we set the end date to null
            $this->selectedStudent->expulsion_end_date = null; // No end date for permanent exclusions
        }

        // Save the student record
        $this->selectedStudent->save();

        // Notify the parent of the expelled student
        $this->sendNotificationToGuardians($this->selectedStudent);

        // Reload the student list
        $this->loadStudents();

        // Reset flags and data
        $this->isRejectingStudent = false;
        $this->resetExpulsion();

        // Set a success message
        session()->flash('success', 'Student expelled successfully.');
    }




    public function resetExpulsion()
    {
        $this->selectedStudent = null; // Clear selected student data
        $this->isExpellingStudent = false; // Reset expulsion flag
        $this->expulsionReason = ''; // Reset reason
        $this->expulsionType = ''; // Reset expulsion type
        $this->expulsionDuration = null; // Reset duration
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
    public function favoriteStudent($studentId)
    {
        // Logic to view student's history or favorite
        $this->selectedStudent = StudentRecord::find($studentId);
        $this->isViewingHistoryDetails = true;
        // Reset other flags
        $this->resetOtherFlags('isViewingHistoryDetails');
    }

    // Approve Student
    public function approveStudent($studentId)
    {
        // Fetch the student data with related models
        $this->selectedStudent = StudentRecord::with(['my_class', 'section', 'parent_detail'])->find($studentId);

        // Check if student exists
        if (!$this->selectedStudent) {
            session()->flash('error', 'Student not found.'); // Set session error message
            return;
        }

        // Set flags for approval process
        $this->isApproving = true;

        // Verify student information (you can customize these checks as needed)
        if (empty($this->selectedStudent->first_name) || empty($this->selectedStudent->last_name) || empty($this->selectedStudent->parent_id_no)) {
            session()->flash('error', 'Please ensure all required student information is filled out.');
            return;
        }
    }


    public function confirmApproval()
    {
        if (!$this->selectedStudent) {
            session()->flash('error', 'No student selected for approval.');
            return;
        }

        // Proceed with approving the student
        $this->selectedStudent->status = 'Approved';
        $this->selectedStudent->save();

        // Notify user of successful approval
        session()->flash('message', 'Student approved successfully.');

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
            session()->flash('error', 'No student selected for disapproval.');
            return;
        }

        // Ensure that the reason is provided
        if (empty($this->disapprovalReason)) {
            session()->flash('error', 'Please provide a reason for disapproval.');
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
        session()->flash('message', 'Student disapproved successfully.');

        // Refresh student list
        $this->loadStudents();
    }

    // Method to send an email notification to the student or parent
    public function sendDisapprovalNotification($student)
    {
        $email = $student->email ?? $student->parent_detail->email; // Assuming parent_detail has an email
        if ($email) {
            \Mail::to($email)->send(new DisapprovalNotification($student, $this->disapprovalReason));
        }
    }



    // Reject Student



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
        $this->isExpellingStudent = false;
        $this->isSuspendingStudent = false;
        $this->isViewingHistoryDetails = false;
        $this->isApproving = false;
        $this->isRejectingStudent = false;
        $this->isSendingStudentMail = false;
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
        session()->flash('message', 'Student record updated successfully!');
    }


    public function deleteRecord($studentId)
    {
        // Logic to delete student
        $this->selectedStudent = StudentRecord::find($studentId);
        $this->showDeleteModal = true;
        // Reset other flags
        $this->resetOtherFlags('showDeleteModal');
    }

    // Add other action methods like viewStudent, studentExpulsion, suspendStudent, etc.

    public function render()
    {
        return view('livewire.manage-students', [
            'noResults' => $this->mystudents->isEmpty(),

            'students' => $this->mystudents,
        ]);
    }

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
