<?php

namespace App\Services;

use Mpdf\Mpdf;

class SuspensionService
{
    public function generateSuspensionPdf($student, $suspensionReason, $suspensionType, $suspensionEndDate)
    {
        // Render the PDF view
        $html = view('livewire.suspensions.student-pdf', [
            'student' => $student,
            'suspensionReason' => $suspensionReason,
            'suspensionType' => $suspensionType,
            'suspensionEndDate' => $suspensionEndDate,
        ])->render();

        // Initialize mPDF
        $mpdf = new Mpdf();
        $mpdf->WriteHTML($html);

        // Generate the file name and path
        $fileName = 'suspension_' . $student->adm_no . '_' . now()->format('Y-m-d_His') . '.pdf';
        $filePath = storage_path("app/public/suspensions/{$fileName}");

        // Ensure the directory exists
        if (!file_exists(dirname($filePath))) {
            mkdir(dirname($filePath), 0777, true);
        }

        // Save the PDF to storage
        $mpdf->Output($filePath, \Mpdf\Output\Destination::FILE);

        return $filePath;
    }
}