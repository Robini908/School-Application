@extends('layouts.master')

@section('page_title', 'Manage Exam Marks Allocation')

@section('content')
<div class="p-2">
    <a href="{{ route('exams.set') }}" class="btn width-auto btn-primary">Back to Exams</a>
</div>

<div class="card">
    <div class="card-header header-elements-inline">
        <h6 class="card-title">Assign Exam Marks</h6>
        {!! Qs::getPanelOptions() !!}
    </div>

    <div class="card-body">
        <!-- Alert for Instructions -->
        <div id="alert" class="alert alert-info alert-dismissible fade show" role="alert">
            <strong>Important!</strong>
            <span id="alertMessage">Please ensure all marks are allocated correctly before final submission...
                <button id="viewMoreBtn" class="btn btn-link p-0">View More</button>
            </span>
            <div id="moreInfo" class="mt-2" style="display: none;">
                <p>Please ensure that you follow the guidelines:</p>
                <ul class="mb-0">
                    <li>Ensure all subjects are assigned marks for each student in each class to prevent the
                        <strong>missing marks problems.</strong>
                    </li>
                    <li>Double-check the marks for accuracy.</li>
                    <li>Consult with subject teachers if needed when assigning marks in bulk.</li>
                </ul>
                <button id="lessInfoBtn" class="btn btn-link p-0">Less</button>
            </div>
            <button type="button" class="close" id="closeAlert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>

        <ul class="nav nav-tabs nav-tabs-highlight p-3" style="margin-bottom: 1rem;">
            <li class="nav-item">
                <a href="#bulk-exam" class="nav-link active" onclick="showTab(event, 'bulk-exam')">Bulk Marks Allocation</a>
            </li>
            <div class="list-icons mb-4">
                <div class="dropdown">
                    <a href="#" class="list-icons-item" data-toggle="dropdown">
                        <i class="icon-menu9"></i> More Actions
                    </a>

                    <div class="dropdown-menu dropdown-menu-left">
                        <button class="dropdown-item" type="button" onclick="showTab(event, 'subject-wise-exam')">
                            <i class="icon-pencil"></i> Assign Marks Subject-wise
                        </button>
                        <button class="dropdown-item" type="button" onclick="showTab(event, 'mark-list-management')">
                            <i class="icon-list"></i> Manage Marks List
                        </button>
                        <button class="dropdown-item" type="button" onclick="showTab(event, 'exam-analysis')">
                            <i class="icon-bar-chart"></i> Exam Analysis
                        </button>
                        <button class="dropdown-item" type="button" onclick="showTab(event, 'subject-champions')">
                            <i class="icon-crown"></i> Subject Champions
                        </button>
                        <button class="dropdown-item" type="button" onclick="showTab(event, 'subject-analysis')">
                            <i class="icon-graph"></i> Subject Analysis
                        </button>
                    </div>
                </div>
            </div>
        </ul>

        <div class="tab-content">
            <!-- Bulk Marks Allocation Tab -->
            <div class="tab-pane fade show active" id="bulk-exam">
                <livewire:assign-batch-marks lazy="on-load" />
            </div>

            <!-- Assign Marks Subject-wise Tab -->
            <div class="tab-pane fade" id="subject-wise-exam">
                <livewire:assign-exams-subjectwise />
            </div>

            <!-- Manage Marks List Tab -->
            <div class="tab-pane fade" id="mark-list-management">
                <livewire:mark-list-management />
            </div>

            <!-- Exam Analysis Tab -->
            <div class="tab-pane fade" id="exam-analysis">
                @livewire('combination-formula') 
            </div>

            <!-- Subject Champions Tab -->
            <div class="tab-pane fade" id="subject-champions">
                <livewire:subject-champions />
            </div>

            <!-- Subject Analysis Tab -->
            <div class="tab-pane fade" id="subject-analysis">
                <livewire:subject-analysis />
            </div>
        </div>
    </div>
</div>

<script>
    function showTab(event, tabId) {
        // Prevent default anchor behavior
        event.preventDefault();

        // Hide all tab content
        const tabs = document.querySelectorAll('.tab-pane');
        tabs.forEach(tab => {
            tab.classList.remove('show', 'active');
        });

        // Deactivate all nav links
        const links = document.querySelectorAll('.nav-link');
        links.forEach(link => {
            link.classList.remove('active');
        });

        // Activate the clicked tab and its content
        document.getElementById(tabId).classList.add('show', 'active');
        event.target.classList.add('active');
    }

    // Show more information in the alert
    document.getElementById('viewMoreBtn').addEventListener('click', function() {
        document.getElementById('alertMessage').style.display = 'none';
        document.getElementById('moreInfo').style.display = 'block';
    });

    // Show less information in the alert
    document.getElementById('lessInfoBtn').addEventListener('click', function() {
        document.getElementById('alertMessage').style.display = 'block';
        document.getElementById('moreInfo').style.display = 'none';
    });

    // Close alert
    document.getElementById('closeAlert').addEventListener('click', function() {
        document.getElementById('alert').style.display = 'none';
    });
</script>
@endsection
