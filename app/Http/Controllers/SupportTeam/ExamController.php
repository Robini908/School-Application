<?php

namespace App\Http\Controllers\SupportTeam;

use App\Helpers\Qs;
use App\Http\Requests\Exam\ExamCreate;
use App\Http\Requests\Exam\ExamUpdate;
use App\Repositories\ExamRepo;
use App\Http\Controllers\Controller;
use App\Models\GradingSystem;
use App\Models\MyClass;

class ExamController extends Controller
{
    protected $exam;
    public function __construct(ExamRepo $exam)
    {
        $this->middleware('teamSA', ['except' => ['destroy',]]);
        $this->middleware('super_admin', ['only' => ['destroy',]]);

        $this->exam = $exam;
    }

    public function index()
    {
        $exams = $this->exam->all();
        $gradingSystems = GradingSystem::all();
        $classes_ = MyClass::all();
        return view('pages.support_team.exams.index', compact('exams', 'gradingSystems', 'classes_'));
    }

    public function store(ExamCreate $req)
    {
        // dd($req->all());
        $data = $req->only(['name', 'term', 'grading_system_id']);

        $data['year'] = Qs::getSetting('current_session');

        $this->exam->create($data);
        return back()->with('flash_success', __('msg.store_ok'));
    }

    public function edit($id)
    {
        $d['ex'] = $this->exam->find($id);
        return view('pages.support_team.exams.edit', $d);
    }

    public function update(ExamUpdate $req, $id)
    {
        $data = $req->only(['name', 'term']);

        $this->exam->update($id, $data);
        return back()->with('flash_success', __('msg.update_ok'));
    }

    public function destroy($id)
    {
        $this->exam->delete($id);
        return back()->with('flash_success', __('msg.del_ok'));
    }
}
