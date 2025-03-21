<?php

namespace App\Traits;

trait ExamAnalysisCacheKeys
{
    protected function getExamListCacheKey($classId, $term = null, $year = null, $search = null)
    {
        return "exams_list:{$classId}:{$term}:{$year}:" . md5($search ?? '');
    }

    protected function getSingleExamAnalysisCacheKey($examId, $classId, $sectionId = null)
    {
        return "exam_analysis:{$examId}:{$classId}:" . ($sectionId ?? 'all');
    }

    protected function getCombinedExamAnalysisCacheKey(array $examIds, $classId, $sectionId = null)
    {
        sort($examIds); // Ensure consistent order
        return "combined_analysis:" . implode(',', $examIds) . ":{$classId}:" . ($sectionId ?? 'all');
    }

    protected function getStudentDataCacheKey($examId, $studentId)
    {
        return "student_data:{$examId}:{$studentId}";
    }

    protected function getClassDataCacheKey($classId)
    {
        return "class_data:{$classId}";
    }
} 