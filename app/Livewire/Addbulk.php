<?php

namespace App\Livewire;

use Throwable;
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
    use WithFileUploads, LivewireAlert;  use WithFilePond; 
 
    public $file;
    public $studentRecords;


    protected $listeners = ['fileUploadProgress' => 'updateProgress'];

    public $progress = 0;

    public function updatedFile()
    {
        $this->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv|max:10240', // Ensure file is an Excel/CSV and not larger than 10MB
        ]);

        $this->progress = 0; // Reset progress bar
    }

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

    public function fetchStudentRecords()
    {
        // Fetch 5 student records, with relationships loaded
        $this->studentRecords = StudentRecord::with('my_class', 'section')->limit(5)->get();
    }

    public function import()
    {
        $this->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv|max:10240',
        ]);

        $this->progress = 0; // Reset progress bar

        try {
            // Try importing the file
            Excel::import(new StudentsImport, $this->file->path());
            // Show success alert using LivewireAlert
            $this->alert('success', 'Student data has been imported successfully!');
            $this->dispatch('fileUploadFinished');
        } catch (ExcelValidationException $e) {
            // Handle Excel validation errors
            $failures = $e->failures();
            $errorDetails = [];

            foreach ($failures as $failure) {
                // Loop through each failure and prepare error messages
                $errorDetails[] = [
                    'row' => $failure->row(), // Row that went wrong
                    'attribute' => $failure->attribute(), // Heading key or column index
                    'errors' => $failure->errors(), // Error messages from Laravel validator
                    'values' => $failure->values(), // Values of the row that has failed
                ];
            }

            // Show error alert with LivewireAlert
            $this->alert('error', 'There were validation errors in the file. Please review the errors and try again.', [
                'position' => 'top', 
                'showConfirmButton' => true,
                'confirmButtonText' => 'OK', 

                'reverseButtons' => true, 
                'timer' => 30000,
                'toast' => false,
            ]);
            
            $this->alert('error', implode("\n", array_map(function ($error) {
                return "Row {$error['row']} ({$error['attribute']}): " . implode(', ', $error['errors']);
            }, $errorDetails)), [
                'position' => 'top', 
                'showConfirmButton' => true,
                'confirmButtonText' => 'OK', 

                'reverseButtons' => true, 
                'timer' => 30000,
                'toast' => false,
            ]);

        } catch (ValidationException $e) {
            // Handle other validation errors
            $this->alert('error', 'Validation error: ' . $e->getMessage(), [
                'position' => 'top', 
                'showConfirmButton' => true,
                'confirmButtonText' => 'OK', 

                'reverseButtons' => true, 
                'timer' => 30000,
                'toast' => false,
            ]);
        } catch (Throwable $e) {
            // Handle unexpected errors
            $this->alert('error', 'An unexpected error occurred: ' . $e->getMessage(), [
                'position' => 'top', 
                'showConfirmButton' => true,
                'confirmButtonText' => 'OK', 

                'reverseButtons' => true, 
                'timer' => 30000,
                'toast' => false,
            ]);
        }

        $this->reset(['file']); // Reset the file upload input
        $this->progress = 100; // Complete the progress bar
        $this->dispatch('fileUploadFinished');
    }


    public function render()
    {
        return view('livewire.addbulk');
    }
}
