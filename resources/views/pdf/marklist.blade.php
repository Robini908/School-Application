<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Marklist PDF</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        h1 {
            text-align: center;
            font-size: 24px;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #000;
            padding: 8px;
            text-align: center;
        }
        th {
            background-color: #f2f2f2;
        }
        .special-grade {
            color: red;
            font-weight: bold;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 12px;
            color: #666;
        }
    </style>
</head>
<body>
    <h1>Marklist Report</h1>
    <p><strong>Class:</strong> {{ $class->name ?? 'N/A' }}</p>
    <p><strong>Section:</strong> {{ $section->name ?? 'N/A' }}</p>
    <p><strong>Exam:</strong> {{ $exam->name ?? 'N/A' }}</p>

    <table>
        <thead>
            <tr>
                <th>Student Name</th>
                <th>Admission Number</th>
                @foreach ($marks->first()['marks'] as $subjectName => $value)
                    <th>{{ $subjectName }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach ($marks as $mark)
                <tr>
                    <td>{{ $mark['student_name'] ?? 'N/A' }}</td>
                    <td>{{ $mark['adm_no'] ?? 'N/A' }}</td>
                    @foreach ($marks->first()['marks'] as $subjectName => $subjectMark)
                        <td>
                            @if (in_array($mark['marks'][$subjectName] ?? '--', ['X', 'Y', 'Z']))
                                <span class="special-grade">{{ $mark['marks'][$subjectName] }}</span>
                            @else
                                {{ $mark['marks'][$subjectName] ?? '--' }}
                            @endif
                        </td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Generated on: {{ now()->format('d/m/Y H:i:s') }}</p>
        <p>Disclaimer: This report is issued based on the information available at the time of generation.</p>
    </div>
</body>
</html>