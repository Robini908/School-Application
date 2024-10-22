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
    public $promotedStreams = [];
    public $notPromotedCount = 0;
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

    // public function updatedSelectedSection($sectionId)
    // {
    //     $this->students = collect(); // Initialize as an empty collection

    //     StudentRecord::where('section_id', $sectionId)->chunk(100, function ($records) {
    //         $this->students = $this->students->merge($records); // Merge each chunk
    //     });

    //     $this->selectedStudents = [];
    // }
    public function updatedSelectedSection($sectionId)
    {
        // Initialize students collection for the selected section
        $this->students = collect();

        // Fetch students in the selected section
        StudentRecord::where('section_id', $sectionId)->chunk(100, function ($records) {
            $this->students = $this->students->merge($records);
        });

        // Fetch IDs of already promoted students for the current academic year in this section
        $promotedStudents = StudentTransition::where('new_section_id', $sectionId)
            ->where('academic_year', $this->academicYear)
            ->pluck('student_id')
            ->toArray();

        // Filter out students who have already been promoted from the total list
        $this->students = $this->students->filter(function ($student) use ($promotedStudents) {
            return !in_array($student->id, $promotedStudents);
        });

        // Get promoted streams (sections) by the section ID
        $this->promotedStreams = Section::whereIn('id', $promotedStudents)->pluck('name')->toArray();

        // Calculate the number of students not yet promoted
        $this->notPromotedCount = $this->students->count();

        // Reset selected students
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
            // Use a more efficient query to fetch and select all student IDs at once
            $this->selectedStudents = $this->students->pluck('id')->toArray();
        } else {
            // Clear the selected students in one go
            $this->selectedStudents = [];
        }
    }



    public function promoteStudents()
    {
        // Validate input fields
        $this->validate([
            'selectedClass' => 'required',
            'selectedSection' => 'required',
            'selectedStudents' => 'required|array|min:1',
            'newClass' => 'required',
            'newSection' => 'required',
        ]);

        $oldClass = MyClass::find($this->selectedClass);
        $newClass = MyClass::find($this->newClass);
        $newSection = Section::find($this->newSection);

        // Scenario 1: Prevent promotion to the same class
        if ($newClass->id === $oldClass->id) {
            $this->alert('error', 'Cannot promote students to the same class.');
            return;
        }

        // Scenario 2: Prevent promotion to a lower class
        if ($newClass->id < $oldClass->id) {
            $this->alert('error', 'Cannot promote students to a class below the current class.');
            return;
        }

        // **New Scenario 3: Ensure promotion is only to the next class in sequence**
        if ($newClass->id !== ($oldClass->id + 1)) {
            $this->alert('error', 'Students can only be promoted to the next sequential class.');
            return;
        }

        // Advanced Scenario 4: Prevent duplicate promotions
        if ($this->hasDuplicatePromotions()) {
            $this->alert('error', 'Some students are already promoted to the new class or section.');
            return;
        }

        $promotedStudents = [];
        $skippedStudents = [];

        try {
            foreach (array_chunk($this->selectedStudents, 50) as $studentBatch) {
                foreach ($studentBatch as $studentId) {
                    // Check if student is already promoted this year to avoid multiple promotions
                    if ($this->isStudentAlreadyPromoted($studentId)) {
                        $skippedStudents[] = $studentId;
                        continue;
                    }

                    // Validate student to prevent repeated promotions within the same class but different sections
                    if ($this->isStudentPromotedToSameClassDifferentSection($studentId, $this->newClass, $this->newSection)) {
                        $skippedStudents[] = $studentId;
                        continue;
                    }

                    // Promote the student by creating a transition record
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

            // Handle post-promotion results
            $this->handlePromotionResults($promotedStudents, $skippedStudents);

            // Reset form and progress step
            $this->resetInput();
            $this->step = 1;
        } catch (QueryException $e) {
            Log::error('SQL Error promoting students: ' . $e->getMessage());
            $this->alert('error', 'A database error occurred while promoting students: ' . $e->getMessage());
        } catch (\Exception $e) {
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



    // private function hasDuplicatePromotions()
    // {
    //     return StudentRecord::where('my_class_id', $this->newClass)
    //         ->where('section_id', $this->newSection)
    //         ->whereIn('id', $this->selectedStudents)
    //         ->exists();
    // }


    public function handlePromotionResults($promotedStudents, $skippedStudents)
    {
        if (count($promotedStudents) === count($this->selectedStudents)) {
            $this->alert('success', 'All selected students were promoted successfully!');
        } elseif (count($promotedStudents) > 0) {
            $this->alert('warning', 'Some students were promoted successfully, but the following were already promoted: ' . implode(', ', $skippedStudents));
        } else {
            $this->alert('error', 'No students were promoted as they were already promoted to the new class or section.');
        }
    }


    public function hasDuplicatePromotions()
    {
        foreach ($this->selectedStudents as $studentId) {
            if ($this->isStudentAlreadyPromoted($studentId)) {
                return true;
            }
        }
        return false;
    }


    private function isStudentAlreadyPromoted($studentId)
    {
        return StudentTransition::where('student_id', $studentId)
            ->where('new_class_id', $this->newClass)
            ->where('new_section_id', $this->newSection)
            ->exists();
    }




    public function isStudentPromotedToSameClassDifferentSection($studentId, $newClassId, $newSectionId)
    {
        return StudentTransition::where('student_id', $studentId)
            ->where('new_class_id', $newClassId)
            ->where('new_section_id', '<>', $newSectionId)
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
