<?php

namespace App\Imports;

use App\Models\StudentRecord;
use App\Models\MyClass;
use App\Models\Section;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class StudentsImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        // Check for the existence of class_name and section_name keys
        if (!isset($row['class_name']) || !isset($row['section_name'])) {
            throw new \Exception("The row is missing 'class_name' or 'section_name'");
        }

        // Retrieve the class ID based on the class name
        $class = MyClass::where('name', $row['class_name'])->first();
        $class_id = $class ? $class->id : null;

        if (!$class_id) {
            throw new \Exception("Class name '{$row['class_name']}' not found in MyClass table.");
        }

        // Retrieve the section ID based on the section name
        $section = Section::where('name', $row['section_name'])->where('my_class_id', $class_id)->first();
        $section_id = $section ? $section->id : null;

        if (!$section_id) {
            throw new \Exception("Section name '{$row['section_name']}' not found for class ID '{$class_id}' in Section table.");
        }

        return new StudentRecord([
            'adm_no' => $row['adm_no'],
            'first_name' => $row['first_name'],
            'middle_name' => $row['middle_name'],
            'last_name' => $row['last_name'],
            'gender' => $row['gender'],
            'dob' => $row['dob'],
            'my_class_id' => $class_id,
            'section_id' => $section_id,
            'year_admitted' => $row['year_admitted'],
            'email' => $row['email'], // Optional, but useful for communication
            'phone' => $row['phone'], // Optional, but useful for communication
        ]);
    }
}
