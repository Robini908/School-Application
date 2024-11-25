<?php

namespace App\Livewire;

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
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

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

    private function prepareStudentData($exam)
    {
        Log::info("Preparing student data for exam ID: {$exam->id}");

        // Invalidate cache if data has changed
        $cacheKey = "exam_{$exam->id}_student_data";
        Cache::forget($cacheKey); // Remove any old cached data if it's out of date

        $studentData = [];
        $updatedStudents = StudentRecord::with('subjects', 'section')->get(); // Always fetch latest student data

        try {
            foreach ($updatedStudents as $student) {
                $studentMarks = [];
                $studentGrades = [];
                $totalMarks = 0;
                $totalPoints = 0;
                $enrolledSubjectIds = $student->subjects->pluck('id')->toArray(); // Get enrolled subjects

                // Initialize enrolled subjects for passing to the view
                $studentMarks['enrolled_subject_ids'] = $enrolledSubjectIds;

                foreach ($this->subjects as $subject) {
                    // Check if the student is enrolled in this subject
                    if (in_array($subject->id, $enrolledSubjectIds)) {
                        // Student is enrolled in this subject, proceed with marks and grades
                        $marksValue = $this->getStudentMarks($student, $subject);
                        $marksValue = (int) $marksValue;

                        $gradeData = $this->getGradeData($marksValue, $exam->gradingSystem->id, $subject->id);

                        $studentMarks[$subject->id] = $marksValue;
                        $points = isset($gradeData['points']) ? (int) $gradeData['points'] : 0;
                        $studentGrades[$subject->id] = $points > 0 ? $gradeData['grade'] : '-';

                        $totalMarks += $marksValue;
                        $totalPoints += $points;
                    } else {
                        // Student is not enrolled in this subject, assign hyphen and skip grade calculation
                        $studentMarks[$subject->id] = '--';  // Double hyphen for un-enrolled subjects
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

            // Calculate positions
            $studentData = $this->calculatePositions($studentData);

            // Synchronize and store the results in cache
            Cache::put($cacheKey, $studentData, now()->addHours(2)); // Store for 2 hours

            Log::info("Student data for exam ID: {$exam->id} has been cached successfully.");
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
            ->first();

        if (!$gradingRange) {
            return ['grade' => 'N/A', 'points' => 'N/A']; // Show "N/A" if range is missing
        }

        return [
            'grade' => $gradingRange->grade,
            'points' => $gradingRange->gpa ?? 'N/A',
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
            Log::error("Error calculating positions: " . $e->getMessage());
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



        // if (!$this->examId) {
        //     $this->errorMessage = 'Please select an exam to view champions.';
        //     return;
        // }

        // $query = ExamMarks::with(['student', 'subject'])->where('exam_id', $this->examId);

        // if ($this->classId) {
        //     $query->whereHas('student', function ($q) {
        //         $q->where('my_class_id', $this->classId);
        //     });
        // }

        // if ($this->streamId) {
        //     $query->whereHas('student', function ($q) {
        //         $q->where('section_id', $this->streamId);
        //     });
        // }

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
