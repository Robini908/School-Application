<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Repositories\UserRepo;

class UserPieChart extends Component
{
    public $userCounts;

    protected $userRepo;

    public function __construct($id = null)
    {
        parent::__construct($id);
        $this->userRepo = app(UserRepo::class); // Injecting the UserRepo instance
    }

    public function mount()
    {
        $this->userCounts = [
            'Students' => $this->userRepo->getUserByType('student')->count(),
            'Teachers' => $this->userRepo->getUserByType('teacher')->count(),
            'Administrators' => $this->userRepo->getUserByType('admin')->count(),
            'Parents' => $this->userRepo->getUserByType('parent')->count(),
        ];
    }

    public function render()
    {
        return view('livewire.user-pie-chart');
    }
}
