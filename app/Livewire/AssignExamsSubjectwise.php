<?php

namespace App\Livewire;

use App\Models\Exam;
use App\Models\MyClass;
use App\Models\Section;
use App\Models\Subject;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use App\Models\ExamMarks;
use Livewire\WithPagination;
use App\Models\StudentRecord;
use Illuminate\Support\Facades\DB;

class AssignExamsSubjectwise extends Component
{
    use WithPagination;
    use LivewireAlert;

    public $selectedClass;
    public $selectedExamName;
    public $marksMessage = null; // Add a public property to store the success
    public $selectedExam;
    public $selectedSubject;
    public $selectedSection;
    public $students = [];
    public $marks = []; // Holds the marks assigned to each student
    public $selectedSubjectName; // Holds the selected subject name
    public $assignedMarks; // Holds the assigned marks for the selected exam and subject
    public $editingMarkId = null; // Tracks the currently editing mark

    // For filtering in the table view
    public $filterClass;
    public $filterExam;
    public $filterSubject;
    public $selectedClassName; // Holds the selected class name
  
    public $filterSection;

    public function mount()
    {
        $this->students = collect(); // Initialize as an empty Collection
        $this->assignedMarks = collect(); // Initialize as an empty Collection
        $this->marks = []; // Initialize marks as an empty array
        $this->editingMarkId = null; // Initialize editing state
    }

    

        // Populate marks array for rendering in the view
        public function render()
        {
            $classes = MyClass::all();
            $exams = $this->selectedClass ? Exam::where('class_id', $this->selectedClass)->get() : collect();
            $subjects = Subject::all();
            $sections = $this->selectedClass ? Section::where('my_class_id', $this->selectedClass)->get() : collect();
        
            // Fetch students based on the selected section
            $this->students = $this->selectedSection ? StudentRecord::where('section_id', $this->selectedSection)->get() : collect();
        
            // Set the selected class name based on the selected class ID
            $this->selectedClassName = $this->selectedClass ? MyClass::find($this->selectedClass)->class_name : null;
        
            // Set the selected exam name based on the selected exam ID
            $this->selectedExamName = $this->selectedExam ? Exam::find($this->selectedExam)->exam_name : null;
        
            // Set the selected subject name based on the selected subject ID
            $this->selectedSubjectName = $this->selectedSubject ? Subject::find($this->selectedSubject)->subject_name : null;
        
            // Fetch assigned marks based on selected class, exam, and subject
            if ($this->selectedExam && $this->selectedSubject) {
                $this->assignedMarks = ExamMarks::where('exam_id', $this->selectedExam)
                    ->where('subject_id', $this->selectedSubject)
                    ->with('student')
                    ->get();
            } else {
                $this->assignedMarks = collect(); // Reset to empty collection if no exam or subject is selected
            }
        
            // Populate marks array for rendering in the view
            $this->populateMarksArray();
        
            return view('livewire.assign-exams-subjectwise', [
                'classes' => $classes,
                'exams' => $exams,
                'subjects' => $subjects,
                'sections' => $sections,
                'assignedMarksForTable' => $this->assignedMarks,
            ]);
        }
        


    public function populateMarksArray()
    {
        // Prepare marks for students, checking if they already have assigned marks
        foreach ($this->students as $student) {
            $assignedMark = $this->assignedMarks->firstWhere('student_id', $student->id);
            $this->marks[$student->id] = $assignedMark ? $assignedMark->marks : null; // Assign existing marks or null if not found
        }
    }

    public function updatedSelectedSection($sectionId)
    {
        // Fetch all students based on the selected section
        $this->students = StudentRecord::where('section_id', $this->selectedSection)->get();

        // Initialize marks for students
        $this->populateMarksArray();
    }

    public function updatedSelectedClass($classId)
    {
        // Reset other selections but keep marks
        $this->reset(['selectedExam', 'selectedSubject', 'selectedSection', 'students', 'assignedMarks']);
    }

    public function updatedSelectedExam($examId)
    {
        // Reset subject and section but keep marks
        $this->reset(['selectedSubject', 'selectedSection', 'students']);
    }

