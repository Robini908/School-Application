<?php

namespace App\Livewire;

use App\Models\StudentRecord;
use App\Models\StudentTransition;
use App\Models\MyClass;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Mpdf\Mpdf;
use Mpdf\Output\Destination;


class GraduateStudents extends Component
{
    use WithPagination;

    public $search = '';
    public $selectedStudents = [];
    public $showGraduationDetailsModal = false; // Controls the visibility of the modal
    public $selectedGraduationDetails = null; // Stores the selected graduation details
    public $showDeleteConfirmationModal = false; // Controls the visibility of the delete confirmation modal
    public $graduationIdToDelete = null; // Stores the ID of the graduation record to be deleted
    public $graduationYear;
    public $filterGraduationYear; // For filtering by graduation year
    public $graduationYearsRequired = 4; // Number of years required for graduation
    public $infoMessage = ''; // Property to hold informative messages
    public $showGraduatedStudents = true; // Toggle between graduated and eligible students
    public $selectedClass = null; // For filtering students by class
    public $showReinstateModal = false; // Controls the visibility of the reinstate modal
    public $selectedStudentId = null;
    public $studentName = ''; // Property to store the student's name
    public $selectAll = false; // Toggle for selectin
    

    // public $students;


    protected $rules = [
        'selectedStudents' => 'required|array|min:1',
        'graduationYear' => 'required|date_format:Y',
    ];

    protected $messages = [
        'selectedStudents.required' => 'Please select at least one student for graduation.',
        'graduationYear.required' => 'The graduation year is required.',
        'graduationYear.date_format' => 'The graduation year must be in the format YYYY.',
    ];



    public function mount()
    {
        // Set the default graduation year to the current year
        $this->graduationYear = now()->year;
        $this->filterGraduationYear = now()->year; // Default filter to the current year

        // Set the default class to the highest class
        $this->selectedClass = MyClass::orderBy('id', 'desc')->first()?->id;
    }
    public function toggleSelectAll()
    {
        if ($this->selectAll) {
            $this->selectedStudents = $this->students->pluck('student.id')->toArray();
        } else {
            $this->selectedStudents = [];
        }
    }


