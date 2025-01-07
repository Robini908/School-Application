<?php

namespace App\Services;

use App\Models\Subject;

class BestScienceSelector
{
    /**
     * Select the best 2 science subjects for a student based on marks.
     *
     * @param array $marksData Array of subjects and their marks for a student.
     * @return array The selected science subjects and their marks.
     */
    public function selectBestSciences(array $marksData): array
    {
        // Filter sciences
        $scienceSubjects = collect($marksData)->filter(function ($mark, $subjectId) {
            $subject = Subject::find($subjectId);
            return $subject && $subject->category->name === 'Sciences';
        });

        // If there are 2 or fewer sciences, return all
        if ($scienceSubjects->count() <= 2) {
            return $scienceSubjects->toArray();
        }

        // Otherwise, sort by marks and pick the top 2
        return $scienceSubjects
            ->sortDesc()
            ->take(2)
            ->toArray();
    }
}
