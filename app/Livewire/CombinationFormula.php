<?php

namespace App\Livewire;

use App\Models\Exam;
use App\Models\MyClass;
use App\Models\Section;
use App\Models\Subject;
use Livewire\Component;
use App\Models\ExamMarks;
use App\Models\GradingGrade;

use App\Models\GradingRange;
use Livewire\WithPagination;

use App\Models\StudentRecord;
use App\Models\StudentResult;
use App\Helpers\StudentHelper;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Parallel;
use App\Notifications\SystemNotification;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Mccarlosen\LaravelMpdf\Facades\LaravelMpdf as PDF;

class CombinationFormula extends Component
{
    // Filter properties
    use LivewireAlert;
    public $selectedClass;
    public $showCombinedExamForm = false;
    public $selectedYearAdmitted;
    public $customExamName;
    public $examPercentages = [];
    public $selectedExamYear;
    public $showTable = false; // Controls the visibility of the table
    public $customExamTerm = 3; // Default to Term 3 (Endterm)
    public $customExamYear; // Stores the custom exam year
    public $gradingSystemName;
    public $selectedSection;
    public $search = '';
    public $reportData = [];
    public $showReport = false;



    public $specialGrades = [];
    public $selectedTerm; // New filter for term
    public $selectedYear; // New filter for year
    public $selectedExam;
    public $selectedExams = []; // Holds the IDs of selected exams
    public $selectedClassData;
    public $selectedExamNames = []; // Add this line to your Livewire component
    public $combinedResults = [];
    public $subjects = [];
    // public $activeTab = 'normal'; // Default active tab
    public $selectedSectionData;

    public $classes;

    public $sections;
    public $gradingSystemNames = [];
    public $hasUnsavedChanges = false;

    public $position = [];
    public $exams;

    public $marks = [];
    public $loading = false;

    public $terms; // New property for terms
    public $students; // This will be a Collection
    public $examResults = []; // Store subjects and scores for selected exam
    use WithPagination;
    protected $listeners = ['selectedExamsUpdated' => 'onSelectedExamsUpdated'];
    protected $rules = [
        'examPercentages.*' => 'required|integer|min:0|max:100',
    ];

    public function onSelectedExamsUpdated($selectedExams)
    {
        $this->selectedExams = $selectedExams;
    }

    public function updatedSelectedExams($value)
    {
        // Ensure $value is an array
        if (!is_array($value)) {
            $value = [$value];
        }

        // Initialize percentages for newly selected exams
        foreach ($value as $examId) {
            if (!isset($this->examPercentages[$examId])) {
                $this->examPercentages[$examId] = 0; // Initialize to 0 if not already set
            }
        }

        // Remove percentages for deselected exams
        $this->examPercentages = array_intersect_key($this->examPercentages, array_flip($value));
    }

    public function generateReport($studentId)
    {
        // Fetch the student's data from the combined results
        $studentData = collect($this->combinedResults)->firstWhere('student_id', $studentId);

        if ($studentData) {
            // Ensure `selectedExamNames` and `examPercentages` are defined and not empty
            $exams = $this->selectedExamNames ?? [];
            $percentages = $this->examPercentages ?? [];

            // Prepare the report data
            $this->reportData = [
                'student_name' => $studentData['student_name'],
                'stream' => $studentData['stream'],
                'marks' => $studentData['marks'],
                'grades' => $studentData['grades'],
                'total_marks' => $studentData['total_marks'],
                'total_points' => $studentData['total_points'],
                'mean_score' => $studentData['mean_score'],
                'mean_grade' => $studentData['mean_grade'],
                'class_position' => $studentData['position'] ?? '-',
                'stream_position' => $studentData['stream_position'] ?? '-',
                'exams' => $exams, // Use the exams array
                'percentages' => $percentages, // Use the percentages array
            ];

            // Show the report view
            $this->showReport = true;
        } else {
            $this->alert('error', 'Student data not found!');
        }
    }
    // Fetch the student's data from the combined results


    public function closeReport()
    {
        $this->showReport = false;
        $this->reportData = [];
    }

