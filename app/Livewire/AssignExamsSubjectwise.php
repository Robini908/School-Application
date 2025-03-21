<?php

namespace App\Livewire;

use App\Models\Exam;
use App\Models\MyClass;
use App\Models\Section;
use App\Models\Subject;
use Livewire\Component;
use App\Models\ExamMarks;
use Livewire\WithPagination;
use App\Models\StudentRecord;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use App\Models\SubjectSelectionSetting;

class AssignExamsSubjectwise extends Component
{
    use WithPagination;
    use LivewireAlert;

    public $selectedClass;
    public $specialGrades = [];
    public $selectedExamName;
    public $marksMessage = null; // Add a public property to store the success
    public $selectedExam;
    public $selectedSubject;
    public $selectedSection;
    /** @var Collection<int, StudentRecord> */
    public $students;

    public $marks = []; // Holds the numeric marks assigned to each student
    public $selectedSubjectName; // Holds the selected subject name
    public $assignedMarks; // Holds the assigned marks for the selected exam and subject
    public $editingMarkId = null; // Tracks the currently editing mark

    // For filtering in the table view
    public $filterClass;

    public $filterExam;
    public $filterSubject;
    public $selectedClassName; // Holds the selected class name
    public $editingSpecialGradeId = null;


    public $filterSection;

    protected $rules = [
        'marks.*' => 'nullable|numeric|min:0|max:100',
        'specialGrades.*' => 'nullable|in:AB,EX,P,F'
    ];

    public function mount()
    {
        // Initialize collections and variables
        $this->students = collect();
        $this->assignedMarks = collect();
        $this->marks = [];
        $this->specialGrades = [];
        $this->editingMarkId = null;
        $this->selectedSubjectName = null;
        $this->selectedClassName = null;
        $this->selectedClass = null;
        $this->selectedSection = null;
        $this->selectedExam = null;
        $this->selectedSubject = null;
    }

    

    public function isStudentEnrolledInSubject($studentId, $subjectId)
    {
        // First check if subject selection is enabled for the class
        $isSelectionEnabled = SubjectSelectionSetting::where('class_id', $this->selectedClass)
            ->where('is_subject_selection_enabled', true)
            ->exists();

        // If subject selection is not enabled for this class, all students are considered enrolled
        if (!$isSelectionEnabled) {
            return true;
        }

        // If subject selection is enabled, check if student has selected this subject
        return DB::table('student_subject')
            ->where('student_id', $studentId)
            ->where('subject_id', $subjectId)
            ->exists();
    }

    public function isSubjectSelectionEnabled($classId)
    {
        $class = MyClass::find($classId);
        return $class?->subjectSelectionSetting?->is_subject_selection_enabled ?? false;
    }

    public function getStudentMark($studentId)
    {
        return $this->assignedMarks
            ->where('student_id', $studentId)
            ->first()
            ->marks ?? null;
    }

    public function getStudentSpecialGrade($studentId)
    {
        return $this->assignedMarks
            ->where('student_id', $studentId)
            ->first()
            ->special_grade ?? null;
    }

    public function render()
    {
        $classes = MyClass::all();
        $exams = $this->selectedClass
            ? Exam::where('class_id', $this->selectedClass)->get()
            : collect();
        $subjects = $this->selectedExam
            ? Subject::whereHas('gradingSystems', function ($query) {
                $query->where('grading_systems.id', Exam::find($this->selectedExam)->grading_system_id);
            })->get()
            : collect();

        // Get sections with student counts
        $sections = collect();
        if ($this->selectedClass) {
            $sections = Section::where('my_class_id', $this->selectedClass)
                ->withCount(['studentRecords' => function ($query) {
                    $query->where('my_class_id', $this->selectedClass);
                }])
                ->get()
                ->map(function ($section) {
                    // Get enrolled student count if subject is selected
                    if ($this->selectedSubject) {
                        request()->merge(['subject_id' => $this->selectedSubject]);
                        $section->enrolled_count = StudentRecord::where('section_id', $section->id)
            ->where('my_class_id', $this->selectedClass)
                            ->get()
                            ->filter(function ($student) {
                                return $student->is_enrolled;
                            })
                            ->count();
                    }
                    return $section;
                });
        }

        // Update class name whenever render is called
        if ($this->selectedClass) {
            $class = MyClass::find($this->selectedClass);
            $this->selectedClassName = $class ? $class->name : null;
        }

        // Fetch students with their relationships
        if ($this->selectedSection) {
            request()->merge(['subject_id' => $this->selectedSubject]);
            $this->students = StudentRecord::with(['user', 'subjects'])
            ->where('section_id', $this->selectedSection)
                ->where('my_class_id', $this->selectedClass)
                ->get()
                ->sortBy(function ($student) {
                    return [!$student->is_enrolled, $student->adm_no];
                })
                ->values();

            $this->populateMarksArray();
        } else {
            $this->students = collect();
        }

        $this->selectedExamName = $this->selectedExam
            ? Exam::find($this->selectedExam)->name
            : null;

        $this->assignedMarks = ($this->selectedExam && $this->selectedSubject)
            ? ExamMarks::where('exam_id', $this->selectedExam)
            ->where('subject_id', $this->selectedSubject)
            ->with('student.user')
            ->get()
            : collect();

        return view('livewire.assign-exams-subjectwise', [
            'classes' => $classes,
            'exams' => $exams,
            'subjects' => $subjects,
            'sections' => $sections,
            'assignedMarksForTable' => $this->assignedMarks,
            'selectedExamName' => $this->selectedExamName,
            'selectedSubjectName' => $this->selectedSubjectName,
        ]);
    }

