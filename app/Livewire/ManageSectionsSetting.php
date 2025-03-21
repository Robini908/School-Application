<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\SettingsSection;

class ManageSectionsSetting extends Component
{
    public $sections; // All sections from the database
    public $name; // Input field for adding/editing a section
    public $editSectionId = null; // Track the section being edited
    public $showForm = false; // Toggle the add/edit form
    public $search = ''; // Search functionality
    public $editing = []; // Track which sections are in edit mode

    protected $rules = [
        'name' => 'required|string|max:255|unique:settings_sections,name',
    ];

    protected $messages = [
        'name.required' => 'The section name is required.',
        'name.unique' => 'This section name already exists.',
    ];

    public function mount()
    {
        // Load all sections when the component is initialized
        $this->loadSections();
    }

    public function loadSections()
    {
        // Fetch sections with search functionality
        $this->sections = SettingsSection::when($this->search, function ($query) {
            return $query->where('name', 'like', '%' . $this->search . '%');
        })->latest()->get();

        // Initialize the editing state for each section
        $this->editing = array_fill_keys($this->sections->pluck('id')->toArray(), false);
    }

    public function addSection()
    {
        $this->validate();

        // Create a new section
        SettingsSection::create([
            'name' => $this->name,
        ]);

        // Reset the form and reload sections
        $this->resetForm();
        $this->loadSections();

        // Show success message
        session()->flash('message', 'Section added successfully.');
    }

    public function editSection($id)
    {
        // Find the section to edit
        $section = SettingsSection::findOrFail($id);
        $this->name = $section->name;
        $this->editSectionId = $id;
        $this->editing[$id] = true; // Enable edit mode for this section
    }

    public function updateSection($id)
    {
        $this->validate([
            'name' => 'required|string|max:255|unique:settings_sections,name,' . $id,
        ]);

        // Update the section
        $section = SettingsSection::findOrFail($id);
        $section->update([
            'name' => $this->name,
        ]);

        // Reset the form and reload sections
        $this->resetForm();
        $this->loadSections();

        // Show success message
        session()->flash('message', 'Section updated successfully.');
    }

    public function deleteSection($id)
    {
        // Delete the section
        SettingsSection::findOrFail($id)->delete();

        // Reload sections
        $this->loadSections();

        // Show success message
        session()->flash('message', 'Section deleted successfully.');
    }

    public function resetForm()
    {
        $this->reset(['name', 'editSectionId', 'showForm']);
        $this->editing = array_fill_keys($this->sections->pluck('id')->toArray(), false);
    }

    public function render()
    {
        return view('livewire.manage-sections-setting');
    }
}