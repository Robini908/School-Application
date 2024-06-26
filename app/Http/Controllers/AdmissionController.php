<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StudentRecord;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class AdmissionController extends Controller
{

    public function view($id)
    {
        try {
            Log::info("Fetching student record with ID: " . $id);
            dd($id); // Debugging line
            $student = StudentRecord::with('user', 'my_class', 'section', 'dorm')->findOrFail($id);
            dd($student); // Debugging line
            return view('pages.support_team.students.view', compact('student'));
        } catch (ModelNotFoundException $e) {
            Log::error("Student record not found: " . $e->getMessage());
            return back()->with('flash_danger', __('Student record not found.'));
        } catch (Exception $e) {
            Log::error("Failed to load student details: " . $e->getMessage());
            return back()->with('flash_danger', __('An error occurred while loading the student details: ') . $e->getMessage());
        }
    }


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
        $this->validate($request, [
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

        $studentRecord = new StudentRecord();
        $studentRecord->session = $request->input('session');
        $studentRecord->user_id = $request->input('user_id');
        $studentRecord->my_class_id = $request->input('my_class_id');
        $studentRecord->section_id = $request->input('section_id');
        $studentRecord->my_parent_id = $request->input('my_parent_id');
        $studentRecord->dorm_id = $request->input('dorm_id');
        $studentRecord->dorm_room_no = $request->input('dorm_room_no');
        $studentRecord->adm_no = $request->input('adm_no');
        $studentRecord->year_admitted = $request->input('year_admitted');
        $studentRecord->wd = $request->input('wd');
        $studentRecord->wd_date = $request->input('wd_date');
        $studentRecord->grad = $request->input('grad');
        $studentRecord->grad_date = $request->input('grad_date');
        $studentRecord->house = $request->input('house');
        $studentRecord->age = $request->input('age');

        $studentRecord->save();

        return response()->json(['success' => 'Student record created successfully.']);
    }

    public function getStatistics()
    {
        $totalSubmissions = StudentRecord::count();
        $approvedSubmissions = StudentRecord::where('status', 'approved')->count();
        $pendingSubmissions = StudentRecord::where('status', 'pending')->count();

        return response()->json([
            'totalSubmissions' => $totalSubmissions,
            'approvedSubmissions' => $approvedSubmissions,
            'pendingSubmissions' => $pendingSubmissions,
        ]);
    }

    public function getStudents(Request $request)
    {
        $searchQuery = $request->query('search', '');
        $filterStatus = $request->query('status', '');

        $students = StudentRecord::query()
            ->where(function ($query) use ($searchQuery) {
                $query->where('first_name', 'like', '%' . $searchQuery . '%')
                    ->orWhere('middle_name', 'like', '%' . $searchQuery . '%')
                    ->orWhere('last_name', 'like', '%' . $searchQuery . '%');
            })
            ->when($filterStatus, function ($query, $status) {
                return $query->where('status', $status);
            })
            ->get();

        return response()->json($students);
    }

    public function index(Request $request)
    {
        $filterStatus = $request->input('status');
        $searchQuery = $request->input('search');

        $students = StudentRecord::query()
            ->when($filterStatus, function ($query, $filterStatus) {
                return $query->where('status', $filterStatus);
            })
            ->when($searchQuery, function ($query, $searchQuery) {
                return $query->where('name', 'like', '%' . $searchQuery . '%');
            })
            ->get();

        return response()->json($students);
    }

    public function update(Request $request, StudentRecord $student)
    {
        $this->validate($request, [
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|string|email|max:255|unique:students,email,' . $student->id,
            'gender' => 'sometimes|in:Male,Female',
            'class' => 'sometimes|string|max:255',
            'section' => 'sometimes|string|max:255',
            'status' => 'sometimes|in:pending,approved,disapproved',
        ]);

        $student->fill($request->all());
        $student->save();

        return response()->json($student);
    }

    public function destroy(StudentRecord $student)
    {
        $student->delete();

        return response()->json(null, 204);
    }

    public function approve($id)
    {
        $student = StudentRecord::find($id);
        if (!$student) {
            return response()->json(['error' => 'Student not found'], 404);
        }

        if ($student->action_done) {
            return response()->json(['error' => 'Action already performed for this student'], 422);
        }

        $student->update(['status' => 'approved', 'action_done' => true]);

        return response()->json($student);
    }

    public function disapprove(Request $request, $id)
    {
        $student = StudentRecord::find($id);
        if (!$student) {
            return response()->json(['error' => 'Student not found'], 404);
        }

        if ($student->action_done) {
            return response()->json(['error' => 'Action already performed for this student'], 422);
        }

        $this->validate($request, [
            'disapproval_reason' => 'required|string|max:255',
            'disapproval_description' => 'nullable|string',
            'disapproval_attachment' => 'nullable|file|mimes:pdf,jpg,jpeg,png',
        ]);

        if ($request->hasFile('disapproval_attachment')) {
            $attachmentPath = $request->file('disapproval_attachment')->store('attachments', 'public');
            $request->merge(['disapproval_attachment' => $attachmentPath]);
        }

        $student->fill($request->all());
        $student->status = 'disapproved';
        $student->action_done = true;
        $student->save();

        return response()->json($student);
    }

    // Additional methods can be added for updating, deleting, and more as per your application's needs
}
