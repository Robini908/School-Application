<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\MyClass;
use App\Models\Exam;
use App\Models\Student;
use App\Models\Mark;
use App\Models\ExamRecord;
use App\Models\Setting;
use App\Models\StudentRecord;

class ExamAnalysis extends Component
{
    public $exam_id;
    public $my_class_id;
    public $section_id;
    public $selected = false;
    public $exams;
    public $my_classes;
    public $sections;
    public $students;
    public $subjects;
    public $marks;
    public $exr;
    public $my_class;
    public $section;
    public $exam;
    public $tex;
    public $year;

    public function mount()
    {
        $this->year = now()->year; // Set your year or get it dynamically
        $this->my_classes = MyClass::all();
        $this->exams = Exam::where('year', $this->year)->get();
    }

    public function updatedMyClassId($class_id)
    {
        $this->sections = MyClass::find($class_id)->sections;
    }

    public function render()
    {
        if ($this->selected) {
            $this->fetchData();
        }

        return view('livewire.exam-analysis');
    }

    public function fetchData()
    {
        if ($this->my_class_id && $this->section_id && $this->exam_id) {
            $wh = [
                'my_class_id' => $this->my_class_id,
                'section_id' => $this->section_id,
                'exam_id' => $this->exam_id,
                'year' => $this->year
            ];

            $sub_ids = Mark::getSubjectIDs($wh);
            $st_ids = Mark::getStudentIDs($wh);

            if (count($sub_ids) < 1 || count($st_ids) < 1) {
                session()->flash('flash_danger', __('msg.srnf'));
                return;
            }

            $this->subjects = MyClass::getSubjectsByIDs($sub_ids);
            $this->students = StudentRecord::getRecordByUserIDs($st_ids)->sortBy('user.name');
            $this->sections = MyClass::allSections();

            $this->selected = true;
            $this->marks = Mark::getMark($wh);
            $this->exr = ExamRecord::getRecord($wh);
            $this->my_class = MyClass::find($this->my_class_id);
            $this->section = MyClass::findSection($this->section_id);
            $this->exam = Exam::find($this->exam_id);
            $this->tex = 'tex' . $this->exam->term;
        }
    }

    public function printTabulation()
    {
        if ($this->selected) {
            $wh = [
                'my_class_id' => $this->my_class_id,
                'section_id' => $this->section_id,
                'exam_id' => $this->exam_id,
                'year' => $this->year
            ];

            $sub_ids = Mark::getSubjectIDs($wh);
            $st_ids = Mark::getStudentIDs($wh);

            if (count($sub_ids) < 1 || count($st_ids) < 1) {
                session()->flash('flash_danger', __('msg.srnf'));
                return;
            }

            $data = [
                'subjects' => MyClass::getSubjectsByIDs($sub_ids),
                'students' => StudentRecord::getRecordByUserIDs($st_ids)->sortBy('user.name'),
                'my_class_id' => $this->my_class_id,
                'exam_id' => $this->exam_id,
                'year' => $this->year,
                'marks' => Mark::getMark($wh),
                'exr' => ExamRecord::getRecord($wh),
                'my_class' => MyClass::find($this->my_class_id),
                'section' => MyClass::findSection($this->section_id),
                'exam' => Exam::find($this->exam_id),
                'tex' => 'tex' . Exam::find($this->exam_id)->term,
                'settings' => Setting::all()->flatMap(function ($s) {
                    return [$s->type => $s->description];
                })
            ];

            return view('pages.support_team.marks.tabulation.print', $data);
        }
    }
}
