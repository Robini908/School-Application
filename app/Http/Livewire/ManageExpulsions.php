<?php

namespace App\Http\Livewire;

use App\Models\StudentRecord;
use Livewire\Component;
use Carbon\Carbon;

class ManageExpulsions extends Component
{
    public $expelledStudents = [];
    public $isReinstating = false;
    public $selectedStudentId;
    public $student;
    public $newExpulsionEndDate;
    public $isExtendingExpulsion = false;

    public $expulsionPeriod;

    public function mount()
    {
        $this->fetchExpelledStudents();
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



    public function fetchExpelledStudents()
    {
        $this->expelledStudents = StudentRecord::where('is_expelled', true)->get();
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
            $student->is_expelled = false; // Reinstate the student
            $student->expulsion_reason = null; // Clear the reason for expulsion
            $student->expelled_by = null; // Clear who expelled the student
            $student->expulsion_date = null; // Clear the expulsion date
            $student->expulsion_type = null; // Clear the type of expulsion
            $student->expulsion_end_date = null; // Clear the end date
            $student->save();

            session()->flash('message', 'Student reinstated successfully.');
            $this->fetchExpelledStudents(); // Fetch updated list of expelled students
        } else {
            session()->flash('error', 'Failed to reinstate student. Please try again.');
        }

        $this->resetFields();
    }



    public function confirmExtension()
    {
        // Validate the new expulsion end date input
        $this->validate([
            'newExpulsionEndDate' => 'required|date|after_or_equal:' . now()->toDateString(),
        ]);

        // Check if the student is set
        if (!$this->student) {
            session()->flash('error', 'Student not found.');
            return;
        }

        // Get the current end date
        $currentEndDate = $this->student->expulsion_end_date
            ? Carbon::parse($this->student->expulsion_end_date)
            : now();

        // Parse the new expulsion end date from the input
        $newEndDate = Carbon::parse($this->newExpulsionEndDate);

        // Calculate total expulsion duration
        if ($this->student->is_expelled) {
            // If the student is already expelled, we extend the end date if the new one is later
            if ($newEndDate->isAfter($currentEndDate)) {
                $this->student->expulsion_end_date = $newEndDate; // Update to new end date
            }
        } else {
            session()->flash('error', 'Student is not currently expelled.');
            return;
        }

        // Save the updated student record
        $this->student->save();

        // Set a success message in the session
        session()->flash('message', 'Expulsion extended successfully.');
        $this->fetchExpelledStudents(); // Fetch updated list of expelled students

        // Reset fields after processing
        $this->resetFields();
    }


    public function extendExpulsion($id)
    {
        $this->selectedStudentId = $id;
        $this->student = StudentRecord::find($id); // Load the student record
        $this->isExtendingExpulsion = true;
    }







    private function resetFields()
    {
        $this->isReinstating = false;
        $this->isExtendingExpulsion = false;
        $this->expulsionPeriod = null;
        $this->selectedStudentId = null;
    }

    public function render()
    {
        // Refresh expelled students list every render call
        $this->fetchExpelledStudents();
        return view('livewire.manage-expulsions', [
            'expelledStudents' => $this->expelledStudents,
            'student' => $this->student, // Pass the student to the view
        ]);
    }
}
