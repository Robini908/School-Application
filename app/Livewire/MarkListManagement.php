<?php

namespace App\Livewire;

use App\Models\Exam;
use App\Models\MyClass;
use App\Models\Section;
use App\Models\Subject;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Mpdf\Mpdf;
use App\Models\ExamMarks;
use App\Models\GradingRange;
use Livewire\WithPagination;
use App\Models\GradingSystem;
use App\Models\StudentRecord;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\StudentDetailsExport;

class MarkListManagement extends Component
{
    use WithPagination;
    use LivewireAlert;

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
    public $selectedExamId;
    public $availableExams = [];
    public $comparisonDetails = [];
    public $studentDetails = []; // Property to hold student details
    public $marks;
    public $showingDetails = false; // Flag to show/hide details card
    public $selectedAdmNo; // Holds the selected admission number

    public $studentAdditionalDetails = []; // Initialize the additional details property
    public $gradingSystemDetails = [];


    public function compareExamPerformance()
    {
        if (!$this->selectedExamId) {
            $this->comparisonDetails = [];
            return;
        }

        // Fetch current exam marks
        $currentExamMarks = ExamMarks::where('exam_id', $this->currentExamId)
            ->where('student_id', $this->selectedStudentId)
            ->get()
            ->keyBy('subject_id');

        // Fetch comparison exam marks
        $comparisonExamMarks = ExamMarks::where('exam_id', $this->selectedExamId)
            ->where('student_id', $this->selectedStudentId)
            ->get()
            ->keyBy('subject_id');

        // Prepare comparison data
        $this->comparisonDetails = [];
        foreach ($currentExamMarks as $subjectId => $currentMark) {
            $comparisonMark = $comparisonExamMarks->get($subjectId);
            $difference = $comparisonMark ? $currentMark->marks - $comparisonMark->marks : null;

            $this->comparisonDetails[] = [
                'subject' => $currentMark->subject->subject_name,
                'current_mark' => $currentMark->marks,
                'comparison_mark' => $comparisonMark->marks ?? 'N/A',
                'difference' => $difference,
                'trend' => $difference > 0 ? 'up' : ($difference < 0 ? 'down' : 'neutral'),
            ];
        }
    }


    public function mount()
    {
        // Initialize marks as an empty collection
        $this->marks = collect();
        $this->studentDetails = [];
        $this->gradingSystemDetails = [];
        $this->studentAdditionalDetails = [];
        $this->fetchAvailableExams();
    }


    private function fetchAvailableExams()
    {
        $this->availableExams = Exam::where('class_id', $this->classId)->get(['id', 'name']);
    }




    // Export data as PDF
    protected function prepareExportData()
    {
        $data = [
            'studentAdditionalDetails' => $this->studentAdditionalDetails,
            'studentDetails' => $this->studentDetails,
            'examName' => $this->examName,
            'gradingSystemDetails' => $this->gradingSystemDetails,
            'totalMarks' => $this->totalMarks,
            'meanScore' => $this->meanScore,
            'totalPoints' => $this->totalPoints,
            'classPosition' => $this->classPosition,
            'streamPosition' => $this->streamPosition,
            'selectedAdmNo' => $this->selectedAdmNo,
        ];

        // Sanitize all string data
        array_walk_recursive($data, function (&$item) {
            if (is_string($item)) {
                $item = mb_convert_encoding($item, 'UTF-8', 'UTF-8');
            }
        });

        return $data;
    }


    // Add use statement for Mpdf

    public function exportToPDF()
    {
        try {
            $data = $this->prepareExportData();

            // Render the Blade view to HTML
            $html = view('exports.student-details-pdf', $data)->render();

            // Create an instance of Mpdf
            $mpdf = new Mpdf();

            // Write the HTML to the PDF
            $mpdf->WriteHTML($html);

            // Generate the file name based on the student's name and admission number
            $studentName = $data['studentAdditionalDetails']['first_name'] . '_' . $data['studentAdditionalDetails']['last_name'];
            $admissionNo = $data['selectedAdmNo'];
            $fileName = 'report_mark_for_' . str_replace(' ', '_', $studentName) . '_' . $admissionNo . '.pdf';

            // Output the PDF as a download with the generated file name
            return response()->streamDownload(function () use ($mpdf) {
                echo $mpdf->Output('', 'S');
            }, $fileName);
        } catch (\Exception $e) {
            $this->alert('error', 'Failed to export to PDF: ' . $e->getMessage());
        }
    }






