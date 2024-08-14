<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\StudentRecord;

class ReportFilter extends Component
{
    public $showReportGenerator = false;
    public $showFilter = false;
    public $filterName = '';
    public $filterGender = '';
    public $filterClass = '';
    public $filterStatus = '';
    public $successMessage;
    public $errorMessage;

    public function toggleReportGenerator()
    {
        $this->showReportGenerator = !$this->showReportGenerator;
    }

    public function toggleFilter()
    {
        $this->showFilter = !$this->showFilter;
    }

    public function generateReports()
    {
        // Logic to generate reports
        try {
            // Assume report generation logic here
            $this->successMessage = "Reports have been generated successfully!";
        } catch (\Exception $e) {
            $this->errorMessage = "Failed to generate reports. Please try again.";
        }
    }

    public function applyFilter()
    {
        // Reset success and error messages
        $this->resetMessages();

        try {
            // Add logic to filter student data
            $query = StudentRecord::query();

            if ($this->filterName) {
                $query->where('first_name', 'like', '%' . $this->filterName . '%')
                      ->orWhere('last_name', 'like', '%' . $this->filterName . '%');
            }

            if ($this->filterGender) {
                $query->where('gender', $this->filterGender);
            }

            if ($this->filterClass) {
                $query->where('classname', $this->filterClass);
            }

            if ($this->filterStatus) {
                $query->where('status', $this->filterStatus);
            }

            // Fetch filtered data
            $filteredStudents = $query->get();

            // Update the student list
            $this->emit('updateStudentList', $filteredStudents);

            $this->successMessage = "Filter applied successfully!";
        } catch (\Exception $e) {
            $this->errorMessage = "Failed to apply filter. Please try again.";
        }
    }

    private function resetMessages()
    {
        $this->successMessage = null;
        $this->errorMessage = null;
    }

    public function render()
    {
        return view('livewire.report-filter');
    }
}
