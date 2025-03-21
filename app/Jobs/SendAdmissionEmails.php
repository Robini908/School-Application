<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use App\Mail\StudentAdmissionMail;
use App\Models\StudentRecord;
use App\Models\ParentDetail;

class SendAdmissionEmails implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    protected $student;
    protected $parent;
    protected $parentPassword;
    protected $studentPassword;

    /**
     * Create a new job instance.
     *
     * @param StudentRecord $student
     * @param ParentDetail $parent
     * @param string $parentPassword
     * @param string $studentPassword
     */
    public function __construct($student, $parent, $parentPassword, $studentPassword)
    {
        $this->student = $student;
        $this->parent = $parent;
        $this->parentPassword = $parentPassword;
        $this->studentPassword = $studentPassword;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // Load relationships for the student
        $this->student->load('my_class', 'section', 'dorm');

        // School details (replace with your actual school details or fetch from config)
        $schoolName = config('app.name', 'Your School Name');
        $schoolWebsite = config('app.url', 'https://yourschool.com');
        $schoolEmail = config('mail.from.address', 'info@yourschool.com');

        // Prepare email data
        $emailData = [
            'student' => $this->student,
            'parent' => $this->parent,
            'parentPassword' => $this->parentPassword,
            'studentPassword' => $this->studentPassword,
            'schoolName' => $schoolName,
            'schoolWebsite' => $schoolWebsite,
            'schoolEmail' => $schoolEmail,
        ];

        // Send email to the student
        Mail::to($this->student->email)->send(new StudentAdmissionMail($emailData));

        // Send email to the parent
        Mail::to($this->parent->parent_email)->send(new StudentAdmissionMail($emailData));
    }
}