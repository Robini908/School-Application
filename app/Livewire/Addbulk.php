<?php

namespace App\Livewire;

use Throwable;
use App\Models\MyClass;
use App\Models\Section;
use Livewire\Component;
use App\Models\StudentRecord;
use Livewire\WithFileUploads;
use App\Imports\StudentsImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use Spatie\LivewireFilepond\WithFilePond;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Validators\ValidationException as ExcelValidationException;

class Addbulk extends Component
{
    use WithFileUploads, LivewireAlert;
    use WithFilePond;

    public $file;
    public $studentRecords;
    public $progress = 0;
    public $showErrorTable = false;
    public $errorDetails = [];
    public $editableRows = [];
    public $isUploading = false;

    public $showEditTable = false;

    protected $listeners = ['fileUploadProgress' => 'updateProgress'];




    public function updateProgress($progress)
    {
        $this->progress = $progress;
        $this->dispatch('fileUploadProgress', $progress);
    }


    public function mount()
    {
        // Fetch student records
        $this->fetchStudentRecords();
    }

    public function importStudents()
    {
        $this->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv|max:10240',
        ]);
    
        $this->progress = 0;
        $this->errorDetails = [];
        $this->showErrorTable = false;
    
        try {
            Excel::import(new StudentsImport, $this->file->path(), null, \Maatwebsite\Excel\Excel::XLSX);
            $this->alert('success', 'Student Data uploaded.');
    
            $this->handleFileUploadMessage(true);
    
            $this->dispatch('fileUploadFinished');
            $this->progress = 100;
            $this->fetchStudentRecords();
        } catch (ExcelValidationException $e) {
            $this->handleFileUploadMessage(false, $e);
        } catch (ValidationException $e) {
            $this->alert('info', 'Validation error: ' . $e->getMessage(), [
                'position' => 'top',
                'showConfirmButton' => true,
                'confirmButtonText' => 'OK',
                'reverseButtons' => true,
                'timer' => 30000,
                'toast' => false,
            ]);
        } catch (Throwable $e) {
            $this->alert('info', 'An unexpected error occurred: ' . $e->getMessage(), [
                'position' => 'top',
                'showConfirmButton' => true,
                'confirmButtonText' => 'OK',
                'reverseButtons' => true,
                'timer' => 30000,
                'toast' => false,
            ]);
        }
    
        $this->reset(['file']);
    }
    


    public function fetchStudentRecords()
    {
        // Fetch 5 student records, with relationships loaded
        $this->studentRecords = StudentRecord::with('my_class', 'section')->limit(5)->get();
    }

    /**
     * Display success or error messages for file uploads based on validation results.
     *
     * @param  bool  $success   If the validation was successful.
     * @param  mixed  $errors   Errors if the validation failed (ExcelValidationException).
     * @return void
     */
    public function handleFileUploadMessage(bool $success, $errors = null)
    {
        if ($success) {
            // Display success message
            $this->alert('success', 'No errors detected! Student uploaded successfully');
        } else {
            // Handle errors and display them
            $this->errorDetails = [];
            $failures = $errors->failures();  // Assuming $errors is an ExcelValidationException

            foreach ($failures as $failure) {
                $this->errorDetails[] = [
                    'row' => $failure->row(),
                    'attribute' => $failure->attribute(),
                    'errors' => $failure->errors(),
                    'values' => $failure->values(),
                ];
            }

            $this->showEditTable = true;

            // Log error details for debugging
            logger('Error details:', $this->errorDetails);

            // Display an error alert with failure details
            $this->alert('info', 'There were validation errors in the file. Please review the errors and try again.', [
                'position' => 'top',
                'showConfirmButton' => true,
                'confirmButtonText' => 'OK',
                'reverseButtons' => true,
                'timer' => 30000,
                'toast' => false,
            ]);
        }
    }




    public function saveCorrections()
    {
        foreach ($this->editableRows as $index => $row) {
            // Validate each corrected row
            $validator = \Validator::make($row['values'], [
                'adm_no' => 'required',
                'first_name' => 'required',
                'last_name' => 'required',
                'gender' => 'required',
                'dob' => 'required|date',
                'class_name' => 'required',
                'section_name' => 'required',
                'year_admitted' => 'required|integer',
                'email' => 'nullable|email',
                'phone' => 'nullable|string',
                'kcpe' => 'nullable|numeric',
            ]);

            if ($validator->fails()) {
                $this->alert('error', "Row {$row['row']} has errors: " . implode(', ', $validator->errors()->all()));
                return;
            }

            // Find class by name
            $class = MyClass::where('name', $row['values']['class_name'])->first();
            if (!$class) {
                $this->alert('error', "Row {$row['row']} has an invalid class name: {$row['values']['class_name']}");
                return;
            }

            // Find section by name within the class
            $section = Section::where('name', $row['values']['section_name'])
                ->where('my_class_id', $class->id)
                ->first();
            if (!$section) {
                $this->alert('error', "Row {$row['row']} has an invalid section name: {$row['values']['section_name']}");
                return;
            }

            // Save or update student record
            StudentRecord::updateOrCreate(
                ['adm_no' => $row['values']['adm_no']],
                [
                    'first_name' => $row['values']['first_name'],
                    'middle_name' => $row['values']['middle_name'],
                    'last_name' => $row['values']['last_name'],
                    'gender' => $row['values']['gender'],
                    'dob' => $row['values']['dob'],
                    'my_class_id' => $class->id,
                    'section_id' => $section->id,
                    'year_admitted' => $row['values']['year_admitted'],
                    'email' => $row['values']['email'],
                    'phone' => $row['values']['phone'],
                    'kcpe' => $row['values']['kcpe'],
                ]
            );
        }

        $this->alert('success', 'Corrections have been saved successfully!');
        $this->showEditTable = false;  // Hide the editable table after saving
        $this->fetchStudentRecords();  // Fetch updated student records
    }

    public function render()
    {
        return view('livewire.addbulk');
    }
}
