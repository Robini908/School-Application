<?php

namespace App\Livewire;

use App\Models\Dorm;
use App\Models\MyClass;
use App\Models\Section;
use Livewire\Component;
use App\Models\BloodGroup;
use App\Models\ParentDetail;
use App\Models\StudentRecord;
use Illuminate\Support\Facades\Mail;
use App\Mail\StudentAdmissionMail;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class AdmitStudent extends Component
{
    use WithFileUploads;
    use LivewireAlert;

    // Step management
    public $currentStep = 1;

    // Step 1: Personal Data
    public $first_name, $middle_name, $last_name, $email, $gender, $phone, $dob, $nationality, $state, $town, $bg_id, $photo;

    // Step 2: Student Data
    public $my_class_id, $section_id, $year_admitted, $dorm_id, $upi_number, $adm_no, $kcpe;

    // Step 3: Parent Details
    public $parent_id_no, $parent_first_name, $parent_middle_name, $parent_last_name, $parent_phone, $parent_email, $parent_password;

    // Step 4: Password
    public $password, $password_confirmation;

    // Sections for the selected class
    public $sections = [];

    // When my_class_id changes, fetch sections
    public function updatedMyClassId($value)
    {
        $this->sections = Section::where('my_class_id', $value)->get();
    }

    // Validation Rules


    protected $rules = [
        // Step 1: Personal Data
        'first_name' => 'required|string|max:255',
        'middle_name' => 'nullable|string|max:255',
        'last_name' => 'required|string|max:255',
        'email' => 'nullable|email|unique:student_records,email|max:255',
        'gender' => 'required|in:Male,Female',
        'phone' => 'nullable|string|max:20|regex:/^\+?\d{10,15}$/',
        'dob' => 'nullable|date|before:today|after:1900-01-01',
        'nationality' => 'required|string|max:255|in:Kenyan,Other',
        'state' => 'required|string|max:255',
        'town' => 'required|string|max:255',
        'bg_id' => 'nullable|exists:blood_groups,id',
        'photo' => 'nullable|image|max:2048|mimes:jpeg,png,jpg',

        // Step 2: Student Data
        'my_class_id' => 'required|exists:my_classes,id',
        'section_id' => 'required|exists:sections,id',
        'year_admitted' => 'required|numeric|min:2000', // Removed dynamic max rule
        'dorm_id' => 'nullable|exists:dorms,id',
        'upi_number' => 'string|max:255|unique:student_records,upi_number',
        'adm_no' => 'required|string|max:255|unique:student_records,adm_no',
        'kcpe' => 'nullable|numeric|min:0|max:500',

        // Step 3: Parent Details
        'parent_id_no' => 'required|string|max:255|unique:parent_details,parent_id_no|regex:/^\d{8}$/',
        'parent_first_name' => 'required|string|max:255|',
        'parent_middle_name' => 'nullable|string|max:255|',
        'parent_last_name' => 'required|string|max:255|',
        'parent_phone' => 'required|string|max:20|regex:/^\+?\d{10,15}$/',
        'parent_email' => 'required|email|unique:parent_details,parent_email|max:255',
        'parent_password' => 'required|string|min:8|max:255',

        // Step 4: Password
        'password' => 'required|string|min:8|max:255|confirmed',
    ];

    protected $messages = [
        // Step 1: Personal Data
        'first_name.required' => 'The first name field is required.',
        'first_name.regex' => 'The first name may only contain letters, spaces, and hyphens.',
        'first_name.max' => 'The first name must not exceed 255 characters.',

        'middle_name.required' => 'The middle name field is required.',
        'middle_name.regex' => 'The middle name may only contain letters, spaces, and hyphens.',
        'middle_name.max' => 'The middle name must not exceed 255 characters.',

        'last_name.required' => 'The last name field is required.',
        'last_name.regex' => 'The last name may only contain letters, spaces, and hyphens.',
        'last_name.max' => 'The last name must not exceed 255 characters.',

        'email.email' => 'Please enter a valid email address.',
        'email.unique' => 'This email address is already in use.',
        'email.max' => 'The email must not exceed 255 characters.',

        'gender.required' => 'Please select a gender.',
        'gender.in' => 'The selected gender is invalid. Choose either "Male" or "Female".',

        'phone.regex' => 'The phone number must be 10–15 digits long and may start with a +.',
        'phone.max' => 'The phone number must not exceed 20 characters.',

        'dob.date' => 'Please enter a valid date of birth.',
        'dob.before' => 'The date of birth must be in the past.',
        'dob.after' => 'The date of birth must be after 1900-01-01.',

        'nationality.required' => 'The nationality field is required.',
        'nationality.in' => 'The nationality must be either "Kenyan" or "Other".',

        'state.required' => 'The county/state field is required.',
        'state.exists' => 'The selected county/state is invalid.',

        'town.required' => 'The town field is required.',
        'town.max' => 'The town must not exceed 255 characters.',

        'bg_id.exists' => 'The selected blood group is invalid.',

        'photo.image' => 'The uploaded file must be an image.',
        'photo.max' => 'The image must not exceed 2MB in size.',
        'photo.mimes' => 'The image must be a file of type: jpeg, png, jpg.',

        // Step 2: Student Data
        'my_class_id.required' => 'Please select a class.',
        'my_class_id.exists' => 'The selected class is invalid.',

        'section_id.required' => 'Please select a section.',
        'section_id.exists' => 'The selected section is invalid.',

        'year_admitted.required' => 'The year admitted field is required.',
        'year_admitted.numeric' => 'The year admitted must be a number.',
        'year_admitted.min' => 'The year admitted must be at least 2000.',
        'year_admitted.max' => 'The year admitted must not be in the future.',

        'dorm_id.exists' => 'The selected dormitory is invalid.',

        'upi_number.required' => 'The UPI number field is required.',
        'upi_number.regex' => 'The UPI number must be alphanumeric and 10–15 characters long.',
        'upi_number.unique' => 'This UPI number is already in use.',

        'adm_no.regex' => 'The admission number must be alphanumeric and 5–10 characters long.',
        'adm_no.unique' => 'This admission number is already in use.',

        'kcpe.numeric' => 'The KCPE marks must be a number.',
        'kcpe.min' => 'The KCPE marks must be at least 0.',
        'kcpe.max' => 'The KCPE marks must not exceed 500.',

        // Step 3: Parent Details
        'parent_id_no.required' => 'The parent ID number field is required.',
        'parent_id_no.regex' => 'The parent ID number must be exactly 8 digits.',
        'parent_id_no.unique' => 'This parent ID number is already in use.',

        'parent_first_name.required' => 'The parent first name field is required.',
        'parent_first_name.regex' => 'The parent first name may only contain letters, spaces, and hyphens.',
        'parent_first_name.max' => 'The parent first name must not exceed 255 characters.',

        'parent_middle_name.regex' => 'The parent middle name may only contain letters, spaces, and hyphens.',
        'parent_middle_name.max' => 'The parent middle name must not exceed 255 characters.',

        'parent_last_name.required' => 'The parent last name field is required.',
        'parent_last_name.regex' => 'The parent last name may only contain letters, spaces, and hyphens.',
        'parent_last_name.max' => 'The parent last name must not exceed 255 characters.',

        'parent_phone.required' => 'The parent phone number field is required.',
        'parent_phone.regex' => 'The parent phone number must be 10–15 digits long and may start with a +.',
        'parent_phone.max' => 'The parent phone number must not exceed 20 characters.',

        'parent_email.required' => 'The parent email field is required.',
        'parent_email.email' => 'Please enter a valid email address.',
        'parent_email.unique' => 'This email address is already in use.',
        'parent_email.max' => 'The parent email must not exceed 255 characters.',

        'parent_password.required' => 'The parent password field is required.',
        'parent_password.min' => 'The parent password must be at least 8 characters long.',
        'parent_password.max' => 'The parent password must not exceed 255 characters.',

        // Step 4: Password
        'password.required' => 'The password field is required.',
        'password.min' => 'The password must be at least 8 characters long.',
        'password.max' => 'The password must not exceed 255 characters.',
        'password.confirmed' => 'The password confirmation does not match.',
    ];

    // Move to the next step
    public function nextStep()
    {
        $this->validate($this->getStepRules());
        $this->currentStep++;
    }

    // Move to the previous step
    public function previousStep()
    {
        $this->currentStep--;
    }

    // Get validation rules for the current step

    protected function getStepRules()
    {
        $stepRules = [
            1 => ['first_name', 'middle_name', 'last_name', 'email', 'gender', 'phone', 'dob', 'nationality', 'state', 'town', 'bg_id', 'photo'],
            2 => ['my_class_id', 'section_id', 'year_admitted', 'dorm_id', 'upi_number', 'adm_no', 'kcpe'],
            3 => ['parent_id_no', 'parent_first_name', 'parent_middle_name', 'parent_last_name', 'parent_phone', 'parent_email', 'parent_password'],
            4 => ['password', 'password_confirmation'],
        ];

        // Add dynamic rules for year_admitted
        $dynamicRules = [
            'year_admitted' => 'max:' . date('Y'),
        ];

        // Merge dynamic rules with the base rules
        $rules = array_intersect_key($this->rules, array_flip($stepRules[$this->currentStep]));
        $rules = array_merge($rules, $dynamicRules);

        return $rules;
    }

    // Submit the form

    public function submit()
    {
        $this->validate();

        // Save Parent Details
        $parent = ParentDetail::create([
            'parent_id_no' => $this->parent_id_no,
            'parent_first_name' => $this->parent_first_name,
            'parent_middle_name' => $this->parent_middle_name,
            'parent_last_name' => $this->parent_last_name,
            'parent_phone_number' => $this->parent_phone,
            'parent_email' => $this->parent_email,
            'parent_password' => bcrypt($this->parent_password),
        ]);

        // Save Student Record
        $student = StudentRecord::create([
            'parent_id_no' => $parent->parent_id_no,
            'my_class_id' => $this->my_class_id,
            'section_id' => $this->section_id,
            'adm_no' => $this->adm_no,
            'dorm_id' => $this->dorm_id,
            'year_admitted' => $this->year_admitted,
            'kcpe' => $this->kcpe,
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
            'photo' => $this->photo ? $this->photo->store('photos', 'public') : null,
            'student_password' => bcrypt($this->password),
        ]);

        // Attach the student to the selected dorm in the pivot table
        if ($this->dorm_id) {
            DB::table('dorm_student')->insert([
                'student_id' => $student->id,
                'dorm_id' => $this->dorm_id,
                'year' => $this->year_admitted, // Use the year_admitted field
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Send admission email to both parent and student
        $this->sendAdmissionEmail($student, $parent);

        // Reset form
        $this->reset();
        $this->alert('success', 'Student admission successful!', [
            'position' => 'top-end',
            'timer' => 3000,
            'toast' => true,
            'timerProgressBar' => true,
        ]);
    }

    protected function sendAdmissionEmail($student, $parent)
    {
        try {
            // Load relationships for the student
            $student->load('my_class', 'section', 'dorm');

            // School details (replace with your actual school details or fetch from config)
            $schoolName = config('app.name', 'Your School Name');
            $schoolWebsite = config('app.url', 'https://yourschool.com');
            $schoolEmail = config('mail.from.address', 'info@yourschool.com');

            // Prepare email data
            $emailData = [
                'student' => $student,
                'schoolName' => $schoolName,
                'schoolWebsite' => $schoolWebsite,
                'schoolEmail' => $schoolEmail,
            ];

            // Send email to the student
            Mail::to($student->email)->send(new StudentAdmissionMail($emailData));

            // Send email to the parent
            Mail::to($parent->parent_email)->send(new StudentAdmissionMail($emailData));
        } catch (\Exception $e) {
            // Log the error and show a warning to the user
            \Log::error('Failed to send admission email: ' . $e->getMessage());
            $this->alert('warning', 'Admission email could not be sent. Please contact the student and parent manually.');
        }
    }
    public function render()
    {
        return view('livewire.admit-student', [
            'bloodGroups' => BloodGroup::all(),
            'myClasses' => MyClass::all(),
            'dorms' => Dorm::all(),
        ]);
    }
}
