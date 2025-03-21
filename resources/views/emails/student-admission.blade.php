<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admission Confirmation</title>
    <!-- Include FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
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
            margin-right: 8px;
            color: #007bff;
        }
    </style>
</head>

<body
    style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f8f9fa; margin: 0; padding: 0; line-height: 1.6;">
    <div
        style="max-width: 600px; margin: 20px auto; background: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);">
        <!-- Header -->
        <div style="background-color: #007bff; color: #ffffff; padding: 20px; text-align: center;">
            <h1 style="margin: 0; font-size: 24px; font-weight: 600;">🎓 Admission Confirmation</h1>
        </div>

        <!-- Body -->
        <div style="padding: 20px; color: #333333;">
            <p style="margin: 0 0 15px; font-size: 16px;">Dear <strong>{{ $student->first_name }}</strong>,</p>
            <p style="margin: 0 0 15px; font-size: 16px;">Congratulations! You have been successfully admitted to
                <strong>{{ $schoolName }}</strong>. We are excited to welcome you to our school community.</p>

            <!-- Admission Details Card -->
            <div
                style="background-color: #f8f9fa; padding: 15px; border-radius: 8px; border: 1px solid #e9ecef; margin-top: 20px;">
                <h2 style="font-size: 18px; color: #007bff; margin-bottom: 10px;">📋 Admission Details</h2>
                <p style="margin: 5px 0; font-size: 14px; color: #555555;">🆔 <strong>Admission Number:</strong>
                    {{ $student->adm_no }}</p>
                <p style="margin: 5px 0; font-size: 14px; color: #555555;">🏫 <strong>Class:</strong>
                    {{ $student->my_class->name ?? 'N/A' }}</p>
                <p style="margin: 5px 0; font-size: 14px; color: #555555;">👥 <strong>Section:</strong>
                    {{ $student->section->name ?? 'N/A' }}</p>
                <p style="margin: 5px 0; font-size: 14px; color: #555555;">🛏️ <strong>Dormitory:</strong>
                    {{ $student->dorm->name ?? 'N/A' }}</p>
                <p style="margin: 5px 0; font-size: 14px; color: #555555;">📅 <strong>Year Admitted:</strong>
                    {{ $student->year_admitted }}</p>
            </div>

            <!-- Student Login Credentials -->
            <div
                style="background-color: #f8f9fa; padding: 15px; border-radius: 8px; border: 1px solid #e9ecef; margin-top: 20px;">
                <h2 style="font-size: 18px; color: #007bff; margin-bottom: 10px;">👨‍🎓 Your Login Credentials</h2>
                <p style="margin: 5px 0; font-size: 14px; color: #555555;">👤 <strong>Username:</strong>
                    {{ $student->adm_no }}</p>
                <p style="margin: 5px 0; font-size: 14px; color: #555555;">🔑 <strong>Password:</strong>
                    {{ $studentPassword }}</p>
            </div>

            <!-- Parent Login Credentials -->
            <div
                style="background-color: #f8f9fa; padding: 15px; border-radius: 8px; border: 1px solid #e9ecef; margin-top: 20px;">
                <h2 style="font-size: 18px; color: #007bff; margin-bottom: 10px;">👪 Parent Login Credentials</h2>
                <p style="margin: 5px 0; font-size: 14px; color: #555555;">👤 <strong>Username:</strong>
                    {{ $parent->parent_id_no }}</p>
                <p style="margin: 5px 0; font-size: 14px; color: #555555;">🔑 <strong>Password:</strong>
                    {{ $parentPassword }}</p>
            </div>

            <!-- Call to Action -->
            <p style="margin: 0 0 15px; font-size: 16px;">To get started, please visit our <a
                    href="{{ $schoolWebsite }}" style="color: #007bff; text-decoration: none; font-weight: 500;">school
                    website</a> for more information about your class schedule, school policies, and upcoming events.
            </p>
        </div>

        <!-- Footer -->
        <div
            style="background-color: #f8f9fa; padding: 15px; text-align: center; font-size: 14px; color: #777777; border-top: 1px solid #e9ecef;">
            <p style="margin: 0;">If you have any questions, feel free to contact us at <a
                    href="mailto:{{ $schoolEmail }}"
                    style="color: #007bff; text-decoration: none; font-weight: 500;">{{ $schoolEmail }}</a>.</p>
            <p style="margin: 0;">Best Regards,<br>{{ $schoolName }}</p>
        </div>
    </div>
</body>


</html>
