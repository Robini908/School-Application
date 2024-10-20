<?php

namespace App\Livewire;

use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\GradingSystem;
use App\Models\Subject;

class GradingManagement extends Component
{
    use WithPagination;
    use LivewireAlert;
    protected $paginationTheme = 'bootstrap';

    public $name, $description, $effective_date, $rules, $selectedSubjects = [];
    public $subjects;
    public $isCreating = false;
    public $isEditing = false;
    public $editingId = null;

    public $allSelectedMessage = ''; // Message for all subjects selected

    // Removed pagination theme as Livewire 3 handles this automatically

    public function mount()
    {
        $this->subjects = Subject::all();
        $this->selectedSubjects = $this->subjects->pluck('id')->toArray();
        $this->allSelectedMessage = 'All subjects are selected';
    }

    public function toggleSelectAll()
    {
        if (count($this->selectedSubjects) === count($this->subjects)) {
            $this->selectedSubjects = [];
        } else {
            $this->selectedSubjects = $this->subjects->pluck('id')->toArray();
        }
    }

    public function create()
    {
        $this->resetForm();
        $this->isCreating = true;
        $this->isEditing = false;
    }

    public function store()
    {
        if (count($this->selectedSubjects) === 0) {
            $this->selectedSubjects = $this->subjects->pluck('id')->toArray();
        }

        $this->validate($this->rules(), $this->messages());

        $formattedRules = $this->formatRules($this->rules);
        $formattedDescription = $this->formatDescription($this->description);

        $gradingSystem = GradingSystem::create([
            'name' => $this->name,
            'description' => $formattedDescription,
            'effective_date' => $this->effective_date,
            'rules' => $formattedRules,
        ]);

        $gradingSystem->subjects()->sync($this->selectedSubjects);

        $this->alert('success', 'Grading system created successfully.');
        $this->resetForm();
        $this->isCreating = false;
    }

    public function edit($id)
    {
        $gradingSystem = GradingSystem::with('subjects')->findOrFail($id);
        $this->name = $gradingSystem->name;
        $this->description = $gradingSystem->description;
        $this->effective_date = $gradingSystem->effective_date;
        $this->rules = $gradingSystem->rules;

        $this->selectedSubjects = $gradingSystem->subjects->pluck('id')->toArray();

        $allSubjectIds = $this->subjects->pluck('id')->toArray();
        foreach ($allSubjectIds as $subjectId) {
            if (!in_array($subjectId, $this->selectedSubjects)) {
                $this->selectedSubjects[] = $subjectId;
            }
        }

        $this->isEditing = true;
        $this->isCreating = false;
        $this->editingId = $id;
    }

    public function update()
    {
        if (count($this->selectedSubjects) === 0) {
            $this->selectedSubjects = $this->subjects->pluck('id')->toArray();
        }

        $this->validate($this->rules(), $this->messages());

        $formattedRules = $this->formatRules($this->rules);
        $formattedDescription = $this->formatDescription($this->description);

        $gradingSystem = GradingSystem::findOrFail($this->editingId);
        $gradingSystem->update([
            'name' => $this->name,
            'description' => $formattedDescription,
            'effective_date' => $this->effective_date,
            'rules' => $formattedRules,
        ]);

        $gradingSystem->subjects()->sync($this->selectedSubjects);

        $this->alert('success', 'Grading system updated successfully.');
        $this->resetForm();
        $this->isEditing = false;
    }

    public function delete($id)
    {
        $gradingSystem = GradingSystem::findOrFail($id);
        $gradingSystem->subjects()->detach();
        $gradingSystem->delete();

        $this->alert('success', 'Grading system deleted successfully.');
    }

    private function resetForm()
    {
        $this->name = '';
        $this->description = '';
        $this->effective_date = '';
        $this->rules = '';
        $this->selectedSubjects = [];
        $this->isEditing = false;
        $this->isCreating = false;
        $this->editingId = null;
    }

    public function cancel()
    {
        $this->resetForm();
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'effective_date' => 'required|date|after_or_equal:today',
            'selectedSubjects' => 'required|array|min:1',
            'rules' => 'nullable|string',
            'description' => 'nullable|string|max:1000',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Please enter a name for the grading system.',
            'effective_date.required' => 'Please select an effective date.',
            'effective_date.date' => 'The effective date must be a valid date.',
            'effective_date.after_or_equal' => 'The effective date must be today or later.',
            'selectedSubjects.required' => 'Please select at least one subject.',
            'selectedSubjects.array' => 'Subjects must be selected correctly.',
            'rules.string' => 'Rules should be written as text.',
            'description.string' => 'Description should be written as text.',
            'description.max' => 'Description can be up to 1000 characters long.',
        ];
    }

    private function formatRules($rules)
    {
        return implode("\n", array_map('trim', array_filter(explode("\n", $rules))));
    }

    private function formatDescription($description)
    {
        return nl2br(htmlentities($description));
    }

    public function render()
    {
        $gradingSystems = GradingSystem::with('subjects')->paginate(4);
        return view('livewire.grading-management', [
            'gradingSystems' => $gradingSystems,
        ]);
    }
}