    public function printBulkCertificates()
    {
        if (empty($this->selectedStudents)) {
            session()->flash('error', 'Please select at least one student to print certificates.');
            return;
        }

        // Fetch selected students and their graduation details
        $students = StudentRecord::whereIn('id', $this->selectedStudents)->get();
        $graduationDetails = StudentTransition::whereIn('student_id', $this->selectedStudents)
            ->where('transition_type', 'graduation')
            ->get()
            ->keyBy('student_id');

        // Initialize mPDF
        $mpdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',
            'orientation' => 'L', // Landscape orientation
            'default_font' => 'helvetica',
        ]);

        // Add custom styles
        $stylesheet = file_get_contents(public_path('css/certificate.css'));
        $mpdf->WriteHTML($stylesheet, \Mpdf\HTMLParserMode::HEADER_CSS);

        // Generate certificates for each student
        foreach ($students as $student) {
            $html = view('certificates.graduate', [
                'student' => $student,
                'graduationDetails' => $graduationDetails[$student->id] ?? null,
            ])->render();

            $mpdf->WriteHTML($html);
            $mpdf->AddPage(); // Add a new page for the next certificate
        }

        // Output the PDF as a download
        return response()->streamDownload(function () use ($mpdf) {
            $mpdf->Output();
        }, 'bulk_graduation_certificates.pdf');
    }




    public function exportGraduatedStudents()
    {
        // Fetch graduated students for the selected year
        $graduatedStudents = StudentTransition::where('transition_type', 'graduation')
            ->where('transition_year', $this->filterGraduationYear)
            ->with('student') // Load the student relationship
            ->get();

        // Generate HTML content for the PDF
        $html = view('exports.graduated-students-pdf', [
            'graduatedStudents' => $graduatedStudents,
            'graduationYear' => $this->filterGraduationYear,
        ])->render();

        // Initialize mPDF
        $mpdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',
            'orientation' => 'L', // Landscape orientation
            'default_font' => 'helvetica',
        ]);

        // Add custom styles (optional)
        $stylesheet = file_get_contents(public_path('css/pdf-styles.css')); // Add a CSS file for styling
        $mpdf->WriteHTML($stylesheet, \Mpdf\HTMLParserMode::HEADER_CSS);

        // Write the HTML content
        $mpdf->WriteHTML($html);

        // Output the PDF as a download
        return response()->streamDownload(function () use ($mpdf) {
            $mpdf->Output('graduated_students_' . $this->filterGraduationYear . '.pdf', Destination::DOWNLOAD);
        }, 'graduated_students_' . $this->filterGraduationYear . '.pdf');
    }

    public function reinstateStudent($graduationId)
    {
        // Fetch the graduation details with the student relationship
        $this->selectedGraduationDetails = StudentTransition::with('student')
            ->where('id', $graduationId)
            ->first();

        // Check if the graduation details were found
        if (!$this->selectedGraduationDetails) {
            $this->infoMessage = 'Graduation record not found.';
            return;
        }

        // Store the student's name
        $this->studentName = $this->selectedGraduationDetails->student->first_name . ' ' . $this->selectedGraduationDetails->student->last_name;

        // Show the modal
        $this->showReinstateModal = true;
    }

    public function confirmReinstate()
    {
        // Delete the graduation record
        StudentTransition::where('student_id', $this->selectedGraduationDetails->student_id)
            ->where('transition_type', 'graduation')
            ->delete();

        // Update the student's status back to active
        StudentRecord::find($this->selectedGraduationDetails->student_id)->update(['status' => 'active']);

        // Hide the modal and reset the selected details
        $this->showReinstateModal = false;
        $this->selectedGraduationDetails = null;

        // Show a success message
        $this->infoMessage = 'Student reinstated successfully.';
    }

    public function cancelReinstate()
    {
        // Hide the modal and reset the selected details
        $this->showReinstateModal = false;
        $this->selectedGraduationDetails = null;
        $this->studentName = ''; // Reset the student name
    }

    public function confirmDeleteGraduation($graduationId)
    {
        // Set the graduation ID to delete and show the confirmation modal
        $this->graduationIdToDelete = $graduationId;
        $this->showDeleteConfirmationModal = true;
    }

    public function deleteGraduationRecord()
    {
        // Delete the graduation record
        StudentTransition::find($this->graduationIdToDelete)->delete();

        // Hide the confirmation modal and reset the ID
        $this->showDeleteConfirmationModal = false;
        $this->graduationIdToDelete = null;

        // Show a success message
        $this->infoMessage = 'Graduation record deleted successfully.';
    }

    public function cancelDelete()
    {
        // Hide the confirmation modal and reset the ID
        $this->showDeleteConfirmationModal = false;
        $this->graduationIdToDelete = null;
    }

    public function viewGraduationDetails($graduationId)
    {
        // Fetch the graduation details
        $this->selectedGraduationDetails = StudentTransition::with('student')
            ->where('id', $graduationId)
            ->first();

        // Show the modal
        $this->showGraduationDetailsModal = true;
    }

    public function closeGraduationDetailsModal()
    {
        // Hide the modal and reset the selected details
        $this->showGraduationDetailsModal = false;
        $this->selectedGraduationDetails = null;
    }


    public function printCertificate($studentId)
    {
        // Fetch the student and their graduation details
        $student = StudentRecord::find($studentId);
        $graduationDetails = StudentTransition::where('student_id', $studentId)
            ->where('transition_type', 'graduation')
            ->first();

        // Generate the HTML content for the certificate
        $html = view('certificates.graduate', [
            'student' => $student,
            'graduationDetails' => $graduationDetails,
        ])->render();

        // Initialize mPDF
        $mpdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',
            'orientation' => 'L', // Landscape orientation
            'default_font' => 'helvetica',
        ]);

        // Add custom styles
        $stylesheet = file_get_contents(public_path('css/certificate.css'));
        $mpdf->WriteHTML($stylesheet, \Mpdf\HTMLParserMode::HEADER_CSS);

        // Write the HTML content
        $mpdf->WriteHTML($html);

        // Output the PDF as a download
        return response()->streamDownload(function () use ($mpdf) {
            $mpdf->Output();
        }, 'graduation_certificate_' . $student->adm_no . '.pdf');
    }


    public function render()
    {
        // Fetch classes for the dropdown
        $classes = MyClass::all();

        // Fetch graduated students if the toggle is enabled
        if ($this->showGraduatedStudents) {
            $students = StudentTransition::where('transition_type', 'graduation')
                ->where('transition_year', $this->filterGraduationYear) // Filter by selected graduation year
                ->whereHas('student', function ($query) {
                    $query->where('status', 'graduated');
                })
                ->where(function ($query) {
                    $query->whereHas('student', function ($q) {
                        $q->where('first_name', 'like', '%' . $this->search . '%')
                            ->orWhere('last_name', 'like', '%' . $this->search . '%')
                            ->orWhere('adm_no', 'like', '%' . $this->search . '%');
                    });
                })
                ->paginate(10);
        } else {
            // Fetch students who are eligible for graduation
            $students = StudentRecord::where('status', operator: 'unverified') // Only active students
                ->whereDoesntHave('transitions', function ($query) {
                    $query->where('transition_type', 'graduation'); // Exclude already graduated students
                })
                ->when($this->selectedClass, function ($query) {
                    $query->where('my_class_id', $this->selectedClass); // Filter by selected class
                })
                ->where(function ($query) {
                    $query->where('first_name', 'like', '%' . $this->search . '%')
                        ->orWhere('last_name', 'like', '%' . $this->search . '%')
                        ->orWhere('adm_no', 'like', '%' . $this->search . '%');
                })
                ->paginate(10);
        }

        // Display a message if no students are found
        if ($students->isEmpty()) {
            $this->infoMessage = $this->showGraduatedStudents
                ? 'No graduated students found for ' . $this->filterGraduationYear . '.'
                : 'No students found for the selected class.';
        } else {
            $this->infoMessage = ''; // Clear the message if students are found
        }

        // Generate years for the dropdown
        $years = range(2000, now()->year + 10);

        return view('livewire.graduate-students', compact('students', 'years', 'classes'));
    }

    public function updatedFilterGraduationYear()
    {
        // Reset pagination when the filter changes
        $this->resetPage();
    }

    public function updatedSelectedClass()
    {
        // Reset pagination when the class changes
        $this->resetPage();
    }

    public function toggleView()
    {
        // Toggle between graduated and eligible students
        $this->showGraduatedStudents = !$this->showGraduatedStudents;

        // Reset the reinstatement confirmation card

        // Reset pagination when toggling
        $this->resetPage();
    }

    public function graduateStudents()
    {
        // Validate the form inputs
        $this->validate();

        try {
            // Log the graduation process for debugging
            Log::info('Graduating students:', [
                'selectedStudents' => $this->selectedStudents,
                'graduationYear' => $this->graduationYear,
            ]);

            // Validation: Ensure at least one student is selected
            if (empty($this->selectedStudents)) {
                $this->infoMessage = 'No students selected for graduation.';
                return;
            }

            // Create a graduation record for each selected student
            foreach ($this->selectedStudents as $studentId) {
                $student = StudentRecord::find($studentId);

                if (!$student) {
                    Log::warning('Student not found:', ['student_id' => $studentId]);
                    continue;
                }

                // Create the graduation record
                StudentTransition::create([
                    'student_id' => $studentId,
                    'transition_year' => $this->graduationYear,
                    'transition_type' => 'graduation',
                    'target_class_id' => null, // No target class for graduation
                    'target_section_id' => null, // No target section for graduation
                    'reason' => 'Manual graduation.',
                    'decision_by' => auth()->id(),
                    'decision_date' => now(),
                ]);

                // Update the student's status to "graduated"
                $student->update(['status' => 'graduated']);
            }

            // Display a success message
            $this->infoMessage = 'Selected students graduated successfully.';

            // Reset the form fields
            $this->reset(['selectedStudents', 'graduationYear']);
            // Re-fetch data to ensure fresh changes are seen
            $this->render();
            $this->showGraduatedStudents = true;
            // This will re-render the component and fetch fresh data
        } catch (\Exception $e) {
            // Log any errors that occur
            Log::error('Error graduating students: ' . $e->getMessage());

            // Display an error message
            $this->infoMessage = 'An error occurred while graduating students: ' . $e->getMessage();
        }
    }

    public function calculateGraduationProgress($student)
    {
        $admissionDate = Carbon::parse($student->created_at);
        $currentDate = now();
        $graduationDate = $admissionDate->copy()->addYears($this->graduationYearsRequired);

        // Calculate progress percentage
        $totalDays = $admissionDate->diffInDays($graduationDate);
        $elapsedDays = $admissionDate->diffInDays($currentDate);
        $progressPercentage = min(100, ($elapsedDays / $totalDays) * 100);

        // Calculate remaining time
        $remainingDays = $graduationDate->diffInDays($currentDate);
        $remainingYears = $graduationDate->diffInYears($currentDate);
        $remainingMonths = $graduationDate->diffInMonths($currentDate) % 12;

        return [
            'percentage' => number_format($progressPercentage, 2) . '%',
            'remaining' => "{$remainingYears} years, {$remainingMonths} months, {$remainingDays} days",
        ];
    }
}
