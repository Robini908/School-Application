<?php

namespace App\Livewire;

use App\Models\Dorm;
use App\Models\MyClass;
use App\Models\Section;
use Livewire\Component;
use App\Models\BloodGroup;
use App\Models\ParentDetail;
use App\Models\StudentRecord;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use App\Notifications\SystemNotification; // Import the notification class

class EditStudent extends Component
{
    use WithFileUploads;
    use LivewireAlert;
    // Student ID or Admission Number
    public $studentId;
    public $admNo;
    public $old_password;

    public $parent_old_password;
    public $parent_new_password;
    public $parent_new_password_confirmation;
    public $adminOverride = false;
    // Student Details
    public $student;
    public $first_name, $middle_name, $last_name, $email, $gender, $phone, $dob, $nationality, $state, $town, $bg_id;

    // Student Academic Details
    public $my_class_id, $section_id, $year_admitted, $dorm_id, $upi_number, $adm_no, $kcpe;

    // Parent Details
    public $parent_id_no, $parent_first_name, $parent_middle_name, $parent_last_name, $parent_phone, $parent_email;

    // Password (Optional)
    public $password, $password_confirmation;

    // Sections for the selected class
    public $sections = [];
    public $isAdmin;

    // Flags for editing specific sections
    public $editPersonalDetails = false;
    public $editAcademicDetails = false;
    public $editParentDetails = false;
    public $editPassword = false;
    public $userType;


    // Fetch student details when component mounts
    public function mount($studentId = null, $admNo = null)
    {
        $this->studentId = $studentId;
        $this->admNo = $admNo;

        // Fetch student details
        $this->fetchStudentDetails();
        $this->userType = auth()->user()->user_type;
        $this->isAdmin = auth()->user()->user_type === 'admin' || auth()->user()->user_type === 'super_admin';
    }


    // Fetch student details
    public function fetchStudentDetails()
    {
        if ($this->studentId) {
            $this->student = StudentRecord::find($this->studentId);
        } elseif ($this->admNo) {
            $this->student = StudentRecord::where('adm_no', $this->admNo)->first();
        }

        if ($this->student) {
            // Debugging: Check if student is found
            logger()->info('Student found:', ['student' => $this->student]);

            // Personal Details
            $this->first_name = $this->student->first_name;
            $this->middle_name = $this->student->middle_name;
            $this->last_name = $this->student->last_name;
            $this->email = $this->student->email;
            $this->gender = $this->student->gender;
            $this->phone = $this->student->phone;
            $this->dob = $this->student->dob;
            $this->nationality = $this->student->nationality;
            $this->state = $this->student->state;
            $this->town = $this->student->town;
            $this->bg_id = $this->student->bg_id;

            // Academic Details
            $this->my_class_id = $this->student->my_class_id;
            $this->section_id = $this->student->section_id;
            $this->year_admitted = $this->student->year_admitted;
            $this->dorm_id = $this->student->dorm_id;
            $this->upi_number = $this->student->upi_number; // Ensure this is being fetched
            $this->adm_no = $this->student->adm_no;
            $this->kcpe = $this->student->kcpe;
            $this->password = ''; // Reset password field

            // Debugging: Check if UPI number is fetched
            logger()->info('UPI Number:', ['upi_number' => $this->upi_number]);

            // Parent Details
            $parent = ParentDetail::where('parent_id_no', $this->student->parent_id_no)->first();
            if ($parent) {
                $this->parent_id_no = $parent->parent_id_no;
                $this->parent_first_name = $parent->parent_first_name;
                $this->parent_middle_name = $parent->parent_middle_name;
                $this->parent_last_name = $parent->parent_last_name;
                $this->parent_phone = $parent->parent_phone_number;
                $this->parent_email = $parent->parent_email;
            }
        } else {
            // Debugging: Student not found
            logger()->error('Student not found:', ['studentId' => $this->studentId, 'admNo' => $this->admNo]);
        }
    }

    // When my_class_id changes, fetch sections
    public function updatedMyClassId($value)
    {
        $this->sections = Section::where('my_class_id', $value)->get();
    }

    // Save changes

    public function savePassword()
    {
        // Validate old password length (only for students)
        if ($this->userType === 'student') {
            $this->validate([
                'old_password' => 'required',
            ]);

            // Verify old password (only for students)
            if (!Hash::check($this->old_password, $this->student->student_password)) {
                $this->addError('old_password', 'The old password is incorrect.');
                return;
            }
        }

        // Validate new password
        $this->validate([
            'password' => 'required|string|min:8|confirmed', // New password must be at least 8 characters and confirmed
        ]);

        // Update password
        $this->student->update([
            'student_password' => Hash::make($this->password),
        ]);

        // Determine the notification message
        $message = "Your password was changed successfully.";
        if ($this->userType !== 'student') {
            $message .= " If you did not perform this action, please contact support immediately.";
        }

        // Send notification to the student with a title "Password Reset"
        $this->student->notify(new SystemNotification($message, 'Password Reset'));

        // Reset fields
        $this->reset(['old_password', 'password', 'password_confirmation']);
        $this->editPassword = false;

        // Flash success message
        $this->alert('success', 'Password updated successfully!');
    }


