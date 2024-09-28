<?php

namespace App\Http\Livewire;

use App\Models\StudentRecord;
use App\Models\MyClass;
use App\Models\Section;
use App\Models\Exam;
use Livewire\Component;

class CombinationFormula extends Component
{
    // Filter properties
    public $selectedClass;
    public $selectedYearAdmitted;
    public $selectedExamYear;
    public $selectedSection;
    public $selectedTerm; // New filter for term
    public $selectedYear; // New filter for year
    public $selectedExam; // Filter for exam name

    // Data properties
    public $classes;
    public $sections;
    public $exams;
    public $terms; // New property for terms
    public $students; // This will be a Collection
    public $examResults = []; // Store subjects and scores for selected exam

    public function mount()
    {
        // Initialize the lists for filtering options
        $this->classes = MyClass::all();
        $this->sections = [];
        $this->exams = [];
        $this->terms = []; // Initialize terms as an empty array or fetch from DB
        $this->students = collect(); // Initialize as an empty collection
    }

    public function updatedSelectedClass($classId)
    {
        // Load sections based on the selected class
        $this->sections = Section::where('my_class_id', $classId)->get();

        // Reset section and exams when class changes
        $this->resetSectionAndExams();

        // Load exams based on the selected class
        $this->exams = Exam::where('class_id', $classId)->get();

        // Re-filter students
        $this->filterStudents();
    }

    public function updatedSelectedSection($sectionId)
    {
        // Load exams based on the selected section
        $this->exams = Exam::where('section_id', $sectionId)
            ->where('class_id', $this->selectedClass) // Ensure the exam belongs to the selected class
            ->get();

        // Re-filter students
        $this->filterStudents();
    }

    public function updatedSelectedExam($examId)
    {
        // Fetch subjects and scores when the exam changes
        $this->loadExamResults($examId);
    }

    public function filterStudents()
    {
        $this->students = StudentRecord::query()
            ->when($this->selectedClass, function ($query) {
                $query->where('my_class_id', $this->selectedClass);
            })
            ->when($this->selectedYearAdmitted, function ($query) {
                $query->where('year_admitted', $this->selectedYearAdmitted);
            })
            ->when($this->selectedSection, function ($query) {
                $query->where('section_id', $this->selectedSection);
            })
            ->when($this->selectedTerm, function ($query) {
                $query->whereHas('examMarks.exam', function ($query) {
                    $query->where('term', $this->selectedTerm);
                });
            })
            ->when($this->selectedYear, function ($query) {
                $query->whereHas('examMarks.exam', function ($query) {
                    $query->where('year', $this->selectedYear);
                });
            })
            ->when($this->selectedExam, function ($query) {
                $query->whereHas('examMarks', function ($query) {
                    $query->where('exam_id', $this->selectedExam);
                });
            })
            ->get();
    }

    public function loadExamResults($examId)
    {
        // Load subjects and scores for the selected exam
        $this->examResults = [];

        foreach ($this->students as $student) {
            $marks = $student->examMarks()->where('exam_id', $examId)->with('subject')->get();
            if ($marks) {
                $this->examResults[$student->id] = $marks;
            }
        }
    }

    public function resetFilter($filter)
    {
        // Reset individual filters
        $this->$filter = null;
        $this->filterStudents(); // Re-filter students after resetting
        $this->examResults = []; // Reset exam results on filter reset
    }

    public function resetAllFilters()
    {
        // Reset all filters
        $this->selectedClass = null;
        $this->selectedYearAdmitted = null;
        $this->selectedSection = null;
        $this->selectedTerm = null;
        $this->selectedYear = null;
        $this->selectedExam = null;
        $this->students = collect(); // Reset students collection
        $this->sections = []; // Reset sections collection
        $this->exams = []; // Reset exams collection
        $this->terms = []; // Reset terms collection if applicable
        $this->examResults = []; // Reset exam results
    }

    public function resetSectionAndExams()
    {
        $this->selectedSection = null;
        $this->exams = []; // Clear exams when class is reset
    }

    public function render()
    {
        return view('livewire.combination-formula');
    }
}

