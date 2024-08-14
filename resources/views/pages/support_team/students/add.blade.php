@extends('layouts.master')
@section('page_title', 'Manage and Admit Students')
@section('content')
<link href=" {{ asset('assets/css/admit_student.css') }}" rel="stylesheet" type="text/css">
<div class="card">
   {{-- <div class="card-header bg-white header-elements-inline">
        {!! Qs::getPanelOptions() !!}
    </div>--}}
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
            <div class="tab-pane fade show active" id="manage-students">
                @livewire('manage-students')
            </div>
            <div class="tab-pane fade " id="admit-student">
                @livewire('admit-student')
            </div>



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
    <div id="reportModal" class="modal">
        <div class="modal-content" style="position: relative; top:5px; margin: auto; width: 70%;">
            <div class="bg-success">
              {{--  <button id="printReportBtn" class="m-1 btn-primary">Print Report</button>--}}
                <span class="close">&times;</span>
            </div>
            <div id="reportContent"></div>           
        </div>
    </div>


    <div id="pdfModal" style="display:none;">
        <iframe id="pdfFrame" width="100%" height="500px"></iframe>
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

        // Handle view report
        $('#studentTable tbody').on('click', '.view-report', function() {
            var data = table.row($(this).parents('tr')).data();
            generatePDFReport(data); // Replace with actual PDF generation
            
        });




</script>
@endsection