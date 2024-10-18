<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Exam;
use App\Models\GradingSystem;
use App\Models\MyClass;
use App\Models\Section;
use Faker\Factory as Faker;

class ExamSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create(); // Create a Faker instance

        // Get all available grading systems, classes, and sections
        $gradingSystems = GradingSystem::all();
        $classes = MyClass::all();
        $sections = Section::all();

        // Define a list of Kenyan exam types
        $examTypes = [
            'Term Exams', 'Prediction Exams', 'District Exams', 
            'National Exams', 'Cluster Exams', 'KCSE Mock Exams', 
            'KCPE Mock Exams', 'Mid-Year Exams', 'End-Year Exams',
            'Continuous Assessment Tests (CATs)', 'Classroom Assessments',
            'Joint Examinations'
        ];

        // Define locations, districts, wards, and sublocations
        $locations = [
            'Nairobi', 'Mombasa', 'Kisumu', 'Nakuru', 'Eldoret', 
            'Thika', 'Nyeri', 'Meru', 'Machakos', 'Embu', 
            'Kakamega', 'Kitale', 'Garissa', 'Malindi', 'Homa Bay', 
            'Kajiado', 'Bomet', 'Bungoma', 'Lamu', 'Siaya', 
            'Narok', 'Uasin Gishu'
        ];

        $districts = [
            'Central Nairobi', 'Coast Region', 'Western Kenya', 
            'Rift Valley', 'Central Kenya', 'Eastern Region'
        ];

        $wards = [
            'Kilimani', 'Westlands', 'Ngara', 'Madaraka', 
            'Mombasa Central', 'Kisumu East', 'Nakuru West',
            'Eldoret North', 'Kakamega South', 'Thika Town'
        ];

        $sublocations = [
            'Lavington', 'Karen', 'Kilimani', 'Nyali',
            'Milimani', 'Shimanzi', 'Kisumu Central', 
            'Eldoret Town', 'Nakuru East', 'Thika East'
        ];

        // Define different formats for exam names
        $examNameFormats = [
            "{examType} - {location} ({district}) - {year}",
            "{examType} - {year} - {location}",
            "{examType} ({district}) - {location} - {ward} - {year}",
            "{location} - {examType} - {sublocation} - {year}",
            "{district} - {examType} - {location} - {ward}",
            "{examType} - {location} - {year} - {ward} - {sublocation}",
            "{year} {examType} - {location} - {district}",
            "{examType} {year} ({district}) - {sublocation}",
            "{examType} - {ward} - {location} - {year}",
            "{location} - {examType} ({district}) - {sublocation}",
        ];

        // Generate 1000 exam records
        for ($i = 0; $i < 1000; $i++) {
            // Randomly select grading system, class, and section
            $gradingSystem = $gradingSystems->random();
            $class = $classes->random();
            $section = $sections->random();

            // Generate logical names for exam, district, ward, and sublocation
            $examType = $examTypes[array_rand($examTypes)]; // Random exam type
            $location = $locations[array_rand($locations)]; // Random location
            $district = $districts[array_rand($districts)]; // Random district
            $ward = $wards[array_rand($wards)]; // Random ward
            $sublocation = $sublocations[array_rand($sublocations)]; // Random sublocation
            $year = $faker->year; // Random year
            $term = $faker->numberBetween(1, 4); // Random term between 1 and 4

            // Randomly select an exam name format
            $examNameFormat = $examNameFormats[array_rand($examNameFormats)];

            // Create the exam name using the selected format
            $examName = str_replace(
                ['{examType}', '{location}', '{district}', '{ward}', '{sublocation}', '{year}'],
                [$examType, $location, $district, $ward, $sublocation, $year],
                $examNameFormat
            );

            // Create the exam record
            Exam::create([
                'name' => $examName, // Generated exam name
                'term' => $term, // Random term between 1 and 4
                'year' => (string)$year, // Ensure the year is a string
                'grading_system_id' => $gradingSystem->id, // Link to a grading system
                'class_id' => $class->id, // Link to a class
                'section_id' => $section ? $section->id : null, // Link to a section, or null
            ]);
        }
    }
}