    public function mount()
    {
        // Initialize properties
        $this->exams = Exam::all(); // Fetch all exams

        // Initialize examPercentages with default values
        if ($this->exams->isNotEmpty()) {
            $this->examPercentages = array_fill_keys($this->exams->pluck('id')->toArray(), 0);
        } else {
            $this->examPercentages = []; // Fallback if no exams are found
            Log::warning("No exams found during component initialization.");
        }

        // Fetch classes and initialize other properties
        $this->classes = MyClass::all();
        $this->sections = []; // Initialize sections as an empty array
        $this->terms = []; // Initialize terms as an empty array
        $this->students = collect(); // Initialize students as an empty collection

        // Ensure selectedExams is always an array
        $this->selectedExams = $this->selectedExams ?? [];

        // Fetch selected exam names and grading systems if selectedExams is not empty
        if (!empty($this->selectedExams)) {
            $this->selectedExamNames = Exam::whereIn('id', $this->selectedExams)
                ->pluck('name')
                ->toArray();

            $this->gradingSystemNames = Exam::whereIn('id', $this->selectedExams)
                ->with('gradingSystem')
                ->get()
                ->pluck('gradingSystem.name')
                ->toArray();
        } else {
            // Initialize selectedExamNames and gradingSystemNames as empty arrays
            $this->selectedExamNames = [];
            $this->gradingSystemNames = [];
        }
    }

    public function saveCombinedExam()
    {
        // Validate the form inputs
        $this->validate([
            'customExamName' => 'required|string|max:255',
            'customExamTerm' => 'required|integer|in:1,2,3',
            'customExamYear' => 'required|integer|min:2000|max:' . (date('Y') + 1),
        ]);

        // Ensure combined results are available
        if (empty($this->combinedResults)) {
            $this->addError('combined_analysis', 'No combined results to save.');
            return;
        }

        // Fetch the first exam to get grading system, class, and section
        $firstExam = Exam::find($this->selectedExams[0]);
        if (!$firstExam) {
            $this->addError('combined_analysis', 'Invalid exam selected.');
            return;
        }

        // Check if the combined exam already exists
        $existingExam = Exam::where('name', $this->customExamName)
            ->where('term', $this->customExamTerm)
            ->where('year', $this->customExamYear)
            ->where('class_id', $this->selectedClass)
            ->first();

        if ($existingExam) {
            // If the exam already exists, use it instead of creating a new one
            $newExam = $existingExam;
        } else {
            // Create the new exam
            $newExam = Exam::create([
                'name' => $this->customExamName,
                'term' => $this->customExamTerm,
                'year' => $this->customExamYear,
                'grading_system_id' => $firstExam->grading_system_id,
                'class_id' => $this->selectedClass,
                'section_id' => null, // No specific section for combined exams
            ]);
        }

        // Fetch all sections in the selected class
        $sections = Section::where('my_class_id', $this->selectedClass)->get();

        // Attach the exam to all sections in the pivot table (if not already attached)
        foreach ($sections as $section) {
            $newExam->classes()->syncWithoutDetaching([
                $this->selectedClass => ['section_id' => $section->id],
            ]);
        }

        // Save the averaged marks for each student
        foreach ($this->combinedResults as $result) {
            foreach ($result['marks'] as $subjectId => $marks) {
                $specialGrade = $result['grades'][$subjectId] ?? null;

                // Use updateOrCreate to handle existing records
                ExamMarks::updateOrCreate(
                    [
                        'student_id' => $result['student_id'],
                        'exam_id' => $newExam->id,
                        'subject_id' => $subjectId,
                    ],
                    [
                        'marks' => in_array($specialGrade, ['X', 'Y', 'Z']) ? null : $marks,
                        'special_grade' => in_array($specialGrade, ['X', 'Y', 'Z']) ? $specialGrade : null,
                    ]
                );
            }
        }

        // Hide the form and show the table

        $this->showCombinedExamForm = false;
        $this->hasUnsavedChanges = false;

        $this->showTable = true;

        // Notify the user
        $this->alert('success', 'Combined exam and results saved', [
            'position' => 'top',
            'showConfirmButton' => true,
            'confirmButtonText' => 'OK',
            'timer' => null, // No auto-close
            'toast' => false,
        ]);
    }

    public function updated($propertyName)
    {
        // Check if the updated property is one of the form fields
        if (in_array($propertyName, ['customExamName', 'customExamTerm', 'customExamYear'])) {
            $this->hasUnsavedChanges = true;
        }
        $this->validateOnly($propertyName);
    }




    public function dehydrate()
    {
        if ($this->hasUnsavedChanges) {
            $this->alert('warning', 'You have unsaved changes!', [
                'position' => 'top',
                'showConfirmButton' => true,
                'confirmButtonText' => 'OK',
                'timer' => null, // No auto-close
                'toast' => false,
            ]);
        }
    }

    public function resetForm()
    {
        $this->reset([
            'customExamName',
            'customExamTerm',
            'customExamYear',
        ]);
    }

