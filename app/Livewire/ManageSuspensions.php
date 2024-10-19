<?php

namespace App\Livewire;

use App\Models\StudentRecord;
use Livewire\Component;
use Carbon\Carbon;

class ManageSuspensions extends Component
{
    public $suspendedStudents = [];
    public $isReinstating = false;
    public $showForm = false;
    
    public $selectedStudentId;
    public $student;
    public $newSuspensionEndDate;
    public $isExtendingSuspension = false;

    public $suspensionPeriod;

    public function mount()
    {
        $this->fetchSuspendedStudents();
    }

    public function humanReadableCountdown($endDate)
    {
        $now = Carbon::now();
        $endDate = Carbon::parse($endDate);

        // Calculate the difference
        $diff = $now->diff($endDate);

        // Format the difference in a human-readable way
        $weeks = $diff->days / 7; // Get weeks from days
        $days = $diff->days % 7; // Remaining days after full weeks

        return sprintf(
            "%d weeks, %d days, %d hours, %d minutes, %d seconds",
            floor($weeks),
            $days,
            $diff->h,
            $diff->i,
            $diff->s
        );
    }

    public function humanReadableElapsedTime($startDate)
    {
        $now = Carbon::now();
        $startDate = Carbon::parse($startDate);

        // Calculate the difference
        $diff = $now->diff($startDate);

        // Format the difference in a human-readable way
        return sprintf(
            "%d days, %d hours, %d minutes, %d seconds ago",
            $diff->d,
            $diff->h,
            $diff->i,
            $diff->s
        );
    }

    public function fetchSuspendedStudents()
    {
        $this->suspendedStudents = StudentRecord::where('is_suspended', true)->get();
    }

    public function reinstate($id)
    {
        $this->selectedStudentId = $id;
        $this->isReinstating = true;
    }

    public function confirmReinstatement()
    {
        $student = StudentRecord::find($this->selectedStudentId);
        if ($student) {
            $student->is_suspended = false; // Reinstate the student
            $student->suspension_reason = null; // Clear the reason for suspension
            $student->suspended_by = null; // Clear who suspended the student
            $student->suspension_date = null; // Clear the suspension date
            $student->suspension_type = null; // Clear the type of suspension
            $student->suspension_end_date = null; // Clear the end date
            $student->save();

            session()->flash('message', 'Student reinstated successfully.');
            $this->fetchSuspendedStudents(); // Fetch updated list of suspended students
        } else {
            session()->flash('error', 'Failed to reinstate student. Please try again.');
        }

        $this->resetFields();
    }

    public function confirmExtension()
    {
        // Validate the new suspension end date input
        $this->validate([
            'newSuspensionEndDate' => 'required|date|after_or_equal:' . now()->toDateString(),
        ]);

        // Check if the student is set
        if (!$this->student) {
            session()->flash('error', 'Student not found.');
            return;
        }

        // Get the current end date
        $currentEndDate = $this->student->suspension_end_date
            ? Carbon::parse($this->student->suspension_end_date)
            : now();

        // Parse the new suspension end date from the input
        $newEndDate = Carbon::parse($this->newSuspensionEndDate);

        // Calculate total suspension duration
        if ($this->student->is_suspended) {
            // If the student is already suspended, we extend the end date if the new one is later
            if ($newEndDate->isAfter($currentEndDate)) {
                $this->student->suspension_end_date = $newEndDate; // Update to new end date
            }
        } else {
            session()->flash('error', 'Student is not currently suspended.');
            return;
        }

        // Save the updated student record
        $this->student->save();

        // Set a success message in the session
        session()->flash('message', 'Suspension extended successfully.');
        $this->fetchSuspendedStudents(); // Fetch updated list of suspended students

        // Reset fields after processing
        $this->resetFields();
    }

    public function extendSuspension($id)
    {
        $this->selectedStudentId = $id;
        $this->student = StudentRecord::find($id); // Load the student record
        $this->isExtendingSuspension = true;
    }

    private function resetFields()
    {
        $this->isReinstating = false;
        $this->isExtendingSuspension = false;
        $this->suspensionPeriod = null;
        $this->selectedStudentId = null;
    }

    public function render()
    {
        // Refresh suspended students list every render call
        $this->fetchSuspendedStudents();
        return view('livewire.manage-suspensions', [
            'suspendedStudents' => $this->suspendedStudents,
            'student' => $this->student, // Pass the student to the view
        ]);
    }
}
