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

    protected $rules = [
        'selectedStudent.user.name' => 'required|string|max:255',
        'selectedStudent.user.email' => 'required|email|max:255',
        'selectedStudent.user.gender' => 'required|in:male,female',
        'selectedStudent.my_class_id' => 'required|exists:classes,id',
        'selectedStudent.section_id' => 'required|exists:sections,id',
    ];

    public function mount()
    {
        $this->fetchStatistics();
    }

    public function render()
    {
        $students = StudentRecord::query()
            ->where('first_name', 'like', "%{$this->searchTerm}%")
            ->when($this->filterClass, function ($query) {
                $query->where('my_class_id', $this->filterClass);
            })
            ->paginate(10);

        $classes = MyClass::all();
        $sections = Section::all();

        return view('livewire.manage-students', compact('students', 'classes', 'sections'));
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
