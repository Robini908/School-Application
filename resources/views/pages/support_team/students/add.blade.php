@extends('layouts.master')
@section('page_title', 'Manage and Admit Students')
@section('content')
<link href="{{ asset('assets/css/admit_student.css') }}" rel="stylesheet" type="text/css">

<div class="card">
    {{-- <div class="card-header bg-white header-elements-inline">
        {!! Qs::getPanelOptions() !!}
    </div>--}}

    <div class="card-body" x-data="{ activeTab: 'manage-students' }">
        <ul class="nav nav-tabs nav-tabs-highlight p-3">
            <!-- Manage Admissions Tab -->
            <li class="nav-item">
                <a href="#" @click.prevent="activeTab = 'manage-students'"
                    :class="{ 'active': activeTab === 'manage-students' }" class="nav-link">Manage Admissions</a>
            </li>

            <!-- Admit New Student Tab -->
            <li class="nav-item">
                <a href="#" @click.prevent="activeTab = 'admit-student'"
                    :class="{ 'active': activeTab === 'admit-student' }" class="nav-link">Admit New Student</a>
            </li>

            <!-- Bulk Admit Tab -->
            <li class="nav-item">
                <a href="#" @click.prevent="activeTab = 'bulk-admit'" :class="{ 'active': activeTab === 'bulk-admit' }"
                    class="nav-link">Bulk Admit</a>
            </li>
        </ul>

        <div class="tab-content" style="margin-top:-50px;">
            <!-- Manage Students Tab Content -->
            <div x-show="activeTab === 'manage-students'" class="p-4">
                @livewire('manage-students')
            </div>

            <!-- Admit New Student Tab Content -->
            <div x-show="activeTab === 'admit-student'" class="p-4">
                @livewire('admit-student')
            </div>

            <!-- Bulk Admit Tab Content -->
            <div x-show="activeTab === 'bulk-admit'" class="p-4">
                @livewire('addbulk')
            </div>
        </div>
    </div>
</div>
<div id="reportModal" class="modal">
    <div class="modal-content" style="position: relative; top:5px; margin: auto; width: 70%;">
        <div class="bg-success">
            {{-- <button id="printReportBtn" class="m-1 btn-primary">Print Report</button>--}}
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

<script src="{{ asset('global_assets/js/main/add_student.js') }}"></script>
<script src="{{ asset('global_assets/js/main/manage_admissions.js') }}"></script>




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