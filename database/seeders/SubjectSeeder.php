<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Subject;
use App\Models\SubjectCategory;

class SubjectSeeder extends Seeder
{
    public function run()
    {
        // Define an associative array of Kenyan subjects with their respective categories
        $subjectsWithCategories = [
            'English' => 'Languages',
            'Kiswahili' => 'Languages',
            'Mathematics' => 'Mathematics',
            'Biology' => 'Sciences',
            'Chemistry' => 'Sciences',
            'Physics' => 'Sciences',
            'History' => 'Humanities',
            'Geography' => 'Humanities',
            'Business Studies' => 'Business Studies',
            'Art and Design' => 'Arts',
            'Computer Studies' => 'Technical Subjects',
            'Physical Education' => 'Physical Education',
            'Religious Education' => 'Religious Education',
            'Creative Arts' => 'Creative Arts',
        ];

        // Get all category IDs to establish relationships
        $categoryIds = SubjectCategory::pluck('id', 'name')->toArray();

        // Ensure we only create subjects with unique names mapped to their categories
        foreach ($subjectsWithCategories as $subjectName => $categoryName) {
            // Check if the category exists before trying to access its ID
            if (array_key_exists($categoryName, $categoryIds)) {
                Subject::create([
                    'subject_name' => $subjectName, // Use predefined subject name
                    'subject_code' => strtoupper(substr($subjectName, 0, 3)) . '-' . rand(100, 999), // Generate a unique subject code
                    'abbreviation' => strtoupper(substr($subjectName, 0, 3)), // Abbreviation (first three letters)
                    'category_id' => $categoryIds[$categoryName], // Use the mapped category ID
                ]);
            } else {
                // Optionally, log an error or handle the missing category case
                \Log::error("Category '$categoryName' not found for subject '$subjectName'.");
            }
        }
    }
}
