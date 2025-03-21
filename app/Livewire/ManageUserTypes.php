<?php

namespace App\Livewire;

use App\Models\UserType;
use Livewire\Component;
use Livewire\WithPagination;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Illuminate\Validation\Rule;
use App\Helpers\Qs;


class ManageUserTypes extends Component
{
    use WithPagination, LivewireAlert;

    // Properties for form inputs
    public $title, $name, $level;
    public $editMode = false;
    public $userTypeId;
    public $search = '';
    public $showForm = false;
    public $showDeleteModal = false; // For delete confirmation modal
    public $deleteId; // ID of the user type to delete

    // Validation rules
    protected function rules()
    {
        return [
            'title' => [
                'required',
                'string',
                'max:255',
                Rule::unique('user_types', 'title')->ignore($this->userTypeId),
            ],
            'name' => 'required|string|max:255',
            'level' => [
                'required',
                'integer',
                'min:1',
                Rule::unique('user_types', 'level')->ignore($this->userTypeId),
                function ($attribute, $value, $fail) {
                    // Ensure levels are sequential and no numbers are skipped
                    $existingLevels = UserType::pluck('level')->toArray();
                    if (!in_array($value, $existingLevels)) {
                        $minLevel = min($existingLevels);
                        $maxLevel = max($existingLevels);
                        if ($value < $minLevel || $value > $maxLevel + 1) {
                            $suggestedLevel = $maxLevel + 1;
                            $fail("Levels must be sequential. The next available level is $suggestedLevel.");
                        }
                    }
                },
            ],
        ];
    }

    // Reset form fields
    public function resetForm()
    {
        $this->reset(['title', 'name', 'level', 'editMode', 'userTypeId']);
        $this->showForm = false;
    }

    // Save or update user type
    public function saveUserType()
    {
        $this->validate();

        if ($this->editMode) {
            // Update existing user type
            $userType = UserType::findOrFail($this->userTypeId);
            $userType->update([
                'title' => $this->title,
                'name' => $this->name,
                'level' => $this->level,
            ]);
            $this->alert('success', 'User type updated successfully.');
        } else {
            // Create new user type
            UserType::create([
                'title' => $this->title,
                'name' => $this->name,
                'level' => $this->level,
            ]);
            $this->alert('success', 'User type created successfully.');
        }

        $this->resetForm();
    }

    // Edit user type
    public function edit($id)
    {
        $userType = UserType::findOrFail($id);
        $this->userTypeId = $id;
        $this->title = $userType->title;
        $this->name = $userType->name;
        $this->level = $userType->level;
        $this->editMode = true;
        $this->showForm = true;
    }

    // Show delete confirmation modal
    public function confirmDelete($id)
    {
        $this->deleteId = $id;
        $this->showDeleteModal = true;
    }

    // Delete user type
    public function delete()
    {
        UserType::findOrFail($this->deleteId)->delete();
        $this->showDeleteModal = false;
        $this->alert('success', 'User type deleted successfully.');
    }

    // Show the form for adding a new user type
    public function showAddForm()
    {
        $this->resetForm();
        $this->showForm = true;
    }

    // Render the component view
    public function render()
    {
        $userTypes = UserType::where('title', 'like', '%' . $this->search . '%')
            ->orWhere('name', 'like', '%' . $this->search . '%')
            ->orWhere('level', 'like', '%' . $this->search . '%')
            ->orderBy('level')
            ->paginate(10);

        return view('livewire.manage-user-types', compact('userTypes'));
    }
}