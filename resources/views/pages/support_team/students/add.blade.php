@extends('layouts.master')
@section('page_title', 'Manage and Admit Students')
@section('content')

<div class="card">
    <div class="card-body" x-data="{ activeTab: 'manage-students' }">
        <ul class="nav nav-tabs nav-tabs-highlight p-3">
            <!-- Manage Admissions Tab -->
            <li class="nav-item">
                <a href="#" @click.prevent="activeTab = 'manage-students'" 
                    :class="{ 'active': activeTab === 'manage-students' }" class="nav-link">Manage Admissions</a>
            </li>

            <!-- Dropdown for Additional Actions -->
            <li class="nav-item dropdown">
                <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">More Actions</a>
                <div class="dropdown-menu">
                    <button class="dropdown-item" type="button" @click.prevent="activeTab = 'admit-student'">
                        <i class="icon-user-plus"></i> Admit New Student
                    </button>
                    <button class="dropdown-item" type="button" @click.prevent="activeTab = 'bulk-admit'">
                        <i class="icon-clipboard-list"></i> Manage Suspensions  <!-- Icon for managing expulsions -->
                    </button>
                    <button class="dropdown-item" type="button" @click.prevent="activeTab = 'addbulk'">
                        <i class="icon-upload"></i> Add Bulk  <!-- Icon for adding bulk -->
                    </button>
                </div>
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
                @livewire('manage-suspensions')
            </div>

            <!-- Add Bulk Tab Content -->
            <div x-show="activeTab === 'addbulk'" class="p-4">
                @livewire('addbulk')
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('tabManager', () => ({
            activeTab: 'manage-students',
            showTab(tab) {
                this.activeTab = tab;
            }
        }));
    });
</script>

@endsection
