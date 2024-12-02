<?php

namespace App\Livewire;

use App\Models\Exam;
use App\Models\MyClass;
use App\Models\Section;
use App\Models\Subject;
use Livewire\Component;
use App\Models\GradingGrade;
use function Amp\Parallel\Worker\parallelMap;
use function Amp\Promise\wait;
use App\Models\GradingRange;
use App\Models\StudentRecord;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Amp\ParallelFunctions\parallelMap;

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

        Log::info("Fetching exam data for exam ID: $examId");

        $exam = Exam::with('gradingSystem.subjects')->find($examId);
        if (!$exam) {
            Log::warning("No exam found for ID: $examId");
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
            ->get();

        Log::info("Fetched Students Count: " . $this->students->count());

        $this->subjects = $exam->gradingSystem->subjects;

        $studentData = $this->prepareStudentData($exam);

        $this->marks = $this->calculatePositions($studentData);

        $this->loading = false;
    }


    public function prepareStudentData($exam)
    {
        Log::info("Preparing student data for exam ID: {$exam->id}");

        $studentData = [];

        // Fetch latest student data with relationships (subjects, section)
        try {
            $updatedStudents = StudentRecord::with('subjects', 'section')->get();
            Log::info('Fetched student data successfully.');

            foreach ($updatedStudents as $student) {
                $studentMarks = [];
                $studentGrades = [];
                $totalMarks = 0;
                $totalPoints = 0;
                $enrolledSubjectIds = $student->subjects->pluck('id')->toArray(); // Get enrolled subjects

                // Initialize enrolled subjects for passing to the view
                $studentMarks['enrolled_subject_ids'] = $enrolledSubjectIds;

                // Loop through the subjects
                foreach ($this->subjects as $subject) {
                    // Check if the student is enrolled in this subject
                    if (in_array($subject->id, $enrolledSubjectIds)) {
                        // Student is enrolled in this subject, proceed with marks and grades
                        $marksValue = $this->getStudentMarks($student, $subject);
                        $marksValue = (int) $marksValue;
                        $gradeData = $this->getGradeData($marksValue, $exam->gradingSystem->id, $subject->id);

                        $studentMarks[$subject->id] = $marksValue;

                        // Points and grades calculation
                        $points = isset($gradeData['points']) ? (int) $gradeData['points'] : 0;
                        $studentGrades[$subject->id] = $points > 0 ? $gradeData['grade'] : '-';

                        $totalMarks += $marksValue;
                        $totalPoints += $points;
                    } else {
                        // Student is not enrolled in this subject, assign hyphen and skip grade calculation
                        $studentMarks[$subject->id] = '--'; // Double hyphen for un-enrolled subjects
                        $studentGrades[$subject->id] = '--';
                    }
                }

                // Only calculate mean score for enrolled subjects
                $meanScore = count($enrolledSubjectIds) ? $totalMarks / count($enrolledSubjectIds) : 0;
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

            // Log the student data size
            Log::info('Processed ' . count($studentData) . ' student records.');

            // Calculate positions (if applicable)
            $studentData = $this->calculatePositions($studentData);
        } catch (\Exception $e) {
            Log::error("Error processing student data: {$e->getMessage()}");
            $this->addError('exam_processing', "Failed to process the exam data: " . $e->getMessage());
        }

        return $studentData;
    }






    //retrieving the data from cache 

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
        return $mark ? $mark->marks : null; // Return null if no marks exist
    }



    // Get both grade and points based on the grading system and subject marks
    private function getGradeData($marksValue, $gradingSystemId, $subjectId)
    {
        if ($marksValue === null) {
            return ['grade' => '-', 'points' => '-']; // Return hyphen for undefined marks
        }

        $gradingRange = GradingRange::where('grading_system_id', $gradingSystemId)
            ->where('subject_id', $subjectId)
            ->where('range_from', '<=', $marksValue)
            ->where('range_to', '>=', $marksValue)
            ->orWhere(function ($query) use ($marksValue) {
                $query->where('range_from', '=', $marksValue)
                    ->where('range_to', '=', $marksValue);
            })
            ->first();


        // Log the fetched grading range
        Log::info("Grading range for subject {$subjectId} and marks {$marksValue}: " . ($gradingRange ? json_encode($gradingRange->toArray()) : 'No grading range found'));

        if (!$gradingRange) {
            return ['grade' => 'N/A', 'points' => 'N/A']; // Show "N/A" if range is missing
        }

        return [
            'grade' => $gradingRange->grade,
            'points' => $gradingRange->gpa ?? 'N/A',
        ];
    }






    private function calculateStreamPositions(array &$studentData): array
    {
        // Group students by stream
        $streams = [];
        foreach ($studentData as $student) {
            $streams[$student['stream']][] = $student;
        }

        // Optimized sorting for each stream and assigning positions
        foreach ($streams as $stream => &$students) {
            // Sort students by total_marks, total_points, then mean_score
            usort($students, function ($a, $b) {
                return $b['total_marks'] <=> $a['total_marks'] ?:
                    $b['total_points'] <=> $a['total_points'] ?:
                    $b['mean_score'] <=> $a['mean_score'];
            });

            // Assign positions based on ranking, handle tied ranks
            $prevStudent = null;
            $position = 1;
            foreach ($students as $index => &$student) {
                if ($index > 0 && $this->isSameRanking($prevStudent, $student)) {
                    $student['stream_position'] = $prevStudent['stream_position'];
                } else {
                    $student['stream_position'] = $position;
                }
                $prevStudent = $student;
                $position++;
            }
        }

        // Flatten and merge stream positions back into the main dataset
        $streamMap = [];
        foreach ($streams as $stream => $students) {
            foreach ($students as $student) {
                $streamMap[$student['student_id']] = $student['stream_position'];
            }
        }

        // Update the original dataset with stream positions
        foreach ($studentData as &$student) {
            $student['stream_position'] = $streamMap[$student['student_id']] ?? null;
        }

        return $studentData;
    }



    /**
     * Check if two students have the same ranking based on criteria.
     *
     * @param array|null $student1
     * @param array|null $student2
     * @return bool
     */
    private function isSameRanking(?array $student1, ?array $student2): bool
    {
        return $student1['total_marks'] === $student2['total_marks'] &&
            $student1['total_points'] === $student2['total_points'] &&
            $student1['mean_score'] === $student2['mean_score'];
    }

    /**
     * Normalize the sample data using vectorized calculations.
     *
     * @param array $samples
     * @return array
     */
    private function normalizeSamples(array $samples): array
    {
        // Transpose samples for feature-wise operations
        $transposed = array_map(null, ...$samples);

        // Normalize each feature
        foreach ($transposed as &$feature) {
            $min = min($feature);
            $max = max($feature);

            if ($max - $min > 0) {
                foreach ($feature as &$value) {
                    $value = ($value - $min) / ($max - $min);
                }
            }
        }

        // Transpose back to original format
        return array_map(null, ...$transposed);
    }

    /**
     * Calculate positions for all students using parallel processing.
     *
     * @param array $studentData
     * @return array Updated student data with overall and stream positions.
     */
    public function calculatePositions(array $studentData): array
    {
        if (empty($studentData)) {
            throw new \InvalidArgumentException('Student data cannot be empty.');
        }

        // Sort and assign overall positions
        usort($studentData, function ($a, $b) {
            return $b['total_marks'] <=> $a['total_marks'] ?:
                $b['total_points'] <=> $a['total_points'] ?:
                $b['mean_score'] <=> $a['mean_score'];
        });

        // Assign overall positions
        $prevStudent = null;
        $position = 1;
        foreach ($studentData as $index => &$student) {
            if ($index > 0 && $this->isSameRanking($prevStudent, $student)) {
                $student['position'] = $prevStudent['position'];
            } else {
                $student['position'] = $position;
            }
            $prevStudent = $student;
            $position++;
        }

        // Calculate stream-specific positions in parallel
        $studentData = $this->calculateStreamPositions($studentData);

        return $studentData;
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
