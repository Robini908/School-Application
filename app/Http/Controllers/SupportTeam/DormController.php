<?php

namespace App\Http\Controllers\SupportTeam;

use App\Helpers\Qs;
use App\Models\Dorm;
use App\Repositories\DormRepo;
use App\Repositories\UserRepo;
use App\Http\Controllers\Controller;
use App\Http\Requests\Dorm\DormCreate;
use App\Http\Requests\Dorm\DormUpdate;

class DormController extends Controller
{
    protected  $dorm;
    protected  $user;

    public function __construct(DormRepo $dorm ,UserRepo $user)
    {
        $this->middleware('teamSA', ['except' => ['destroy',] ]);
        $this->middleware('super_admin', ['only' => ['destroy',] ]);

        $this->dorm = $dorm;
        $this->user = $user;
    }

    public function index()
    {
        /* $d['teachers'] = $this->user->getUserByType('teacher'); */
        $d['dorms'] = $this->dorm->getAll();
        $d['users'] = $this->user->getAll();
        return view('pages.support_team.dorms.index', $d);
    }

    public function store(DormCreate $req)
    {
        $data = $req->only(['name', 'capacity','dorm_master']);
        $this->dorm->create($data);
        

        return Qs::jsonStoreOk(); 
    }

    public function edit($id)
    {
        $d['teachers'] = $this->user->getUserByType('teacher');
        $d['dorm'] = $dorm = $this->dorm->find($id);

        return !is_null($dorm) ? view('pages.support_team.dorms.edit', $d)
            : Qs::goWithDanger('dorms.index');
    }

    public function update(DormUpdate $req, $id)
    {
        $data = $req->only(['name', 'capacity','dorm_master']);
        $this->dorm->update($id, $data);

        return Qs::jsonUpdateOk();
    }

    public function destroy($id)
    {
        $this->dorm->find($id)->delete();
        return back()->with('flash_success', __('msg.delete_ok'));
    }

    /* public function show($id){
        $dormms = Dorm::find($id);

        var_dump($dormms->users);

        return view('pages.support_team.dorms.show', $dormms);
    } */
}