    public function populateMarksArray()
    {
        foreach ($this->students as $student) {
            $assignedMark = $this->assignedMarks->firstWhere('student_id', $student->id);
            if ($assignedMark) {
                if ($assignedMark->special_grade) {
                    $this->specialGrades[$student->id] = $assignedMark->special_grade;
                    $this->marks[$student->id] = null;
                } else {
                    $this->marks[$student->id] = $assignedMark->marks;
                    $this->specialGrades[$student->id] = null;
                }
            } else {
                $this->marks[$student->id] = null;
                $this->specialGrades[$student->id] = null;
            }
        }
    }

    public function updatedSelectedClass($classId)
    {
        $this->reset(['selectedExam', 'selectedSubject', 'selectedSection', 'students', 'assignedMarks', 'selectedClassName']);
        
        if ($classId) {
            $class = MyClass::find($classId);
            $this->selectedClassName = $class ? $class->name : null;
        }
    }

    public function updatedSelectedExam($examId)
    {
        $this->reset(['selectedSubject', 'selectedSection', 'students']);
    }

    public function updatedSelectedSection($sectionId)
    {
        if ($sectionId) {
            $this->students = StudentRecord::with(['user', 'subjects'])
                ->where('section_id', $sectionId)
                ->where('my_class_id', $this->selectedClass)
                ->get()
                ->map(function ($student) {
                    $student->is_enrolled = $this->isStudentEnrolledInSubject($student->id, $this->selectedSubject);
                    return $student;
                })
                ->sortBy(function ($student) {
                    return [!$student->is_enrolled, $student->adm_no];
                })
                ->values();

            $this->populateMarksArray();
        } else {
            $this->students = collect();
        }
    }

    protected function getStudents()
    {
        if (!$this->selectedSection) {
            return collect();
        }

        return StudentRecord::with(['user', 'subjects'])
            ->where('section_id', $this->selectedSection)
            ->where('my_class_id', $this->selectedClass)
            ->get()
        ->map(function ($student) {
            $student->is_enrolled = $this->isStudentEnrolledInSubject($student->id, $this->selectedSubject);
            return $student;
        })
        ->sortBy(function ($student) {
            return [!$student->is_enrolled, $student->adm_no];
        })
        ->values();
    }

    public function updatedSelectedSubject($subjectId)
    {
        $this->reset(['students', 'marks', 'specialGrades', 'selectedSection']);

        if ($subjectId) {
            $subject = Subject::find($subjectId);
            $this->selectedSubjectName = $subject ? $subject->subject_name : 'Unknown Subject';
        } else {
            $this->selectedSubjectName = null;
        }
    }

    public function saveMark($studentId)
    {
        $this->validateOnly("marks.{$studentId}");
        
        // Clear any special grade when saving a numeric mark
        if (isset($this->marks[$studentId])) {
            $this->specialGrades[$studentId] = null;
            
            // Save to database
            ExamMarks::updateOrCreate(
                        [
                            'student_id' => $studentId,
                            'exam_id' => $this->selectedExam,
                            'subject_id' => $this->selectedSubject,
                        ],
                        [
                    'marks' => $this->marks[$studentId],
                    'special_grade' => null,
                ]
            );
            
            // Refresh the marks data
            $this->refreshMarksData();
            
            $this->dispatch('mark-saved', studentId: $studentId);
        }
    }