    public function save()
    {
        // Validate and save personal details
        if ($this->editPersonalDetails) {
            $this->validate([
                'first_name' => 'required|string|max:255|regex:/^[A-Za-z\s\-]+$/',
                'middle_name' => 'required|string|max:255|regex:/^[A-Za-z\s\-]+$/',
                'last_name' => 'required|string|max:255|regex:/^[A-Za-z\s\-]+$/',
                'email' => 'nullable|email|max:255',
                'gender' => 'required|in:Male,Female',
                'phone' => 'nullable|string|max:20|regex:/^\+?\d{10,15}$/',
                'dob' => 'nullable|date|before:today|after:1900-01-01',
                'nationality' => 'required|string|max:255|in:Kenyan,Other',
                'state' => 'required|string|max:255',
                'town' => 'required|string|max:255',
                'bg_id' => 'nullable|exists:blood_groups,id',
            ]);

            $this->student->update([
                'first_name' => $this->first_name,
                'middle_name' => $this->middle_name,
                'last_name' => $this->last_name,
                'email' => $this->email,
                'gender' => $this->gender,
                'phone' => $this->phone,
                'dob' => $this->dob,
                'nationality' => $this->nationality,
                'state' => $this->state,
                'town' => $this->town,
                'bg_id' => $this->bg_id,
            ]);
        }

        // Validate and save academic details
        if ($this->editAcademicDetails) {
            $this->validate([
                'my_class_id' => 'required|exists:my_classes,id',
                'section_id' => 'required|exists:sections,id',
                'year_admitted' => 'required|numeric|min:2000|max:' . date('Y'),
                'dorm_id' => 'nullable|exists:dorms,id',
                'upi_number' => 'required|string|max:255|regex:/^[A-Z0-9]{10,15}$/',
                'adm_no' => 'nullable|string|max:255|regex:/^[A-Z0-9]{5,10}$/',
                'kcpe' => 'nullable|numeric|min:0|max:500',
            ]);

            $this->student->update([
                'my_class_id' => $this->my_class_id,
                'section_id' => $this->section_id,
                'year_admitted' => $this->year_admitted,
                'dorm_id' => $this->dorm_id,
                'upi_number' => $this->upi_number,
                'adm_no' => $this->adm_no,
                'kcpe' => $this->kcpe,
            ]);
        }

        // Validate and save parent details
        if ($this->editParentDetails) {
            $validationRules = [
                'parent_id_no' => 'required|string|max:255|regex:/^\d{8}$/',
                'parent_first_name' => 'required|string|max:255|regex:/^[A-Za-z\s\-]+$/',
                'parent_middle_name' => 'nullable|string|max:255|regex:/^[A-Za-z\s\-]+$/',
                'parent_last_name' => 'required|string|max:255|regex:/^[A-Za-z\s\-]+$/',
                'parent_phone' => 'required|string|max:20|regex:/^\+?\d{10,15}$/',
                'parent_email' => 'required|email|max:255',
                'parent_new_password' => 'required|string|min:8|confirmed',
                'parent_new_password_confirmation' => 'required|string|min:8',
            ];

            if (!$this->isAdmin) {
                $validationRules['parent_old_password'] = 'required|string|min:8';
            }

            $this->validate($validationRules);

            // Update parent details
            $parent = ParentDetail::updateOrCreate(
                ['parent_id_no' => $this->parent_id_no],
                [
                    'parent_first_name' => $this->parent_first_name,
                    'parent_middle_name' => $this->parent_middle_name,
                    'parent_last_name' => $this->parent_last_name,
                    'parent_phone_number' => $this->parent_phone,
                    'parent_email' => $this->parent_email,
                ]
            );

            // Update parent password if new password is provided
            if ($this->parent_new_password) {
                if ($this->isAdmin || Hash::check($this->parent_old_password, $parent->password)) {
                    $parent->update([
                        'password' => Hash::make($this->parent_new_password),
                    ]);

                    // Send notification to the parent
                    $message = "Your password was changed successfully.";
                    if ($this->isAdmin) {
                        $message .= " This action was performed by an administrator.";
                    } else {
                        $message .= " If you did not perform this action, please contact support immediately.";
                    }

                    $parent->notify(new SystemNotification($message, 'Password Reset'));
                } else {
                    $this->addError('parent_old_password', 'The old password is incorrect.');
                    return;
                }
            }
        }

        $this->editPersonalDetails = false;
        $this->editAcademicDetails = false;
        $this->editParentDetails = false;
        $this->editPassword = false;

        $this->alert('success', 'Student details updated successfully!', [
            'position' => 'top-end',
            'timer' => 3000,
            'toast' => true,
            'timerProgressBar' => true,
        ]);

    }



    public function render()
    {
        return view('livewire.edit-student', [
            'bloodGroups' => BloodGroup::all(),
            'myClasses' => MyClass::all(),
            'dorms' => Dorm::all(),
        ]);
    }
}
