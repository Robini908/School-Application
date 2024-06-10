<?php

namespace App\Http\Controllers\SupportTeam;

use App\Helpers\Qs;
use App\Http\Requests\Subject\SubjectCreate;
use App\Http\Requests\Subject\SubjectUpdate;
use App\Repositories\MyClassRepo;
use App\Repositories\UserRepo;
use App\Http\Controllers\Controller;
use App\Models\Subject;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    protected $my_class, $user;

    public function __construct(MyClassRepo $my_class, UserRepo $user)
    {
        $this->middleware('teamSA', ['except' => ['destroy',]]);
        $this->middleware('super_admin', ['only' => ['destroy',]]);

        $this->my_class = $my_class;
        $this->user = $user;
    }

    public function index()
    {
        $subjects = Subject::all();
        return view('pages.support_team.subjects.index', compact('subjects'));
    }

    public function store(Request $request)
    {
        // Create and save a new subject
        $subject = Subject::create([
            'subject_name' => $request->input('subname'),
            'subject_code' => $request->input('subcode'),
            'abbreviation' => $request->input('subabbrev'),
        ]);
        // Redirect or return a response
        return view('pages.support_team.subjects.index')->with('success', 'Subject created successfully.');
    }



    public function show($id)
    {
        $subject = Subject::query()->with('subjects')->findOrFail($id);
        $code = $subject['subject_code'];
        $name = $subject['subject_name'];
        $abbr = $subject['abbreviation'];
        $id = $subject['id'];


        return view('pages.support_team.grading_system.edit', compact(['gradingSystem', 'code', 'name', 'abbr', 'id']));
    }



    public function edit($id)
    {
        // Fetch the subject by ID
        $subject = Subject::findOrFail($id);
        return is_null($subject) ? Qs::goWithDanger('subjects.index') : view('pages.support_team.subjects.edit', compact('subject'));
    }

    public function update(Request $req, $id)
    {
        // Fetch the subject by ID
        $subject = Subject::findOrFail($id);

        // Update the subject attributes
        $subject->subject_name = $request->input('subject_name');
        $subject->subject_code = $request->input('subject_code');
        $subject->abbreviation = $request->input('abbreviation');

        // Save the updated subject to the database
        $subject->save();
        $subjects = Subject::all();
        return view('pages.support_team.subjects.index', compact('subjects'));
    }


    public function destroy($id)
    {
        $subject = Subject::find($id);

        $subject->delete();
        return redirect()->route('subjects.index')->with('flash_success', 'Subject deleted successfully');
    }
}
