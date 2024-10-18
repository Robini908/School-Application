<?php

namespace App\Http\Controllers;

use App\Helpers\Qs;
use App\Models\ParentDetail;
use App\Repositories\UserRepo;
use App\Models\StudentRecord; // Import the StudentRecord model

class HomeController extends Controller
{
    protected $user;

    public function __construct(UserRepo $user)
    {
        $this->user = $user;
    }

    public function index()
    {
        return redirect()->route('dashboard');
    }

    public function privacy_policy()
    {
        $data['app_name'] = config('app.name');
        $data['app_url'] = config('app.url');
        $data['contact_phone'] = Qs::getSetting('phone');
        return view('pages.other.privacy_policy', $data);
    }

    public function terms_of_use()
    {
        $data['app_name'] = config('app.name');
        $data['app_url'] = config('app.url');
        $data['contact_phone'] = Qs::getSetting('phone');
        return view('pages.other.terms_of_use', $data);
    }

    public function dashboard()
    {
        $d = [];
        if (Qs::userIsTeamSAT()) {
            $d['users'] = $this->user->getAll();
            // Count total students using the StudentRecord model
            $d['totalStudents'] = StudentRecord::count();
            $d['totalParents'] = ParentDetail::count();

        }

        return view('pages.support_team.dashboard', $d);
    }

    public function landingpage()
    {
         return view('outerpages.landing');
    }

    public function contactpage()
    {
         return view('outerpages.contact');
    }

    public function pricingpage()
    {
         return view('outerpages.pricing');
    }

    public function signuppage()
    {
         return view('auth.register');
    }
}
