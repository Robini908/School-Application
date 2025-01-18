<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Admission Confirmation</title>
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
            background-color: #007bff;
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
        .email-body a {
            color: #007bff;
            text-decoration: none;
            font-weight: 500;
        }
        .email-body a:hover {
            text-decoration: underline;
        }
        .details-card {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            border: 1px solid #e9ecef;
            margin-top: 20px;
        }
        .details-card h2 {
            font-size: 18px;
            color: #007bff;
            margin-bottom: 10px;
        }
        .details-card p {
            margin: 5px 0;
            font-size: 14px;
            color: #555555;
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
        .icon {
            width: 20px;
            height: 20px;
            vertical-align: middle;
            margin-right: 8px;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="email-header">
            <h1>🎉 Admission Confirmation</h1>
        </div>

        <!-- Body -->
        <div class="email-body">
            <p>Dear <strong>{{ $student->first_name }}</strong>,</p>
            <p>Congratulations! You have been successfully admitted to <strong>{{ $schoolName }}</strong>. We are excited to welcome you to our school community.</p>

            <!-- Admission Details Card -->
            <div class="details-card">
                <h2>📋 Admission Details</h2>
                <p><img src="https://img.icons8.com/ios-filled/50/007bff/admission.png" class="icon" alt="Admission Icon"> <strong>Admission Number:</strong> {{ $student->adm_no }}</p>
                <p><img src="https://img.icons8.com/ios-filled/50/007bff/class.png" class="icon" alt="Class Icon"> <strong>Class:</strong> {{ $student->my_class->name ?? 'N/A' }}</p>
                <p><img src="https://img.icons8.com/ios-filled/50/007bff/group.png" class="icon" alt="Section Icon"> <strong>Section:</strong> {{ $student->section->name ?? 'N/A' }}</p>
                <p><img src="https://img.icons8.com/ios-filled/50/007bff/bed.png" class="icon" alt="Dorm Icon"> <strong>Dormitory:</strong> {{ $student->dorm->name ?? 'N/A' }}</p>
                <p><img src="https://img.icons8.com/ios-filled/50/007bff/calendar.png" class="icon" alt="Year Icon"> <strong>Year Admitted:</strong> {{ $student->year_admitted }}</p>
            </div>

            <!-- Call to Action -->
            <p>To get started, please visit our <a href="{{ $schoolWebsite }}">school website</a> for more information about your class schedule, school policies, and upcoming events.</p>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>If you have any questions, feel free to contact us at <a href="mailto:{{ $schoolEmail }}">{{ $schoolEmail }}</a>.</p>
            <p>Best Regards,<br>{{ $schoolName }}</p>
        </div>
    </div>
</body>
</html>