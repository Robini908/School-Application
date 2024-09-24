<?php

namespace App\Http\Livewire;

use App\Models\Exam;
use App\Models\MyClass;
use App\Models\Section;
use App\Models\Subject;
use Livewire\Component;
use App\Models\ExamMarks;
use Livewire\WithPagination;
use App\Models\StudentRecord;

class MarkListManagement extends Component
{
    use WithPagination;

    public $classId;
    public $examId;
    public $sectionId;
    public $studentDetails = []; // Property to hold student details
    public $marks;
    public $showingDetails = false; // Flag to show/hide details card
    public $selectedAdmNo; // Holds the selected admission number

    public $studentAdditionalDetails = []; // Initialize the additional details property


    public function mount()
    {
        // Initialize marks as an empty collection
        $this->marks = collect();
    }

    public function render()
    {
        // Fetch all classes for selection
        $classes = MyClass::all();

        // Fetch exams based on selected class
        $exams = $this->classId ? Exam::whereHas('classes', function ($query) {
            $query->where('class_id', $this->classId);
        })->get() : collect();

        // Fetch sections based on selected class
        $sections = $this->classId ? Section::where('my_class_id', $this->classId)->get() : collect();

        // Fetch students based on selected class and section
        $students = $this->classId && $this->sectionId ? StudentRecord::where('my_class_id', $this->classId)
            ->where('section_id', $this->sectionId)
            ->get() : collect();

        return view('livewire.mark-list-management', [
            'classes' => $classes,
            'exams' => $exams,
            'sections' => $sections,
            'students' => $students,
            'marks' => $this->marks,
        ]);
    }


    public function updatedClassId()
    {
        // Reset fields when class is changed
        $this->reset(['examId', 'sectionId', 'marks', 'showingDetails']);
        $this->sectionId = null;
    }

    public function updatedSectionId()
    {
        // Reset marks when section is changed
        $this->marks = collect();
        $this->fetchMarks();
    }

    public function updatedExamId()
    {
        // Reset marks when exam is changed
        $this->marks = collect();
        $this->fetchMarks();
    }

    public function fetchStudentDetails($admNo)
    {
        // Fetch the student by admission number
        $student = StudentRecord::with('my_class', 'section')->where('adm_no', $admNo)->first();

        if ($student) {
            // Get all available subjects
            $subjects = Subject::all(); // Assuming you have a Subject model

            // Get marks for the student
            $marks = ExamMarks::with('subject')
                ->where('student_id', $student->id)
                ->get()
                ->keyBy('subject_id') // Key marks by subject ID for easier access
                ->toArray();

            // Prepare student details with subjects and their marks
            $this->studentDetails = [];
            foreach ($subjects as $subject) {
                $this->studentDetails[] = [
                    'subject_name' => $subject->subject_name,
                    'marks' => isset($marks[$subject->id]) ? $marks[$subject->id]['marks'] : 'N/A', // Check if marks exist, else 'N/A'
                ];
            }

            // Fetch class and section names using their relationships
            $className = $student->my_class ? $student->my_class->name : 'N/A'; // Assuming you have a relationship named 'my_class'
            $sectionName = $student->section ? $student->section->name : 'N/A'; // Assuming you have a relationship named 'section'

            // Add additional student details
            $this->studentAdditionalDetails = [
                'class_name' => $className,
                'section_name' => $sectionName,
                'photo' => $student->photo,
                'gender' => $student->gender,
                'first_name' => $student->first_name,
                'middle_name' => $student->middle_name,
                'last_name' => $student->last_name,
            ];

            // Set the selected admission number and show details
            $this->selectedAdmNo = $admNo;
            $this->showingDetails = true;
        } else {
            $this->studentDetails = []; // Reset if no student found
            $this->studentAdditionalDetails = []; // Reset additional details
        }
    }




    public function closeDetails()
    {
        $this->showingDetails = false; // Hide details card
        $this->selectedAdmNo = null; // Reset selected admission number
        $this->studentDetails = []; // Reset student details
    }

    public function fetchMarks()
    {
        if ($this->sectionId && $this->examId) {
            // Fetch student IDs from the selected section
            $studentIds = StudentRecord::where('section_id', $this->sectionId)->pluck('id');

            // Fetch marks using model IDs and eager load relationships
            $marks = ExamMarks::with(['student', 'subject'])
                ->where('exam_id', $this->examId)
                ->whereIn('student_id', $studentIds)
                ->get();

            // Get all subjects that can be displayed in the table
            $allSubjects = ExamMarks::with('subject')->where('exam_id', $this->examId)->pluck('subject_id')->unique();

            // Format marks to include student names and subject names
            $this->marks = $marks->groupBy('student_id')->map(function ($marks, $studentId) use ($allSubjects) {
                $firstMark = $marks->first(); // Get the first mark to fetch student details

                // Create a default array with N/A for all subjects
                $subjectMarks = $allSubjects->mapWithKeys(function ($subjectId) use ($marks) {
                    // Find the mark for this subject
                    $subjectMark = $marks->firstWhere('subject_id', $subjectId);

                    // Get subject name or default to 'N/A'
                    $subjectName = $subjectMark ? $subjectMark->subject->subject_name : 'N/A';
                    $marksValue = $subjectMark ? $subjectMark->marks : 'N/A'; // Default to 'N/A' if no marks

                    return [$subjectName => $marksValue]; // Use subject name as the key
                });

                return [
                    'student_name' => $firstMark->student->first_name . ' ' . $firstMark->student->last_name,
                    'adm_no' => $firstMark->student->adm_no, // Add admission number
                    'marks' => $subjectMarks,
                ];
            });
        } else {
            $this->marks = collect(); // Reset if no valid selections
        }
    }
}
