<?php

namespace App\Http\Livewire;

use App\Models\Exam;
use App\Models\MyClass;
use App\Models\Section;
use App\Models\Subject;
use Livewire\Component;
use App\Models\StudentRecord;

class CombinationFormula extends Component
{
    // Filter properties
    public $selectedClass;
    public $selectedYearAdmitted;
    public $selectedExamYear;
    public $selectedSection;
    public $selectedTerm; // New filter for term
    public $selectedYear; // New filter for year
    public $selectedExam;
    public $selectedClassData;
    public $selectedSectionData;

    public $classes;
    public $student;
    public $sections;
    public $exams;

    public $marks = [];
    public $subjects = [];
    public $loading = false;

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
        $this->loading = true;
        // Load sections based on the selected class
        $this->sections = Section::where('my_class_id', $classId)->get();

        // Fetch the class to display its name later
        $this->selectedClassData = MyClass::find($classId);

        // Reset section and exams when class changes
        $this->resetSectionAndExams();

        // Load exams based on the selected class
        $this->exams = Exam::where('class_id', $classId)->get();

        // Reset marks and subjects
        $this->marks = [];
        $this->subjects = [];

        $this->loading = false;
    }

    public function updatedSelectedSection($sectionId)
    {
        $this->loading = true;
        // Load exams based on the selected section and class
        $this->exams = Exam::where('section_id', $sectionId)
            ->where('class_id', $this->selectedClass) // Ensure the exam belongs to the selected class
            ->get();

        // Fetch the selected section data for display
        $this->selectedSectionData = Section::find($sectionId);

        // Reset marks and subjects
        $this->marks = [];
        $this->subjects = [];

        $this->loading = false;
    }

    public function updatedSelectedExam($examId)
    {
        $this->loading = true;
        // Fetch the selected exam along with grading system and subjects
        $exam = Exam::with('gradingSystem.subjects')->find($examId);

        if (!$exam) {
            // Handle case where exam is not found
            $this->marks = []; // Clear marks if exam not found
            $this->subjects = [];
            return;
        }

        // Fetch students and their marks for the selected exam and section
        $this->students = StudentRecord::where('my_class_id', $this->selectedClass)
            ->when($this->selectedSection, function ($query) {
                $query->where('section_id', $this->selectedSection); // Filter by selected section
            })
            ->with(['examMarks' => function ($query) use ($examId) {
                $query->where('exam_id', $examId);
            }])
            ->get();

        // Create a collection for marks
        $this->marks = [];
        $this->subjects = $exam->gradingSystem->subjects; // Get subjects associated with the grading system

        // Populate marks with student names and an associative array of subject marks
        foreach ($this->students as $student) {
            $studentMarks = [];

            foreach ($student->examMarks as $mark) {
                // Store marks with subject ID as key
                $studentMarks[$mark->subject_id] = $mark->marks; // Assuming subject_id is available
            }

            // Add student information and their marks to the marks collection
            $this->marks[] = [
                'student_id' => $student->id, // Add student ID for reference
                'student_name' => "{$student->first_name} {$student->last_name}", // Full student name
                'marks' => $studentMarks, // Store marks by subject ID
            ];
        }

        $this->loading = false;
    }

    public function filterStudents()
    {
        // Reset exams if no class is selected
        if ($this->selectedClass) {
            // Fetch exams for the selected class and store in a property
            $this->exams = MyClass::find($this->selectedClass)->exams;
        } else {
            $this->exams = collect(); // Reset if no class is selected
        }

        // Start with the base query for StudentRecord
        $this->students = StudentRecord::query();

        // Apply filters based on user selections
        if ($this->selectedClass) {
            $this->students->where('my_class_id', $this->selectedClass);
        }

        if ($this->selectedSection) {
            $this->students->where('section_id', $this->selectedSection);
        }

        if ($this->selectedYearAdmitted) {
            $this->students->where('year_admitted', $this->selectedYearAdmitted);
        }

        if ($this->selectedExam) {
            $this->students->whereHas('examMarks', function ($query) {
                $query->where('exam_id', $this->selectedExam);
            });
        }

        if ($this->selectedTerm) {
            $this->students->whereHas('examMarks.exam', function ($query) {
                $query->where('term', $this->selectedTerm);
            });
        }

        if ($this->selectedYear) {
            $this->students->whereHas('examMarks.exam', function ($query) {
                $query->where('year', $this->selectedYear);
            });
        }

        // Finally, retrieve the results
        $this->students = $this->students->get();
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
