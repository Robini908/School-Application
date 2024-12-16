<?php

namespace App\Services;

use App\Models\StudentRecord;
use App\Models\Subject;
use App\Models\SubjectCategory;
use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;

class SubjectSelectionService
{
    /**
     * Validate the subject selection for a student.
     *
     * @param StudentRecord $student
     * @param Collection $selectedSubjects
     * @return bool
     * @throws ValidationException
     */
    public function validateSelection(StudentRecord $student, Collection $selectedSubjects)
    {
        // Initialize an array to hold error messages
        $validationErrors = [];

        // Validate compulsory subjects
        if (!$this->hasAllCompulsorySubjects($selectedSubjects)) {
            $validationErrors[] = 'You must select all compulsory subjects.';
        }

        // Validate category subject requirements
        $categoryCounts = $this->countSubjectsByCategory($selectedSubjects);
        foreach ($this->getCategoryRules() as $categoryName => $requiredCount) {
            $selectedCount = $categoryCounts[$categoryName] ?? 0;
            if ($selectedCount < $requiredCount) {
                $validationErrors[] = "You must select at least {$requiredCount} {$categoryName} subjects. You have selected {$selectedCount}.";
            }
        }

        // Validate prerequisites
        foreach ($selectedSubjects as $subject) {
            if ($subject->prerequisite && !$selectedSubjects->contains($subject->prerequisite)) {
                $validationErrors[] = "You must select {$subject->prerequisite->subject_name} before selecting {$subject->subject_name}.";
            }
        }

        // Validate total number of subjects
        $totalSubjects = $selectedSubjects->count();
        if ($totalSubjects < 7 || $totalSubjects > 8) {
            $validationErrors[] = "You must select between 7 and 8 subjects. You have selected {$totalSubjects}.";
        }

        // If there are validation errors, throw ValidationException with the formatted message
        if (!empty($validationErrors)) {
            $errorMessage = implode(' ', $validationErrors);
            throw ValidationException::withMessages(['subject_selection' => $errorMessage]);
        }

        // Return true if no validation errors
        return true;
    }


    public function countSubjectsByCategory($selectedSubjects)
    {
        $categoryCounts = [];

        foreach ($selectedSubjects as $subject) {
            $categoryName = $subject->category->name;
            if (!isset($categoryCounts[$categoryName])) {
                $categoryCounts[$categoryName] = 0;
            }
            $categoryCounts[$categoryName]++;
        }

        return $categoryCounts;
    }

    /**
     * Check if all compulsory subjects are selected.
     *
     * @param Collection $selectedSubjects
     * @return bool
     */
    protected function hasAllCompulsorySubjects(Collection $selectedSubjects): bool
    {
        $compulsorySubjects = Subject::where('type', 'compulsory')->pluck('id');
        $selectedCompulsoryIds = $selectedSubjects->where('type', 'compulsory')->pluck('id');

        // Debugging logs
        Log::info('Compulsory Subjects: ', $compulsorySubjects->toArray());
        Log::info('Selected Compulsory Subjects: ', $selectedCompulsoryIds->toArray());

        return $selectedCompulsoryIds->count() === $compulsorySubjects->count() &&
            $selectedCompulsoryIds->diff($compulsorySubjects)->isEmpty();
    }


    /**
     * Define subject selection rules by category.
     *
     * @return array
     */
    protected function getCategoryRules(): array
    {
        return [
            'Humanities' => 1,
            'Sciences' => 2,
            'Languages' => 2,
            'Technical Subjects' => 1,

        ];
    }

    /**
     * Log errors for debugging.
     *
     * @param array $errors
     * @return void
     */
    protected function logErrors(array $errors): void
    {
        array_map(fn($error) => Log::error($error), $errors);
    }
}
