<?php

namespace App\Livewire;

use App\User;
use App\Models\MyClass;
use App\Models\Section;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\StudentRecord;
use Carbon\Carbon;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class ClassManagement extends Component
{
    use WithPagination;
    use LivewireAlert;
    // Class Properties
    public $name;
    public $session;
    public $teacher;
    public $classTeacher;
    public $classId;
    public $class;
    public $classSession;
    public $showClassMaster = false;
    public $editMode = false;

    // Stream Properties
    public $streamName;
    public $streams = [];
    public $selectedStream = null;
    public $activeFilters = []; // To hold active filters for display
    public $streamEditMode = false;
    public $streamAdded = false;
    public $showForm = false;
    public $isCreating = false;

    // Selected Class for Viewing Streams or Entries
    public $selectedClass = null;
    public $isDisplayingStreams = false;
    public $viewEntriesMode = false;

    // Stream Teacher Assignment
    public $streamTeacher;
    public $session_year;

    // Students Data
    public $students = [];
    public $teacherFilter = ''; // Filter by teacher
    public $sessionFilter = ''; // Filter by session
    public $stream;
    public $isEditing = false;
    public $isViewingClassTeacher = false;
    public $selectedStreamEntries = []; // to store the entries data
    public $isLoadingAssign = false;
    public $isLoadingEdit = false;
    public $isLoadingDelete = false;

    public $studentsCount = 0;
    public $loading = false;


    // Modal Properties (for viewing stream students)
    public $showStudentsModal = false;
    public $modalStudents = [];
    public $modalStudentsCount = 0;
    public $modalStreamName = '';

    public $assignedTeacher; // To hold the assigned teacher
    public $assignedSession; // To hold the assigned session
    public $viewClassMasterMode = false; // Toggle to show

    public $classMaster;

    public $teachers;
    public $showInlineForm = false;
    public $selectedClassForAssignment;
    public $selectedTeacherId;

    public $genderBalance;
    public $admissionPeriod;
    public $studentsWithSameParent;
    public $studentsFromSameTown;
    public $suspendedStudents;
    public $kcpeBatches;
    public $studentsDormInfo;
    public $studentsByParent;
    public $studentsFullNameAndAdmission;

    public $sessionYear; // Add this line to declare the sessionYear property
    public $editingStreamId = null;





    // Validation Rules
    protected function rules()
    {
        return [
            'name' => 'required|string',
            'session' => 'required|string',
            'classTeacher' => 'nullable|exists:users,id',
            'streamName' => 'required|string',
            'streamTeacher' => 'nullable|exists:users,id',
        ];
    }

    // Fetch initial data when component mounts
    public function mount()
    {
        $this->teachers = User::where('user_type', 'teacher')->get();
    }
    public function editStreamTeacher($streamId)
    {
        $this->editingStreamId = $streamId;
        $stream = Section::find($streamId);

        if ($stream) {
            // Set the selected teacher ID and session year
            $this->selectedTeacherId = $stream->teacher_id;
            $this->sessionYear = $stream->session_year;
        }

        // Load teachers list
        $this->teachers = User::where('user_type', 'teacher')->get();
    }

    public function showCreateForm()
    {
        $this->resetForm();
        $this->showForm = true;
        $this->isCreating = true;
        $this->isEditing = false;
    }


    public function viewEntries($classId)
    {
        $class = MyClass::with('student_record')->findOrFail($classId);

        $this->selectedClass = $class;
        $this->studentsCount = $class->student_record->count();  // Corrected count
        $this->viewEntriesMode = true;
        $this->isDisplayingStreams = false;

        // Gender balance using filter on collection, with names in a row
        $maleStudents = $class->student_record->filter(function ($student) {
            return !empty($student->gender) && strtolower(trim($student->gender)) === 'male';
        })->pluck('first_name', 'last_name')->map(function ($first_name, $last_name) {
            return $first_name . ' ' . $last_name;
        });

        $femaleStudents = $class->student_record->filter(function ($student) {
            return !empty($student->gender) && strtolower(trim($student->gender)) === 'female';
        })->pluck('first_name', 'last_name')->map(function ($first_name, $last_name) {
            return $first_name . ' ' . $last_name;
        });

        // Gender balance with names
        $this->genderBalance = [
            'male' => $maleStudents->count(),
            'female' => $femaleStudents->count(),
            'male_names' => $maleStudents->implode(', '), // Join names with commas
            'female_names' => $femaleStudents->implode(', ') // Join names with commas
        ];

        $studentsByParent = $class->student_record->groupBy('parent_id_no')->map(function ($group, $parentId) {
            // Get the parent details from the parent_detail relationship
            $parent = $group->first()->parent_detail;
            $parentName = $parent ? $parent->parent_first_name . ' ' . $parent->parent_middle_name . ' ' . $parent->parent_last_name : 'Unknown Parent';

            // Get student details: names, admission year, and graduation status
            $studentDetails = $group->map(function ($student) {
                // Calculate graduation status (assuming 4 years of study)
                $yearAdmitted = $student->year_admitted;
                $currentYear = now()->year; // Get current year
                $status = ($currentYear - $yearAdmitted >= 4) ? 'Graduated' : 'Still in session, admitted in ' . $yearAdmitted;

                return [
                    'name' => $student->first_name . ' ' . $student->last_name,
                    'status' => $status,
                    'year_admitted' => $yearAdmitted
                ];
            });

            return [
                'parent_name' => $parentName,
                'students' => $studentDetails
            ];
        })->filter(function ($parentGroup) {
            // Only keep parents with more than one student
            return $parentGroup['students']->count() > 1;
        });

        // Pass this grouped data to the view
        $this->studentsByParent = $studentsByParent;
    }






    public function toggleClassForm($classId = null)
    {
        if ($classId) {
            // Editing Existing Class
            $class = MyClass::with('sections', 'teacher')->findOrFail($classId);
            $this->classId = $class->id;
            $this->name = $class->name;
            $this->session = $class->session;
            $this->classTeacher = $class->user_id;
            $this->streams = $class->sections->toArray();
            $this->showForm = true;
            $this->isCreating = false;
            $this->isEditing = true;
        } else {
            // Adding New Class
            $this->showCreateForm();
        }
    }


    public function assignStreamTeacher($streamId)
    {
        $this->isLoadingAssign = true;
        $stream = Section::find($streamId);

        if ($stream) {
            $stream->teacher_id = $this->selectedTeacherId;
            $stream->session_year = $this->sessionYear;
            $stream->save();

            // Clear the selected data immediately to reflect changes
            $this->editingStreamId = null;
            $this->selectedTeacherId = null;
            $this->sessionYear = '';

            // Optionally refresh the teachers list
            $this->teachers = User::where('user_type', 'teacher')->get();
            $this->isLoadingAssign = false;
        }
    }

    public function cancelEdit()
    {
        $this->editingStreamId = null; // Reset the editing state
        $this->selectedTeacherId = null; // Clear the selected teacher
        $this->sessionYear = ''; // Reset session/year input
        $this->teachers = []; // Optionally clear the teachers list
    }
    // Reset form fields and modes
    public function resetForm()
    {
        $this->reset([
            'name',
            'session',
            'classTeacher',
            'classId',
            'editMode',
            'streamName',
            'streams',
            'selectedStream',
            'streamEditMode',
            'isDisplayingStreams',
            'viewEntriesMode',
            'streamTeacher',
            'students',
            'studentsCount',
            'showStudentsModal',
            'modalStudents',
            'modalStudentsCount',
            'modalStreamName',
        ]);
        $this->showForm = false;
        $this->isCreating = false;
        $this->isEditing = false;
        $this->streamAdded = false;
    }

    // private function resetForm()
    // {
    //     $this->reset(['classId', 'name', 'session', 'classTeacher', 'streams']);
    //     $this->showForm = false;
    //     $this->isCreating = false;
    //     $this->isEditing = false;
    // }

    public function resetModalFields()
    {
        $this->selectedStream = null;
        $this->selectedClass = null;
        $this->streamTeacher = null;
        $this->classMaster = null;
        $this->session = null; // Reset session
    }

    public function viewClassMaster($classId)
    {
        // Find the class by ID
        $class = MyClass::find($classId);

        if ($class) {
            $this->class = $class; // Store the class object

            // Check if the class has an assigned teacher (master)
            if ($class->master) {
                $this->classTeacher = $class->master->name;
                $this->classSession = $class->session;
                $this->teacher = $class->master;
            } else {
                $this->classTeacher = 'No teacher assigned';
                $this->teacher = null;
                $this->classSession = 'N/A'; // Or whatever placeholder is suitable
            }
        }

        // Toggle the visibility of the card
        $this->isViewingClassTeacher = !$this->isViewingClassTeacher;
    }






    // Save or update class
    public function saveClass()
    {
        // Start loading
        $this->loading = true;

        try {
            // Validate the input
            $this->validate([
                'name' => 'required|string',
            ]);

            // Save or update the class
            $classData = [
                'name' => $this->name,
            ];

            $class = MyClass::updateOrCreate(
                ['id' => $this->classId],
                $classData
            );

            // Save streams
            foreach ($this->streams as $stream) {
                Section::updateOrCreate(
                    ['id' => $stream['id'] ?? null],
                    [
                        'name' => $stream['name'],
                        'my_class_id' => $class->id,
                    ]
                );
            }

            // Display success message
            $this->alert('success', $this->editMode ? 'Class updated successfully.' : 'Class created successfully.');
        } catch (\Exception $e) {
            // Handle any errors
            $this->alert('error', 'An error occurred: ' . $e->getMessage());
        } finally {
            // End loading and reset form
            $this->loading = false;
            $this->resetForm();
        }
    }

    // Add stream to the form dynamically
    // Add a new stream to the class
    public function addStream()
    {
        // Validate the stream name
        $this->validate([
            'streamName' => 'required|string|max:255', // Max length added for better validation
        ]);

        // Add the stream to the streams list
        $this->streams[] = ['name' => $this->streamName];

        // Reset streamName and flag the addition
        $this->streamName = '';
        $this->streamAdded = true;

        // Flash a success message to notify the user
        $this->alert('success', 'Stream added successfully.');
    }

    // Edit a selected stream
    public function editStream($index)
    {
        // Ensure the index exists before proceeding
        if (isset($this->streams[$index])) {
            // Enable edit mode and select the stream to edit
            $this->streamEditMode = true;
            $this->selectedStream = $index;
            $this->streamName = $this->streams[$index]['name']; // Load the stream name into the input
        } else {
            $this->alert('error', 'The selected stream does not exist.');
        }
    }

    // Update an existing stream
    public function updateStream()
    {
        // Validate the stream name before updating
        $this->validate([
            'streamName' => 'required|string|max:255',
        ]);

        // Check if the selected stream exists in the array
        if (isset($this->streams[$this->selectedStream])) {
            // Update the selected stream's name
            $this->streams[$this->selectedStream]['name'] = $this->streamName;

            // Reset the input and edit mode
            $this->streamEditMode = false;
            $this->selectedStream = null;
            $this->streamName = '';

            // Flash a success message to notify the user
            $this->alert('success', 'Stream updated successfully.');
        } else {
            $this->alert('error', 'The selected stream could not be updated.');
        }
    }

    // Remove a stream from the list
    public function removeStream($index)
    {
        // Ensure the index is valid before removing the stream
        if (isset($this->streams[$index])) {
            // Remove the stream at the specified index
            unset($this->streams[$index]);

            // Re-index the streams array to avoid index gaps
            $this->streams = array_values($this->streams);

            // Flash a success message to notify the user
            $this->alert('success', 'Stream removed successfully.');
        } else {
            $this->alert('error', 'The stream could not be found.');
        }
    }

    // View streams of a selected class
    public function viewStreams($classId)
    {
        $this->selectedClass = MyClass::with('sections.teacher', 'sections.studentRecords')->findOrFail($classId);
        $this->isDisplayingStreams = true;
        $this->viewEntriesMode = false;
    }



    // View entries (students) of a selected class






    public function toggleAssignTeacher($classId)
    {
        $this->selectedClassForAssignment = $classId;
        $class = MyClass::find($classId);

        if ($class->master) {
            // Prefill the form with existing teacher and session values
            $this->streamTeacher = $class->master->id;
            $this->session = $class->session;
        } else {
            // Reset form fields if no teacher is assigned
            $this->reset(['streamTeacher', 'session']);
        }

        $this->showInlineForm = true; // Show the form
    }



    public function closeInlineForm()
    {
        $this->showInlineForm = false;
        $this->reset(['streamTeacher', 'session', 'showInlineForm', 'selectedClassForAssignment']);
    }






    public function saveStreamTeacher()
    {
        // Validate the inputs
        $this->validate([
            'streamTeacher' => 'required|exists:users,id',
            'session' => 'required|integer',
        ]);

        // Check if the teacher is already assigned to another class
        $existingClass = MyClass::where('master_id', $this->streamTeacher)->first();
        if ($existingClass && $existingClass->id !== $this->selectedClassForAssignment) {
            $this->alert('error', 'This teacher is already assigned to another class.');
            return;
        }

        // Update or assign the teacher to the selected class
        $class = MyClass::find($this->selectedClassForAssignment);
        $class->update([
            'master_id' => $this->streamTeacher,
            'session' => $this->session,
        ]);

        $this->reset(['streamTeacher', 'session', 'showInlineForm', 'selectedClassForAssignment']);
        $this->alert('success', 'Class teacher has been successfully assigned/updated.');
        $this->closeInlineForm();
    }




    public function fetchTeacherInformation($teacherId)
    {
        // Assuming you want to fetch the teacher's information based on the selected teacher ID
        $teacher = User::find($teacherId);

        if ($teacher) {
            // You can now use $teacher to get the information you need
            $this->streamTeacher = $teacher->id;
            // Populate any other fields as needed
        } else {
            $this->alert('error', 'Teacher not found.');
        }
    }





    // Delete a class
    public function deleteClass($classId)
    {
        $class = MyClass::findOrFail($classId);
        $class->delete();
        $this->alert('success', 'Class deleted successfully.');
        $this->resetForm();
    }

    // Delete a stream
    public function deleteStream($streamId)
    {
        $stream = Section::findOrFail($streamId);
        $classId = $stream->my_class_id;
        $stream->delete();
        $this->alert('success', 'Stream deleted successfully.');
        $this->viewStreams($classId);
    }
    // Your Livewire Component


    public function showStreamEntries($streamId)
    {
        $stream = Section::find($streamId); // Retrieve the stream using the ID
        if ($stream) {
            $this->selectedStreamEntries = $stream->studentEntriesByYear;
        } else {
            $this->selectedStreamEntries = []; // no data found
        }
    }



    public function viewStreamStudents($streamId)
    {
        $stream = Section::with('studentRecords')->findOrFail($streamId);
        $this->modalStudents = $stream->studentRecords;
        $this->modalStudentsCount = $stream->studentRecords->count();
        $this->modalStreamName = $stream->name;
        $this->showStudentsModal = true;
    }


    public function render()
    {
        // Fetch teachers
        $teachers = User::where('user_type', 'teacher')->get();

        // Build query for classes
        $classesQuery = MyClass::with('teacher');

        // Apply teacher filter if selected
        if ($this->teacherFilter) {
            $classesQuery->where('master_id', $this->teacherFilter);
            $this->activeFilters['Teacher'] = $teachers->find($this->teacherFilter)->name;
        }

        // Apply session filter if selected
        if ($this->sessionFilter) {
            $classesQuery->where('session', $this->sessionFilter);
            $this->activeFilters['Session'] = $this->sessionFilter;
        }

        // Fetch filtered classes with pagination
        $classes = $classesQuery->paginate(10);

        return view('livewire.class-management', [
            'teachers' => $teachers,
            'classes' => $classes,
            'activeFilters' => $this->activeFilters,
        ]);
    }

    public function clearFilter($filter)
    {
        if ($filter == 'teacher') {
            $this->teacherFilter = '';
        } elseif ($filter == 'session') {
            $this->sessionFilter = '';
        }

        // Reset the active filters array after clearing
        $this->activeFilters = array_filter($this->activeFilters, function ($key) use ($filter) {
            return $key !== ucfirst($filter);
        }, ARRAY_FILTER_USE_KEY);
    }

    // In your Livewire component
    public function getYearsRange()
    {
        $currentYear = date('Y'); // Get the current year
        $startYear = 2020; // Start year, you can change this
        $endYear = $currentYear + 5; // End year is 5 years after the current year

        // Generate an array of years
        return range($startYear, $endYear);
    }


    // Reset all filters
    public function resetFilters()
    {
        $this->teacherFilter = '';
        $this->sessionFilter = '';
        $this->activeFilters = [];
    }
}
