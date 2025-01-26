<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Suspension Notification</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
            line-height: 1.6;
        }
        .email-container {
            max-width: 600px;
            margin: 20px auto;
            background: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        .email-header {
            background-color: #dc3545;
            color: #ffffff;
            padding: 20px;
            text-align: center;
        }
        .email-header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 600;
        }
        .email-body {
            padding: 20px;
            color: #333333;
        }
        .email-body p {
            margin: 0 0 15px;
            font-size: 16px;
        }
        .footer {
            background-color: #f8f9fa;
            padding: 15px;
            text-align: center;
            font-size: 14px;
            color: #777777;
            border-top: 1px solid #e9ecef;
        }
        .footer p {
            margin: 0;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="email-header">
            <h1>⚠️ Suspension Notification</h1>
        </div>

        <!-- Body -->
        <div class="email-body">
            <p>Dear <strong>{{ $student->first_name }}</strong>,</p>
            <p>We regret to inform you that your admission to <strong>{{ $schoolName }}</strong> has been suspended due to the following reason:</p>

            <p><strong>Reason:</strong> {{ $suspensionReason }}</p>
            <p><strong>Type:</strong> {{ ucfirst($suspensionType) }}</p>

            @if ($suspensionEndDate)
                <p><strong>Suspension End Date:</strong> {{ $suspensionEndDate->format('jS F, Y') }}</p>
            @endif

            <p>If you have any questions or concerns, please contact us at <a href="mailto:{{ $schoolEmail }}">{{ $schoolEmail }}</a>.</p>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>Best Regards,<br>{{ $schoolName }}</p>
        </div>
    </div>
</body>
</html>