    public function assignSpecialGrade($grade, $studentId)
    {
        if (!in_array($grade, ['AB', 'EX', 'P', 'F'])) {
            return;
        }

        // Clear numeric mark when assigning special grade
        $this->marks[$studentId] = null;
        $this->specialGrades[$studentId] = $grade;

        // Save to database
        ExamMarks::updateOrCreate(
            [
                'student_id' => $studentId,
                'exam_id' => $this->selectedExam,
                'subject_id' => $this->selectedSubject,
            ],
            [
                'marks' => null,
                'special_grade' => $grade,
            ]
        );

        // Refresh the marks data
        $this->refreshMarksData();

        $this->dispatch('mark-saved', studentId: $studentId);
    }

    public function clearMark($studentId)
    {
        $this->marks[$studentId] = null;
        $this->specialGrades[$studentId] = null;

        // Remove from database
        ExamMarks::where('student_id', $studentId)
            ->where('exam_id', $this->selectedExam)
            ->where('subject_id', $this->selectedSubject)
            ->delete();

        // Refresh the marks data
        $this->refreshMarksData();

        $this->dispatch('mark-saved', studentId: $studentId);
    }

    public function refreshMarks()
    {
        // Refresh the marks data
        $this->refreshMarksData();
    }

    protected function refreshMarksData()
    {
        // Fetch latest marks from database
        $latestMarks = ExamMarks::where('exam_id', $this->selectedExam)
            ->where('subject_id', $this->selectedSubject)
            ->get();

        // Reset arrays
        $this->marks = [];
        $this->specialGrades = [];

        // Populate arrays with latest data
        foreach ($latestMarks as $mark) {
            if ($mark->special_grade) {
                $this->specialGrades[$mark->student_id] = $mark->special_grade;
            } else {
                $this->marks[$mark->student_id] = $mark->marks;
            }
        }

        // Dispatch event for UI update
        $this->dispatch('marks-updated');
    }

    public function assignMarks()
    {
        $this->validate();

        foreach ($this->students as $student) {
            if (!$student->is_enrolled) {
                continue;
            }

            $mark = $this->marks[$student->id] ?? null;
            $specialGrade = $this->specialGrades[$student->id] ?? null;

            // Ensure mutual exclusivity
            if ($mark && $specialGrade) {
                continue; // Skip if both are set (shouldn't happen due to UI constraints)
            }

            // Get existing mark record or create new one
            $examMark = ExamMarks::firstOrNew([
                'student_id' => $student->id,
                'exam_id' => $this->selectedExam,
                'subject_id' => $this->selectedSubject
            ]);

            $examMark->marks = $mark;
            $examMark->special_grade = $specialGrade;
            $examMark->save();
        }

        // Refresh the marks data after bulk save
        $this->refreshMarksData();

        $this->dispatch('marks-assigned');
        session()->flash('success', 'Marks assigned successfully!');
    }

    public function editMark($studentId)
    {
        $this->editingMarkId = $studentId;

        // Pre-fill the form with existing marks or special grade
        $assignedMark = ExamMarks::where('student_id', $studentId)
            ->where('exam_id', $this->selectedExam)
            ->where('subject_id', $this->selectedSubject)
            ->first();

        if ($assignedMark) {
            $this->marks[$studentId] = $assignedMark->marks;
            $this->specialGrades[$studentId] = $assignedMark->special_grade;
        }
    }

    public function updateMark($studentId)
    {
        $this->validate([
            "marks.$studentId" => 'nullable|numeric|min:0|max:100',
            "specialGrades.$studentId" => 'nullable|in:X,Y,Z',
        ]);

        // Ensure either marks or special grade is provided, but not both
        if (!empty($this->marks[$studentId]) && !empty($this->specialGrades[$studentId])) {
            $this->addError("marks.$studentId", 'Cannot provide both marks and a special grade.');
            return;
        }

        ExamMarks::updateOrCreate(
            [
                'student_id' => $studentId,
                'exam_id' => $this->selectedExam,
                'subject_id' => $this->selectedSubject,
            ],
            [
                'marks' => $this->marks[$studentId],
                'special_grade' => $this->specialGrades[$studentId],
            ]
        );

        $this->editingMarkId = null;
        $this->refreshAssignedMarks();
        $this->alert('success', 'Marks/Grade updated successfully.');
    }
}
