<?php

namespace App\Http\Controllers;

use App\Models\GradingSystem;
use App\Models\Subject;
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
        $subjects = Subject::all();
        return view('pages.support_team.grading_system.index', compact(['gradingSystems', 'subjects']));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $gradingSystems = GradingSystem::all();
        return view('pages.support_team.grading_system.create', compact('gradingSystems'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $new_grading_system = GradingSystem::create([
            "name" => $data['name'],
        ]);


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
        $gradingSystem = GradingSystem::query()->with('gradingRanges')->findOrFail($id);
        $ranges = $gradingSystem['gradingRanges'];
        $name = $gradingSystem['name'];
        $id = $gradingSystem['id'];
        $subjects = Subject::all();
        $gradingSystems = GradingSystem::all();
        return view('pages.support_team.grading_system.edit', compact(['gradingSystem', 'ranges', 'name', 'id', 'gradingSystems', 'subjects']));
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
        $data = $request->all();
        $gradingSystem = GradingSystem::find($id);
        $gradingSystem->name = $data['name'];
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
        $grading = GradingSystem::find($id);

        if (!$grading) {
            return redirect()->route('grading_system.index')->with('flash_warning', 'Grading system entry not found.');
        }

        $grading->delete();
        return redirect()->route('grading_system.index')->with('flash_success', 'Grading system deleted successfully');
    }
}
