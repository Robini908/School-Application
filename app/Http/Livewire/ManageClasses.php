<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\ClassType;
use App\Models\StudentRecord;
use App\Models\User;
use Illuminate\Support\Facades\Gate;

class ManageClasses extends Component
{
    use WithPagination;

    public $name;
    public $classId;
    public $selectedClass;

    protected $rules = [
        'name' => 'required|string|max:255',
    ];

    public function render()
    {
        $my_classes = ClassType::paginate(10);
        $users = StudentRecord::all(); // Adjust this according to your actual data source
        return view('livewire.manage-classes', [
            'my_classes' => $my_classes,
            'users' => $users,
        ]);
    }

    public function store()
    {
        $this->validate();
        ClassType::create(['name' => $this->name]);
        session()->flash('message', 'Class created successfully.');
        $this->resetInputFields();
        $this->emit('refreshClasses');
    }

    public function edit($id)
    {
        $this->selectedClass = ClassType::find($id);
        $this->name = $this->selectedClass->name;
        $this->classId = $id;
    }

    public function update()
    {
        $this->validate();
        $class = ClassType::find($this->classId);
        $class->update(['name' => $this->name]);
        session()->flash('message', 'Class updated successfully.');
        $this->resetInputFields();
        $this->emit('refreshClasses');
    }

    public function delete($id)
    {
        if (Gate::allows('delete-class')) {
            ClassType::find($id)->delete();
            session()->flash('message', 'Class deleted successfully.');
            $this->emit('refreshClasses');
        } else {
            session()->flash('error', 'You are not authorized to delete classes.');
        }
    }

    private function resetInputFields()
    {
        $this->name = '';
        $this->classId = null;
        $this->selectedClass = null;
    }
}
