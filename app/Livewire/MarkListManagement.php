<?php

namespace App\Livewire;

use Mpdf\Mpdf;
use App\Models\Exam;
use App\Models\MyClass;
use App\Models\Section;
use App\Models\Subject;
use Livewire\Component;
use App\Models\ExamMarks;
use App\Models\GradingGrade;
use App\Models\GradingRange;
use Livewire\WithPagination;
use App\Models\GradingSystem;
use App\Models\StudentRecord;
use App\Helpers\StudentHelper;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\StudentDetailsExport;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Response;
use Jantinnerezo\LivewireAlert\LivewireAlert;

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
    public $subjectsCount = 0; // Add missing property

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
    protected function prepareExportData($selectedColumns)
    {
        $exportData = [];

        foreach ($this->marks as $mark) {
            $rowData = [];

            // Add basic student info if selected
            if (!empty($selectedColumns['student_name'])) {
                $rowData['Student Name'] = $mark['student_name'];
            }
            if (!empty($selectedColumns['adm_no'])) {
                $rowData['Admission No'] = $mark['adm_no'];
            }

            // Add selected subject marks
            foreach ($mark['marks'] as $subject => $marks) {
                if (!empty($selectedColumns[$subject])) {
                    $rowData[$subject] = is_numeric($marks) ? number_format($marks, 2) : $marks;
                }
            }

            $exportData[] = $rowData;
        }

        return $exportData;
    }

  






    // Export data as Excel
    public function exportToExcel($selectedColumns = null)
    {
        try {
            // Use provided columns or default to all columns if none selected
            $columns = $selectedColumns ? json_decode($selectedColumns, true) : [
                'student_name' => true,
                'adm_no' => true,
                ...$this->marks->first()['marks']->mapWithKeys(fn($value, $key) => [$key => true])->toArray()
            ];

            // Prepare the data for export with selected columns
            $data = $this->prepareExportData($columns);

            // Cache the export data to prevent regeneration
            $cacheKey = 'marks_export_' . md5(json_encode($data));
            $exportData = (array) Cache::remember($cacheKey, now()->addMinutes(5), function() use ($data) {
                return $data;
            });

            // Generate a meaningful filename
            $filename = sprintf(
                'marks_report_%s_%s_%s.xlsx',
                optional($this->marks->first())['class_name'] ?? 'all',
                $this->examId ? 'exam_' . $this->examId : 'all',
                now()->format('Y_m_d_H_i_s')
            );

            // Return the Excel download response
            return Excel::download(new StudentDetailsExport($exportData), $filename);

        } catch (\Exception $e) {
            Log::error('Excel export failed: ' . $e->getMessage());
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

    public function fetchMarks()
    {
        if (!$this->sectionId || !$this->examId) {
            $this->alert('error', 'Please select a class, exam, and section first.');
            return;
        }

        try {
            // Fetch student IDs from the selected section
            $studentIds = StudentRecord::where('section_id', $this->sectionId)->pluck('id');

            // Fetch marks using model IDs and eager load relationships
            $marks = ExamMarks::with(['student.user', 'subject'])
                ->where('exam_id', $this->examId)
                ->whereIn('student_id', $studentIds)
                ->get();

            // Get all subjects that can be displayed in the table
            $allSubjects = ExamMarks::with('subject')
                ->where('exam_id', $this->examId)
                ->pluck('subject_id')
                ->unique();

            // Check if subject selection is enabled for the class
            $isSelectionEnabled = MyClass::where('id', function ($query) {
                $query->select('my_class_id')
                    ->from('sections')
                    ->where('id', $this->sectionId);
            })->first()?->subjectSelectionSetting?->is_subject_selection_enabled ?? false;

            // Format marks to include student names and subject names
            $this->marks = $marks->groupBy('student_id')->map(function ($marks, $studentId) use ($allSubjects, $isSelectionEnabled) {
                $firstMark = $marks->first(); // Get the first mark to fetch student details
                $student = $firstMark->student;

                // Create a default array with '--' for all subjects if selection is enabled
                $subjectMarks = $allSubjects->mapWithKeys(function ($subjectId) use ($marks, $studentId, $isSelectionEnabled) {
                    $subjectMark = $marks->firstWhere('subject_id', $subjectId);
                    $subjectName = $subjectMark ? $subjectMark->subject->subject_name : 'N/A';

                    if ($isSelectionEnabled) {
                        // Check if the student is enrolled in the subject
                        $isEnrolled = $this->isStudentEnrolledInSubject($studentId, $subjectId);
                        if (!$isEnrolled) {
                            return [$subjectName => '--'];
                        }
                    }

                    // If the student has a special grade, display it
                    if ($subjectMark && $subjectMark->special_grade) {
                        return [$subjectName => $subjectMark->special_grade];
                    }

                    // Otherwise, display the marks or '--'
                    return [$subjectName => $subjectMark?->marks ?? '--'];
                });

                return [
                    'student_name' => $student->name ?? $student->first_name . ' ' . $student->last_name,
                    'adm_no' => $student->adm_no,
                    'marks' => $subjectMarks,
                ];
            });

            if ($this->marks->isEmpty()) {
                $this->alert('info', 'No marks found for the selected criteria.');
            }
        } catch (\Exception $e) {
            $this->alert('error', 'Error fetching marks: ' . $e->getMessage());
            $this->marks = collect();
        }
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


    public function getFiltersAppliedProperty()
    {
        return $this->classId && $this->examId && $this->sectionId;
    }




    private function getStudentSpecialGrade($student, $subject)
    {
        $mark = $student->examMarks->firstWhere('subject_id', $subject->id);
        return $mark ? $mark->special_grade : null;
    }





    public function getStudentGradingDetails($marks, $subjectId)
    {
        if (!$this->isStudentEnrolledInSubject($this->selectedStudentId, $subjectId)) {
            return ['grade' => 'N/A', 'remark' => 'Not Enrolled', 'gpa' => 'N/A'];
        }

        // Fetch the exam marks for the student and subject
        $examMark = ExamMarks::where('student_id', $this->selectedStudentId)
            ->where('subject_id', $subjectId)
            ->first();

        // Check if the student has a special grade for this subject
        if ($examMark && $examMark->special_grade) {
            $specialGrades = ExamMarks::getSpecialGrades();
            $remark = $specialGrades[$examMark->special_grade] ?? 'N/A';
            return ['grade' => $examMark->special_grade, 'remark' => $remark, 'gpa' => 'N/A'];
        }

        // If no special grade, proceed with normal grading
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


   
    public function fetchStudentDetails($admNo)
    {
        try {
            $this->resetStudentDetails();

            // Single query to fetch student with all necessary relationships
            $student = StudentRecord::with([
                'my_class',
                'section',
                'user',
                'examMarks' => function ($query) {
                    $query->where('exam_id', $this->examId)
                        ->with(['subject.category', 'gradingRange']);
                }
            ])->where('adm_no', $admNo)->first();

            if (!$student) {
                $this->alert('error', 'Student not found.');
                return;
            }

            // Single query to fetch exam with all necessary relationships
            $exam = Exam::with([
                'gradingSystem' => function ($query) {
                    $query->with([
                        'gradingRanges' => function ($q) {
                            $q->orderBy('range_from', 'desc');
                        },
                        'grades' => function ($q) {
                            $q->orderBy('range_from', 'desc');
                        },
                        'subjects.category'
                    ]);
                }
            ])->find($this->examId);

            if (!$exam || !$exam->gradingSystem) {
                $this->alert('error', 'Exam or grading system not found.');
                return;
            }

            // Cache frequently accessed data
            $gradingSystem = $exam->gradingSystem;
            $examMarks = $student->examMarks;
            $subjects = $gradingSystem->subjects->keyBy('id');

            // Initialize student details
            $this->initializeStudentDetails($student, $exam);

            // Process marks efficiently
            $this->processMarks($examMarks, $subjects, $gradingSystem);

            // Calculate final statistics
            $this->calculateFinalStatistics($student, $exam);

            $this->showingDetails = true;

        } catch (\Exception $e) {
            \Log::error('Error in fetchStudentDetails: ' . $e->getMessage());
            $this->alert('error', 'Error fetching student details: ' . $e->getMessage());
            $this->resetStudentDetails();
        }
    }

    private function initializeStudentDetails($student, $exam)
    {
        $this->selectedAdmNo = $student->adm_no;
        $this->examName = $exam->name;

        $this->studentDetails = [
            'student_name' => $student->user->name ?? "{$student->first_name} {$student->last_name}",
            'adm_no' => $student->adm_no,
            'class_name' => $student->my_class->name ?? 'N/A',
            'section_name' => $student->section->name ?? 'N/A',
            'exam_name' => $exam->name,
            'academic_year' => $exam->year,
            'marks' => [],
            'total_marks' => 0,
            'mean_score' => 0,
            'grade' => 'N/A',
            'position' => 'N/A'
        ];

        $this->gradingSystemDetails = [
            'name' => $exam->gradingSystem->name,
            'description' => $exam->gradingSystem->description,
            'effective_date' => $exam->gradingSystem->effective_date
        ];
    }

    private function processMarks($examMarks, $subjects, $gradingSystem)
    {
        $totalMarks = 0;
        $totalPoints = 0;
        $subjectsWithMarks = 0;
        $specialGradeCount = 0;

        // Use collection methods for better performance
        $examMarks->each(function ($mark) use ($subjects, $gradingSystem, &$totalMarks, &$totalPoints, &$subjectsWithMarks, &$specialGradeCount) {
            $subject = $subjects->get($mark->subject_id);
            
            if (!$subject) return;

            if ($mark->special_grade) {
                $this->processSpecialGrade($mark, $subject);
                $specialGradeCount++;
                return;
            }

            $gradingRange = $this->findGradingRange($mark->marks, $subject->id, $gradingSystem);
            if ($gradingRange) {
                $this->processNormalGrade($mark, $subject, $gradingRange);
                $totalMarks += $mark->marks;
                $totalPoints += $gradingRange->gpa;
                $subjectsWithMarks++;
            }
        });

        // Store totals for later use
        $this->totalMarks = $totalMarks;
        $this->totalPoints = $totalPoints;
        $this->subjectsCount = $subjectsWithMarks;
    }

    private function findGradingRange($marks, $subjectId, $gradingSystem)
    {
        return $gradingSystem->gradingRanges
            ->where('subject_id', $subjectId)
            ->where('range_from', '<=', $marks)
            ->where('range_to', '>=', $marks)
            ->first();
    }

    private function processSpecialGrade($mark, $subject)
    {
        $this->studentDetails['marks'][$subject->subject_name] = [
            'marks' => null,
            'grade' => $mark->special_grade,
            'remarks' => ExamMarks::getSpecialGrades()[$mark->special_grade] ?? 'N/A',
            'gpa' => 'N/A',
            'special_grade' => $mark->special_grade
        ];
    }

    private function processNormalGrade($mark, $subject, $gradingRange)
    {
        $this->studentDetails['marks'][$subject->subject_name] = [
            'marks' => $mark->marks,
            'grade' => $gradingRange->grade,
            'remarks' => $gradingRange->remark,
            'gpa' => $gradingRange->gpa,
            'special_grade' => null
        ];
    }

    private function calculateFinalStatistics($student, $exam)
    {
        if ($this->subjectsCount > 0) {
            $meanScore = $this->totalMarks / $this->subjectsCount;
            $meanGpa = $this->totalPoints / $this->subjectsCount;

            $this->studentDetails['total_marks'] = $this->totalMarks;
            $this->studentDetails['mean_score'] = round($meanScore, 2);
            
            // Cache the grades query result
            $meanGrade = $exam->gradingSystem->grades
                ->where('range_from', '<=', $meanGpa)
                ->where('range_to', '>=', $meanGpa)
                ->first();

            $this->studentDetails['grade'] = $meanGrade ? $meanGrade->grade : 'N/A';
        }

        // Calculate position using cached query results where possible
        $this->studentDetails['position'] = StudentHelper::calculateStreamPosition($student, $exam);
    }

    protected function generateAndSendReport($student, $exam)
    {
        try {
            // Include all columns for the report
            $selectedColumns = [
                'student_name' => true,
                'adm_no' => true,
            ];
            
            // Add all subject columns
            foreach ($this->marks->first()['marks'] as $subjectName => $value) {
                $selectedColumns[$subjectName] = true;
            }

            // Prepare the data for hashing
            $data = $this->prepareExportData($selectedColumns);
            $dataHash = md5(json_encode($data)); // Generate a hash of the data

            // Check if the data has changed
            $cacheKey = "report_data_hash_{$student->id}_{$exam->id}";
            $previousHash = Cache::get($cacheKey);

            if ($previousHash === $dataHash) {
                // Data hasn't changed, don't send the email again
                $this->alert('info', 'Report data has not changed. Email not sent again.');
                return;
            }

            // Generate the PDF
            $pdfPath = $this->exportToPDF();

            // Ensure the PDF was generated successfully
            if (!$pdfPath || !file_exists($pdfPath)) {
                throw new \Exception('Failed to generate PDF: File not found.');
            }

            // Send the email with the PDF attachment
            Mail::send('emails.student-report', ['student' => $student], function ($message) use ($student, $pdfPath) {
                $message->to($student->email)
                    ->subject('Your Academic Performance Report')
                    ->attach($pdfPath, [
                        'as' => 'student_report.pdf',
                        'mime' => 'application/pdf',
                    ]);
            });

            // Store the new data hash in the cache
            Cache::put($cacheKey, $dataHash, now()->addHours(24)); // Cache for 24 hours

            $this->alert('success', 'Report generated and sent successfully.');
        } catch (\Exception $e) {
            $this->alert('error', 'Failed to send report: ' . $e->getMessage());
        }
    }

    public function exportToPDF($selectedColumns = null)
    {
        try {
            // Use provided columns or default to all columns if none selected
            $columns = $selectedColumns ? json_decode($selectedColumns, true) : [
                'student_name' => true,
                'adm_no' => true,
                ...$this->marks->first()['marks']->mapWithKeys(fn($value, $key) => [$key => true])->toArray()
            ];

            // Prepare the data for export with selected columns
            $data = $this->prepareExportData($columns);

            // Get class and section details
            $class = MyClass::find($this->classId);
            $section = Section::find($this->sectionId);
            $exam = Exam::find($this->examId);

            // Initialize mPDF
            $mpdf = new Mpdf([
                'mode' => 'utf-8',
                'format' => 'A4-L', // Landscape orientation
                'margin_left' => 15,
                'margin_right' => 15,
                'margin_top' => 15,
                'margin_bottom' => 15,
            ]);

            // Generate PDF content using the blade template
            $html = view('exports.marks-table-pdf', [
                'data' => $data,
                'class' => $class,
                'section' => $section,
                'exam' => $exam
            ])->render();

            // Write the content to PDF
            $mpdf->WriteHTML($html);

            // Generate a meaningful filename
            $filename = sprintf(
                'marks_report_%s_%s_%s.pdf',
                $class ? Str::slug($class->name) : 'all',
                $this->examId ? 'exam_' . $this->examId : 'all',
                now()->format('Y_m_d_H_i_s')
            );

            // Return the PDF as a download
            return response()->streamDownload(
                function() use ($mpdf) {
                    echo $mpdf->Output('', \Mpdf\Output\Destination::STRING_RETURN);
                },
                $filename,
                [
                    'Content-Type' => 'application/pdf',
                    'Content-Disposition' => 'attachment; filename="' . $filename . '"'
                ]
            );

        } catch (\Exception $e) {
            Log::error('PDF export failed: ' . $e->getMessage());
            $this->alert('error', 'Failed to export to PDF: ' . $e->getMessage());
            return null;
        }
    }
}
