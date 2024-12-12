<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class StudentDetailsExport implements FromCollection, WithHeadings, WithMapping
{
    protected $studentDetails;

    public function __construct($studentDetails)
    {
        $this->studentDetails = $studentDetails;
    }

    public function collection()
    {
        return collect($this->studentDetails);
    }

    public function headings(): array
    {
        return [
            'Subject',
            'Marks',
            'Grade',
            'Remark',
            'GPA'
        ];
    }

    public function map($detail): array
    {
        return [
            $detail['subject_name'] ?? 'N/A',
            $detail['marks'] ?? 'N/A',
            $detail['grade'] ?? 'N/A',
            $detail['remark'] ?? 'N/A',
            $detail['gpa'] ?? 'N/A'
        ];
    }
}
