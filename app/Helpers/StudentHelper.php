<?php

namespace App\Helpers;

use App\Models\StudentRecord;
use App\Models\StudentTransition;

class StudentHelper
{
    /**
     * Filter students by transition year.
     *
     * @param int $transitionYear
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getStudentsByTransitionYear(int $transitionYear)
    {
        return StudentRecord::whereHas('transitions', function ($query) use ($transitionYear) {
            $query->where('transition_year', $transitionYear);
        })->get();
    }
}