<?php

namespace App\Livewire;

use App\Models\Subject;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\StudentRecord;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;
use Exception;

class ManageStudentSubjects extends Component
{
    use WithPagination;
    use LivewireAlert;

    public $searchTerm = '';
    public $student = null;
    public $rechooseSubjectId = null;
    public $newSubjectId = null;
    public $sameCategorySubjects = [];
    public $showStudentList = true;
    public $showSubjectManagement = false;

    protected $queryString = ['searchTerm'];

    public function updatedSearchTerm()
    {
        $this->resetPage();
    }

    public function selectStudent($studentId)
    {
        try {
            $this->student = StudentRecord::with('subjects')->findOrFail($studentId);
            $this->showStudentList = false;
            $this->showSubjectManagement = true;
        } catch (Exception $e) {
            Log::error('Error selecting student: ' . $e->getMessage());
            $this->alert('error', 'Unable to find the student. Please try again later.');
        }
    }

    public function deregisterSubject($subjectId)
    {
        try {
            $subjectCount = $this->student->subjects->count();
            if ($subjectCount <= 7) {
                $this->alert('error', 'Cannot deregister subjects. Minimum 7 subjects required.', [
                    'position' => 'top',
                    'showConfirmButton' => true,
                    'confirmButtonText' => 'OK',
                    'reverseButtons' => true,
                    'timer' => 30000,
                    'toast' => false,
                ]);
                return;
            }

            if ($this->student && !$this->isCompulsory($subjectId)) {
                $this->student->subjects()->detach($subjectId);
                $this->student->load('subjects');
            }
        } catch (Exception $e) {
            Log::error('Error deregistering subject: ' . $e->getMessage());
            $this->alert('error', 'There was an error deregistering the subject. Please try again later.');
        }
    }

    public function loadRechooseOptions($subjectId)
    {
        try {
            $this->rechooseSubjectId = $subjectId;

            // Retrieve the category ID of the subject
            $categoryId = Subject::where('id', $subjectId)->value('category_id');

            // Get subjects within the same category, that are elective, and not already selected by the student
            $this->sameCategorySubjects = Subject::where('category_id', $categoryId)
                ->where('type', 'elective')  // Ensure the subject is elective
                ->whereNotIn('id', $this->student->subjects->pluck('id'))  // Exclude already selected subjects
                ->where('id', '!=', $subjectId)  // Exclude the current subject
                ->get();
        } catch (Exception $e) {
            Log::error('Error loading rechoose options: ' . $e->getMessage());
            $this->alert('error', 'There was an error loading rechoose options. Please try again later.');
        }
    }

    public function updateSubjectSelection($subjectId)
    {
        try {
            $this->validate([
                'newSubjectId' => [
                    'required',
                    Rule::exists('subjects', 'id')->where('type', 'elective'),
                ],
            ]);

            if ($this->student && $this->newSubjectId) {
                $this->student->subjects()->detach($subjectId);
                $this->student->subjects()->attach($this->newSubjectId);
                $this->reset('rechooseSubjectId', 'newSubjectId', 'sameCategorySubjects');
                $this->student->load('subjects');
            }
        } catch (Exception $e) {
            Log::error('Error updating subject selection: ' . $e->getMessage());
            $this->alert('error', 'There was an error updating the subject selection. Please try again later.');
        }
    }

    public function resetRechooseSelection()
    {
        try {
            $this->reset('rechooseSubjectId', 'newSubjectId', 'sameCategorySubjects');
        } catch (Exception $e) {
            Log::error('Error resetting rechoose selection: ' . $e->getMessage());
            $this->alert('error', 'There was an error resetting the rechoose selection. Please try again later.');
        }
    }

    public function closeCard()
    {
        try {
            $this->showStudentList = true;
            $this->showSubjectManagement = false;
        } catch (Exception $e) {
            Log::error('Error closing card: ' . $e->getMessage());
            $this->alert('error', 'There was an error closing the card. Please try again later.');
        }
    }

    private function isCompulsory($subjectId)
    {
        try {
            $subject = Subject::findOrFail($subjectId);
            return $subject->type === 'compulsory';
        } catch (Exception $e) {
            Log::error('Error checking compulsory subject: ' . $e->getMessage());
            $this->alert('error', 'There was an error checking if the subject is compulsory. Please try again later.');
            return false; // Return false in case of error
        }
    }

    public function render()
    {
        try {
            $students = StudentRecord::with('subjects')
                ->whereHas('subjects')
                ->where(function ($query) {
                    $query->where('first_name', 'like', "%{$this->searchTerm}%")
                        ->orWhere('last_name', 'like', "%{$this->searchTerm}%")
                        ->orWhereHas('subjects', function ($query) {
                            $query->where('subject_name', 'like', "%{$this->searchTerm}%");
                        });
                })
                ->paginate(10);

            return view('livewire.manage-student-subjects', ['students' => $students]);
        } catch (Exception $e) {
            Log::error('Error rendering students: ' . $e->getMessage());
            $this->alert('error', 'There was an error loading the students. Please try again later.', [
                'position' => 'top',
                'showConfirmButton' => true,
                'confirmButtonText' => 'OK',
                'reverseButtons' => true,
                'timer' => 30000,
                'toast' => false,
            ]);
            return view('livewire.manage-student-subjects', ['students' => []]);
        }
    }
}
