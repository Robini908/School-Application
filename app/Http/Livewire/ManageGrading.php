<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\GradingSystem;
use App\Models\Subject;

class ManageGrading extends Component
{
    use WithPagination;

    public $name;
    public $subjects;

    protected $paginationTheme = 'bootstrap'; // Ensure Tailwind pagination styles are used

    public function mount()
    {
        $this->subjects = Subject::all();
    }

    public function store()
    {
        $this->validate([
            'name' => 'required|string|max:255',
        ]);

        GradingSystem::create([
            'name' => $this->name,
        ]);

        $this->reset('name');
        $this->resetPage(); // Reset pagination to the first page after storing
    }

    public function render()
    {
        $gradingSystems = GradingSystem::paginate(6); // Adjust the number of items per page as needed
        return view('livewire.manage-grading', compact('gradingSystems'));
    }
}
