<?php

namespace App\Livewire;

use App\User;
use Carbon\Carbon;
use App\Models\MyClass;
use App\Models\Section;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\StudentRecord;
use Illuminate\Support\Collection;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Support\Facades\Response;

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
    public Collection $classTeachers;   // List of teachers assigned to the class
    public $hasTeachers = false;
    public $teacherFilter = ''; // Filter by teacher
    public $sessionFilter = ''; // Filter by session
    public $stream;
    public $isEditing = false;
    public $isViewingClassTeacher = false;
    public $selectedStreamEntries = []; // to store the entries data
    public $isLoadingAssign = false;
    public $editingTeacherId = null; // Track the teacher being edited
    public $editingSession = null;   // Track the session being edited
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



    public function exportPdf()
    {
        // Fetch the class and teachers data
        $class = MyClass::find($this->selectedClassForAssignment);

        if (!$class) {
            session()->flash('error', 'Class not found.');
            return;
        }

        $classTeachers = $class->teachers()->withPivot('session')->get();

        // Ensure UTF-8 encoding for all data
        foreach ($classTeachers as $teacher) {
            $teacher->name = mb_convert_encoding($teacher->name, 'UTF-8', 'auto');
        }

        // Generate HTML content for the PDF
        $html = view('pdf.class-teachers', [
            'class' => $class,
            'classTeachers' => $classTeachers,
        ])->render();

        // Initialize Dompdf
        $options = new Options();
        $options->set('isRemoteEnabled', true); // Enable remote files (e.g., images)
        $options->set('defaultFont', 'DejaVu Sans'); // Use a Unicode font

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        // Generate the PDF file
        $pdfContent = $dompdf->output();

        // Download the PDF
        return Response::make($pdfContent, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="class-teachers.pdf"',
        ]);
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
        $this->classTeachers = collect();
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
        $this->reset(['streamTeacher', 'session', 'editingTeacherId', 'editingSession']);

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
        // Set the selected class ID
        $this->selectedClassForAssignment = $classId;

        // Find the class by ID
        $class = MyClass::find($classId);

        if ($class) {
            $this->class = $class; // Store the class object

            // Fetch all teachers assigned to this class with their sessions
            $this->classTeachers = $class->teachers()->withPivot('session')->get();

            // Check if any teachers are assigned
            $this->hasTeachers = $this->classTeachers->isNotEmpty();
        }

        // Toggle the visibility of the card
        $this->isViewingClassTeacher = !$this->isViewingClassTeacher;
    }

    public function deleteTeacher($teacherId, $session)
    {
        // Debugging: Check the value of selectedClassForAssignment
        logger('Selected Class ID: ' . $this->selectedClassForAssignment);

        if (!$this->selectedClassForAssignment) {
            session()->flash('error', 'No class selected.');
            return;
        }

        // Find the class by ID
        $class = MyClass::find($this->selectedClassForAssignment);

        if (!$class) {
            session()->flash('error', 'Class not found.');
            return;
        }

        // Remove the teacher assignment for the specified session
        $class->teachers()
            ->wherePivot('user_id', $teacherId)
            ->wherePivot('session', $session)
            ->detach();

        // Refresh the list of teachers
        $this->viewClassMaster($this->selectedClassForAssignment);

        // Show a success message
        session()->flash('message', 'Teacher assignment deleted successfully.');
    }

    public function toggleAssignTeacher($classId)
    {
        $this->selectedClassForAssignment = $classId;
        $class = MyClass::find($classId);

        if ($class) {
            $this->class = $class; // Store the class object

            // Reset form fields for a new assignment
            $this->reset(['streamTeacher', 'session', 'editingTeacherId', 'editingSession']);

            // Show the form
            $this->showInlineForm = true;
        }
    }

    public function editTeacher($teacherId, $session)
    {
        // Set the form fields for editing
        $this->editingTeacherId = $teacherId;
        $this->editingSession = $session;
        $this->streamTeacher = $teacherId;
        $this->session = $session;
        $this->showInlineForm = true; // Show the form
    }

    public function saveStreamTeacher()
    {
        // Validate the input fields
        $this->validate([
            'streamTeacher' => 'required|exists:users,id',
            'session' => 'required|string|digits:4|integer|min:1900|max:' . (date('Y') + 1), // Ensure valid year
        ], [
            'streamTeacher.required' => 'Please select a teacher.',
            'streamTeacher.exists' => 'The selected teacher does not exist.',
            'session.required' => 'Please select a session.',
            'session.digits' => 'The session must be a 4-digit year.',
            'session.integer' => 'The session must be a valid year.',
            'session.min' => 'The session year must be 1900 or later.',
            'session.max' => 'The session year cannot be later than ' . (date('Y') + 1) . '.',
        ]);

        $class = MyClass::find($this->selectedClassForAssignment);

        // Ensure the selected user is a teacher
        $teacher = User::where('id', $this->streamTeacher)->where('user_type', 'teacher')->first();
        if (!$teacher) {
            $this->addError('streamTeacher', 'The selected user is not a teacher.');
            return;
        }

        // Check if the teacher is already assigned to another class for the same session
        $existingAssignment = \DB::table('class_teacher')
            ->where('user_id', $this->streamTeacher)
            ->where('session', $this->session)
            ->where('my_class_id', '!=', $class->id) // Exclude the current class
            ->first();

        if ($existingAssignment) {
            $existingClass = MyClass::find($existingAssignment->my_class_id);
            $this->addError(
                'streamTeacher',
                'The teacher "' . $teacher->name . '" is already assigned to class "' . $existingClass->name . '" for the session "' . $this->session . '".'
            );
            return;
        }

        // Check if the teacher is already assigned to this class for the same session (duplicate)
        $isDuplicate = $class->teachers()
            ->wherePivot('user_id', $this->streamTeacher)
            ->wherePivot('session', $this->session)
            ->exists();

        if ($isDuplicate) {
            $this->addError(
                'streamTeacher',
                'The teacher "' . $teacher->name . '" is already assigned to this class "' . $class->name . '" for the session "' . $this->session . '".'
            );
            return;
        }

        // If editing, remove the existing assignment for the session
        if ($this->editingTeacherId && $this->editingSession) {
            $class->teachers()
                ->wherePivot('user_id', $this->editingTeacherId)
                ->wherePivot('session', $this->editingSession)
                ->detach();
        }

        // Assign the teacher for the selected session
        $class->assignTeacherForSession($this->streamTeacher, $this->session);

        // Reset form fields and editing properties
        $this->reset(['streamTeacher', 'session', 'editingTeacherId', 'editingSession', 'showInlineForm']);

        // Refresh the list of teachers
        $this->viewClassMaster($this->selectedClassForAssignment);

        // Show a success message (optional)
        session()->flash('message', 'Teacher assignment saved successfully.');
    }

    /**
     * Close the inline form.
     */
    public function closeInlineForm()
    {
        // Reset form fields
        $this->reset(['streamTeacher', 'session', 'showInlineForm', 'editingTeacherId', 'editingSession']);

        // Clear all validation errors
        $this->resetErrorBag();
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



    public function fetchTeacherInformation($teacherId)
    {
        // Assuming i want to fetch the teacher's information based on the selected teacher ID
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
        $startYear = 2009;
        $endYear = $currentYear + 5;

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
