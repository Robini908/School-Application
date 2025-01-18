<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Graduated Students - {{ $graduationYear }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        {!! file_get_contents(public_path('css/pdf-styles.css')) !!}
    </style>
</head>
<body>
    <!-- School Info Section -->
    <div class="school-info">
        <div class="school-logo">
            <img src="{{ public_path('images/school-logo.png') }}" alt="School Logo">
        </div>
        <div class="school-details">
            <h2>Greenwood High School</h2>
            <p>123 Education Avenue, Knowledge City</p>
            <p>Phone: +123 456 7890 | Email: info@greenwoodhigh.edu</p>
            <p>Website: www.greenwoodhigh.edu</p>
        </div>
    </div>

    <!-- Header Section -->
    <div class="header">
        <h1>Graduated Students - {{ $graduationYear }}</h1>
        <p class="subtitle">List of students who graduated in {{ $graduationYear }}</p>
    </div>

    <!-- Table Section -->
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Admission Number</th>
                <th>Student Name</th>
                <th>Graduation Year</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($graduatedStudents as $index => $student)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $student->student->adm_no }}</td>
                    <td>{{ $student->student->first_name }} {{ $student->student->last_name }}</td>
                    <td>{{ $student->transition_year }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Footer Section -->
    <div class="footer">
        <p>Generated on: {{ now()->format('F j, Y') }}</p>
    </div>
</body>
</html>