<?php

namespace App\Http\Livewire;

use App\Models\Exam;
use App\Models\MyClass;
use App\Models\Section;
use App\Models\Subject;
use Livewire\Component;
use App\Models\GradingGrade;
use App\Models\GradingRange;
use App\Models\StudentRecord;
use App\Models\StudentResult;
use Illuminate\Support\Facades\DB;
use Livewire\WithPagination;

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

    public $sections;
    public $position = [];
    public $exams;

    public $marks = [];
    public $subjects = [];
    public $loading = false;

    public $terms; // New property for terms
    public $students; // This will be a Collection
    public $examResults = []; // Store subjects and scores for selected exam
    use WithPagination;


    public function mount()
    {
        $this->classes = MyClass::all();
        $this->sections = [];
        $this->exams = [];
        $this->terms = []; // Initialize terms
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


    public function updatedSelectedExam($examId)
    {
        $this->loading = true;

        \Log::info("Fetching exam data for exam ID: $examId");

        $exam = Exam::with('gradingSystem.subjects')->find($examId);
        if (!$exam) {
            \Log::warning("No exam found for ID: $examId");
            $this->resetExamData();
            return;
        }

        $this->students = StudentRecord::with(['examMarks' => function ($query) use ($examId) {
            $query->where('exam_id', $examId);
        }, 'section'])
            ->where('my_class_id', $this->selectedClass)
            ->when($this->selectedSection, function ($query) {
                $query->where('section_id', $this->selectedSection);
            })
            ->paginate(50); // Load 50 students per page

        \Log::info("Fetched Students Count: " . $this->students->count());

        $this->subjects = $exam->gradingSystem->subjects;

        $studentData = $this->prepareStudentData($exam);

        $this->marks = $this->calculatePositions($studentData);

        $this->loading = false;
    }

    private function prepareStudentData($exam)
    {
        \Log::info("Preparing student data for exam ID: {$exam->id}");
        $studentData = [];
        DB::beginTransaction(); // Start transaction

        try {
            foreach ($this->students as $student) {
                $studentMarks = [];
                $studentGrades = [];
                $totalMarks = 0;  // Ensure this is an integer
                $totalPoints = 0;  // Ensure this is an integer

                foreach ($this->subjects as $subject) {
                    $marksValue = $this->getStudentMarks($student, $subject);

                    // Cast marksValue to integer, in case it's a string
                    $marksValue = (int)$marksValue;

                    // Get grade data, and ensure points are treated correctly
                    $gradeData = $this->getGradeData($marksValue, $exam->gradingSystem->id, $subject->id);

                    $studentMarks[$subject->id] = $marksValue;
                    // Ensure points are handled safely
                    $points = isset($gradeData['points']) ? (int)$gradeData['points'] : 0; // Cast to int
                    $studentGrades[$subject->id] = $points > 0 ? $gradeData['grade'] : '-';

                    // Perform the arithmetic operations safely
                    $totalMarks += $marksValue;
                    $totalPoints += $points;
                }

                $meanScore = count($this->subjects) ? $totalMarks / count($this->subjects) : 0;
                $meanGrade = $this->getMeanGrade($totalPoints, $exam->gradingSystem->id);

                $studentResult = [
                    'student_id' => $student->id,
                    'exam_id' => $exam->id,
                    'student_name' => "{$student->first_name} {$student->last_name}",
                    'marks' => $studentMarks,
                    'grades' => $studentGrades,
                    'total_marks' => $totalMarks,
                    'total_points' => $totalPoints,
                    'mean_score' => $meanScore,
                    'mean_grade' => $meanGrade,
                    'stream' => $student->section->name ?? '-',
                ];

                $studentData[] = $studentResult;
            }

            // Calculate overall and stream positions
            $studentData = $this->calculatePositions($studentData);

            // Save each student's result with positions
            foreach ($studentData as $studentResult) {
                $this->saveStudentResult($studentResult);
            }

            DB::commit(); // Commit transaction if all student data is processed successfully

        } catch (\Exception $e) {
            DB::rollBack(); // Rollback the transaction if an error occurs
            \Log::error("Failed to process student data for exam ID: {$exam->id}. Error: {$e->getMessage()}");

            $this->addError('exam_processing', "Failed to process the exam data: " . $e->getMessage());
        }

        \Log::info("Total Students Processed: " . count($studentData));

        return $studentData;
    }

    private function saveStudentResult($data)
    {
        \Log::info("Attempting to save student result: " . json_encode($data));

        try {
            StudentResult::updateOrCreate(
                ['student_id' => $data['student_id'], 'exam_id' => $data['exam_id']],
                $data
            );
            \Log::info("Saved student result for Student ID: {$data['student_id']} in Exam ID: {$data['exam_id']}");
        } catch (\Exception $e) {
            \Log::error("Failed to save student result for Student ID: {$data['student_id']} - Error: {$e->getMessage()}");

            // Add an error to the Livewire error bag for UI display
            $this->addError('student_save', "Failed to save result for student: {$data['student_name']} - {$e->getMessage()}");
        }
    }



    private function resetExamData()
    {
        $this->marks = [];
        $this->subjects = [];
        $this->loading = false;
    }


    // Get student marks for a specific subject
    private function getStudentMarks($student, $subject)
    {
        $mark = $student->examMarks->firstWhere('subject_id', $subject->id);
        return $mark ? $mark->marks : 0; // Return marks or 0 if not available
    }

    // Get both grade and points based on the grading system and subject marks
    private function getGradeData($marksValue, $gradingSystemId, $subjectId)
    {
        if ($marksValue === '-' || $marksValue === null) {
            return ['grade' => '-', 'points' => '-']; // Handle undefined marks
        }

        // Fetch the grading range that corresponds to the marks
        $gradingRange = GradingRange::where('grading_system_id', $gradingSystemId)
            ->where('subject_id', $subjectId)
            ->where('range_from', '<=', $marksValue)
            ->where('range_to', '>=', $marksValue)
            ->first();

        // If no grading range found, log the issue and return N/A
        if (!$gradingRange) {
            \Log::warning("No grading range found for Marks: $marksValue, Grading System ID: $gradingSystemId, Subject ID: $subjectId");

            return [
                'grade' => '-', // Explicitly return 'N/A' for grade
                'points' => '-' // Default points
            ];
        }

        // Return both grade and points (GPA)
        return [
            'grade' => $gradingRange->grade,
            'points' => $gradingRange->gpa ?? '-' // Points (GPA)
        ];
    }

    // Calculate positions and sort the student data with tie-breaking logic
    private function calculatePositions($studentData)
    {
        try {
            usort($studentData, function ($a, $b) {
                if ($b['total_marks'] === $a['total_marks']) {
                    if ($b['total_points'] === $a['total_points']) {
                        return $b['mean_score'] <=> $a['mean_score'];
                    }
                    return $b['total_points'] <=> $a['total_points'];
                }
                return $b['total_marks'] <=> $a['total_marks'];
            });

            $position = 1;
            foreach ($studentData as $index => &$data) {
                if (
                    $index > 0 &&
                    $data['total_marks'] === $studentData[$index - 1]['total_marks'] &&
                    $data['total_points'] === $studentData[$index - 1]['total_points'] &&
                    $data['mean_score'] === $studentData[$index - 1]['mean_score']
                ) {
                    $data['position'] = $studentData[$index - 1]['position'];
                } else {
                    $data['position'] = $position;
                }
                $position++;
            }

            // Calculate stream positions
            $this->calculateStreamPositions($studentData);
        } catch (\Exception $e) {
            \Log::error("Error calculating positions: " . $e->getMessage());
            $this->addError('calculatePositions', 'Error calculating positions: ' . $e->getMessage());
        }

        return $studentData;
    }

    // Calculate stream positions without tie-breaking
    private function calculateStreamPositions(&$studentData)
    {
        $streams = [];
        foreach ($studentData as &$data) {
            $streamKey = $data['stream'];
            if (!isset($streams[$streamKey])) {
                $streams[$streamKey] = [];
            }
            $streams[$streamKey][] = &$data; // Reference to the student data
        }

        foreach ($streams as $stream => $students) {
            usort($students, function ($a, $b) {
                if ($b['total_marks'] === $a['total_marks']) {
                    if ($b['total_points'] === $a['total_points']) {
                        return $b['mean_score'] <=> $a['mean_score'];
                    }
                    return $b['total_points'] <=> $a['total_points'];
                }
                return $b['total_marks'] <=> $a['total_marks'];
            });

            $position = 1;
            foreach ($students as $index => &$data) {
                $data['stream_position'] = $position++; // Assign stream position
            }
        }
    }


    public function getMeanGrade($totalPoints, $gradingSystemId)
    {
        // Find the corresponding grade for the total points based on the grading system
        $gradeData = GradingGrade::where('grading_system_id', $gradingSystemId)
            ->where('range_from', '<=', $totalPoints) // Updated column name
            ->where('range_to', '>=', $totalPoints)   // Updated column name
            ->first();

        return $gradeData ? $gradeData->grade : '-'; // Return '-' if no grade found
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
