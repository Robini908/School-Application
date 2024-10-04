<?php

namespace App\Http\Livewire;

use App\Models\Exam;
use App\Models\MyClass;
use App\Models\Section;
use App\Models\Subject;
use Livewire\Component;
use App\Models\GradingRange;
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

    public function updatedSelectedSection($sectionId)
    {
        $this->loading = true;

        // Load exams based on the selected section and class
        $this->exams = Exam::where('section_id', $sectionId)
            ->where('class_id', $this->selectedClass)
            ->get();

        // Fetch the selected section data for display
        $this->selectedSectionData = Section::find($sectionId);

        // Reset marks and subjects
        $this->marks = [];
        $this->subjects = [];

        $this->loading = false;
    }

    // public function getStudentCounts()
    // {
    //     // Load sections with their related student records
    //     return collect($this->sections)->map(function ($section) {
    //         // Use the relationship to get the count of student records
    //         $studentCount = $section->studentRecords()->count(); // Count the number of students in the section

    //         return [
    //             'section' => $section,
    //             'student_count' => $studentCount,
    //         ];
    //     });
    // }



    public function updatedSelectedExam($examId)
    {
        $this->loading = true;

        // Fetch the exam along with its associated grading system and subjects
        $exam = Exam::with('gradingSystem.subjects')->find($examId);

        if (!$exam) {
            // If no exam is found, reset marks and subjects
            $this->marks = [];
            $this->subjects = [];
            $this->loading = false;
            return;
        }

        // Fetch students and their marks for the selected exam and section
        $this->students = StudentRecord::where('my_class_id', $this->selectedClass)
            ->when($this->selectedSection, function ($query) {
                $query->where('section_id', $this->selectedSection);
            })
            ->with(['examMarks' => function ($query) use ($examId) {
                $query->where('exam_id', $examId);
            }, 'section']) // Load the associated section (stream)
            ->get();

        // Store subjects for display
        $this->subjects = $exam->gradingSystem->subjects;

        // Prepare student data for calculation
        $studentData = $this->prepareStudentData($exam);

        // Calculate positions with tie-breaking logic
        $this->marks = $this->calculatePositions($studentData);

        $this->loading = false;
    }

    // Prepare student data for calculation
    // Prepare student data for calculation
    private function prepareStudentData($exam)
    {
        $studentData = [];

        foreach ($this->students as $student) {
            $studentMarks = [];
            $totalMarks = 0;
            $totalPoints = 0;

            foreach ($this->subjects as $subject) {
                $marksValue = $this->getStudentMarks($student, $subject);
                $studentMarks[$subject->id] = $marksValue;
                $totalMarks += $marksValue;
                $totalPoints += $this->getTotalPoints($marksValue, $subject, $exam);
            }

            // Calculate mean score if subjects are present to avoid division by zero
            $meanScore = count($this->subjects) > 0 ? $totalMarks / count($this->subjects) : 0;

            $studentData[] = [
                'student_id' => $student->id,
                'student_name' => "{$student->first_name} {$student->last_name}",
                'marks' => $studentMarks,
                'total_marks' => $totalMarks,
                'total_points' => $totalPoints,
                'mean_score' => $meanScore, // Initialize mean score
                'stream' => $student->section->name ?? 'N/A',
            ];
        }

        return $studentData;
    }


    // Get student marks for a specific subject
    private function getStudentMarks($student, $subject)
    {
        $mark = $student->examMarks->firstWhere('subject_id', $subject->id);
        return $mark ? $mark->marks : 0; // Return marks or 0 if not available
    }

    // Get total points based on the grading ranges
    private function getTotalPoints($marksValue, $subject, $exam)
    {
        $gradingRange = GradingRange::where('grading_system_id', $exam->gradingSystem->id)
            ->where('subject_id', $subject->id)
            ->where('range_from', '<=', $marksValue)
            ->where('range_to', '>=', $marksValue)
            ->first();

        return $gradingRange ? ($gradingRange->gpa ?? 0) : 0; // Default to 0 if GPA is not found
    }

    // Calculate positions and sort the student data with tie-breaking logic
    // Calculate positions with tie-breaking logic
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

            $this->calculateStreamPositions($studentData);
        } catch (\Exception $e) {
            $this->addError('calculatePositions', 'Error calculating positions: ' . $e->getMessage());
        }

        return $studentData;
    }


    // Calculate stream positions without tie-breaking
    private function calculateStreamPositions(&$studentData)
    {
        // Group students by stream
        $streams = [];
        foreach ($studentData as &$data) {
            $streamKey = $data['stream'];
            if (!isset($streams[$streamKey])) {
                $streams[$streamKey] = [];
            }
            $streams[$streamKey][] = &$data; // Reference to the student data
        }

        // Calculate positions for each stream
        foreach ($streams as $stream => $students) {
            // Sort the students in the stream by total marks, total points, and mean scores in descending order
            usort($students, function ($a, $b) {
                if ($b['total_marks'] === $a['total_marks']) {
                    if ($b['total_points'] === $a['total_points']) {
                        return $b['mean_score'] <=> $a['mean_score']; // Sort by mean score if points are equal
                    }
                    return $b['total_points'] <=> $a['total_points']; // Otherwise, sort by total points
                }
                return $b['total_marks'] <=> $a['total_marks']; // Otherwise, sort by total marks
            });

            // Assign positions for the stream without tie-breaking
            $position = 1;
            foreach ($students as $index => &$data) {
                $data['stream_position'] = $position++; // Assign position without tie-breaking
            }
        }
    }










    public function getGrade($marksValue, $gradingSystemId, $subjectId)
    {
        if ($marksValue === 'N/A' || $marksValue === null) {
            return 'N/A'; // Handle undefined marks
        }

        // Fetch the grading range that corresponds to the marks
        $gradingRange = GradingRange::where('grading_system_id', $gradingSystemId)
            ->where('subject_id', $subjectId)
            ->where('range_from', '<=', $marksValue)
            ->where('range_to', '>=', $marksValue)
            ->first();

        return $gradingRange ? $gradingRange->grade : 'N/A'; // Assuming 'grade' is the column with grade (A, B, etc.)
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
