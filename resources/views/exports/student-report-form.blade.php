<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Student Academic Report Form</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            margin: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .school-logo {
            width: 80px;
            height: 80px;
            margin: 0 auto;
        }
        .student-info {
            width: 100%;
            margin-bottom: 15px;
        }
        .student-info td {
            padding: 3px;
        }
        table.marks-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .marks-table th, .marks-table td {
            border: 1px solid #000;
            padding: 5px;
            text-align: center;
        }
        .marks-table th {
            background-color: #f0f0f0;
        }
        .progress-chart {
            width: 100%;
            height: 200px;
            margin-bottom: 20px;
        }
        .remarks-section {
            margin-top: 20px;
        }
        .signature-section {
            margin-top: 30px;
        }
        .grade-key {
            font-size: 10px;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="school-logo">
            <img src="{{ $school->logo_url }}" alt="School Logo">
        </div>
        <h2>{{ $school->name }}</h2>
        <p>P.O Box {{ $school->address }}, {{ $school->location }} Tel: {{ $school->telephone }}</p>
        <h3>TERM {{ $exam->term }} ACADEMIC REPORT FORM-YEAR {{ $exam->year }}</h3>
    </div>

    <table class="student-info">
        <tr>
            <td><strong>NAME:</strong> {{ $student->full_name }}</td>
            <td><strong>ADM NO:</strong> {{ $student->admission_number }}</td>
            <td><strong>CLASS:</strong> {{ $student->class_name }}</td>
            <td><strong>STREAM:</strong> {{ $student->section_name }}</td>
        </tr>
    </table>

    <table class="marks-table">
        <thead>
            <tr>
                <th>SUBJECT</th>
                @foreach($examResults as $term => $result)
                    <th>TERM {{ $term }}</th>
                @endforeach
                <th>REMARKS</th>
                <th>INITIAL</th>
            </tr>
        </thead>
        <tbody>
            @foreach($subjects as $subject)
            <tr>
                <td>{{ $subject->subject_name }}</td>
                @foreach($examResults as $term => $result)
                    <td>
                        @if(isset($result['marks'][$subject->id]))
                            {{ $result['marks'][$subject->id] }}
                            ({{ $result['grades'][$subject->id] }})
                        @else
                            -
                        @endif
                    </td>
                @endforeach
                <td>{{ $remarks[$subject->id] ?? '' }}</td>
                <td>{{ $initials[$subject->id] ?? '' }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td><strong>TOTAL MARKS</strong></td>
                @foreach($examResults as $result)
                    <td><strong>{{ $result['total_marks'] ?? '-' }}</strong></td>
                @endforeach
                <td colspan="2"></td>
            </tr>
            <tr>
                <td><strong>MEAN GRADE</strong></td>
                @foreach($examResults as $result)
                    <td><strong>{{ $result['mean_grade'] ?? '-' }}</strong></td>
                @endforeach
                <td colspan="2"></td>
            </tr>
            <tr>
                <td><strong>POSITION</strong></td>
                @foreach($examResults as $result)
                    <td>
                        <strong>
                            @if(isset($result['position']))
                                {{ $result['position'] }} out of {{ $result['total_students'] }}
                            @else
                                -
                            @endif
                        </strong>
                    </td>
                @endforeach
                <td colspan="2"></td>
            </tr>
        </tfoot>
    </table>

    <div class="progress-chart">
        <!-- We'll implement the chart using a library like Chart.js -->
        {!! $progressChart !!}
    </div>

    <div class="remarks-section">
        <p><strong>Class Teacher's Remarks:</strong> {{ $classTeacherRemarks }}</p>
        <p><strong>Head Teacher's Remarks:</strong> {{ $headTeacherRemarks }}</p>
    </div>

    <div class="signature-section">
        <table width="100%">
            <tr>
                <td>
                    <strong>Class Teacher's Sign:</strong> _________________
                </td>
                <td>
                    <strong>Head Teacher's Sign:</strong> _________________
                </td>
            </tr>
        </table>
    </div>

    <div class="grade-key">
        <p><strong>Grading Key:</strong></p>
        <p>
            @foreach($gradingSystem->gradingRanges as $range)
                {{ $range->grade }} ({{ $range->min_marks }}-{{ $range->max_marks }}),
            @endforeach
        </p>
    </div>

    <div class="footer">
        <p>Next Term Begins: {{ $nextTermDate }}</p>
        <p>School Fees Balance: {{ $feesBalance }}</p>
    </div>
</body>
</html> 