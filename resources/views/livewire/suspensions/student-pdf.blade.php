<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Suspension Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .header h1 {
            font-size: 2em;
            margin-bottom: 5px;
        }
        .header p {
            font-size: 1em;
            color: #555;
        }
        .student-details {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 15px;
            background-color: #f9f9f9;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .student-details h3 {
            font-size: 1.5em;
            text-align: center;
            color: #333;
            margin-bottom: 10px;
        }
        .student-details p {
            margin: 10px 0;
            font-size: 1em;
        }
        .footer {
            text-align: center;
            font-size: 0.9em;
            color: #888;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Suspension Report</h1>
        <p>Generated on {{ now()->format('l, F jS, Y') }}</p>
    </div>

    <div class="card">
        <h3>{{ $student->adm_no }} - {{ $student->first_name }} {{ $student->last_name }}</h3>
        <p>This is to officially report that <strong>{{ $student->first_name }} {{ $student->last_name }}</strong>(<strong>{{ $student->adm_no }}</strong>), has been suspended from school due to  <strong class="text-danger">{{ $student->suspension_reason }}</strong>.</p>
        <p>The suspension has been categorized as a <strong>{{ ucfirst($student->suspension_type) }} suspension</strong>.</p>
        <p>The suspension commenced on <strong>{{ $student->suspension_date ? $student->suspension_date->format('l, F jS, Y \a\t h:i A') : 'N/A' }}</strong> and is scheduled to end on <strong>{{ $student->suspension_end_date ? $student->suspension_end_date->format('l, F jS, Y \a\t h:i A') : 'N/A' }}</strong>.</p>
        <p>We expect <strong>{{ $student->first_name }}</strong> to resume studies on the date specified above unless further actions are necessary.</p>
    </div>

    <div class="footer">
        <p>&copy; {{ now()->year }} Mbuku Erp. All rights reserved.</p>
    </div>
</body>
</html>
