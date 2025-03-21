<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Student Marks Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .school-info {
            margin-bottom: 15px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f5f5f5;
            font-weight: bold;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .footer {
            text-align: center;
            font-size: 10px;
            color: #666;
            margin-top: 20px;
        }
        .meta-info {
            margin-bottom: 15px;
            font-size: 11px;
        }
        .meta-info span {
            margin-right: 20px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Student Marks Report</h1>
    </div>

    <div class="meta-info">
        <span><strong>Class:</strong> {{ $class->name ?? 'N/A' }}</span>
        <span><strong>Section:</strong> {{ $section->name ?? 'N/A' }}</span>
        <span><strong>Exam:</strong> {{ $exam->name ?? 'N/A' }}</span>
        <span><strong>Date:</strong> {{ now()->format('d/m/Y') }}</span>
    </div>

    <table>
        <thead>
            <tr>
                @foreach(array_keys($data[0] ?? []) as $header)
                <th>{{ $header }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($data as $row)
            <tr>
                @foreach($row as $value)
                <td>{{ $value }}</td>
                @endforeach
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Generated on {{ now()->format('d/m/Y H:i:s') }}</p>
        <p>This is a computer-generated document. No signature is required.</p>
    </div>
</body>
</html> 