    public function updatedSelectedSubject($subjectId)
    {
        // Reset section but keep marks
        $this->reset(['selectedSection', 'students']);
    }

    public function assignMarks()
    {
        try {
            // Validate the required fields
            $this->validate([
                'selectedExam' => 'required|exists:exams,id',
                'selectedSubject' => 'required|exists:subjects,id',
                'selectedSection' => 'required|exists:sections,id',
                'marks' => 'required|array',
                'marks.*' => 'nullable|numeric|min:0|max:100',
            ]);

            DB::beginTransaction();
            $updatedCount = $insertedCount = $skippedCount = $errorCount = 0;

            foreach ($this->marks as $studentId => $mark) {
                if (is_null($mark)) {
                    $skippedCount++;
                    continue;
                }

                if (!is_numeric($mark) || $mark < 0 || $mark > 100) {
                    $errorCount++;
                    continue;
                }

                try {
                    $examMark = ExamMarks::updateOrCreate(
                        [
                            'student_id' => $studentId,
                            'exam_id' => $this->selectedExam,
                            'subject_id' => $this->selectedSubject,
                        ],
                        ['marks' => $mark]
                    );

                    if ($examMark->wasRecentlyCreated) {
                        $insertedCount++;
                    } else {
                        $updatedCount++;
                    }
                } catch (\Exception $e) {
                    $errorCount++;
                    \Log::error("Error assigning marks for student ID {$studentId}: " . $e->getMessage());
                }
            }

            DB::commit();

            // Set messages based on the results
            if ($insertedCount > 0 || $updatedCount > 0) {
                $this->alert('success', "Marks assigned successfully! ");
            }

            if ($skippedCount > 0) {
                $this->alert('warning', "{$skippedCount} students were skipped due to null marks.");
            }

            if ($errorCount > 0) {
                $this->alert('error', "{$errorCount} errors occurred while assigning marks. Please check the logs for details.");
            }

            if ($insertedCount == 0 && $updatedCount == 0 && $skippedCount == 0 && $errorCount == 0) {
                $this->alert('info', "No changes were made. All marks remained the same.");
            }

            // Refresh the assigned marks
            $this->refreshAssignedMarks();

            // Reset only the marks array, keeping other selections intact
            $this->marks = [];
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->alert('error', 'Validation failed: ' . implode(', ', $e->errors()));
        } catch (\Exception $e) {
            DB::rollBack();
            $this->alert('error', 'An unexpected error occurred: ' . $e->getMessage());
            \Log::error("Error in assignMarks: " . $e->getMessage());
        }
    }

    public function refreshAssignedMarks()
    {
        if ($this->selectedExam && $this->selectedSubject) {
            $this->assignedMarks = ExamMarks::where('exam_id', $this->selectedExam)
                ->where('subject_id', $this->selectedSubject)
                ->with('student')
                ->get();
        } else {
            $this->assignedMarks = collect(); // Reset to empty collection if no exam or subject is selected
        }
    }



    public function editMark($studentId)
    {
        $this->editingMarkId = $studentId;

        $examMark = ExamMarks::where('student_id', $studentId)
            ->where('exam_id', $this->selectedExam)
            ->where('subject_id', $this->selectedSubject)
            ->first();

        if ($examMark) {
            $this->marks[$studentId] = $examMark->marks; // Pre-fill mark for editing
        } else {
            $this->marks[$studentId] = null; // Set to null if no marks exist
        }
    }

    public function updateMark($studentId)
    {
        if ($this->editingMarkId) {
            $examMark = ExamMarks::where('student_id', $studentId)
                ->where('exam_id', $this->selectedExam)
                ->where('subject_id', $this->selectedSubject)
                ->first();

            if ($examMark) {
                $examMark->marks = $this->marks[$studentId];
                $examMark->save();

                $this->alert('success', 'Marks updated successfully!');
            }

            $this->editingMarkId = null; // Reset editing state
            $this->refreshAssignedMarks(); // Refresh marks after update
        }
    }
}
