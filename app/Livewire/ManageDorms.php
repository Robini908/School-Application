<?php

namespace App\Livewire;

use App\User;
use App\Models\Dorm;
use App\Models\MyClass;
use App\Models\Section;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\StudentRecord;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class ManageDorms extends Component
{
    use WithPagination;
    use LivewireAlert;

    // Properties for form inputs
    public $name, $capacity, $description, $dormId;
    public $teacherId, $session, $classId;

    // Properties for UI state
    public $isCreating = false;

    public $selectedYear;
    public $showStudentsList = false;
    public $studentsInDorm = [];
    public $showOccupancyCard = false;
    public $occupancyData = [];
    public $students = array();
    public $myStudents;
    public $availableStudents; // Property to store students available for assignment

    public $dorm;

    public $dormCapacity;
    public $loadedStudents; // This will store the loaded students as a collection
    public $chunkSize = 50; // Number of students to load per chunk
    public $totalStudents = 0; // Total number of students (for progress tracking)
    public $isLoading = false; // Track loading state
    public $isAddingStudents = false;
    public $searchQuery = '';
    public $selectedStudents = [];
    public $isEditing = false;
    public $isAssigningDormMaster = false;
    public $isViewingDormMasters = false;
    public $selectedDormId;

    // Already exists, but ensure it's there
    public $selectedClass = null;
    public $selectedSection = null;
    public $classes; // To store all classes
    public $sections = []; // To store sections based on the selected class
    public $studentsList = []; // To store filtered students
    public $year;

    // Property to store the dorm name
    public $dormName;

    // Validation rules
    protected $rules = [
        'name' => 'required|string|max:255',
        'capacity' => 'required|integer|min:1',
        'description' => 'nullable|string',
        'teacherId' => 'required|exists:users,id',
        'session' => 'required|string|max:255',
    ];

    // Reset form fields and UI state
    public function resetForm()
    {
        $this->reset(['name', 'capacity', 'description', 'teacherId', 'session', 'dormId', 'dormName', 'classId']);
        $this->isCreating = false;
        $this->isEditing = false;
        $this->isAssigningDormMaster = false;
        $this->isViewingDormMasters = false;
        $this->resetErrorBag();
    }
    public function mount()
    {
        $this->classes = MyClass::with('sections')->get();
        $this->loadedStudents = collect(); // Initialize as an empty collection
        $this->selectedStudents = []; // Initialize as an empty array
    }


    public function searchStudents()
    {
        $query = StudentRecord::query();

        if ($this->classId) {
            $query->where('my_class_id', $this->classId);
        }

        if ($this->selectedSection) {
            $query->where('section_id', $this->selectedSection);
        }

        if ($this->searchQuery) {
            $query->where(function ($q) {
                $q->where('first_name', 'like', '%' . $this->searchQuery . '%')
                    ->orWhere('last_name', 'like', '%' . $this->searchQuery . '%')
                    ->orWhere('adm_no', 'like', '%' . $this->searchQuery . '%');
            });
        }

        // Store all filtered students in the $myStudents property
        $this->myStudents = $query->get();
        $this->totalStudents = $this->myStudents->count();

        // Reset loaded students and load the first chunk
        $this->loadedStudents = collect();
        $this->loadNextChunk();
    }


    public function loadNextChunk()
    {
        $this->isLoading = true;

        // Get the next chunk of students
        $nextChunk = $this->myStudents->splice(0, $this->chunkSize);

        // Merge the next chunk into the loaded students
        $this->loadedStudents = $this->loadedStudents->merge($nextChunk);

        $this->isLoading = false;
    }

    public function loadMore()
    {
        if ($this->loadedStudents->count() < $this->totalStudents) {
            $this->loadNextChunk();
        }
    }

    // Add students to the selected list
    public function addStudent($studentId)
    {
        if (!in_array($studentId, $this->selectedStudents)) {
            $this->selectedStudents[] = $studentId;
        }
    }

    public function removeStudent($studentId)
    {
        // Remove student from the selected list when adding students
        if ($this->isAddingStudents) {
            $this->selectedStudents = array_diff($this->selectedStudents, [$studentId]);
            return;
        }
        
        // Remove student from the dormitory in the dorm_student pivot table
        if ($this->selectedDormId && $this->year) {
            DB::table('dorm_student')
                ->where('dorm_id', $this->selectedDormId)
                ->where('student_id', $studentId)
                ->where('year', $this->year)
                ->delete();
            
            $this->alert('success', 'Student removed from dormitory successfully.');
            
            // Refresh the students list
            $this->viewStudents($this->year);
        }
    }


    public function updatedSelectedClass($value)
    {
        $this->classId = $value; // Set classId to be consistent
        $this->sections = Section::where('my_class_id', $value)->get();
        $this->selectedSection = null; // Reset selected section
        $this->resetLoadedStudents(); // Reset loaded students and apply filters
    }

    public function updatedSearchQuery()
    {
        $this->resetLoadedStudents(); // Reset loaded students and apply filters
    }

    public function updatedSelectedSection()
    {
        $this->resetLoadedStudents(); // Reset loaded students and apply filters
    }

    // Helper method to reset loaded students and apply filters
    public function resetLoadedStudents()
    {
        $this->loadedStudents = collect(); // Reset loaded students
        $this->searchStudents(); // Reapply filters and load the first chunk
    }

    public function assignStudentsToDorm()
    {
        // Validate session and class selection
        $this->validate([
            'session' => 'required|numeric|min:2000|max:' . (date('Y') + 5), // Adjust validation rules as needed
            'classId' => 'required|exists:my_classes,id'
        ]);

        if (empty($this->selectedStudents) || !is_array($this->selectedStudents)) {
            $this->alert('error', 'No students selected.');
            return;
        }

        // Fetch the dorm to check its capacity
        $dorm = Dorm::find($this->dormId);

        if (!$dorm) {
            $this->alert('error', 'Dorm not found.');
            return;
        }

        // Check if the dorm has reached its capacity
        $currentOccupancy = $dorm->students()->wherePivot('year', $this->session)->count();
        $availableCapacity = $dorm->capacity - $currentOccupancy;

        if ($availableCapacity <= 0) {
            $this->alert('error', 'Dorm is at full capacity. Cannot assign more students.');
            return;
        }

        // Ensure we don't exceed the dorm's capacity
        $studentsToAssign = min(count($this->selectedStudents), $availableCapacity);

        // Assign students to the dorm
        $assignedCount = 0;
        foreach ($this->selectedStudents as $studentId) {
            // Check if the student is already assigned to another dorm in the same year
            $isAlreadyAssigned = DB::table('dorm_student')
                ->where('student_id', $studentId)
                ->where('year', $this->session)
                ->exists();

            if ($isAlreadyAssigned) {
                $this->alert('error', "Student with ID {$studentId} is already assigned to another dorm in the same year.");
                continue;
            }

            // Assign the student to the dorm for the selected year
            $dorm->students()->attach($studentId, ['year' => $this->session]);

            $assignedCount++;

            // Stop if we've reached the dorm's capacity
            if ($assignedCount >= $availableCapacity) {
                break;
            }
        }

        if ($assignedCount > 0) {
            $this->alert('success', "{$assignedCount} students assigned to dorm successfully.");
        } else {
            $this->alert('warning', 'No students were assigned to the dorm.');
        }

        // Reset form and UI state
        $this->reset(['isAddingStudents', 'selectedStudents', 'searchQuery', 'selectedClass', 'selectedSection', 'classId', 'session']);
    }

    public function addStudents($dormId)
    {
        $this->dormId = $dormId;
        $this->selectedStudents = []; // Reset to empty array
        $this->year = date('Y'); // Set default year to current year
        $this->session = date('Y'); // Set default session to current year
        $this->classId = null; // Reset classId
        
        $dorm = Dorm::find($dormId);
        if ($dorm) {
            $this->dormName = $dorm->name;
        }
        
        $this->isAddingStudents = true;
        $this->searchStudents(); // Load initial student list
    }



    public function saveStudents()
    {
        foreach ($this->selectedStudents as $studentId) {
            StudentRecord::create([
                'dorm_id' => $this->dormId,
                'student_id' => $studentId,
            ]);
        }

        $this->reset(['isAddingStudents', 'selectedStudents', 'searchQuery']);
        session()->flash('message', 'Students added successfully.');
    }

    public function cancelAddingStudents()
    {
        $this->reset(['isAddingStudents', 'selectedStudents', 'searchQuery']);
    }

    public function setSelectedDormIdAndViewStudents($dormId, $year)
    {
        $this->selectedDormId = $dormId; // Set the selected dorm ID
        $this->viewStudents($year); // Call the viewStudents method
    }


    

    public function getCurrentYearOccupancy($dormId)
    {
        $currentYear = date('Y'); // Get the current year
        return DB::table('dorm_student')
            ->where('dorm_id', $dormId)
            ->where('year', $currentYear) // Filter by the current year
            ->count();
    }
    public function getOccupancy($dormId)
    {
        return DB::table('dorm_student')
            ->where('dorm_id', $dormId)
            ->where('year', $this->year) // Filter by the selected year
            ->count();
    }

    public function viewOccupancy($dormId)
    {
        $this->selectedDormId = $dormId;
        $this->showOccupancyCard = true;
        $this->loadOccupancyData($dormId);

        // Fetch the dorm's name and capacity
        $dorm = Dorm::find($dormId);
        $this->dormName = $dorm ? $dorm->name : 'Unknown Dorm';
        $this->dormCapacity = $dorm ? $dorm->capacity : 0;
    }
    public function loadOccupancyData($dormId)
    {
        // Fetch occupancy data over the years from the pivot table
        $this->occupancyData = DB::table('dorm_student')
            ->select('year', DB::raw('count(*) as occupancy'))
            ->where('dorm_id', $dormId)
            ->groupBy('year')
            ->orderBy('year')
            ->get();
    }

    public function viewStudents($year)
    {
        $this->year = $year;
        $this->selectedYear = $year;
        $this->showStudentsList = true;
        $this->resetPage(); // Reset pagination when changing year

        // Fetch the dorm name
        $dorm = Dorm::find($this->selectedDormId);
        if ($dorm) {
            $this->dormName = $dorm->name;
        } else {
            $this->dormName = 'Unknown Dorm';
        }
    }



    public function closeStudentsList()
    {
        $this->showStudentsList = false;
        $this->studentsInDorm = [];
        $this->selectedYear = null;
    }

    public function closeOccupancyCard()
    {
        $this->showOccupancyCard = false;
        $this->selectedDormId = null;
        $this->occupancyData = [];
        $this->dormName = null;
        $this->dormCapacity = 0;
        $this->closeStudentsList();
    }

    public function getYearsRange()
    {
        $currentYear = date('Y');
        $startYear = 2000; // You can adjust the start year as needed
        $endYear = $currentYear + 5; // You can adjust the end year as needed

        return range($startYear, $endYear);
    }

    // Show the create dorm form
    public function create()
    {
        $this->resetForm();

        $this->isCreating = true;
    }

    // Show the edit dorm form
    public function editDorm($id)
    {
        $this->resetForm();
        $dorm = Dorm::findOrFail($id);
        $this->dormId = $dorm->id;
        $this->name = $dorm->name;
        $this->capacity = $dorm->capacity;
        $this->description = $dorm->description;
        $this->isEditing = true;
    }

    // Create or update a dorm
    public function saveDorm()
    {
        $this->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('dorms')->ignore($this->dormId), // Ensure unique dorm names
            ],
            'capacity' => [
                'required',
                'integer',
                'min:1',
                'max:1000', // Reasonable capacity limit
            ],
            'description' => 'nullable|string|max:500', // Limit description length
        ]);

        Dorm::updateOrCreate(
            ['id' => $this->dormId],
            [
                'name' => $this->name,
                'capacity' => $this->capacity,
                'description' => $this->description,
            ]
        );

        $this->resetForm();
        $this->alert('success', 'Dorm saved successfully!', [
            'position' => 'top-end',
            'timer' => 5000,
            'toast' => true,
            'timerProgressBar' => true,
        ]);

    }

    // Delete a dorm
    public function deleteDorm($id)
    {
        Dorm::destroy($id);
        $this->alert('success', 'Dorm deleted successfully.');
    }

    // Show the assign dorm master form
    public function assignDormMaster($id)
    {
        $this->dormId = $id;
        $this->isAssigningDormMaster = true;
    }

    public function saveDormMaster()
    {
        // Basic validation
        $this->validate([
            'teacherId' => 'required|exists:users,id',
            'session' => 'required|string|max:255',
        ]);

        // Fetch the teacher and dorm details for user-friendly messages
        $teacher = User::find($this->teacherId); // Use find() instead of findOrFail() to avoid exceptions
        $dorm = Dorm::find($this->dormId); // Use find() instead of findOrFail() to avoid exceptions

        // Check if teacher and dorm exist
        if (!$teacher) {
            $this->alert('error', 'Teacher not found.');
            return;
        }

        if (!$dorm) {
            $this->alert('error', 'Dorm not found.');
            return;
        }

        // Check if the teacher is already assigned to the same dorm for the same session
        $isAlreadyAssignedToSameDorm = $dorm->teachers()
            ->where('user_id', $this->teacherId)
            ->where('session', $this->session)
            ->exists();

        if ($isAlreadyAssignedToSameDorm) {
            // Show an error message if the teacher is already assigned to the same dorm for the same session
            $this->alert('error', "Teacher {$teacher->name} is already assigned to {$dorm->name} for the session {$this->session}.");
            return;
        }

        // Check if the teacher is already assigned to another dorm for the same session
        $isAssignedToAnotherDorm = Dorm::whereHas('teachers', function ($query) {
            $query->where('user_id', $this->teacherId)
                ->where('session', $this->session);
        })->exists();

        if ($isAssignedToAnotherDorm) {
            // Show an error message if the teacher is already assigned to another dorm for the same session
            $this->alert('error', "Teacher {$teacher->name} is already assigned to another dorm for the session {$this->session}.");
            return;
        }

        // Assign the teacher to the dorm
        $dorm->teachers()->attach($this->teacherId, ['session' => $this->session]);

        // Reset the form and show a success message
        $this->resetForm();
        // $this->isViewingDormMasters = true;
        $this->alert('success', 'Teacher assigned');
    }



    // Show the dorm masters for a specific dorm
    public function viewDormMasters($id)
    {
        $dorm = Dorm::findOrFail($id);
        $this->dormId = $dorm->id;
        $this->dormName = $dorm->name; // Store the dorm name
        $this->isViewingDormMasters = true;
    }

    // Add a method to select all students on the current page
    public function selectAll()
    {
        // Get the IDs of the currently loaded students
        $loadedStudentIds = $this->loadedStudents->pluck('id')->toArray();

        // Merge the IDs into the selectedStudents array
        $this->selectedStudents = array_unique(array_merge($this->selectedStudents, $loadedStudentIds));
    }

    // Add a method to clear all selections
    public function clearSelection()
    {
        $this->selectedStudents = [];
    }
    
    // Close the add students form
    public function closeAddStudents()
    {
        $this->reset(['isAddingStudents', 'selectedStudents', 'searchQuery', 'classId', 'session']);
        $this->resetErrorBag();
    }

    // Show the add students form from the students list view
    public function showAddStudents()
    {
        // Keep the currently selected dorm ID
        $dormId = $this->selectedDormId;
        
        // Close the students list view
        $this->closeStudentsList();
        
        // Open the add students form for the current dorm
        $this->addStudents($dormId);
    }

    // Also add a new method to handle classId updates directly
    public function updatedClassId($value)
    {
        $this->selectedClass = $value; // Keep selectedClass in sync
        $this->sections = Section::where('my_class_id', $value)->get();
        $this->selectedSection = null; // Reset selected section
        $this->resetLoadedStudents(); // Reset loaded students and apply filters
    }

    // Render the component
    public function render()
    {
        $studentsInDorm = collect(); // Default empty collection
        
        // Fetch students in the dorm for the selected year with full details when in students list view
        if ($this->showStudentsList && $this->selectedDormId && $this->year) {
            // Get student IDs from the pivot table
            $studentIds = DB::table('dorm_student')
                ->where('dorm_id', $this->selectedDormId)
                ->where('year', $this->year)
                ->pluck('student_id');
                
            // Fetch the complete student records with pagination
            if ($studentIds->count() > 0) {
                $studentsInDorm = StudentRecord::with(['my_class', 'section'])
                    ->whereIn('id', $studentIds)
                    ->paginate(15);
            }
        }
        
        // Only search available students when in the appropriate modes
        $availableStudents = collect(); // Default empty collection
        if ($this->isAddingStudents) {
            // Fetch available students who are not already assigned to this dorm
            $query = StudentRecord::query();
            
            if ($this->selectedClass) {
                $query->where('my_class_id', $this->selectedClass);
            }
            
            if ($this->searchQuery) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->searchQuery . '%')
                        ->orWhere('adm_no', 'like', '%' . $this->searchQuery . '%');
                });
            }
            
            // Get students who aren't assigned to this dorm in the current year
            $currentYear = date('Y');
            if ($this->session) {
                $currentYear = $this->session;
            }
            
            $assignedStudentIds = DB::table('dorm_student')
                ->where('dorm_id', $this->dormId)
                ->where('year', $currentYear)
                ->pluck('student_id');
                
            $query->whereNotIn('id', $assignedStudentIds);
            
            $availableStudents = $query->paginate(15);
        }

        // Get classes for the dropdown
        $classes = MyClass::all();

        // Fetch dorm data with pagination (10 dorms per page)
        $dorms = Dorm::paginate(10);
        $teachers = User::where('user_type', 'teacher')->get();
        $dormMasters = $this->isViewingDormMasters ? Dorm::findOrFail($this->dormId)->teachers : collect();
        
        return view('livewire.manage-dorms', [
            'dorms' => $dorms,
            'studentsInDorm' => $studentsInDorm,
            'availableStudents' => $availableStudents,
            'teachers' => $teachers,
            'dormMasters' => $dormMasters,
            'loadedStudents' => $this->loadedStudents,
            'totalStudents' => $this->totalStudents,
            'isLoading' => $this->isLoading,
            'classes' => $classes
        ]);
    }
}
