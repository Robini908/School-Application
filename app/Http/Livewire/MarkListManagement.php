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
use App\Models\GradingSystem;
use App\Models\StudentRecord;
use Illuminate\Support\Facades\DB;

class MarkListManagement extends Component
{
    use WithPagination;

    public $classId;
    public $examName;
    public $gradingSystemRanges = []; // Initialize as an empty array
    public $totalMarks;
    public $meanScore;
    public $totalPoints;
    public $meanGrade;
    public $classPosition;
    public $streamPosition;


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

            // Initialize variables
            $marks = [];
            $totalMarks = 0;
            $totalPoints = 0;
            $meanScore = 0;

            // Check if an exam was found
            if ($exam) {
                // Get the grading system associated with the exam
                $gradingSystem = $exam->gradingSystem;

                if ($gradingSystem) {
                    // Fetch the student's marks for the subjects in the exam
                    $marks = ExamMarks::with('subject')
                        ->where('student_id', $student->id)
                        ->where('exam_id', $exam->id)
                        ->get()
                        ->keyBy('subject_id')
                        ->toArray();

                    $gradingSystemSubjects = DB::table('grading_system_subject')
                        ->where('grading_system_id', $gradingSystem->id)
                        ->pluck('subject_id')
                        ->toArray();

                    $missingSubjects = [];

                    foreach ($marks as $mark) {
                        if (!in_array($mark['subject_id'], $gradingSystemSubjects)) {
                            $missingSubjects[] = $mark['subject']['subject_name'] ?? 'Unknown Subject';
                        }
                    }

                    if (!empty($missingSubjects)) {
                        session()->flash('warning', 'The following subjects are not part of the grading system: ' . implode(', ', $missingSubjects));
                    }

                    $this->studentDetails = [];
                    $subjectCount = count($gradingSystemSubjects);

                    foreach ($gradingSystemSubjects as $subjectId) {
                        $subjectMarks = $marks[$subjectId] ?? null;

                        if ($subjectMarks) {
                            $subjectName = $subjectMarks['subject']['subject_name'];
                            $subjectMarksValue = $subjectMarks['marks'];

                            $gradingRange = GradingRange::where('grading_system_id', $gradingSystem->id)
                                ->where('subject_id', $subjectId)
                                ->where('range_from', '<=', $subjectMarksValue)
                                ->where('range_to', '>=', $subjectMarksValue)
                                ->first();

                            $grade = $gradingRange->grade ?? 'N/A';
                            $remark = $gradingRange->remark ?? 'N/A';
                            $gpa = $gradingRange->gpa ?? 'N/A';

                            $this->studentDetails[] = [
                                'subject_name' => $subjectName,
                                'marks' => $subjectMarksValue,
                                'grade' => $grade,
                                'remark' => $remark,
                                'gpa' => $gpa,
                            ];

                            $totalMarks += $subjectMarksValue;
                            $totalPoints += $gpa;
                        } else {
                            $subject = DB::table('subjects')->where('id', $subjectId)->first();
                            $this->studentDetails[] = [
                                'subject_name' => $subject->subject_name ?? 'Unknown Subject',
                                'marks' => 'N/A',
                                'grade' => 'N/A',
                                'remark' => 'N/A',
                                'gpa' => 'N/A',
                            ];
                        }
                    }

                    $meanScore = $subjectCount > 0 ? $totalMarks / $subjectCount : 'N/A';

                    $this->gradingSystemDetails = [
                        'name' => $gradingSystem->name,
                        'description' => $gradingSystem->description,
                        'effective_date' => $gradingSystem->effective_date,
                    ];
                } else {
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

                // Fetch class and section names
                $className = $student->my_class ? $student->my_class->name : 'N/A';
                $sectionName = $student->section ? $student->section->name : 'N/A';

                // Calculate class and stream positions using the respective functions
                $classPosition = $this->calculateClassPosition($student, $exam);
                $streamPosition = $this->calculateStreamPosition($student, $exam);

                // Additional student details
                $this->studentAdditionalDetails = [
                    'class_name' => $className,
                    'section_name' => $sectionName,
                    'photo' => $student->photo,
                    'gender' => $student->gender,
                    'first_name' => $student->first_name,
                    'middle_name' => $student->middle_name,
                    'last_name' => $student->last_name,
                ];

                // Set values for displaying in the UI
                $this->selectedAdmNo = $admNo;
                $this->examName = $exam->name;
                $this->totalMarks = $totalMarks;
                $this->meanScore = $meanScore;
                $this->totalPoints = $totalPoints;
                $this->classPosition = $classPosition;
                $this->streamPosition = $streamPosition;
                $this->showingDetails = true;
            } else {
                $this->resetStudentDetails();
            }
        } else {
            $this->resetStudentDetails();
        }
    }



    private function resetDetails()
    {
        $this->studentDetails = [];
        $this->studentAdditionalDetails = [];
        $this->gradingSystemDetails = [];
        $this->totalMarks = 'N/A';
        $this->meanScore = 'N/A';
        $this->totalPoints = 'N/A';
        $this->classPosition = 'N/A';
        $this->streamPosition = 'N/A';
    }
    // Calculate class position based on total marks
    private function calculateClassPosition($student, $exam)
    {
        $students = StudentRecord::where('my_class_id', $student->my_class_id)->get();
        $studentScores = [];

        foreach ($students as $studentRecord) {
            $totalMarks = ExamMarks::where('student_id', $studentRecord->id)
                ->where('exam_id', $exam->id)
                ->sum('marks');

            $studentScores[] = ['student_id' => $studentRecord->id, 'total_marks' => $totalMarks];
        }

        // Sort students by total marks in descending order
        usort($studentScores, function ($a, $b) {
            return $b['total_marks'] <=> $a['total_marks'];
        });

        // Find position of the current student
        foreach ($studentScores as $index => $studentScore) {
            if ($studentScore['student_id'] == $student->id) {
                return $index + 1; // Return position
            }
        }

        return 'N/A';
    }

    // Calculate stream position based on total marks
    private function calculateStreamPosition($student, $exam)
    {
        $students = StudentRecord::where('section_id', $student->section_id)->get();
        $studentScores = [];

        foreach ($students as $studentRecord) {
            $totalMarks = ExamMarks::where('student_id', $studentRecord->id)
                ->where('exam_id', $exam->id)
                ->sum('marks');

            $studentScores[] = ['student_id' => $studentRecord->id, 'total_marks' => $totalMarks];
        }

        // Sort students by total marks in descending order
        usort($studentScores, function ($a, $b) {
            return $b['total_marks'] <=> $a['total_marks'];
        });

        // Find position of the current student
        foreach ($studentScores as $index => $studentScore) {
            if ($studentScore['student_id'] == $student->id) {
                return $index + 1;
            }
        }

        return 'N/A';
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
