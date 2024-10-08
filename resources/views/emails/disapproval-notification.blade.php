<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Disapproval Notification</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            color: #333;
        }
        p {
            line-height: 1.5;
            color: #555;
        }
        .footer {
            margin-top: 20px;
            font-size: 0.8em;
            color: #777;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Disapproval Notification</h1>
        <p>Dear {{ $studentName }},</p>
        <p>{{ $disapprovalReason }}</p>
        <p>Best Regards,<br>Your School Team</p>
        
        <div class="footer">
            <p>This email was sent to you because you are registered as a student at our institution.</p>
        </div>
    </div>
</body>
</html>
