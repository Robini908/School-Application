@extends('layouts.master')
@section('page_title', 'Manage and Admit Students')
@section('content')
<link href=" {{ asset('assets/css/admit_student.css') }}" rel="stylesheet" type="text/css">
<div class="card">
    <div class="card-header bg-white header-elements-inline">
        {!! Qs::getPanelOptions() !!}
    </div>
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

        <div class="tab-content mt-3">
            <!-- Manage Students Tab -->
            @livewire('manage-students')
            @livewire('admit-student')


            <div class="tab-pane fade" id="bulk-admit">
                <div class="card container">
                    <form method="POST" action="" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="bulk_files">Upload Bulk Files:</label>
                            <input type="file" name="bulk_files[]" id="bulk_files" class="form-control-file" multiple>
                            <small class="form-text text-muted">Upload Excel, Word, or PDF files.</small>
                        </div>
                        <button type="submit" class="btn btn-md btn-primary m-1 float-right">Upload Files</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endsection
    @section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/vue@2/dist/vue.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="{{ asset('global_assets/js/main/add_student.js') }}"></script>
    <script src="{{ asset('global_assets/js/main/manage_admissions.js') }}"></script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.7.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.2.2/jszip.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.print.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.colVis.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.68/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.68/vfs_fonts.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"
        integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js"
        integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous">
    </script>
    <script>
        

        $(document).ready(function() {
        var table = $('#studentTable').DataTable({
            dom: 'Bfrtip',
            lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
            buttons: [
                
                {
                    extend: 'csv',
                    text: 'CSV',
                    exportOptions: {
                        columns: ':visible:not(:last-child)',
                        modifier: {
                            search: 'applied',  // Export only filtered data
                            order: 'applied'    // Export data in the current order
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
                            search: 'applied',  // Export only filtered data
                            order: 'applied'    // Export data in the current order
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
                            search: 'applied',  // Export only filtered data
                            order: 'applied'    // Export data in the current order
                        }
                    },
                    title: 'Tiger Enterprises Mbuku',
                    messageTop: 'Employee Data 2024',
                    customize: function(doc) {
                        doc.header = {
                            text: 'Tiger Enterprises Kenya',
                            alignment: 'center',
                            margin: [0, 0, 0, 10],
                            image: 'data:images/mbukulogo.png;base64,YOUR_BASE64_ENCODED_LOGO' // Replace with your base64 encoded image
                        };
                        doc.footer = {
                            text: 'Page ' + doc.pageNumber + ' of ' + doc.pageCount,
                            alignment: 'center'
                        };
                    }
                },
                {
                    extend: 'print',
                    text: 'Print',
                    exportOptions: {
                        columns: ':visible:not(:last-child)',
                        modifier: {
                            search: 'applied',  // Export only filtered data
                            order: 'applied'    // Export data in the current order
                        }
                    },
                    title: 'Tiger Enterprises',
                    messageTop: 'Employee Data 2024',
                    customize: function(doc) {
                        doc.header = {
                            text: 'Tiger Enterprises Kenya',
                            alignment: 'center',
                            margin: [0, 0, 0, 10],
                            image: 'data:images/mbukulogo.png;base64,YOUR_BASE64_ENCODED_LOGO' // Replace with your base64 encoded image
                        };
                        doc.footer = {
                            text: 'Page ' + doc.pageNumber + ' of ' + doc.pageCount,
                            alignment: 'center'
                        };
                    }
                },
                'colvis'
            ],
            columnDefs: [{
                targets: -1,
                data: null,
                defaultContent: '<div class="actions-dropdown"><span class="breadcrumb-icon">☰</span><div class="dropdown-menu"><a href="#" class="edit">Edit</a><a href="#" class="delete">Delete</a><a href="#" class="view-report">View Report</a></div></div>'
            }]
        });
    });


    $('#studentTable tbody').on('click', '.actions-dropdown', function(e) {
            e.stopPropagation();
            $(this).find('.dropdown-menu').toggle();
        });

        // Close dropdown when clicking outside
        $(document).click(function() {
            $('.dropdown-menu').hide();
        });

        // Handle edit
        $('#studentTable tbody').on('click', '.edit', function() {
            var data = table.row($(this).parents('tr')).data();
            alert('Edit ' + data[0]); // Replace with actual edit functionality
        });

        // Handle delete
        $('#studentTable tbody').on('click', '.delete', function() {
            var data = table.row($(this).parents('tr')).data();
            if (confirm('Are you sure you want to delete ' + data[0] + '?')) {
                table.row($(this).parents('tr')).remove().draw();
            }
        });

        // Handle view report
        $('#studentTable tbody').on('click', '.view-report', function() {
            var data = table.row($(this).parents('tr')).data();
            generatePDFReport(data); // Replace with actual PDF generation
            
        });




    </script>
    @endsection