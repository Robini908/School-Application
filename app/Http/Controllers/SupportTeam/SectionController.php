<?php

namespace App\Http\Controllers\SupportTeam;

use App\Helpers\Qs;
use App\Http\Requests\Section\SectionCreate;
use App\Http\Requests\Section\SectionUpdate;
use App\Repositories\MyClassRepo;
use App\Http\Controllers\Controller;
use App\Repositories\UserRepo;

class SectionController extends Controller
{
    protected $my_class, $user;

    public function __construct(MyClassRepo $my_class, UserRepo $user)
    {
        $this->middleware('teamSA', ['except' => ['destroy',] ]);
        $this->middleware('super_admin', ['only' => ['destroy',] ]);

        $this->my_class = $my_class;
        $this->user = $user;
    }

    public function index()
    {
        $d['my_classes'] = $this->my_class->all();
        $d['sections'] = $this->my_class->getAllSections();
        $d['teachers'] = $this->user->getUserByType('teacher');

        return view('pages.support_team.sections.index', $d);
    }

    public function store(SectionCreate $req)
    {
        $data = $req->all();
    
        // Check if the teacher is already assigned to another section
        $existing_section = $this->my_class->getSectionByTeacher($data['teacher_id']);
        if ($existing_section) {
            return back()->with('pop_warning', 'The selected teacher is already assigned to another section.');
        }
    
        $this->my_class->createSection($data);
    
        return Qs::jsonStoreOk();
    }
    
    public function update(SectionUpdate $req, $id)
    {
        $data = $req->all();
    
        // Check if the teacher is already assigned to another section (excluding the current section)
        $existing_section = $this->my_class->getSectionByTeacher($data['teacher_id'], $id);
        if ($existing_section) {
            return back()->with('pop_warning', 'The selected teacher is already assigned to another section.');
        }
    
        $this->my_class->updateSection($id, $data);
    
        return Qs::jsonUpdateOk();
    }
    

    public function edit($id)
    {
        $d['s'] = $s = $this->my_class->findSection($id);
        $d['teachers'] = $this->user->getUserByType('teacher');

        return is_null($s) ? Qs::goWithDanger('sections.index') :view('pages.support_team.sections.edit', $d);
    }

    

    public function destroy($id)
    {
        if($this->my_class->isActiveSection($id)){
            return back()->with('pop_warning', 'Every class must have a default section, You Cannot Delete It');
        }

        $this->my_class->deleteSection($id);
        return back()->with('flash_success', __('msg.del_ok'));
    }

}
