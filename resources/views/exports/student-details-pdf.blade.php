<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Performance Report</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ public_path('css/student-report.css') }}">
</head>
<body>
    <!-- Page 1: School & Student Info -->
    <div class="page">
        <!-- Header -->
        <div class="header text-center mb-4">
            <img src="{{ public_path('images/school_logo.png') }}" alt="School Logo" class="logo mb-3" width="100">
            <h1 class="text-primary">School Name</h1>
            <p class="text-muted">School Motto or Tagline</p>
            <p class="text-muted">School Address | Phone: +123 456 7890 | Email: info@school.com</p>
        </div>

        <!-- Student Information -->
        <div class="content mb-4">
            <h2 class="text-primary mb-3">Student Information</h2>
            <table class="table table-bordered">
                <tbody>
                    <tr>
                        <td><strong>Name:</strong> {{ $studentAdditionalDetails['first_name'] ?? 'N/A' }} {{ $studentAdditionalDetails['middle_name'] ?? '' }} {{ $studentAdditionalDetails['last_name'] ?? '' }}</td>
                        <td><strong>Admission No:</strong> {{ $selectedAdmNo ?? 'N/A' }}</td>
                        <td><strong>Class:</strong> {{ $studentAdditionalDetails['class_name'] ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td><strong>Section:</strong> {{ $studentAdditionalDetails['section_name'] ?? 'N/A' }}</td>
                        <td><strong>Gender:</strong> {{ $studentAdditionalDetails['gender'] ?? 'N/A' }}</td>
                        <td><strong>Date:</strong> {{ now()->format('jS F, Y') }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Footer -->
        <div class="footer mt-5">
            <p class="text-muted">Disclaimer: This report is issued on the basis of information available to the school as of the date shown above. It is therefore a provisional document and cannot be deemed final.</p>
        </div>
    </div>

    <!-- Page 2: Marks & Performance Info -->
    <div class="page">
        <!-- Header -->
        <div class="header text-center mb-4">
            <h1 class="text-primary">Academic Performance</h1>
            <p class="text-muted">Exam: {{ $examName ?? 'N/A' }}</p>
        </div>

        <!-- Subject Marks & Grades -->
        <div class="content mb-4">
            <h2 class="text-primary mb-3">Subject Marks & Grades</h2>
            <table class="table table-bordered">
                <thead class="table-primary">
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

        <!-- Overall Performance -->
        <div class="content mb-4">
            <h2 class="text-primary mb-3">Overall Performance</h2>
            <table class="table table-bordered">
                <thead class="table-primary">
                    <tr>
                        <th>Total Marks</th>
                        <th>Mean Score</th>
                        <th>Total Points</th>
                        <th>Class Position</th>
                        <th>Stream Position</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ $totalMarks ?? 'N/A' }}</td>
                        <td>{{ $meanScore ?? 'N/A' }}</td>
                        <td>{{ $totalPoints ?? 'N/A' }}</td>
                        <td>{{ $classPosition ?? 'N/A' }}</td>
                        <td>{{ $streamPosition ?? 'N/A' }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Footer with Signatures and Comments -->
        <div class="footer mt-5">
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Principal's Signature:</strong> ________________________</p>
                </div>
                <div class="col-md-6">
                    <p><strong>Date:</strong> {{ now()->format('jS F, Y') }}</p>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <p><strong>General Comments:</strong> _________________________________________________________</p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>