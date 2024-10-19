import Handsontable from 'handsontable';
import 'handsontable/dist/handsontable.full.min.css';

document.addEventListener('livewire:init', function () {
    const students = JSON.parse(document.querySelector('meta[name="students"]').getAttribute('content'));
    const subjects = JSON.parse(document.querySelector('meta[name="subjects"]').getAttribute('content'));
    const marksData = JSON.parse(document.querySelector('meta[name="marks"]').getAttribute('content'));

    // Initialize Handsontable
    const hotElement = document.querySelector('#marks-handsontable');
    const hot = new Handsontable(hotElement, {
        data: formatTableData(students, subjects, marksData),
        colHeaders: generateHeaders(subjects),
        rowHeaders: true,
        minSpareRows: 0,
        stretchH: 'all',
        columns: generateColumns(subjects),
        licenseKey: 'non-commercial-and-evaluation' // for Handsontable evaluation purposes
    });

    // Format data for Handsontable
    function formatTableData(students, subjects, marksData) {
        return students.map(student => {
            let row = [student.first_name + ' ' + student.last_name, student.adm_no];
            subjects.forEach(subject => {
                row.push(marksData[student.id]?.[subject.id] || '');
            });
            return row;
        });
    }

    // Generate column headers
    function generateHeaders(subjects) {
        return ['Student Name', 'Admission No', ...subjects.map(subject => subject.subject_name)];
    }

    // Define columns for Handsontable
    function generateColumns(subjects) {
        let columns = [
            { readOnly: true }, // Student Name
            { readOnly: true }  // Admission No
        ];

        subjects.forEach(() => {
            columns.push({ type: 'numeric', allowInvalid: false, min: 0, max: 100 });
        });

        return columns;
    }

    // Handle save button
    document.querySelector('#save-marks').addEventListener('click', function () {
        const data = hot.getData();
        const marks = formatMarksForLivewire(data, students, subjects);

        // Call Livewire method to assign marks
        Livewire.dispatch('saveMarks', marks);
    });

    function formatMarksForLivewire(data, students, subjects) {
        let marks = {};
        data.forEach((row, rowIndex) => {
            let student = students[rowIndex];
            marks[student.id] = {};
            subjects.forEach((subject, subjectIndex) => {
                marks[student.id][subject.id] = row[subjectIndex + 2] || null; // 2 for Name and Admission No
            });
        });
        return marks;
    }
});
