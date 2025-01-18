<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Report</title>
</head>
<body>
    <div class="container">
        <h1>Academic Performance Report</h1>
        <p>Dear {{ $student->first_name }},</p>
        <p>Please find attached your academic performance report.</p>
        <p>Best Regards,<br>Your School Team</p>
    </div>
</body>
</html>