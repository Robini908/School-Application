<?php

namespace App\Livewire;

use App\Models\StudentPromotionDemotion;
use App\Models\StudentRecord;
use App\Models\MyClass;
use App\Models\Section;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class ManagePromotionsDemotions extends Component
{
    use AuthorizesRequests;

    public $promotionsDemotions = [];
    public $students = [];
    public $classes = [];
    public $sections = [];

    public $selectedClassId;
    public $action;
    public $selectedSectionId;
    public $student_record_id;
    public $old_class_id;
    public $new_class_id;
    public $old_section_id;
    public $new_section_id;
    public $reason;
    public $event_date;
    public $academic_year;
    public $remarks;
    public $isShowingForm = false;
    public $isCreating = false;
    public $isEditing = false;
    public $selectAll = false;
    public $selectedStudents = [];
    public $isPromotion; // New property to track if the action is promotion or demotion

    public function mount() {
        // Load initial data
        $this->loadData();
    }

    public function loadData() {
        // Load classes
        $this->classes = MyClass::all();

        // Initially load sections and students as empty
        $this->sections = [];
        $this->students = collect(); // Initialize as an empty collection
    }

    public function updatedSelectedClassId($classId) {
        // Reset section and student selection if class changes
        $this->selectedSectionId = null;
        $this->students = collect(); // Reset students

        // Update sections based on selected class
        $this->sections = Section::where('my_class_id', $classId)->get();
    }

    public function updatedSelectedSectionId($sectionId) {
        // Load students based on selected section
        $this->students = StudentRecord::where('section_id', $sectionId)
            ->when($this->selectedClassId, function($query) {
                return $query->where('my_class_id', $this->selectedClassId);
            })
            ->get();
    }

    public function updatedSelectAll($value)
    {
        if ($value) {
            $this->selectedStudents = $this->students->pluck('id')->toArray();
        } else {
            $this->resetSelectedStudents();
        }
    }

    public function resetSelectedStudents()
    {
        $this->selectedStudents = [];
    }

    public function showForm($action)
    {
        $this->action = $action; 
        $this->isShowingForm = true;
    }


    public function promoteAll()
    {
        $this->processAllStudents('promotion');
    }

    public function demoteAll()
    {
        $this->processAllStudents('demotion');
    }

    protected function processAllStudents($action)
    {
        foreach ($this->students as $student) {
            $this->student_record_id = $student->id;
            $this->old_class_id = $student->class_id;

            if ($action === 'promotion') {
                $this->new_class_id = $this->getNewClass($student->class_id);
                $this->new_section_id = $this->getNewSection($student->section_id);
                $this->reason = 'Promotion';
            } else {
                $this->new_class_id = $this->getPreviousClass($student->class_id);
                $this->new_section_id = $this->getPreviousSection($student->section_id);
                $this->reason = 'Demotion';
            }

            $this->event_date = now();
            $this->academic_year = date('Y');
            $this->save();
        }

        session()->flash('message', 'All students ' . ($action === 'promotion' ? 'promoted' : 'demoted') . ' successfully!');
    }

    public function save()
    {
        $this->validate([
            'student_record_id' => 'required',
            'old_class_id' => 'required',
            'new_class_id' => 'required',
            'old_section_id' => 'required',
            'new_section_id' => 'required',
        ]);

        StudentPromotionDemotion::updateOrCreate(
            ['student_record_id' => $this->student_record_id],
            [
                'old_class_id' => $this->old_class_id,
                'new_class_id' => $this->new_class_id,
                'old_section_id' => $this->old_section_id,
                'new_section_id' => $this->new_section_id,
                'reason' => $this->reason,
                'event_date' => $this->event_date,
                'academic_year' => $this->academic_year,
                'remarks' => $this->remarks,
            ]
        );

        $this->resetForm();
        session()->flash('message', $this->reason . ' saved successfully!');
    }

    public function resetForm()
    {
        $this->reset([
            'student_record_id',
            'old_class_id',
            'new_class_id',
            'old_section_id',
            'new_section_id',
            'reason',
            'event_date',
            'academic_year',
            'remarks',
            'selectedStudents',
            'selectAll',
        ]);
        $this->isEditing = false;
        $this->isCreating = false;
        $this->isShowingForm = false;
        $this->isPromotion = null; // Reset the promotion state
    }

    public function render()
    {
        return view('livewire.manage-promotions-demotions', [
            'students' => $this->students, // Pass filtered students to the view
            'classes' => $this->classes,
            'isPromotion' => $this->isPromotion, // Pass promotion state to the view for conditional rendering
        ]);
    }

    // Logic for determining new and previous class and section
    protected function getNewClass($currentClassId)
    {
        $currentClass = MyClass::find($currentClassId);
        return $currentClass ? $currentClass->next_class_id : null; // Assuming you have a relationship or next_class_id field
    }

    protected function getNewSection($currentSectionId)
    {
        $currentSection = Section::find($currentSectionId);
        return $currentSection ? $currentSection->next_section_id : null; // Assuming you have a relationship or next_section_id field
    }

    protected function getPreviousClass($currentClassId)
    {
        $currentClass = MyClass::find($currentClassId);
        return $currentClass ? $currentClass->previous_class_id : null; // Assuming you have a relationship or previous_class_id field
    }

    protected function getPreviousSection($currentSectionId)
    {
        $currentSection = Section::find($currentSectionId);
        return $currentSection ? $currentSection->previous_section_id : null; // Assuming you have a relationship or previous_section_id field
    }
}
