<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Approval Confirmation</title>
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
            background-color: #28a745;
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
            <h1>🎉 Approval Confirmation</h1>
        </div>

        <!-- Body -->
        <div class="email-body">
            <p>Dear <strong>{{ $student->first_name }}</strong>,</p>
            <p>We are pleased to inform you that your admission to <strong>{{ $schoolName }}</strong> has been approved. Welcome to our school community!</p>

            <p>Here are your admission details:</p>
            <ul>
                <li><strong>Admission Number:</strong> {{ $student->adm_no }}</li>
                <li><strong>Class:</strong> {{ $student->my_class->name ?? 'N/A' }}</li>
                <li><strong>Section:</strong> {{ $student->section->name ?? 'N/A' }}</li>
                <li><strong>Dormitory:</strong> {{ $student->dorm->name ?? 'N/A' }}</li>
            </ul>

            <p>If you have any questions, feel free to contact us at <a href="mailto:{{ $schoolEmail }}">{{ $schoolEmail }}</a>.</p>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>Best Regards,<br>{{ $schoolName }}</p>
        </div>
    </div>
</body>
</html>