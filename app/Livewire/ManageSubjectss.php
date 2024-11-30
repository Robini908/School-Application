<?php

namespace App\Livewire;

use App\Models\Subject;

use Livewire\Component;
use Usernotnull\Toast\Toast;
use Masmerise\Toaster\Toaster;
use App\Models\SubjectCategory;
use Illuminate\Support\Facades\Session;
use Usernotnull\Toast\Concerns\WireToast;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class ManageSubjectss extends Component
{
    use LivewireAlert;
    use WireToast;
    public $subjects;
    public $subjectId, $subject_name, $subject_code, $abbreviation, $category_id;
    public $categories = []; // Hold subject categories
    public $new_category = ''; // Hold the new category name
    public $isEditing = false;
    public $isCreating = false;
    public $showForm = false;
    public $showNewCategoryForm = false;
    public $subjectsWithoutCategory;
    public $subjectsWithCategory;
    public $selectedCategory;

    public $filteredSubjects;
    public $allSubjects;



    public $editingCategoryId = null;

    public $edit_category = '';




    public function loadSubjects()
    {
        // Load all subjects including those without categories
        $this->allSubjects = Subject::with('category')->get();
    }

    public function mount()
    {
        $this->loadSubjects();
        $this->categories = SubjectCategory::all(); // Load all categories
    }

    public function updateType($subjectId, $type)
    {
        $subject = Subject::find($subjectId);

        if ($subject) {
            $subject->type = $type;
            $subject->save();

            // Show success alert using LivewireAlert
            $this->alert('info', 'Saved');

            // Optionally, you can emit an event to handle UI updates
            $this->dispatch('typeUpdated', $subjectId);
        }
    }


    public function filterByCategory($categoryId)
    {
        // Filter subjects by selected category and load their categories
        $this->filteredSubjects = Subject::with('category')
            ->where('category_id', $categoryId)
            ->get();
    }






    // Edit Category method
    public function editCategory($id)
    {
        $category = SubjectCategory::find($id);
        if ($category) {
            $this->editingCategoryId = $id;
            $this->edit_category = $category->name;
        }
    }

    // Update Category method
    public function updateCategory()
    {
        $this->validate([
            'edit_category' => 'required|string|max:255',
        ]);

        $category = SubjectCategory::find($this->editingCategoryId);
        if ($category) {
            $category->update(['name' => $this->edit_category]);
        }

        $this->cancelEdit(); // Reset editing state
        $this->categories = SubjectCategory::all(); // Refresh categories
    }

    // Cancel Edit method
    public function cancelEdit()
    {
        $this->editingCategoryId = null;
        $this->edit_category = '';
    }


    // Initialize the component by loading the subjects and categories




    // Show the form for creating a new subject
    public function create()
    {
        $this->resetForm();
        $this->isCreating = true;
        $this->isEditing = false;
        $this->showForm = true;
    }




    // Store a new subject
    public function store()
    {
        $this->validate([
            'subject_name' => 'required',
            'subject_code' => 'required',
            'abbreviation' => 'required',
            'category_id' => 'required|exists:subject_categories,id'
        ]);

        try {
            Subject::create([
                'subject_name' => $this->subject_name,
                'subject_code' => $this->subject_code,
                'abbreviation' => $this->abbreviation,
                'category_id' => $this->category_id
            ]);

            // Load subjects after creation
            $this->loadSubjects();
            $this->resetForm();
            $this->showForm = false;
            $this->isCreating = false;


            $this->alert('success', 'Subject created successfully.');
        } catch (\Exception $e) {

            $this->alert('error', 'An error occurred while creating the subject: ' . $e->getMessage());
        }
    }



    // Show the form for editing a subject
    public function edit($id)
    {
        $subject = Subject::findOrFail($id);
        $this->subjectId = $subject->id;
        $this->subject_name = $subject->subject_name;
        $this->subject_code = $subject->subject_code;
        $this->abbreviation = $subject->abbreviation;
        $this->category_id = $subject->category_id; // Set the current category ID for the dropdown

        $this->isEditing = true;
        $this->isCreating = false;
        $this->showForm = true;
    }

    public function update()
    {
        $this->validate([
            'subject_name' => 'required',
            'subject_code' => 'required',
            'abbreviation' => 'required',
            'category_id' => 'required|exists:subject_categories,id'
        ]);

        try {
            $subject = Subject::findOrFail($this->subjectId);
            $subject->update([
                'subject_name' => $this->subject_name,
                'subject_code' => $this->subject_code,
                'abbreviation' => $this->abbreviation,
                'category_id' => $this->category_id
            ]);

            // Load subjects after update
            $this->loadSubjects();

            // Reset the form and hide it
            $this->resetForm();
            $this->showForm = false;
            $this->isEditing = false;

            $this->alert('success', 'Subject Edited successfully.');

            // toast()
            //     ->success('Subject Updated successfully.')
            //     ->push();

            // Toaster::success(' Subject Updated successfully!'); 
        } catch (\Exception $e) {

            $this->alert('error', 'An error occurred while updating the subject: ' . $e->getMessage());
        }
    }




    // Remove a subject
    public function delete($id)
    {
        try {
            $subject = Subject::findOrFail($id);
            $subject->delete();

            $this->loadSubjects(); // Refresh subjects after deletion
            $this->alert('success', 'Subject deleted successfully.');
        } catch (\Exception $e) {
            $this->alert('error', 'An error occurred while deleting the subject: ' . $e->getMessage());
        }
    }

    // Reset the form fields
    private function resetForm()
    {
        $this->subjectId = null;
        $this->subject_name = '';
        $this->subject_code = '';
        $this->abbreviation = '';
        $this->category_id = null;
        $this->new_category = ''; // Reset new category
        $this->isEditing = false;
        $this->isCreating = false;
        $this->showForm = false;
    }

    // Add a new category
    public function addCategory()
    {
        $this->validate([
            'new_category' => 'required|unique:subject_categories,name'
        ]);

        try {
            SubjectCategory::create(['name' => $this->new_category]);
            $this->categories = SubjectCategory::all(); // Refresh categories
            $this->new_category = ''; // Reset new category input

            $this->alert('success', 'Category added successfully.');
        } catch (\Exception $e) {
            $this->alert('error', 'An error occurred while adding the category: ' . $e->getMessage());
        }
    }

    // Remove a category
    public function removeCategory($id)
    {
        try {
            $category = SubjectCategory::findOrFail($id);
            $category->delete();
            $this->categories = SubjectCategory::all(); // Refresh categories

            $this->alert('success', 'Category removed successfully.');
        } catch (\Exception $e) {
            $this->alert('error', 'An error occurred while removing the category: ' . $e->getMessage());
        }
    }
    public function cancel()
    {
        $this->resetForm();
        $this->showForm = false;
        $this->isEditing = false;
        $this->isCreating = false;
    }

    public function render()
    {
        return view('livewire.manage-subjectss', [
            'subjects' => $this->subjects,
            'categories' => $this->categories
        ]);
    }
}
