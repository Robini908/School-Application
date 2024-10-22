<?php

namespace App\Livewire;

use Carbon\Carbon;
use App\Models\MyClass;
use App\Models\Section;
use Livewire\Component;
use App\Models\StudentRecord;
use App\Models\StudentTransition;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Cache;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class ManagePromotions extends Component
{
    use LivewireAlert;

    public $classes;
    public $sections = [];

    public $notPromotedStudents = [];
    public $students;
    public $newSections = [];
    public $selectedClass = null;
    public $selectedSection = null;
    public $selectedStudents = [];
    public $newClass = null;
    public $newSection = null;
    public $eventDate;
    public $reason;
    public $academicYear;
    public $nextAcademicYear;
    public $step = 1;
    public $suggestedClass = null;

    protected $rules = [
        'selectedClass' => 'required|exists:my_classes,id',
        'selectedSection' => 'required|exists:sections,id',
        'selectedStudents' => 'required|array|min:1',
        'newClass' => 'required|exists:my_classes,id',
        'newSection' => 'nullable|exists:sections,id',
        'eventDate' => 'required|date',
        'academicYear' => 'required|date_format:Y',
        'nextAcademicYear' => 'required|date_format:Y',
        'reason' => 'nullable|string',
    ];

    public function mount()
    {
        // Cache classes to avoid multiple database hits
        $this->classes = Cache::remember('classes', now()->addDay(), function () {
            return MyClass::all();
        });

        $this->eventDate = Carbon::now()->format('Y-m-d');
        $this->academicYear = Carbon::now()->format('Y');
        $this->nextAcademicYear = Carbon::now()->addYear()->format('Y');
    }

    public function updatedSelectedClass($classId)
    {
        $this->sections = Cache::remember("sections_class_{$classId}", now()->addDay(), function () use ($classId) {
            return Section::where('my_class_id', $classId)->get();
        });
        $this->selectedSection = null;
        $this->students = [];
        $this->suggestedClass = $this->getSuggestedClass($classId);
    }

    public function updatedSelectedSection($sectionId)
    {
        $this->students = collect(); // Initialize as an empty collection

        StudentRecord::where('section_id', $sectionId)->chunk(100, function ($records) {
            $this->students = $this->students->merge($records); // Merge each chunk
        });

        $this->selectedStudents = [];
    }


    public function selectStudents()
    {
        $this->validate();
        $this->step = 2;
    }

    public function updatedNewClass($classId)
    {
        // Cache new sections based on the new class
        $this->newSections = Cache::remember("new_sections_class_{$classId}", now()->addDay(), function () use ($classId) {
            return Section::where('my_class_id', $classId)->get();
        });
        $this->newSection = null;
    }

    public function selectAllStudents($isSelected)
    {
        if ($isSelected) {
            // Select all student IDs from the currently loaded students
            $this->selectedStudents = $this->students->pluck('id')->toArray();
        } else {
            // Clear the selected students array
            $this->selectedStudents = [];
        }
    }


    public function promoteStudents()
    {
        $this->validate([
            'selectedClass' => 'required',
            'selectedSection' => 'required',
            'selectedStudents' => 'required|array|min:1',
            'newClass' => 'required',
            'newSection' => 'required',
        ]);

        $oldClass = MyClass::find($this->selectedClass);
        $newClass = MyClass::find($this->newClass);

        if ($newClass->id === $oldClass->id) {
            $this->alert('error', 'Cannot promote students to the same class.');
            return;
        }

        if ($newClass->id < $oldClass->id) {
            $this->alert('error', 'Cannot promote to a class below the current class.');
            return;
        }

        if ($this->newSection && $this->hasDuplicatePromotions()) {
            $this->alert('error', 'Some students are already in the new class or section.');
            return;
        }

        $promotedStudents = [];
        $skippedStudents = [];

        try {
            foreach (array_chunk($this->selectedStudents, 50) as $studentBatch) {
                foreach ($studentBatch as $studentId) {
                    if ($this->isStudentAlreadyPromoted($studentId)) {
                        $skippedStudents[] = $studentId;
                        continue;
                    }

                    StudentTransition::create([
                        'student_id' => $studentId,
                        'new_class_id' => $this->newClass,
                        'new_section_id' => $this->newSection,
                        'transition_type' => 'promotion',
                        'reason' => $this->reason,
                        'event_date' => $this->eventDate,
                        'academic_year' => $this->academicYear,
                        'next_academic_year' => $this->nextAcademicYear,
                        'approved_by' => Auth::id(),
                    ]);

                    $promotedStudents[] = $studentId;
                }
            }

            if (count($promotedStudents) === count($this->selectedStudents)) {
                $this->alert('success', 'All selected students were promoted successfully!');
            } elseif (count($promotedStudents) > 0) {
                $this->alert('warning', 'Some students were promoted successfully, but the following were already promoted: ' . implode(', ', $skippedStudents));
            } else {
                $this->alert('error', 'No students were promoted as they were already promoted to the new class or section.');
            }

            // Reset after successful promotion
            $this->resetInput();
            $this->step = 1;
        }
        
        catch (QueryException $e) {
            Log::error('SQL Error promoting students: ' . $e->getMessage());
            $this->alert('error', 'A database error occurred while promoting students: ' . $e->getMessage());
        } 
        catch (\Exception $e) {
            Log::error('Error promoting students: ' . $e->getMessage());
            $this->alert('error', 'An error occurred while promoting students. Please try again later.');
        }
    }

    public function filterUnpromotedStudents($classId, $sectionId, $academicYear)
    {
        // Validate inputs
        $this->validate([
            'classId' => 'required|exists:my_classes,id',
            'sectionId' => 'required|exists:sections,id',
            'academicYear' => 'required|string',
        ]);

        // Fetch unpromoted students based on filters
        $this->notPromotedStudents = collect(StudentRecord::where('class_id', $classId)
            ->where('section_id', $sectionId)
            ->whereHas('transitions', function ($query) use ($academicYear) {
                $query->where('academic_year', $academicYear)
                    ->where('transition_type', 'promotion');
            }, '<', 1) // Ensure no promotions exist for the given academic year
            ->get());
    }


    public function applyFilters()
    {
        // Ensure the selected class and section are valid before filtering
        if ($this->selectedClass && $this->selectedSection) {
            $this->filterUnpromotedStudents($this->selectedClass, $this->selectedSection, $this->academicYear);
        } else {
            $this->notPromotedStudents = []; // Clear if no valid selections
        }
    }



    private function hasDuplicatePromotions()
    {
        return StudentRecord::where('my_class_id', $this->newClass)
            ->where('section_id', $this->newSection)
            ->whereIn('id', $this->selectedStudents)
            ->exists();
    }

    private function isStudentAlreadyPromoted($studentId)
    {
        return StudentTransition::where('student_id', $studentId)
            ->where('new_class_id', $this->newClass)
            ->where('new_section_id', $this->newSection)
            ->exists();
    }

    private function getSuggestedClass($classId)
    {
        // Cache suggestion to reduce repeated queries
        return Cache::remember("suggested_class_{$classId}", now()->addDay(), function () use ($classId) {
            return MyClass::where('id', '>', $classId)->first()?->id;
        });
    }

    private function resetInput()
    {
        $this->selectedClass = null;
        $this->selectedSection = null;
        $this->selectedStudents = [];
        $this->newClass = null;
        $this->newSection = null;
        $this->reason = '';
        $this->eventDate = Carbon::now()->format('Y-m-d');
        $this->academicYear = Carbon::now()->format('Y');
        $this->nextAcademicYear = Carbon::now()->addYear()->format('Y');
        $this->students = [];
        $this->sections = [];
        $this->newSections = [];
        $this->suggestedClass = null;
    }

    public function render()
    {
        return view('livewire.manage-promotions');
    }
}
