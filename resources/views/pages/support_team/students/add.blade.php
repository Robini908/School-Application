@extends('layouts.master')
@section('page_title', 'Manage and Admit Students')
@section('content')
<link href=" {{ asset('assets/css/admit_student.css') }}" rel="stylesheet" type="text/css">
<div class="card">
    <div class="card-body">
        <ul class="nav nav-tabs nav-tabs-highlight p-3">
            <li class="nav-item">
                <a href="#manage-students" class="nav-link active" data-toggle="tab">Manage Admissions</a>
            </li>

            <li class="nav-item">
                <a href="#admit-student" class="nav-link " data-toggle="tab">Admit New Student</a>
            </li>

            <li class="nav-item">
                <a href="#bulk-admit" class="nav-link" data-toggle="tab">Bulk Admit</a>
            </li>
        </ul>

        <div class="tab-content" style="margin-top:-50px;">
            <!-- Manage Students Tab -->
            <div class="tab-pane fade show active" id="manage-students">
                @livewire('manage-students')
            </div>
            <div class="tab-pane fade p-1" id="admit-student" style="margin-top:-20px;">
                @livewire('admit-student')
            </div>
            <div class="tab-pane fade p-1" id="bulk-admit" style="margin-top:-20px;">
                @livewire('addbulk')
            </div>
        </div>
    </div>
    @endsection
    @section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/vue@2/dist/vue.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="{{ asset('global_assets/js/main/add_student.js') }}"></script>
    <script src="{{ asset('global_assets/js/main/manage_admissions.js') }}"></script>

    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.colVis.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"
        integrity="sha384-e0Ri0eHb9NvcGhrkvq6xY4fZG+W5dNg6p+orHHCfh/0kBh1sZ/Nprf3Wc7lBlTR1" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"
        integrity="sha384-+YQ4HkS8e5FGG3C2FPJdK0H6N2KAy4LHCpzlVZ81L/Pdf/0I7p7NuOSX2Ulm6oxk" crossorigin="anonymous">
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>


    <script>
        $(document).ready(function () {
    var table = $('#studentTable').DataTable({
        dom: 'Bfrtip', // Ensure this includes 'B' for buttons and 'frtip' for filter, pagination, etc.
        lengthMenu: [[5, 10, 25, 50, 100, -1], [5, 10, 25, 50, 100, "All"]],
        buttons: [
            {
                extend: 'csv',
                text: 'CSV',
                exportOptions: {
                    columns: ':visible:not(:last-child)',
                    modifier: {
                        search: 'applied',
                        order: 'applied'
                    }
                },
                title: 'Tiger Enterprises',
                messageTop: 'Employee Data 2024'
            },
            {
                extend: 'excel',
                text: 'Excel',
                exportOptions: {
                    columns: ':visible:not(:last-child)',
                    modifier: {
                        search: 'applied',
                        order: 'applied'
                    }
                },
                title: 'Tiger Enterprises',
                messageTop: 'Employee Data 2024'
            },
            {
                extend: 'pdf',
                text: 'PDF',
                exportOptions: {
                    columns: ':visible:not(:last-child)',
                    modifier: {
                        search: 'applied',
                        order: 'applied'
                    }
                },
                title: 'Tiger Enterprises Mbuku',
                messageTop: 'Employee Data 2024',
                customize: function (doc) {
                    doc.content.unshift({
                        text: 'Tiger Enterprises Kenya',
                        alignment: 'center',
                        margin: [0, 0, 0, 10]
                    });
                    doc.footer = function (currentPage, pageCount) {
                        return {
                            text: 'Page ' + currentPage + ' of ' + pageCount,
                            alignment: 'center'
                        };
                    };
                }
            },
            {
                extend: 'print',
                text: 'Print',
                exportOptions: {
                    columns: ':visible:not(:last-child)',
                    modifier: {
                        search: 'applied',
                        order: 'applied'
                    }
                },
                title: 'Tiger Enterprises',
                messageTop: 'Employee Data 2024',
                customize: function (win) {
                    $(win.document.body)
                        .css('font-size', '10pt')
                        .prepend(
                            `<div style="text-align: center; margin: 10px 0;">
                                <img src="data:image/png;base64,YOUR_BASE64_ENCODED_LOGO" style="max-width:100px;" />
                                <h2>Tiger Enterprises Kenya</h2>
                             </div>`
                        );
                    $(win.document.body).find('table')
                        .addClass('compact')
                        .css('font-size', 'inherit');
                }
            },
            'colvis'
        ],
        responsive: true,
        columnDefs: [{
            targets: -1,
            data: null,
            defaultContent: `
                <div class="actions-dropdown" style="position: relative;">
                    <button class="breadcrumb-icon">☰</button>
                    <div class="dropdown-menu">
                        <a href="#" class="edit">Edit</a><br>
                        <a href="#" class="delete">Delete</a><br>
                        <a href="#" class="view-report">View Report</a>
                    </div>
                </div>`
        }]
    });

    // Existing functionality for dropdown actions
    $('#studentTable tbody').on('click', '.breadcrumb-icon', function (e) {
        e.stopPropagation();
        var dropdownMenu = $(this).siblings('.dropdown-menu');
        $('.dropdown-menu').not(dropdownMenu).hide();
        dropdownMenu.toggle();
    });

    $(document).click(function () {
        $('.dropdown-menu').hide();
    });

    $('#studentTable tbody').on('click', '.edit', function () {
        var data = table.row($(this).parents('tr')).data();
        $('#modal').show().css({ top: $(this).offset().top, left: $(this).offset().left });
        alert('Edit ' + data[0]); // Replace with actual edit functionality
    });

    $('#studentTable tbody').on('click', '.delete', function () {
        var data = table.row($(this).parents('tr')).data();
        if (confirm('Are you sure you want to delete ' + data[0] + '?')) {
            table.row($(this).parents('tr')).remove().draw();
        }
    });

    $('#studentTable tbody').on('click', '.view-report', function () {
        var data = table.row($(this).parents('tr')).data();
        generatePDFReport(data);
    });

    // Function to generate a single PDF report
    function generatePDFReport(data) {
        var iframe = document.createElement('iframe');
        iframe.style.position = 'absolute';
        iframe.style.width = '0px';
        iframe.style.height = '0px';
        iframe.style.border = 'none';
        document.body.appendChild(iframe);

        var iframeDoc = iframe.contentDocument || iframe.contentWindow.document;

        iframeDoc.open();
        iframeDoc.write(`
            <!DOCTYPE html>
            <html lang="en">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Student Report</title>
                <style>
                    body {
                        font-family: Arial, sans-serif;
                        margin: 0;
                        padding: 0;
                    }
                    .container {
                        width: 80%;
                        margin: 20px auto;
                        padding: 20px;
                        border: 1px solid #ccc;
                        border-radius: 8px;
                        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
                        background-color: #f9f9f9;
                    }
                    .header, .footer {
                        text-align: center;
                        margin-bottom: 20px;
                    }
                    .header img {
                        max-width: 100px;
                    }
                    .report-title {
                        font-size: 24px;
                        font-weight: bold;
                        margin-bottom: 10px;
                    }
                    .details {
                        margin-bottom: 20px;
                    }
                    .details table {
                        width: 100%;
                        border-collapse: collapse;
                    }
                    .details table th, .details table td {
                        border: 1px solid #ddd;
                        padding: 8px;
                        text-align: left;
                    }
                    .details table th {
                        background-color: #f4f4f4;
                    }
                </style>
            </head>
            <body>
                <div class="container">
                    <div class="header">
                        <img src="YOUR_LOGO_URL" alt="Company Logo"> <!-- Update with your logo -->
                        <div class="report-title">Student Report</div>
                    </div>
                    <div class="details">
                        <table>
                            <tr>
                                <th>Admission</th>
                                <td>${data[0]}</td>
                            </tr>
                            <tr>
                                <th>Student Photo</th>
                                <td>${data[1]}</td>
                            </tr>
                            <tr>
                                <th>Name</th>
                                <td>${data[2]}</td>
                            </tr>
                            <tr>
                                <th>Gender</th>
                                <td>${data[3]}</td>
                            </tr>
                            <tr>
                                <th>Class</th>
                                <td>${data[4]}</td>
                            </tr>
                            <tr>
                                <th>Section</th>
                                <td>${data[5]}</td>
                            </tr>
                            <tr>
                                <th>Status</th>
                                <td>${data[6]}</td>
                            </tr>
                            <tr>
                                <th>Parent Name</th>
                                <td>${data[7]}</td>
                            </tr>
                            <tr>
                                <th>Parent Contact</th>
                                <td>${data[8]}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="footer">
                        &copy; 2024 Tiger Enterprises
                    </div>
                </div>
            </body>
            </html>
        `);
        iframeDoc.close();

        setTimeout(function () {
            iframe.contentWindow.print();
        }, 500);
    }

    // Function to generate multiple PDF reports based on row range
    $('#generateReports').on('click', function () {
        var startRow = parseInt($('#startRow').val(), 10);
        var endRow = parseInt($('#endRow').val(), 10);
        var totalRows = table.rows().count();
        
        // Validation
        if (isNaN(startRow) || isNaN(endRow) || startRow < 1 || endRow < 1 || startRow > totalRows || endRow > totalRows || startRow > endRow) {
            alert('Please enter a valid row range.');
            return;
        }
        
        // Generate reports for each row in the range
        var dataToGenerate = [];
        for (var i = startRow - 1; i < endRow; i++) {
            var rowData = table.row(i).data();
            dataToGenerate.push(rowData);
            generatePDFReport(rowData); // Generate report for each row
        }
    });
});



    </script>
    @endsection