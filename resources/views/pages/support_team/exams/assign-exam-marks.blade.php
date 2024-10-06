@extends('layouts.master')

@section('page_title', 'Manage Exam Marks Allocation')

@section('content')
<div class="p-2">
    <a href="{{ route('exams.set') }}" class="btn btn-primary">Back to Exams</a>
</div>

<div class="card">
    <div class="card-header header-elements-inline">
        <h6 class="card-title">Assign Exam Marks</h6>
        {!! Qs::getPanelOptions() !!}
    </div>

    <div class="card-body">
        <!-- Alert for Instructions -->
        <div id="alert" class="alert alert-info alert-dismissible fade show mb-4" role="alert">
            <strong>Important!</strong>
            <span id="alertMessage">Please ensure all marks are allocated correctly before final submission...
                <button class="btn btn-link p-0" id="toggleMoreInfo">View More</button>
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
                <button class="btn btn-link p-0" id="closeMoreInfo">Less</button>
            </div>
            <button type="button" class="close" id="closeAlert" aria-label="Close" data-dismiss="alert">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>

        <ul class="nav nav-tabs nav-tabs-highlight" id="tabLinks">
            <li class="nav-item">
                <a href="#" class="nav-link active" data-tab="bulk-exam">Bulk Marks Allocation</a>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="moreActionsDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    More Actions
                </a>
                <div class="dropdown-menu" aria-labelledby="moreActionsDropdown">
                    <button class="dropdown-item" data-tab="subject-wise-exam">
                        <i class="fas fa-book" style="color: #007bff; margin-right: 10px;"></i>
                        <span>Assign Marks Subject-wise</span>
                    </button>
                    <button class="dropdown-item" data-tab="mark-list-management">
                        <i class="fas fa-list-alt" style="color: #28a745; margin-right: 10px;"></i>
                        <span>Manage Marks List</span>
                    </button>
                    <button class="dropdown-item" data-tab="exam-analysis">
                        <i class="fas fa-chart-line" style="color: #ffc107; margin-right: 10px;"></i>
                        <span>Exam Analysis</span>
                    </button>
                    <button class="dropdown-item" data-tab="subject-champions">
                        <i class="fas fa-trophy" style="color: #fd7e14; margin-right: 10px;"></i>
                        <span>Subject Champions</span>
                    </button>
                    <button class="dropdown-item" data-tab="subject-analysis">
                        <i class="fas fa-pie-chart" style="color: #6f42c1; margin-right: 10px;"></i>
                        <span>Subject Analysis</span>
                    </button>
                </div>
            </li>
        </ul>

        <div class="tab-content">
            <!-- Bulk Marks Allocation Tab -->
            <div class="tab-pane active" id="bulk-exam">
                <livewire:assign-batch-marks lazy="on-load" />
            </div>

            <!-- Assign Marks Subject-wise Tab -->
            <div class="tab-pane" id="subject-wise-exam" style="display: none;">
                <livewire:assign-exams-subjectwise />
            </div>

            <!-- Manage Marks List Tab -->
            <div class="tab-pane" id="mark-list-management" style="display: none;">
                <livewire:mark-list-management />
            </div>

            <!-- Exam Analysis Tab -->
            <div class="tab-pane" id="exam-analysis" style="display: none;">
                @livewire('combination-formula') 
            </div>

            <!-- Subject Champions Tab -->
            <div class="tab-pane" id="subject-champions" style="display: none;">
                <livewire:subject-champions />
            </div>

            <!-- Subject Analysis Tab -->
            <div class="tab-pane" id="subject-analysis" style="display: none;">
                <livewire:subject-analysis />
            </div>
        </div>
    </div>
</div>

<script>
    // Function to switch tabs
    function switchTab(tabId) {
        const tabContents = document.querySelectorAll('.tab-pane');
        const tabLinks = document.querySelectorAll('.nav-link');

        // Hide all tab contents and remove 'active' class from all tab links
        tabContents.forEach(content => {
            content.style.display = 'none';
            content.classList.remove('active');
        });
        tabLinks.forEach(link => {
            link.classList.remove('active');
        });

        // Show the selected tab content and add 'active' class to the corresponding link
        const selectedTab = document.getElementById(tabId);
        if (selectedTab) {
            selectedTab.style.display = 'block';
            selectedTab.classList.add('active');
        }

        const activeLink = Array.from(tabLinks).find(link => link.dataset.tab === tabId);
        if (activeLink) {
            activeLink.classList.add('active');
        }
    }

    // Event listeners for tab clicks
    document.querySelectorAll('#tabLinks a, #tabLinks .dropdown-item').forEach(tab => {
        tab.addEventListener('click', function (event) {
            event.preventDefault();
            const tabId = this.dataset.tab || this.closest('.dropdown-item').dataset.tab;
            switchTab(tabId);
        });
    });

    // Set default tab on page load
    document.addEventListener('DOMContentLoaded', () => {
        switchTab('bulk-exam');  // Default tab to show
    });

    // Event listener for toggling additional info
    document.getElementById('toggleMoreInfo').addEventListener('click', function () {
        const moreInfo = document.getElementById('moreInfo');
        moreInfo.style.display = (moreInfo.style.display === 'none') ? 'block' : 'none';
    });

    // Close alert
    document.getElementById('closeAlert').addEventListener('click', function () {
        document.getElementById('alert').style.display = 'none';
    });

    // Close more info section
    document.getElementById('closeMoreInfo').addEventListener('click', function () {
        document.getElementById('moreInfo').style.display = 'none';
    });
</script>

@endsection
