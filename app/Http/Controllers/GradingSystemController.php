<?php

namespace App\Http\Controllers;

use App\Models\GradingSystem;

use Illuminate\Http\Request;

class GradingSystemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $gradingSystems = GradingSystem::all();
        return view('pages.support_team.grading_system.index', compact('gradingSystems'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {

        return view('pages.support_team.grading_system.create');
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
        // Assuming $request->all() returns the array you posted
        $data = $request->all();


        // Initialize an empty array to hold the results
        $ranges = [];

        // Loop through the range_from array
        foreach ($data['range_from'] as $index => $from) {
            $to = $data['range_to'][$index];
            $grade = $data['grade'][$index];

            // Add the range and grade to the result array
            $ranges[] = [
                'range_from' => $from,
                'range_to' => $to,
                'grade' => $grade
            ];
        }

        $new_grading_system = GradingSystem::create([
            "name" => $data['name'],
        ]);
        $new_grading_system->gradingRanges()->createMany($ranges);


        return redirect()->route('grading_system.index')->with('flash_success', 'Grading system created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $gradingSystem = GradingSystem::query()->with('gradingRanges')->findOrFail($id);
        $ranges = $gradingSystem['gradingRanges'];
        $name = $gradingSystem['name'];
        $id = $gradingSystem['id'];

        return view('pages.support_team.grading_system.edit', compact(['gradingSystem', 'ranges']));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $gradingSystem = GradingSystem::query()->with('gradingRanges')->findOrFail($id);
        $ranges = $gradingSystem['gradingRanges'];
        $name = $gradingSystem['name'];
        $id = $gradingSystem['id'];

        return view('pages.support_team.grading_system.edit', compact(['gradingSystem', 'ranges']));
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
        $data = $request->all();
        $gradingSystem = GradingSystem::find($id);
        $gradingSystem->name = $data['name'];

        // Loop through the range_from array
        foreach ($data['range_from'] as $index => $from) {
            $to = $data['range_to'][$index];
            $grade = $data['grade'][$index];

            // Add the range and grade to the result array
            $ranges[] = [
                'range_from' => $from,
                'range_to' => $to,
                'grade' => $grade
            ];
        }
        $gradingSystem->gradingRanges()->delete();
        $gradingSystem->gradingRanges()->createMany($ranges);
        $gradingSystem->save();
        return redirect()->route('grading_system.index')->with('flash_success', 'Grading system updated successfully');
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
        $grading = GradingSystem::find($id);
        $grading->delete();
        return redirect()->route('support_team.grading_system.index');
    }
}