    public function viewAnalyzedResults()
    {
        // Hide the combined exam form
        $this->showCombinedExamForm = false;
    }


    public function getExamsProperty()
    {
        return Exam::query()
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%');
            })
            ->when($this->selectedTerm, function ($query) {
                $query->where('term', $this->selectedTerm);
            })
            ->when($this->selectedYear, function ($query) {
                $query->where('year', $this->selectedYear);
            })
            ->when($this->selectedClass, function ($query) {
                $query->where('class_id', $this->selectedClass);
            })
            ->with('myClass', 'section') // Eager load relationships
            ->get();
    }

    public function updatedSearch()
    {
        $this->resetPage(); // Reset pagination when searching
    }

    public function updatedSelectedTerm()
    {
        $this->resetPage(); // Reset pagination when filtering by term
    }

    public function updatedSelectedYear()
    {
        $this->resetPage(); // Reset pagination when filtering by year
    }

    public function resetFields()
    {
        $this->selectedClass = null; // Reset selected class
        $this->selectedExams = []; // Reset selected exams
        $this->combinedResults = []; // Clear combined results
        $this->subjects = []; // Clear subjects
        $this->selectedSection = null; // Reset selected section (if applicable)
    }

    public function removeSelectedExam($examId)
    {
        // Remove the exam ID from the selectedExams array
        $this->selectedExams = array_filter($this->selectedExams, function ($id) use ($examId) {
            return $id != $examId;
        });

        // Re-index the array to avoid gaps
        $this->selectedExams = array_values($this->selectedExams);

        // Optionally dispatch an event to notify other components
        $this->dispatch('selectedExamsUpdated', $this->selectedExams);
    }

    // Analyze combined results
    public function analyzeCombinedResults()
    {
        // Validate inputs
        $this->validate([
            'selectedExams' => 'required|array|min:2',
            'selectedClass' => 'required|exists:my_classes,id',
            'examPercentages' => [
                'required',
                function ($attribute, $value, $fail) {
                    $totalPercentage = array_sum($value);
                    if ($totalPercentage != 100) {
                        $fail("The total percentage must be 100%. Current total: {$totalPercentage}%.");
                    }
                },
            ],
        ]);

        $this->loading = true;

        try {
            // Fetch data for all selected exams
            $examsData = [];
            $examIds = $this->selectedExams;

            // Ensure exams exist and are valid
            $exams = Exam::with('gradingSystem.subjects')
                ->whereIn('id', $examIds)
                ->get();

            if ($exams->isEmpty()) {
                throw new \Exception("No valid exams found for the selected IDs.");
            }

            $this->selectedExamNames = $exams->pluck('name')->toArray();
            $this->gradingSystemNames = $exams->pluck('gradingSystem.name')->toArray();

            foreach ($exams as $exam) {
                $students = StudentRecord::with(['examMarks' => function ($query) use ($exam) {
                    $query->where('exam_id', $exam->id);
                }, 'section'])
                    ->where('my_class_id', $this->selectedClass)
                    ->when($this->selectedSection, function ($query) {
                        $query->where('section_id', $this->selectedSection);
                    })
                    ->get();

                if ($students->isEmpty()) {
                    Log::warning("No students found for exam ID: {$exam->id}");
                    continue;
                }

                $examsData[] = [
                    'exam' => $exam,
                    'students' => $students,
                    'percentage' => $this->examPercentages[$exam->id] / 100, // Convert percentage to decimal
                ];
            }

            if (empty($examsData)) {
                throw new \Exception("No valid exam data found for analysis.");
            }

            // Prepare combined results
            $this->combinedResults = $this->prepareCombinedResults($examsData);

            // Calculate positions for combined results
            $this->combinedResults = $this->calculatePositionsWithHelper($this->combinedResults, $exam);


            // Ensure subjects are passed to the view
            $this->subjects = $examsData[0]['exam']->gradingSystem->subjects;

            // Check if the selected exams have already been combined
            $combinedExamExists = Exam::where('name', $this->customExamName)
                ->where('term', $this->customExamTerm)
                ->where('year', $this->customExamYear)
                ->exists();

            // Show the form only if the exams have not been combined yet
            $this->showCombinedExamForm = !$combinedExamExists;
            $this->showTable = $combinedExamExists;

            // Display success alert
            $this->alert('success', 'Combined exam and results saved successfully.', [
                'position' => 'top-end',
                'timer' => 3000,
                'toast' => true,
                'timerProgressBar' => true,
            ]);

            Log::info("Combined analysis completed for exams: " . implode(', ', $this->selectedExams));
        } catch (\Throwable $e) {
            Log::error("Error in analyzeCombinedResults: " . $e->getMessage());

            // Display error alert
            $this->alert('error', "Failed to process combined exam data: {$e->getMessage()}", [
                'position' => 'top-end',
                'timer' => 5000,
                'toast' => true,
                'timerProgressBar' => true,
            ]);

            // Add error to Livewire error bag
            $this->addError('combined_analysis', "Failed to process combined exam data: {$e->getMessage()}");
        } finally {
            $this->loading = false;
        }
    }

    public function updatedExamPercentages($value, $key)
    {
        // Ensure all values are integers
        $this->examPercentages = array_map('intval', $this->examPercentages);

        $totalPercentage = array_sum($this->examPercentages);

        if ($totalPercentage > 100) {
            $excess = $totalPercentage - 100;
            $this->examPercentages[$key] -= $excess;

            // Show a warning message
            $this->alert('warning', 'Percentage exceeded! Adjusted the last input to maintain 100%.');
        }
    }

    private function prepareCombinedResults($examsData)
    {
        $combinedStudentData = [];

        // Validate exam data
        if (empty($examsData)) {
            throw new \Exception("No exam data provided.");
        }

        // Use subjects from the first exam
        $subjects = $examsData[0]['exam']->gradingSystem->subjects ?? null;

        if (empty($subjects)) {
            throw new \Exception("No subjects found for the selected exams.");
        }

        // Group students by ID
        $students = [];
        foreach ($examsData as $examData) {
            // Validate exam percentage
            $examPercentage = $examData['percentage'] ?? 0;
            if (!is_numeric($examPercentage) || $examPercentage < 0 || $examPercentage > 100) {
                Log::error("Invalid exam percentage: {$examPercentage}");
                throw new \Exception("Invalid exam percentage. Must be between 0 and 100.");
            }

            foreach ($examData['students'] as $student) {
                if (!isset($students[$student->id])) {
                    $students[$student->id] = [
                        'student' => $student,
                        'marks' => [],
                        'grades' => [],
                        'total_marks' => 0,
                        'total_points' => 0,
                        'mean_score' => 0,
                        'mean_grade' => '--',
                        'stream' => $student->section->name ?? '-',
                        'has_special_grade' => false,
                    ];
                }

                // Fetch marks for each subject in this exam using StudentHelper
                foreach ($subjects as $subject) {
                    $marksValue = StudentHelper::getStudentMarks($student, $subject);
                    $specialGrade = StudentHelper::getStudentSpecialGrade($student, $subject);

                    if (!isset($students[$student->id]['marks'][$subject->id])) {
                        $students[$student->id]['marks'][$subject->id] = [];
                    }

                    // Treat missing marks as '--' instead of 0
                    if ($marksValue === '--' || $marksValue === null) {
                        $students[$student->id]['marks'][$subject->id][] = '--'; // Store as '--'
                    } else {
                        // Apply percentage and round to the nearest whole number
                        $weightedMark = (int) round((int) $marksValue * $examPercentage);
                        $students[$student->id]['marks'][$subject->id][] = $weightedMark;
                    }
                }
            }
        }

        if (empty($students)) {
            throw new \Exception("No student data found for the selected exams.");
        }

        // Calculate weighted averages for each student
        foreach ($students as $studentId => $studentData) {
            $studentMarks = [];
            $studentGrades = [];
            $totalMarks = 0;
            $totalPoints = 0;
            $validSubjects = 0;
            $hasSpecialGrade = false;
            $specialGradeCounts = ['X' => 0, 'Y' => 0, 'Z' => 0]; // Track counts of special grades

            // Get marks for all subjects
            $allMarks = [];
            foreach ($subjects as $subject) {
                $marksArray = $studentData['marks'][$subject->id] ?? [];
                if (empty($marksArray)) {
                    Log::warning("No marks found for student ID: {$studentId}, subject ID: {$subject->id}");
                    continue;
                }

                // Check if any marks are missing (i.e., '--')
                if (in_array('--', $marksArray)) {
                    $weightedMarks = '--'; // Treat as missing
                } else {
                    // Sum weighted marks and divide by 100 to get the final weighted mark
                    $weightedMarks = array_sum($marksArray) / 100;
                }

                $specialGrade = StudentHelper::getStudentSpecialGrade($studentData['student'], $subject);

                if ($specialGrade !== null) {
                    $hasSpecialGrade = true;
                    $specialGradeCounts[$specialGrade]++; // Increment count for the special grade
                }

                // Store marks and special grades
                $allMarks[$subject->id] = [
                    'subject' => $subject,
                    'marks' => $weightedMarks,
                    'special_grade' => $specialGrade,
                ];
            }

            // Calculate total marks and points for selected subjects
            foreach ($allMarks as $subjectId => $markData) {
                $subject = $markData['subject'];
                $marksValue = $markData['marks'];
                $specialGrade = $markData['special_grade'];

                if ($specialGrade !== null) {
                    // If special grade exists, assign it and skip marks calculation
                    $gradeData = ['grade' => $specialGrade, 'points' => 0];
                } else {
                    // Calculate grade data based on marks using StudentHelper
                    if ($marksValue === '--') {
                        $gradeData = ['grade' => '--', 'points' => 0]; // Treat missing marks as '--'
                    } else {
                        $gradeData = StudentHelper::getGradeData($marksValue, $examsData[0]['exam']->gradingSystem->id, $subject->id);
                    }
                }

                // Ensure gradeData is valid
                if (!$gradeData || !isset($gradeData['points'])) {
                    Log::warning("Invalid grade data for student ID: {$studentId}, subject ID: {$subject->id}");
                    $gradeData = ['grade' => '--', 'points' => 0];
                }

                $gradeData['points'] = is_numeric($gradeData['points'] ?? null) ? $gradeData['points'] : 0;

                $studentMarks[$subject->id] = $marksValue;
                $studentGrades[$subject->id] = $gradeData['grade'] ?? '--';

                // Only add to total marks and points if there are no special grades and marks are not missing
                if ($specialGrade === null && $marksValue !== '--') {
                    $totalMarks += is_numeric($marksValue) ? (int) $marksValue : 0;
                    $totalPoints += $gradeData['points'];
                    $validSubjects++;
                }
            }

            // Calculate mean score and mean grade
            $meanScore = $validSubjects > 0 ? (int) round($totalMarks / $validSubjects) : 0; // Round to the nearest whole number

            // Determine the mean grade for students with special grades
            if ($hasSpecialGrade) {
                // Find the dominant special grade
                $dominantSpecialGrade = array_search(max($specialGradeCounts), $specialGradeCounts);
                $meanGrade = $dominantSpecialGrade; // Assign the dominant special grade as the mean grade
            } else {
                // Calculate mean grade based on total points using StudentHelper
                $meanGrade = StudentHelper::getMeanGrade($totalPoints, $examsData[0]['exam']->gradingSystem->id);
            }

            $combinedStudentData[] = [
                'student_id' => $studentId,
                'student_name' => "{$studentData['student']->first_name} {$studentData['student']->last_name}",
                'marks' => $studentMarks,
                'grades' => $studentGrades,
                'total_marks' => $totalMarks,
                'total_points' => $totalPoints,
                'mean_score' => $meanScore,
                'mean_grade' => $meanGrade,
                'stream' => $studentData['stream'],
                'has_special_grade' => $hasSpecialGrade,
            ];
        }

        return $combinedStudentData;
    }

    public function quickAnalyzeCombinedResults()
    {
        // Validate inputs
        $this->validate([
            'selectedExams' => 'required|array|min:2',
            'selectedClass' => 'required|exists:my_classes,id',
            'examPercentages' => [
                'required',
                function ($attribute, $value, $fail) {
                    $totalPercentage = array_sum($value);
                    if ($totalPercentage != 100) {
                        $fail("The total percentage must be 100%. Current total: {$totalPercentage}%.");
                    }
                },
            ],
        ]);

        $this->loading = true;

        try {
            // Fetch data for all selected exams
            $examsData = [];
            $examIds = $this->selectedExams;

            // Ensure exams exist and are valid
            $exams = Exam::with('gradingSystem.subjects')
                ->whereIn('id', $examIds)
                ->get();

            if ($exams->isEmpty()) {
                throw new \Exception("No valid exams found for the selected IDs.");
            }

            $this->selectedExamNames = $exams->pluck('name')->toArray();
            $this->gradingSystemNames = $exams->pluck('gradingSystem.name')->toArray();

            foreach ($exams as $exam) {
                $students = StudentRecord::with(['examMarks' => function ($query) use ($exam) {
                    $query->where('exam_id', $exam->id);
                }, 'section'])
                    ->where('my_class_id', $this->selectedClass)
                    ->when($this->selectedSection, function ($query) {
                        $query->where('section_id', $this->selectedSection);
                    })
                    ->get();

                if ($students->isEmpty()) {
                    Log::warning("No students found for exam ID: {$exam->id}");
                    continue;
                }

                // Store exam data with percentage contribution
                $examsData[] = [
                    'exam' => $exam,
                    'students' => $students,
                    'percentage' => $this->examPercentages[$exam->id], // Use percentage directly (e.g., 30 for 30%)
                ];
            }

            if (empty($examsData)) {
                throw new \Exception("No valid exam data found for analysis.");
            }

            // Prepare combined results
            $this->combinedResults = $this->prepareCombinedResults($examsData);

            // Calculate positions for combined results
            $this->combinedResults = $this->calculatePositionsWithHelper($this->combinedResults,$exam);

            // Ensure subjects are passed to the view
            $this->subjects = $examsData[0]['exam']->gradingSystem->subjects;

            // Show the table without showing the form
            $this->showCombinedExamForm = false;
            $this->showTable = true;

            // Display success alert
            $this->alert('success', 'Quick combined analysis completed successfully.', [
                'position' => 'top-end',
                'timer' => 3000,
                'toast' => true,
                'timerProgressBar' => true,
            ]);

            Log::info("Quick combined analysis completed for exams: " . implode(', ', $this->selectedExams));
        } catch (\Throwable $e) {
            Log::error("Error in quickAnalyzeCombinedResults: " . $e->getMessage());

            // Display error alert
            $this->alert('error', "Failed to process combined exam data: {$e->getMessage()}", [
                'position' => 'top-end',
                'timer' => 5000,
                'toast' => true,
                'timerProgressBar' => true,
            ]);

            // Add error to Livewire error bag
            $this->addError('combined_analysis', "Failed to process combined exam data: {$e->getMessage()}");
        } finally {
            $this->loading = false;
        }
    }

    public function updatedSelectedClass($classId)
    {
        $this->loading = true;

        // Reset and reload data
        $this->sychAfresh();

        // Load sections based on the selected class
        $this->sections = Section::where('my_class_id', $classId)->get();

        // Fetch the class to display its name later
        $this->selectedClassData = MyClass::find($classId);

        // Load exams based on the selected class
        $this->exams = Exam::where('class_id', $classId)->get();

        // Automatically analyze combined results if conditions are met
        if ($this->selectedClass && count($this->selectedExams) > 1) {
            $this->analyzeCombinedResults();
        }

        $this->loading = false;
    }

    public function sychAfresh()
    {
        // Reset all relevant properties
        $this->reset([
            'selectedExams', // Reset selected exams
            'selectedTerm',  // Reset selected term
            'selectedYear',  // Reset selected year
            'selectedExam',  // Reset selected exam
            'examResults',   // Reset exam results
            'selectedSection', // Reset selected section
            'showTable',     // Hide the results table
            'showCombinedExamForm', // Hide the combined exam form
            'combinedResults', // Reset combined results
            'customExamName', // Reset custom exam name
            'customExamTerm', // Reset custom exam term
            'customExamYear', // Reset custom exam year
            'selectedExamNames', // Reset selected exam names
            'gradingSystemNames', // Reset grading system names
            'subjects',      // Reset subjects
            'marks',         // Reset marks
        ]);

        // Reload exams based on the selected class
        if ($this->selectedClass) {
            $this->exams = Exam::where('class_id', $this->selectedClass)->get();
        } else {
            $this->exams = []; // Reset exams if no class is selected
        }

        // Reload sections based on the selected class
        if ($this->selectedClass) {
            $this->sections = Section::where('my_class_id', $this->selectedClass)->get();
        } else {
            $this->sections = []; // Reset sections if no class is selected
        }

        // Reset the selected class data
        $this->selectedClassData = null;

        // Reset loading state
        $this->loading = false;
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
        $this->selectedSection = null;
        $this->showTable = false;
    }


    public function resetAnalysisTable()
    {
        $this->combinedResults = []; // Clear the analysis results
    }


    public function updatedSelectedExam($examId)
    {
        $this->loading = true;

        Log::info("Fetching exam data for exam ID: $examId");

        try {
            // Fetch the exam with its grading system and subjects
            $exam = Exam::with('gradingSystem.subjects')->find($examId);
            if (!$exam) {
                Log::warning("No exam found for ID: $examId");
                $this->resetExamData();
                return;
            }

            // Fetch students based on the selected class and section
            $this->students = StudentRecord::with(['examMarks' => function ($query) use ($examId) {
                $query->where('exam_id', $examId);
            }, 'section', 'parent_detail'])
                ->where('my_class_id', $this->selectedClass)
                ->when($this->selectedSection, function ($query) {
                    $query->where('section_id', $this->selectedSection);
                })
                ->get();

            Log::info("Fetched Students Count: " . $this->students->count());

            // Set the subjects for the exam
            $this->subjects = $exam->gradingSystem->subjects;

            // Prepare student data (marks, grades, etc.)
            $studentData = $this->prepareStudentData($exam);

            // Calculate positions using the StudentHelper class
            $this->marks = $this->calculatePositionsWithHelper($studentData, exam: $exam);

            // Send a notification to the authenticated user
            $this->sendNotificationToUser($exam);

            // Send notifications to parents
            $this->sendNotificationsToParents($exam, $studentData);
        } catch (\Throwable $e) {
            Log::error("Error in updatedSelectedExam: " . $e->getMessage());
            $this->addError('exam_processing', "Failed to process exam data: {$e->getMessage()}");
        } finally {
            $this->loading = false;
        }
    }

    private function sendNotificationToUser($exam)
    {
        $user = Auth::user();
        $message = "Exam analysis completed for {$exam->name}.";

        // Send the notification to the authenticated user
        $user->notify(new SystemNotification($message));

        Log::info("Notification sent to user ID: {$user->id} for exam ID: {$exam->id}");
    }

    private function sendNotificationsToParents($exam, $studentData)
    {
        foreach ($studentData as $student) {
            $studentRecord = StudentRecord::with('parent_detail')->find($student['student_id']);

            if ($studentRecord && $studentRecord->parent_detail) {
                $parent = $studentRecord->parent_detail;

                $message = "Exam analysis completed for your child, {$student['student_name']}, in {$exam->name}. "
                    . "Total Marks: {$student['total_marks']}, Mean Grade: {$student['mean_grade']}.";

                // Send the notification to the parent
                $parent->notify(new SystemNotification($message));

                Log::info("Notification sent to parent ID: {$parent->id} for student ID: {$student['student_id']}");
            }
        }
    }

    private function prepareStudentData($exam)
    {
        Log::info("Preparing student data for exam ID: {$exam->id}");

        try {
            $studentData = [];
            $specialGradeStudents = [];

            // Ensure grading system and subjects are loaded
            if (!$exam->gradingSystem || !$exam->gradingSystem->subjects) {
                Log::warning("Grading system or subjects not found for exam ID: {$exam->id}");
                return [];
            }

            // Fetch all subjects and group them by category
            $subjects = $exam->gradingSystem->subjects;
            $scienceSubjects = $subjects->filter(function ($subject) {
                return $subject->category->name === 'Sciences'; // Adjust category name as needed
            });

            $otherSubjects = $subjects->filter(function ($subject) {
                return $subject->category->name !== 'Sciences'; // Adjust category name as needed
            });

            foreach ($this->students as $student) {
                $studentMarks = [];
                $studentGrades = [];
                $totalMarks = 0;
                $totalPoints = 0;
                $validSubjects = 0;
                $hasSpecialGrade = false;
                $specialGradeCounts = ['X' => 0, 'Y' => 0, 'Z' => 0]; // Track counts of special grades

                // Get marks for all subjects
                $allMarks = [];
                foreach ($subjects as $subject) {
                    $marksValue = StudentHelper::getStudentMarks($student, $subject);
                    $specialGrade = StudentHelper::getStudentSpecialGrade($student, $subject);

                    if ($specialGrade !== null) {
                        $hasSpecialGrade = true;
                        $specialGradeCounts[$specialGrade]++; // Increment count for the special grade
                    }

                    // Store marks and special grades
                    $allMarks[$subject->id] = [
                        'subject' => $subject,
                        'marks' => $marksValue,
                        'special_grade' => $specialGrade,
                    ];
                }

                // Select the best 2 science subjects
                $scienceMarks = collect($allMarks)->filter(function ($mark) use ($scienceSubjects) {
                    return $scienceSubjects->contains('id', $mark['subject']->id);
                })->sortByDesc('marks')->take(2);

                // Select the top 5 other subjects
                $otherMarks = collect($allMarks)->filter(function ($mark) use ($otherSubjects) {
                    return $otherSubjects->contains('id', $mark['subject']->id);
                })->sortByDesc('marks')->take(5);

                // Combine the selected subjects
                $selectedSubjects = $scienceMarks->merge($otherMarks);

                // Calculate total marks and points for selected subjects
                foreach ($selectedSubjects as $subjectId => $markData) {
                    $subject = $markData['subject'];
                    $marksValue = $markData['marks'];
                    $specialGrade = $markData['special_grade'];

                    if ($specialGrade !== null) {
                        // If special grade exists, assign it and skip marks calculation
                        $gradeData = ['grade' => $specialGrade, 'points' => 0];
                    } else {
                        // Calculate grade data based on marks
                        $gradeData = StudentHelper::getGradeData($marksValue, $exam->gradingSystem->id, $subject->id);
                    }

                    // Ensure gradeData is valid
                    if (!$gradeData || !isset($gradeData['points'])) {
                        Log::warning("Invalid grade data for student ID: {$student->id}, subject ID: {$subject->id}");
                        $gradeData = ['grade' => '--', 'points' => 0];
                    }

                    $gradeData['points'] = is_numeric($gradeData['points'] ?? null) ? $gradeData['points'] : 0;

                    $studentMarks[$subject->id] = $marksValue;
                    $studentGrades[$subject->id] = $gradeData['grade'] ?? '--';

                    // Only add to total marks and points if there are no special grades
                    if ($specialGrade === null) {
                        $totalMarks += is_numeric($marksValue) ? (int) $marksValue : 0;
                        $totalPoints += $gradeData['points'];
                        $validSubjects++;
                    }
                }

                // Calculate mean score and mean grade
                $meanScore = $validSubjects > 0 ? ($totalMarks / $validSubjects) : 0;

                // Determine the mean grade for students with special grades
                if ($hasSpecialGrade) {
                    // Find the dominant special grade
                    $dominantSpecialGrade = array_search(max($specialGradeCounts), $specialGradeCounts);
                    $meanGrade = $dominantSpecialGrade; // Assign the dominant special grade as the mean grade
                } else {
                    // Calculate mean grade based on total points using StudentHelper
                    $meanGrade = StudentHelper::getMeanGrade($totalPoints, $exam->gradingSystem->id);
                }

                // Save or update the student result in the database
                StudentResult::updateOrCreate(
                    [
                        'student_id' => $student->id,
                        'exam_id' => $exam->id,
                    ],
                    [
                        'mean_grade' => $meanGrade,
                    ]
                );

                $studentEntry = [
                    'student_id' => $student->id,
                    'exam_id' => $exam->id,
                    'student_name' => "{$student->first_name} {$student->last_name}",
                    'marks' => $studentMarks,
                    'grades' => $studentGrades,
                    'total_marks' => $totalMarks,
                    'total_points' => $totalPoints,
                    'mean_score' => $meanScore,
                    'mean_grade' => $meanGrade,
                    'stream' => $student->section->name ?? '-', // Handle null section
                    'has_special_grade' => $hasSpecialGrade,
                ];

                if ($hasSpecialGrade) {
                    $specialGradeStudents[] = $studentEntry;
                } else {
                    $studentData[] = $studentEntry;
                }
            }

            // Calculate positions for normal students using StudentHelper
            $studentData = $this->calculatePositionsWithHelper($studentData, $exam);

            // Add special grade students at the bottom
            foreach ($specialGradeStudents as &$student) {
                $student['position'] = 'N/A';
                $student['stream_position'] = 'N/A';
                $studentData[] = $student;
            }
        } catch (\Throwable $e) {
            Log::error("Error preparing student data for exam ID: {$exam->id}: {$e->getMessage()}");
            $this->addError('exam_processing', "Failed to process exam data: {$e->getMessage()}");
            return [];
        }

        return $studentData;
    }

    private function calculatePositionsWithHelper(array $studentData, Exam $exam)
    {
        // Sort students using the helper method
        $studentData = StudentHelper::sortStudents($studentData);

        // Assign positions using the helper method
        $studentData = StudentHelper::assignPositions($studentData);

        // Calculate stream positions for each student
        foreach ($studentData as $index => &$student) {
            $studentRecord = StudentRecord::find($student['student_id']);

            if ($studentRecord) {
                $student['stream_position'] = StudentHelper::calculateStreamPosition($studentRecord, $exam);
            } else {
                $student['stream_position'] = 'N/A';
            }
        }

        return $studentData;
    }
    private function resetExamData()
    {
        $this->marks = [];
        $this->subjects = [];
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


    public function resetSectionAndExams()
    {
        $this->selectedSection = null;
        $this->exams = []; // Clear exams when class is reset
    }


    public function render()
    {
        return view('livewire.combination-formula', [
            'combinedResults' => $this->combinedResults,
            'subjects' => $this->subjects ?? [],
            'selectedExamNames' => $this->selectedExamNames ?? [],
            'gradingSystemNames' => $this->gradingSystemNames ?? [],
            'showCombinedExamForm' => $this->showCombinedExamForm,
            'showTable' => $this->showTable,
            'customExamName' => $this->customExamName,
        ]);
    }
}
