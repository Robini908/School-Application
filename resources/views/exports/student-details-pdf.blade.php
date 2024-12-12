<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Details</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h1 {
            font-size: 24px;
            color: #333;
        }
        .student-info, .exam-info, .subject-info, .performance-info {
            margin-bottom: 20px;
        }
        .student-info strong, .exam-info strong, .subject-info strong, .performance-info strong {
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 8px;
            text-align: center;
        }
        th {
            background-color: #007bff;
            color: white;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Details for Admission No: {{ $selectedAdmNo ?? 'N/A' }}</h1>
    </div>
    <div class="student-info">
        <strong>Name:</strong> {{ $studentAdditionalDetails['first_name'] ?? 'N/A' }} {{ $studentAdditionalDetails['middle_name'] ?? '' }} {{ $studentAdditionalDetails['last_name'] ?? '' }}<br>
        <strong>Class:</strong> {{ $studentAdditionalDetails['class_name'] ?? 'N/A' }}<br>
        <strong>Section:</strong> {{ $studentAdditionalDetails['section_name'] ?? 'N/A' }}<br>
        <strong>Gender:</strong> {{ $studentAdditionalDetails['gender'] ?? 'N/A' }}<br>
    </div>
    <div class="exam-info">
        <strong>Exam:</strong> {{ $examName ?? 'N/A' }}<br>
        <strong>Grading System:</strong> {{ $gradingSystemDetails['name'] ?? 'N/A' }}<br>
        <strong>Description:</strong> {{ $gradingSystemDetails['description'] ?? 'N/A' }}<br>
    </div>
    <div class="subject-info">
        <h4>Subject Marks & Grades</h4>
        <table>
            <thead>
                <tr>
                    <th>Subject</th>
                    <th>Marks</th>
                    <th>Grade</th>
                    <th>Remark</th>
                    <th>GPA</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($studentDetails as $detail)
                    <tr>
                        <td>{{ $detail['subject_name'] ?? 'N/A' }}</td>
                        <td>{{ $detail['marks'] ?? 'N/A' }}</td>
                        <td>{{ $detail['grade'] ?? 'N/A' }}</td>
                        <td>{{ $detail['remark'] ?? 'N/A' }}</td>
                        <td>{{ $detail['gpa'] ?? 'N/A' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="performance-info">
        <h4>Overall Performance</h4>
        <p><strong>Total Marks:</strong> {{ $totalMarks ?? 'N/A' }}</p>
        <p><strong>Mean Score:</strong> {{ $meanScore ?? 'N/A' }}</p>
        <p><strong>Total Points:</strong> {{ $totalPoints ?? 'N/A' }}</p>
        <p><strong>Position in Class:</strong> {{ $classPosition ?? 'N/A' }}</p>
        <p><strong>Position in Stream:</strong> {{ $streamPosition ?? 'N/A' }}</p>
    </div>
</body>
</html>
