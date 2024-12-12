<div>
    <div  x-data="{ activeTab: 'classes-selection-off' }">
        <ul class="nav nav-tabs nav-tabs-highlight p-3">
            <!-- Manage Admissions Tab -->
            <li class="nav-item">
                <a href="#" @click.prevent="activeTab = 'classes-selection-off'"
                    :class="{ 'active': activeTab === 'classes-selection-off' }" class="nav-link">Exam Analysis</a>
            </li>

            <!-- Dropdown for Additional Actions -->
            <li class="nav-item dropdown">
                <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">More Actions</a>
                <div class="dropdown-menu">
                    <button class="dropdown-item" type="button" @click.prevent="activeTab = 'classes-selection-on'">
                        <i class="icon-user-plus"></i> Classes with Subject selection Enabled
                    </button>
                </div>
            </li>
        </ul>

        <div class="tab-content" style="margin-top:-50px;">
            <!-- Manage Students Tab Content -->
            <div x-show="activeTab === 'classes-selection-off'" class="p-4">

                <livewire:combination-formula lazy/>
            </div>

            <!-- Admit New Student Tab Content -->
            <div x-show="activeTab === 'classes-selection-on'" class="p-4">
                <livewire:exam-analysis-subject-selection-enabled lazy/>
            </div>
        </div>
    </div>
</div>