<?php

namespace App\Livewire;

use Mpdf\Mpdf;
use App\Models\Exam;
use App\Models\MyClass;
use Livewire\Component;
use App\Models\ExamMarks;
use Phpml\Dataset\ArrayDataset;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Phpml\Classification\KNearestNeighbors;
use Phpml\CrossValidation\StratifiedRandomSplit;

class SubjectChampions extends Component
{
    public $classId;
    public $streamId;
    public $examId;
    public $term;
    public $year;
    public $champions;
    public $classes;
    public $exams = [];
    public $filteredExams = [];
    public $errorMessage;
    public $className;
    public $examName;
    public $streamName;

    public function mount()
    {
        $this->classes = MyClass::with('sections')->get();
        $this->champions = collect(); // Initialize champions
    }

    public function updatedClassId()
    {
        $this->streamId = null;
        $this->examId = null;
        $this->exams = [];
        $this->champions = collect(); // Reset champions
        $this->errorMessage = null;

        if ($this->classId) {
            $class = MyClass::find($this->classId);
            $this->className = $class->name;
            $this->exams = $class->exams;
        }
    }

    public function updatedStreamId()
    {
        $this->examId = null;
        $this->champions = collect(); // Reset champions
        $this->errorMessage = null;

        if ($this->streamId) {
            $this->streamName = MyClass::find($this->classId)->sections->find($this->streamId)->name;
        }
    }

    public function updatedExamId()
    {
        $this->champions = collect(); // Reset champions
        $this->errorMessage = null;
        $exam = MyClass::find($this->classId)->exams->find($this->examId);
        $this->examName = $exam->name;
        $this->term = $exam->term;
        $this->year = $exam->year;
        
        $this->getChampions(); // Fetch champions for the newly selected exam
    }

    public function getChampions()
    {
        if (!$this->examId) {
            $this->errorMessage = 'Please select an exam to view champions.';
            return;
        }

        $query = ExamMarks::with(['student', 'subject'])
            ->where('exam_id', $this->examId);

        if ($this->classId) {
            $query->whereHas('student', function ($q) {
                $q->where('my_class_id', $this->classId);
            });
        }

        if ($this->streamId) {
            $query->whereHas('student', function ($q) {
                $q->where('section_id', $this->streamId);
            });
        }

        $this->champions = $query->get()
            ->groupBy('subject_id')
            ->map(function ($marks) {
                $highestMarks = $marks->max('marks');
                return $marks->where('marks', $highestMarks);
            })->flatten(1);

        if ($this->champions->isEmpty()) {
            $this->errorMessage = 'No champions found for the selected criteria.';
        }
    }

    public function exportPDF()
    {
        $mpdf = new \Mpdf\Mpdf();
        $html = view('pdf.champions', [
            'champions' => $this->champions,
            'className' => $this->className,
            'examName' => $this->examName,
            'term' => $this->term,
            'year' => $this->year,
            'streamName' => $this->streamName,
        ])->render();
        $mpdf->WriteHTML($html);

        // Generate a descriptive file name
        $fileName = 'Subject_Champions_for_' . str_replace(' ', '_', $this->className) . '_Term_' . $this->term . '_Year_' . $this->year;
        if ($this->streamName) {
            $fileName .= '_Stream_' . str_replace(' ', '_', $this->streamName);
        }
        $fileName .= '.pdf';

        $filePath = storage_path('app/public/' . $fileName);
        $mpdf->Output($filePath, \Mpdf\Output\Destination::FILE);

        return response()->download($filePath);
    }

    public function exportExcel()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'Champions Report');
        $sheet->setCellValue('A2', 'Class Name: ' . $this->className);
        $sheet->setCellValue('A3', 'Exam Name: ' . $this->examName);
        $sheet->setCellValue('A4', 'Term: ' . $this->term);
        $sheet->setCellValue('A5', 'Year: ' . $this->year);
        if ($this->streamName) {
            $sheet->setCellValue('A6', 'Stream Name: ' . $this->streamName);
        }

        $sheet->setCellValue('A8', 'Subject');
        $sheet->setCellValue('B8', 'Student');
        $sheet->setCellValue('C8', 'Admission Number');
        $sheet->setCellValue('D8', 'Stream');
        $sheet->setCellValue('E8', 'Marks');

        $row = 9;
        foreach ($this->champions as $champion) {
            $sheet->setCellValue('A' . $row, $champion->subject->subject_name);
            $sheet->setCellValue('B' . $row, $champion->student->first_name . ' ' . $champion->student->last_name);
            $sheet->setCellValue('C' . $row, $champion->student->adm_no);
            $sheet->setCellValue('D' . $row, $champion->student->section->name);
            $sheet->setCellValue('E' . $row, $champion->marks);
            $row++;
        }

        // Generate a descriptive file name
        $fileName = 'Subject_Champions_for_' . str_replace(' ', '_', $this->className) . '_Term_' . $this->term . '_Year_' . $this->year;
        if ($this->streamName) {
            $fileName .= '_Stream_' . str_replace(' ', '_', $this->streamName);
        }
        $fileName .= '.xlsx';

        $writer = new Xlsx($spreadsheet);
        $writer->save(storage_path('app/public/' . $fileName));

        return response()->download(storage_path('app/public/' . $fileName));
    }




    public function render()
    {
        return view('livewire.subject-champions', ['champions' => $this->champions, 'errorMessage' => $this->errorMessage, 'terms' => Exam::distinct()->pluck('term'), 'years' => Exam::distinct()->pluck('year'), 'filteredExams' => $this->filteredExams,]);
    }
}
