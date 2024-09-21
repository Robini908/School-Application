<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\MyClass;
use Livewire\WithPagination;

class ManageClasses extends Component
{
    use WithPagination;

    public $name, $classId;
    public $search = '';

    protected $rules = [
        'name' => 'required|string|max:100|unique:my_classes,name',
    ];

    public function create()
    {
        $this->validate();
        
        MyClass::create([
            'name' => $this->name,
            'session' => now()->year, // You can modify this according to your needs
        ]);

        $this->resetInputFields();
        session()->flash('message', 'Class created successfully.');
    }

    public function edit($id)
    {
        $class = MyClass::find($id);
        $this->classId = $class->id;
        $this->name = $class->name;
    }

    public function update()
    {
        $this->validate([
            'name' => 'required|string|max:100|unique:my_classes,name,' . $this->classId,
        ]);

        $class = MyClass::find($this->classId);
        $class->update([
            'name' => $this->name,
        ]);

        $this->resetInputFields();
        session()->flash('message', 'Class updated successfully.');
    }

    public function delete($id)
    {
        MyClass::find($id)->delete();
        session()->flash('message', 'Class deleted successfully.');
    }

    public function resetInputFields()
    {
        $this->name = '';
        $this->classId = null;
    }

    public function render()
    {
        return view('livewire.manage-classes', [
            'my_classes' => MyClass::where('name', 'like', '%' . $this->search . '%')->paginate(10),
        ]);
    }
}
