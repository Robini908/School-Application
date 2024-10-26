<?php

namespace App\Livewire;

use App\Models\MyClass;
use App\Models\Section;
use App\User;
use App\Models\StudentRecord;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithPagination;

class ClassManagement extends Component
{
    use WithPagination;
    use LivewireAlert;
    // Class Properties
    public $name;
    public $session;
    public $classTeacher;
    public $classId;
    public $editMode = false;

    // Stream Properties
    public $streamName;
    public $streams = [];
    public $selectedStream = null;
    public $streamEditMode = false;
    public $streamAdded = false;
    public $showForm = false;
    public $isCreating = false;

    // Selected Class for Viewing Streams or Entries
    public $selectedClass = null;
    public $viewStreamsMode = false;
    public $viewEntriesMode = false;

    // Stream Teacher Assignment
    public $streamTeacher;
    public $session_year;

    // Students Data
    public $students = [];
    public $stream;

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




    // public function creatingClass(){

    //     $this->isCreating = true;
    //     $this->editMode = false;
    //     $this->showForm = true;
    // }



    public function showCreateForm()
    {
        $this->resetForm();
        $this->showForm = true;
        $this->isCreating = true;
        $this->editMode = false;
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
            'viewStreamsMode',
            'viewEntriesMode',
            'streamTeacher',
            'students',
            'studentsCount',
            'showStudentsModal',
            'modalStudents',
            'modalStudentsCount',
            'modalStreamName',
        ]);
        $this->streamAdded = false;
    }

    public function resetModalFields()
    {
        $this->selectedStream = null;
        $this->selectedClass = null;
        $this->streamTeacher = null;
        $this->classMaster = null;
        $this->session = null; // Reset session
    }

    public function viewClassMater($classId)
    {
        // Retrieve the class and its assigned teacher/session
        $this->selectedClass = MyClass::find($classId);
        if ($this->selectedClass) {
            $this->assignedTeacher = $this->selectedClass->teacher; // Assuming relationship
            $this->assignedSession = $this->selectedClass->session; // Adjust as needed
            $this->viewClassMasterMode = true; // Set to true to show class master information
        }
    }



    // Toggle class form for adding or editing
    public function toggleClassForm($classId = null)
    {
        if ($classId) {
            // Editing Existing Class
            $this->editMode = true;
            $class = MyClass::with('sections', 'teacher')->findOrFail($classId);
            $this->classId = $class->id;
            $this->name = $class->name;
            $this->session = $class->session;
            $this->classTeacher = $class->user_id;
            $this->streams = $class->sections->toArray();
        } else {
            // Adding New Class

        }
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
        $this->viewStreamsMode = true;
        $this->viewEntriesMode = false;
    }

    // View entries (students) of a selected class
    public function viewEntries($classId)
    {
        $class = MyClass::withCount('student_record')->findOrFail($classId);
        $this->selectedClass = $class;
        $this->studentsCount = $class->student_record_count;
        $this->viewEntriesMode = true;
        $this->viewStreamsMode = false;
    }





    public function toggleAssignTeacher($classId)
    {
        if ($this->selectedClassForAssignment === $classId) {
            $this->showInlineForm = !$this->showInlineForm; // Toggle the form
        } else {
            $this->selectedClassForAssignment = $classId; // Set the selected class
            $this->showInlineForm = true; // Show the form
        }

        // Reset the fields for teacher and session
        $this->reset(['streamTeacher', 'session']);
    }

    public function closeInlineForm()
    {
        $this->showInlineForm = false;
        $this->reset(['streamTeacher', 'session']);
    }

    public function saveStreamTeacher()
    {
        $this->validate([
            'streamTeacher' => 'required|exists:users,id',
            'session' => 'required|string',
        ]);

        // Fetch the selected class
        $class = MyClass::find($this->selectedClassForAssignment);

        if ($class) {
            // Assign the teacher as the class master
            $class->master_id = $this->streamTeacher; // Use master_id to assign the class master
            $class->session = $this->session; // Save the session
            $class->save(); // Save changes

            $this->alert('success', 'Class Master assigned successfully.');
        } else {
            $this->alert('error', 'Class not found.');
        }

        // Close the form and reset
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
        $teachers = User::where('user_type', 'teacher')->get(); // Fetch teachers

        return view('livewire.class-management', [
            'teachers' => $teachers,
            'classes' => MyClass::with('teacher')->paginate(10),
        ]);
    }
}
