<?php

namespace App\Livewire;

use Livewire\Component;
use App\User; // Import your User model or the appropriate model for your users
use Qs; // Import your Qs facade if it's not already imported

class Dashboard extends Component
{
    public $totalStudents;
    public $marksDeadline;
    public $users;

    public function mount()
    {
        // Fetch total students and users
        $this->totalStudents = User::where('user_type', 'student')->count();
        $this->users = User::all();
        $this->marksDeadline = '2024-10-30'; // Set your marks allocation deadline
    }

    public function render()
    {
        return view('livewire.dashboard');
    }
}
