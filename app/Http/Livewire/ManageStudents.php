<?php

namespace App\Http\Livewire;


use Livewire\Component;
use Livewire\WithPagination;
use App\Models\StudentRecord;
 // Assuming you have a Class model
use App\Models\Section; // Assuming you have a Section model
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\StudentsImport;
use App\Models\MyClass;
use Illuminate\Support\Facades\DB;


class ManageStudents extends Component
{
    use WithPagination;

    public $searchTerm = '';
    public $filterClass = '';
    public $bulkAction = '';
    public $importFile;
    public $selectAll = false;
    public $selectedStudents = [];
    public $selectedStudent;
    public $selectedStudentHistory;
    public $totalSubmissions = 0;
    public $approvedSubmissions = 0;
    public $pendingSubmissions = 0;
    public $disapprovedSubmissions = 0;
    public $mystudents;

    protected $rules = [
        'selectedStudent.user.name' => 'required|string|max:255',
        'selectedStudent.user.email' => 'required|email|max:255',
        'selectedStudent.user.gender' => 'required|in:male,female',
        'selectedStudent.my_class_id' => 'required|exists:classes,id',
        'selectedStudent.section_id' => 'required|exists:sections,id',
    ];

    public function mount()
    {
        $this->mystudents=DB::select("select student_records.*,my_classes.name as classname,sections.name as sectionname,
        parent_details.parent_first_name,parent_details.parent_last_name,parent_details.parent_phone_number
        from student_records join my_classes on student_records.my_class_id=my_classes.id join sections
        on student_records.section_id=sections.id join parent_details on student_records.parent_id=parent_details.parent_id_no order by student_records.id desc");
    
    }

    public function deleteRecord($recordId)
    {
        //dd('hello people');
        $deleted = DB::delete('DELETE FROM student_records WHERE id = ?', [$recordId]);

        if ($deleted)
        {
            $this->mystudents=DB::select("select student_records.*,my_classes.name as classname,sections.name as sectionname,
            parent_details.parent_first_name,parent_details.parent_last_name,parent_details.parent_phone_number
            from student_records join my_classes on student_records.my_class_id=my_classes.id join sections
            on student_records.section_id=sections.id join parent_details on student_records.parent_id=parent_details.parent_id_no order by student_records.id desc");
            session()->flash('message', 'Record deleted successfully.');
        } 
        else 
        {
            session()->flash('error', 'Record not found.');
        }
    }

    public function render()
    {            
        return view('livewire.manage-students');
    }

    public function applyBulkAction()
    {
        if ($this->bulkAction === 'approve') {
            StudentRecord::whereIn('id', $this->selectedStudents)->update(['status' => 'approved']);
        } elseif ($this->bulkAction === 'disapprove') {
            StudentRecord::whereIn('id', $this->selectedStudents)->update(['status' => 'disapproved']);
        } elseif ($this->bulkAction === 'delete') {
            StudentRecord::destroy($this->selectedStudents);
        }
        $this->reset(['selectedStudents', 'bulkAction']);
        $this->fetchStatistics();
    }

    public function importFromFile()
    {
        if ($this->importFile) {
            Excel::import(new StudentsImport, $this->importFile->getRealPath());
            $this->reset('importFile');
            $this->fetchStatistics();
        }
    }

    public function exportToCsv()
    {
        $fileName = 'students-' . now()->format('Y-m-d') . '.csv';
        $students = StudentRecord::all();
        $headers = ['Content-Type' => 'text/csv'];
        $handle = fopen('php://output', 'w');
        fputcsv($handle, ['Name', 'Email', 'Gender', 'Class', 'Section', 'Status']);
        foreach ($students as $student) {
            fputcsv($handle, [
                $student->user->name ?? 'N/A',
                $student->user->email ?? 'N/A',
                $student->user->gender ?? 'N/A',
                $student->my_class->name ?? 'N/A',
                $student->section->name ?? 'N/A',
                $student->status,
            ]);
        }
        fclose($handle);
        return response()->stream(
            function () use ($handle) {
                fclose($handle);
            },
            200,
            $headers
            )->header('Content-Disposition', 'attachment; filename="' . $fileName . '"');
    }

    public function viewStudent($id)
    {
        $this->selectedStudent = StudentRecord::find($id);
        $this->selectedStudentHistory = $this->selectedStudent->history;
    }

    public function editStudent($id)
    {
        $this->selectedStudent = StudentRecord::find($id);
    }

    public function updateStudent()
    {
        $this->validate();
        $this->selectedStudent->user->update([
            'name' => $this->selectedStudent->user->name,
            'email' => $this->selectedStudent->user->email,
            'gender' => $this->selectedStudent->user->gender,
        ]);
        $this->selectedStudent->update([
            'my_class_id' => $this->selectedStudent->my_class_id,
            'section_id' => $this->selectedStudent->section_id,
        ]);
        $this->emit('studentUpdated');
    }

    public function approveSubmission($id)
    {
        StudentRecord::find($id)->update(['status' => 'approved']);
        $this->fetchStatistics();
    }

    public function disapproveSubmission($id)
    {
        StudentRecord::find($id)->update(['status' => 'disapproved']);
        $this->fetchStatistics();
    }

    public function confirmDelete($id)
    {
        $this->selectedStudent = StudentRecord::find($id);
    }

    public function deleteStudent()
    {
        if ($this->selectedStudent) {
            $this->selectedStudent->delete();
            $this->reset('selectedStudent');
            $this->fetchStatistics();
        }
    }

    public function viewHistory($id)
    {
        $this->selectedStudent = StudentRecord::find($id);
        $this->selectedStudentHistory = $this->selectedStudent->history;
    }

    protected function fetchStatistics()
    {
        $this->totalSubmissions = StudentRecord::count();
        $this->approvedSubmissions = StudentRecord::where('status', 'approved')->count();
        $this->pendingSubmissions = StudentRecord::where('status', 'pending')->count();
        $this->disapprovedSubmissions = StudentRecord::where('status', 'disapproved')->count();
    }
}
