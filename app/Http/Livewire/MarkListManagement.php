<?php

namespace App\Http\Livewire;

use App\Models\Exam;
use App\Models\MyClass;
use App\Models\Section;
use App\Models\Subject;
use Livewire\Component;
use App\Models\ExamMarks;
use App\Models\GradingRange;
use Livewire\WithPagination;
use App\Models\StudentRecord;
use App\Models\GradingSystem;

class MarkListManagement extends Component
{
    use WithPagination;

    public $classId;
    public $examName;
    public $gradingSystemRanges = []; // Initialize as an empty array

    public $examId;
    public $sectionId;
    public $studentDetails = []; // Property to hold student details
    public $marks;
    public $showingDetails = false; // Flag to show/hide details card
    public $selectedAdmNo; // Holds the selected admission number

    public $studentAdditionalDetails = []; // Initialize the additional details property
    public $gradingSystemDetails = [];

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
        // Get the latest exam the student participated in
        $exam = Exam::whereHas('examMarks', function ($query) use ($student) {
            $query->where('student_id', $student->id);
        })->orderBy('created_at', 'desc')->first();

        // Initialize $marks as an empty array to avoid uninitialized variable issues
        $marks = [];

        // Check if an exam was found
        if ($exam) {
            // Get the grading system associated with the exam
            $gradingSystem = $exam->gradingSystem;

            // Check if the grading system exists
            if ($gradingSystem) {
                // Get all subjects for which the student has marks in this exam
                $marks = ExamMarks::with('subject')
                    ->where('student_id', $student->id)
                    ->where('exam_id', $exam->id)
                    ->get()
                    ->keyBy('subject_id') // Key marks by subject ID for easier access
                    ->toArray();

                // Prepare student details with subjects, marks, grades, remarks, and GPA
                $this->studentDetails = [];

                foreach ($marks as $mark) {
                    $subjectId = $mark['subject_id'];
                    $subjectName = $mark['subject']['subject_name'];
                    $subjectMarks = $mark['marks'];

                    // Fetch the grading range for this subject based on the marks and grading system
                    $gradingRange = GradingRange::where('grading_system_id', $gradingSystem->id)
                        ->where('subject_id', $subjectId)
                        ->where('range_from', '<=', $subjectMarks)
                        ->where('range_to', '>=', $subjectMarks)
                        ->first();

                    // Populate grade, remark, and GPA if the grading range exists
                    if ($gradingRange) {
                        $grade = $gradingRange->grade;
                        $remark = $gradingRange->remark;
                        $gpa = $gradingRange->gpa;
                    } else {
                        $grade = 'N/A';
                        $remark = 'N/A';
                        $gpa = 'N/A';
                    }

                    // Add the subject, marks, grade, remark, and GPA to the student details array
                    $this->studentDetails[] = [
                        'subject_name' => $subjectName,
                        'marks' => $subjectMarks,
                        'grade' => $grade,
                        'remark' => $remark,
                        'gpa' => $gpa,
                    ];
                }

                // Add the grading system name and description to student details
                $this->gradingSystemDetails = [
                    'name' => $gradingSystem->name,
                    'description' => $gradingSystem->description,
                    'effective_date' => $gradingSystem->effective_date,
                ];

            } else {
                // If no grading system found, set student details to display subjects but without grades, remarks, or GPA
                foreach ($marks as $mark) {
                    $this->studentDetails[] = [
                        'subject_name' => $mark['subject']['subject_name'] ?? 'N/A',
                        'marks' => $mark['marks'] ?? 'N/A',
                        'grade' => 'N/A',
                        'remark' => 'N/A',
                        'gpa' => 'N/A',
                    ];
                }
                $this->gradingSystemDetails = ['name' => 'N/A', 'description' => 'N/A', 'effective_date' => 'N/A'];
            }

            // Fetch class and section names using their relationships
            $className = $student->my_class ? $student->my_class->name : 'N/A'; 
            $sectionName = $student->section ? $student->section->name : 'N/A'; 

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

            // Set the selected admission number and pass exam name
            $this->selectedAdmNo = $admNo;
            $this->examName = $exam->name; // Exam name
            $this->showingDetails = true;
        } else {
            // Reset if no exam found
            $this->studentDetails = [];
            $this->studentAdditionalDetails = [];
            $this->gradingSystemDetails = [];
        }
    } else {
        // Reset if no student found
        $this->studentDetails = [];
        $this->studentAdditionalDetails = [];
        $this->gradingSystemDetails = [];
    }
}




    public function getStudentGradingDetails($marks, $subjectId)
    {
        // Fetch the grading system associated with the exam
        $gradingSystem = GradingSystem::where('id', $this->gradingSystemId) // Assume you have gradingSystemId defined
            ->first();

        if (!$gradingSystem) {
            return ['grade' => 'N/A', 'remark' => 'N/A', 'gpa' => 'N/A'];
        }

        // Get the grading ranges for the subject
        $gradingRange = GradingRange::where('subject_id', $subjectId)
            ->where('grading_system_id', $gradingSystem->id)
            ->where('range_from', '<=', $marks)
            ->where('range_to', '>=', $marks)
            ->first();

        // Return the grade, remark, and GPA
        if ($gradingRange) {
            return [
                'grade' => $gradingRange->grade,
                'remark' => $gradingRange->remark,
                'gpa' => $gradingRange->gpa,
            ];
        }

        // Default if no range found
        return ['grade' => 'N/A', 'remark' => 'N/A', 'gpa' => 'N/A'];
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
