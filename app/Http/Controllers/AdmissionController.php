<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StudentRecord;

class AdmissionController extends Controller
{

    public function checkNumber($number)
    {
        // Validate the input
        if (!is_numeric($number) || $number <= 0) {
            return response()->json(['error' => 'Invalid number provided.'], 400);
        }

        // School code and current year
        $schoolCode = "SCH"; // Replace with actual school code
        $currentYear = date('Y');

        // Check if the entered number has been used
        $paddedNumber = str_pad($number, 5, '0', STR_PAD_LEFT);
        $admissionNumber = "$schoolCode/$paddedNumber/$currentYear";
        $existingRecord = StudentRecord::where('adm_no', $admissionNumber)->first();

        // Suggest the smallest skipped number
        $suggestedNumber = null;
        for ($i = 1; $i < $number; $i++) {
            $checkNumber = str_pad($i, 5, '0', STR_PAD_LEFT);
            $checkAdmissionNumber = "$schoolCode/$checkNumber/$currentYear";
            $exists = StudentRecord::where('adm_no', $checkAdmissionNumber)->exists();
            if (!$exists) {
                $suggestedNumber = $i;
                break;
            }
        }

        if ($existingRecord) {
            return response()->json(['exists' => true, 'suggestion' => $suggestedNumber]);
        }

        return response()->json(['exists' => false, 'suggestion' => $suggestedNumber]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'session' => 'required',
            'user_id' => 'required|integer',
            'my_class_id' => 'required|integer',
            'section_id' => 'required|integer',
            'my_parent_id' => 'required|integer',
            'dorm_id' => 'nullable|integer',
            'dorm_room_no' => 'nullable|string',
            'adm_no' => 'required|unique:student_records,adm_no',
            'year_admitted' => 'required|integer',
            'wd' => 'nullable|boolean',
            'wd_date' => 'nullable|date',
            'grad' => 'nullable|boolean',
            'grad_date' => 'nullable|date',
            'house' => 'nullable|string',
            'age' => 'required|integer',
        ]);

        $studentRecord = new StudentRecord($request->all());
        $studentRecord->save();

        return response()->json(['success' => 'Student record created successfully.']);
    }
}
