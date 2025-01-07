<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Class Teachers Information</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid #000;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h1>Class Teachers Information</h1>
    <p><strong>Class:</strong> {{ $class->name }}</p>

    <table>
        <thead>
            <tr>
                <th>Session</th>
                <th>Teacher Name</th>
                <th>Phone</th>
                <th>Gender</th>
                <th>Code</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($classTeachers->groupBy('pivot.session') as $session => $teachers)
                @foreach ($teachers as $teacher)
                    <tr>
                        <td>{{ $session }}</td>
                        <td>{{ $teacher->name }}</td>
                        <td>{{ $teacher->phone ?? '--' }}</td>
                        <td>{{ $teacher->gender ?? '--' }}</td>
                        <td>{{ $teacher->code ?? '--' }}</td>
                    </tr>
                @endforeach
            @endforeach
        </tbody>
    </table>
</body>
</html>