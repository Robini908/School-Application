<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\StudentRecord;
use Illuminate\Support\Facades\DB;

class ManageStudents extends Component
{
    use WithPagination;

    public $selectedStudent; // Stores the currently selected student's details
    public $mystudents;
    public $verificationStatus = '';
    public $student;

    protected $listeners = [
        'refreshStudents' => 'loadStudents',
        'studentSelected' => 'selectStudent' // Add listener for student selection
    ];

    public function mount()
    {
        // Initialize student data if needed or load students
        $this->loadStudents(); // Ensure students are loaded on mount
    }

    public function loadStudents()
    {
        $this->mystudents = DB::table('student_records')
            ->join('my_classes', 'student_records.my_class_id', '=', 'my_classes.id')
            ->join('sections', 'student_records.section_id', '=', 'sections.id')
            ->join('parent_details', 'student_records.parent_id', '=', 'parent_details.parent_id_no')
            ->select('student_records.*', 'my_classes.name as classname', 'sections.name as sectionname', 'parent_details.parent_first_name', 'parent_details.parent_last_name', 'parent_details.parent_phone_number')
            ->orderBy('student_records.id', 'desc')
            ->get();
    }

    public function verifyStatus($studentId)
    {
        $student = StudentRecord::find($studentId);

        if ($student) {
            if ($student->status == 'Pending Verification') {
                $student->status = 'Verified';
                $student->save();

                session()->flash('message', 'Student verified successfully.');
            } else {
                session()->flash('error', 'Student is already verified or ineligible for verification.');
            }

            $this->emit('refreshStudents');
        }
    }

    public function changeStatus($studentId, $newStatus)
    {
        $student = StudentRecord::find($studentId);

        if ($student) {
            $student->status = $newStatus;
            $student->save();

            session()->flash('message', "Student status changed to $newStatus successfully.");

            $this->emit('refreshStudents');
        }
    }

    public function render()
    {
        return view('livewire.manage-students', [
            'students' => $this->mystudents, // Pass data to the view
        ]);
    }
}
