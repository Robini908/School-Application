<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Notification</title>
    <!-- Include Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Include Compiled Email CSS -->
    <link rel="stylesheet" href="{{ public_path('css/email.css') }}">
</head>
<body>
    <div class="email-container">
        <!-- Email Header -->
        <div class="email-header">
            <h1>Important Notification</h1>
        </div>

        <!-- Email Body -->
        <div class="email-body">
            <p>Dear {{ $studentName }},</p>

            <!-- Render TinyMCE content -->
            <div class="email-content">
                {!! $notificationContent !!}
            </div>

            <!-- Attachment Section -->
            @if ($filePath)
                <div class="attachment-section">
                    <p>You can download the attached file using the link below:</p>
                    <p>
                        <a href="{{ asset('storage/' . $filePath) }}" class="btn btn-primary">
                            Download Attachment
                        </a>
                    </p>
                </div>
            @endif

            <p>Best Regards,<br>Your School Team</p>
        </div>

        <!-- Email Footer -->
        <div class="email-footer">
            <p>This email was sent to you because you are registered as a student at our institution.</p>
        </div>
    </div>
</body>
</html>