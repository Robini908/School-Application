<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SubjectCategory;

class SubjectCategorySeeder extends Seeder
{
    public function run()
    {
        // Define an array of Kenyan subject categories
        $categories = [
            'Languages',
            'Sciences',
            'Mathematics',
            'Humanities',
            'Technical Subjects',
            'Arts',
            'Business Studies',
            'Physical Education',
            'Religious Education',
            'Computer Studies',
            'Creative Arts'
        ];

        // Insert each category into the database
        foreach ($categories as $category) {
            SubjectCategory::create([
                'name' => $category,
            ]);
        }
    }
}
