<?php

namespace App\Http\Controllers;

use App\Models\GradingRange;
use App\Models\GradingSystem;
use App\Models\Subject;
use Illuminate\Http\Request;

class SubjectRangesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        dd($request->params);
        $subject = Subject::find($request->id);
        $gradingSystem = GradingSystem::find($request->grading_system_id);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $grading_system_id, $subject_range_id)
    {
        // Retrieve the subject based on the subject_range_id
        $subject = Subject::findOrFail($subject_range_id);

        // Retrieve the grading system
        $gradingSystem = GradingSystem::findOrFail($grading_system_id);

        // Retrieve the grading ranges that have subject_id matching subject_range_id
        $ranges = GradingRange::where('grading_system_id', $grading_system_id)
            ->where('subject_id', $subject_range_id)
            ->get();

        // Pass the data to the view
        return view('pages.support_team.grading_system.subject-ranges.index', compact('subject', 'gradingSystem', 'ranges'));
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $grading_system_id, $subject_range_id)
    {
        $data = $request->all();
        $gradingSystem = GradingSystem::find($grading_system_id);


        $ranges = [];
        foreach ($data['range_from'] as $index => $from) {
            $to = $data['range_to'][$index];
            $grade = $data['grade'][$index];
            $remark = $data['remark'][$index] ?? 'N/A';
            $gpa = $data['gpa'][$index] ?? 0;

            $ranges[] = [
                'range_from' => $from,
                'range_to' => $to,
                'grade' => $grade,
                'remark' => $remark,
                'gpa' => $gpa,
                'subject_id' => $subject_range_id
            ];
        }

        $gradingSystem->gradingRanges()->delete();
        $gradingSystem->gradingRanges()->createMany($ranges);
        $gradingSystem->save();

        return redirect()->route('grading_system.index')->with('flash_success', 'Grading system updated successfully');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