    // Export data as Excel
    public function exportToExcel()
    {
        try {
            $data = $this->studentDetails; // assuming $studentDetails is an array of data for export
            return Excel::download(new StudentDetailsExport($data), 'student-details.xlsx');
        } catch (\Exception $e) {
            $this->alert('error', 'Failed to export to Excel: ' . $e->getMessage());
        }
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
            'studentDetails' => $this->studentDetails,
            'gradingSystemDetails' => $this->gradingSystemDetails,
            'studentAdditionalDetails' => $this->studentAdditionalDetails,
        ]);
    }

    public function updatedClassId()
    {
        // Reset fields when class is changed
        $this->reset(['examId', 'sectionId', 'marks', 'studentDetails', 'gradingSystemDetails', 'studentAdditionalDetails']);
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

        if (!$student) {
            return $this->resetStudentDetails();
        }

        // Get the latest exam the student participated in
        $exam = Exam::whereHas('examMarks', function ($query) use ($student) {
            $query->where('student_id', $student->id);
        })->orderBy('created_at', 'desc')->first();

        if (!$exam) {
            return $this->resetStudentDetails();
        }

        // Initialize variables
        $this->initializeStudentDetails();
        $gradingSystem = $exam->gradingSystem;

        if ($gradingSystem) {
            $this->fetchMarksAndDetails($student, $exam, $gradingSystem);
        } else {
            $this->fetchMarksWithoutGradingSystem($student);
        }

        // Fetch class and section names
        $this->studentAdditionalDetails = [
            'class_name' => $student->my_class->name ?? 'N/A',
            'section_name' => $student->section->name ?? 'N/A',
            'photo' => $student->photo,
            'gender' => $student->gender,
            'first_name' => $student->first_name,
            'middle_name' => $student->middle_name,
            'last_name' => $student->last_name,
        ];

        // Calculate positions
        $this->classPosition = $this->calculateClassPosition($student, $exam);
        $this->streamPosition = $this->calculateStreamPosition($student, $exam);

        // Set exam-related details
        $this->selectedAdmNo = $admNo;
        $this->examName = $exam->name;
        $this->showingDetails = true;
    }

    private function initializeStudentDetails()
    {
        $this->studentDetails = [];
        $this->gradingSystemDetails = [];
        $this->totalMarks = 0;
        $this->totalPoints = 0;
        $this->meanScore = 0;
    }

    private function fetchMarksAndDetails($student, $exam, $gradingSystem)
    {
        $marks = ExamMarks::with('subject')
            ->where('student_id', $student->id)
            ->where('exam_id', $exam->id)
            ->get()
            ->keyBy('subject_id');

        $gradingSystemSubjects = DB::table('grading_system_subject')
            ->where('grading_system_id', $gradingSystem->id)
            ->pluck('subject_id')
            ->toArray();

        $subjectCount = 0;
        foreach ($gradingSystemSubjects as $subjectId) {
            if ($this->isStudentEnrolledInSubject($student->id, $subjectId)) {
                $this->processSubjectMarks($marks, $subjectId, $gradingSystem);
                $subjectCount++;
            }
        }

        $this->meanScore = $subjectCount > 0 ? $this->totalMarks / $subjectCount : 'N/A';

        $this->gradingSystemDetails = [
            'name' => $gradingSystem->name,
            'description' => $gradingSystem->description,
            'effective_date' => $gradingSystem->effective_date,
        ];
    }


    private function processSubjectMarks($marks, $subjectId, $gradingSystem)
    {
        $subjectMarks = $marks->get($subjectId);
        $subject = DB::table('subjects')->where('id', $subjectId)->first();

        if ($subjectMarks) {
            $subjectName = $subjectMarks->subject->subject_name;
            $subjectMarksValue = $subjectMarks->marks;

            $gradingRange = GradingRange::where('grading_system_id', $gradingSystem->id)
                ->where('subject_id', $subjectId)
                ->where('range_from', '<=', $subjectMarksValue)
                ->where('range_to', '>=', $subjectMarksValue)
                ->first();

            $this->studentDetails[] = [
                'subject_name' => $subjectName,
                'marks' => $subjectMarksValue,
                'grade' => $gradingRange->grade ?? 'N/A',
                'remark' => $gradingRange->remark ?? 'N/A',
                'gpa' => $gradingRange->gpa ?? 'N/A',
            ];

            $this->totalMarks += $subjectMarksValue;
            $this->totalPoints += $gradingRange->gpa ?? 0;
        }
    }


    private function fetchMarksWithoutGradingSystem($student)
    {
        $marks = ExamMarks::with('subject')
            ->where('student_id', $student->id)
            ->get();

        foreach ($marks as $mark) {
            if ($this->isStudentEnrolledInSubject($student->id, $mark->subject_id)) {
                $this->studentDetails[] = [
                    'subject_name' => $mark->subject->subject_name ?? 'N/A',
                    'marks' => $mark->marks ?? 'N/A',
                    'grade' => 'N/A',
                    'remark' => 'N/A',
                    'gpa' => 'N/A',
                ];
            }
        }

        $this->gradingSystemDetails = ['name' => 'N/A', 'description' => 'N/A', 'effective_date' => 'N/A'];
    }

    /**
     * Check if subject selection is enabled for a specific class.
     *
     * @param int $classId
     * @return bool
     */
    protected function isSubjectSelectionEnabled(int $classId): bool
    {
        return MyClass::find($classId)?->subjectSelectionSetting?->is_subject_selection_enabled ?? false;
    }

    /**
     * Check if a student is enrolled in a specific subject.
     *
     * @param int $studentId
     * @param int $subjectId
     * @return bool
     */
    private function isStudentEnrolledInSubject(int $studentId, int $subjectId): bool
    {
        $student = StudentRecord::with('subjects', 'my_class.subjectSelectionSetting')->find($studentId);

        if (!$student) {
            return false;
        }

        $isSelectionEnabled = $student->my_class?->subjectSelectionSetting?->is_subject_selection_enabled ?? false;

        // Treat all students as enrolled if subject selection is disabled
        return !$isSelectionEnabled || $student->subjects->contains('id', $subjectId);
    }


    private function resetStudentDetails()
    {
        $this->studentDetails = [];
        $this->gradingSystemDetails = [];
        $this->studentAdditionalDetails = [];
        $this->totalMarks = 0;
        $this->meanScore = 0;
        $this->totalPoints = 0;
        $this->showingDetails = false;
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
        if (!$this->isStudentEnrolledInSubject($this->selectedStudentId, $subjectId)) {
            return ['grade' => 'N/A', 'remark' => 'Not Enrolled', 'gpa' => 'N/A'];
        }

        $gradingSystem = GradingSystem::find($this->gradingSystemId);
        if (!$gradingSystem) {
            return ['grade' => 'N/A', 'remark' => 'N/A', 'gpa' => 'N/A'];
        }

        return GradingRange::where('grading_system_id', $gradingSystem->id)
            ->where('subject_id', $subjectId)
            ->where('range_from', '<=', $marks)
            ->where('range_to', '>=', $marks)
            ->first(['grade', 'remark', 'gpa']);
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

            // Check if subject selection is enabled for the class
            $isSelectionEnabled = MyClass::where('id', function ($query) {
                $query->select('my_class_id')->from('sections')->where('id', $this->sectionId);
            })->first()?->subjectSelectionSetting?->is_subject_selection_enabled ?? false;

            // Format marks to include student names and subject names
            $this->marks = $marks->groupBy('student_id')->map(function ($marks, $studentId) use ($allSubjects, $isSelectionEnabled) {
                $firstMark = $marks->first(); // Get the first mark to fetch student details

                // Create a default array with '--' for all subjects if selection is enabled
                $subjectMarks = $allSubjects->mapWithKeys(function ($subjectId) use ($marks, $studentId, $isSelectionEnabled) {
                    $subjectMark = $marks->firstWhere('subject_id', $subjectId);
                    $subjectName = $subjectMark ? $subjectMark->subject->subject_name : 'N/A';

                    if ($isSelectionEnabled) {
                        // Check if the student is enrolled in the subject
                        $isEnrolled = $this->isStudentEnrolledInSubject($studentId, $subjectId);
                        $marksValue = $isEnrolled ? ($subjectMark?->marks ?? '--') : '--';
                    } else {
                        $marksValue = $subjectMark?->marks ?? '--';
                    }

                    return [$subjectName => $marksValue];
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
