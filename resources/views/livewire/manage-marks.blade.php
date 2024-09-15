<div class="card">
    <div class="card-header header-elements-inline">
        <h5 class="card-title"><i class="icon-books mr-2"></i> Manage Exam Marks</h5>
        {!! Qs::getPanelOptions() !!}
    </div>

    <div class="card-body">
        @livewire('marks-selector')
    </div>
</div>
