<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Graduation Certificate</title>
    <link rel="stylesheet" href="{{ public_path('css/certificate.css') }}">
</head>
<body>
    <div class="certificate-container">
        <!-- Header -->
        <div class="header">
            <img src="{{ public_path('images/school_logo.png') }}" alt="School Logo" class="logo">
            <h1>Certificate of Graduation</h1>
            <h2>{{ $graduationDetails->transition_year ?? now()->year }}</h2>
        </div>

        <!-- Content -->
        <div class="content">
            <p>This certifies that</p>
            <h3>{{ $student->first_name }} {{ $student->last_name }}</h3>
            <p>with admission number <strong>{{ $student->adm_no }}</strong></p>
            <p>has successfully completed the course of study and is hereby awarded this</p>
            <h4>Certificate of Graduation</h4>
            <p>on this <strong>{{ now()->format('jS F, Y') }}</strong>.</p>
        </div>

        <!-- Signature -->
        <div class="signature">
            <div class="signature-line"></div>
            <p>Principal</p>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>School Name | Address | Phone: +123 456 7890 | Email: info@school.com</p>
        </div>
    </div>
</body>
</html>