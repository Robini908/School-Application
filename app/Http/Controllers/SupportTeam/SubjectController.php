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


    public function show($id)
    {
        $subject = Subject::query()->with('subjects')->findOrFail($id);
        $code = $subject['subject_code'];
        $name = $subject['subject_name'];
        $abbr = $subject['abbreviation'];
        $id = $subject['id'];


        return view('pages.support_team.grading_system.show', compact(['gradingSystem', 'code', 'name', 'abbr', 'id']));
    }


    public function store(Request $request)
    {
        // Validate the incoming request data
        $validatedData = $request->validate([
            'subname' => 'required|unique:subjects,subject_name',
            'subcode' => 'required|unique:subjects,subject_code',
            'subabbrev' => 'required|unique:subjects,abbreviation',
        ], [
            'subname.required' => 'Subject name is required.',
            'subname.unique' => 'Subject name already exists.',
            'subcode.required' => 'Subject code is required.',
            'subcode.unique' => 'Subject code already exists.',
            'subabbrev.required' => 'Subject abbreviation is required.',
            'subabbrev.unique' => 'Subject abbreviation is already allocated for another subject.',
        ]);

        // Use a database transaction to ensure data integrity
        try {
            \DB::beginTransaction();

            // Create a new subject instance with the provided data
            $subject = Subject::create([
                'subject_name' => $validatedData['subname'],
                'subject_code' => $validatedData['subcode'],
                'abbreviation' => $validatedData['subabbrev'],
            ]);

            \DB::commit();

            // Redirect to the index page with a success message
            return redirect()->route('subjects.index')->with('flash_success', 'Subject created successfully.');
        } catch (\Exception $e) {
            // Roll back the transaction if an exception occurs
            \DB::rollBack();

            // Log the error for debugging
            \Log::error('Error creating subject: ' . $e->getMessage());

            // Redirect back with error message
            return back()->withInput()->with('flash_error', 'Failed to create subject. Please try again.');
        }
    }







    public function edit($id)
    {
        // Fetch the subject by ID
        $subject = Subject::findOrFail($id);
        return is_null($subject) ? Qs::goWithDanger('subjects.index') : view('pages.support_team.subjects.edit', compact('subject'));
    }

    public function update(Request $request, $id)
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
