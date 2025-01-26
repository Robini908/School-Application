<?php

namespace App\Livewire;

use Mpdf\Mpdf;
use Carbon\Carbon;
use Livewire\Component;
use App\Models\StudentRecord;
use App\Services\SuspensionService;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class ManageSuspensions extends Component
{
    use LivewireAlert;
    public $suspendedStudents = [];
    public $isReinstating = false;
    public $showForm = false;

    public $selectedStudentId;
    public $student;
    public $studentName;
    public $newSuspensionEndDate;
    public $isExtendingSuspension = false;

    public $suspensionPeriod;

    public function mount()
    {
        $this->fetchSuspendedStudents();
    }

    public function printSuspension($id)
    {
        $student = StudentRecord::find($id);
        if (!$student) {
            $this->alert('error', 'Student not found.');
            return;
        }
        $html = view('pdf.suspension', ['student' => $student])->render();
        return response()->streamDownload(function () use ($html) {
            echo $html;
        }, 'suspension_details.pdf');
    }

    public function downloadStudentSuspension($studentId)
    {
        $student = $this->suspendedStudents->where('id', $studentId)->first();

        if ($student) {
            $suspensionService = new SuspensionService();
            $pdfPath = $suspensionService->generateSuspensionPdf(
                $student,
                $student->suspension_reason,
                $student->suspension_type,
                $student->suspension_end_date
            );

            return response()->download($pdfPath)->deleteFileAfterSend();
        }
    }


    public function printStudentSuspension($studentId)
    {
        $student = $this->suspendedStudents->where('id', $studentId)->first();

        if ($student) {
            $this->dispatch('printStudentSuspension', $student);
        }
    }



    public function printSuspensions()
    {
        // Emit an event to handle client-side printing
        $this->dispatch('printSuspensions');
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
        // $this->studentName = $this->student->name;
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

            $this->alert('success', 'Student reinstated successfully.');
            $this->fetchSuspendedStudents(); // Fetch updated list of suspended students
        } else {
            $this->alert('error', 'Failed to reinstate student. Please try again.');
        }

        $this->resetFields();
    }


    public function checkSuspensions()
    {
        $now = Carbon::now();

        $studentsToReinstate = StudentRecord::where('is_suspended', true)
            ->whereNotNull('suspension_end_date')
            ->where('suspension_end_date', '<=', $now)
            ->get();

        foreach ($studentsToReinstate as $student) {
            $student->update([
                'is_suspended' => false,
                'suspension_reason' => null,
                'suspended_by' => null,
                'suspension_date' => null,
                'suspension_type' => null,
                'suspension_end_date' => null,
            ]);
        }

        $this->fetchSuspendedStudents(); // Refresh the list of suspended students
    }

    public function confirmExtension()
    {
        // Validate the new suspension end date input
        $this->validate([
            'newSuspensionEndDate' => 'required|date|after_or_equal:' . now()->toDateString(),
        ]);

        // Check if the student is set
        if (!$this->student) {
            $this->alert('error', 'Student not found.');
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
            $this->alert('error', 'Student is not currently suspended.');
            return;
        }

        // Save the updated student record
        $this->student->save();

        // Set a success message in the session
        $this->alert('success', 'Suspension extended successfully.');
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
