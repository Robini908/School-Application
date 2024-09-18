@extends('layouts.master')

@section('page_title', 'Manage Exams')

@section('content')
<div class="container-fluid p-0">
    <div x-data="{ mainTab: 'exam-management', subTab: 'exam-list' }" class="bg-white shadow-lg rounded-lg border border-gray-200">
        <!-- Main Tabs (Exam Management, Grading System) -->
        <ul class="nav nav-tabs border-b border-gray-300 mb-6">
            <li class="nav-item">
                <a href="#" @click.prevent="mainTab = 'exam-management'; subTab = 'exam-list'"
                   :class="{ 'active': mainTab === 'exam-management' }"
                   class="nav-link rounded-md px-4 py-2 transition-colors duration-300"
                   :class="{ 'bg-gray-200 text-blue-700': mainTab === 'exam-management', 'text-gray-600': mainTab !== 'exam-management' }">
                   <i class="fas fa-clipboard-list"></i> Exam Management
                </a>
            </li>
            <li class="nav-item">
                <a href="#" @click.prevent="mainTab = 'grading-system'; subTab = 'grade-list'"
                   :class="{ 'active': mainTab === 'grading-system' }"
                   class="nav-link rounded-md px-4 py-2 transition-colors duration-300"
                   :class="{ 'bg-gray-200 text-blue-700': mainTab === 'grading-system', 'text-gray-600': mainTab !== 'grading-system' }">
                   <i class="fas fa-chart-bar"></i> Grading System
                </a>
            </li>
        </ul>

        <!-- Content for Main Tabs -->
        <div class="p-4">
            <!-- Exam Management Tab -->
            <div x-show="mainTab === 'exam-management'" class="tab-content">
                <!-- Sub-tabs for Exam Management -->
                <ul class="nav nav-pills mb-6">
                    <li class="nav-item">
                        <a href="#" @click.prevent="subTab = 'exam-list'"
                           :class="{ 'active': subTab === 'exam-list' }"
                           class="nav-link rounded-md px-4 py-2 transition-colors duration-300"
                           :class="{ 'bg-gray-100 text-blue-600': subTab === 'exam-list', 'text-gray-500': subTab !== 'exam-list' }">
                           <i class="fas fa-list"></i> Exam List
                        </a>
                    </li>
                    @if(Qs::userIsTeamSAT())
                    <li class="nav-item">
                        <a href="#" @click.prevent="subTab = 'marks'"
                           :class="{ 'active': subTab === 'marks' }"
                           class="nav-link rounded-md px-4 py-2 transition-colors duration-300"
                           :class="{ 'bg-gray-100 text-blue-600': subTab === 'marks', 'text-gray-500': subTab !== 'marks' }">
                           <i class="fas fa-tachometer-alt"></i> Marks
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#" @click.prevent="subTab = 'marksheet'"
                           :class="{ 'active': subTab === 'marksheet' }"
                           class="nav-link rounded-md px-4 py-2 transition-colors duration-300"
                           :class="{ 'bg-gray-100 text-blue-600': subTab === 'marksheet', 'text-gray-500': subTab !== 'marksheet' }">
                           <i class="fas fa-file-alt"></i> Marksheet
                        </a>
                    </li>
                    @endif
                    <li class="nav-item">
                        <a href="#" @click.prevent="subTab = 'exam-analysis'"
                           :class="{ 'active': subTab === 'exam-analysis' }"
                           class="nav-link rounded-md px-4 py-2 transition-colors duration-300"
                           :class="{ 'bg-gray-100 text-blue-600': subTab === 'exam-analysis', 'text-gray-500': subTab !== 'exam-analysis' }">
                           <i class="fas fa-chart-line"></i> Exam Analysis
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#" @click.prevent="subTab = 'reports'"
                           :class="{ 'active': subTab === 'reports' }"
                           class="nav-link rounded-md px-4 py-2 transition-colors duration-300"
                           :class="{ 'bg-gray-100 text-blue-600': subTab === 'reports', 'text-gray-500': subTab !== 'reports' }">
                           <i class="fas fa-file"></i> Reports
                        </a>
                    </li>
                </ul>

                <!-- Content for Sub-tabs of Exam Management -->
                <div class="tab-content transition-opacity duration-500 ease-in-out">
                    <div x-show="subTab === 'exam-list'" class="p-4 bg-gray-50 rounded-lg border border-gray-200">
                        <!-- Content for Exam List -->
                        @livewire('exam-list')
                    </div>
                    @if(Qs::userIsTeamSAT())
                    <div x-show="subTab === 'marks'" class="p-4 bg-gray-50 rounded-lg border border-gray-200">
                        <!-- Content for Marks -->
                        @livewire('manage-marks')
                    </div>
                    <div x-show="subTab === 'marksheet'" class="p-4 bg-gray-50 rounded-lg border border-gray-200">
                        <!-- Content for Marksheet -->
                        @livewire('marks-bulk')
                    </div>
                    @endif
                    <div x-show="subTab === 'exam-analysis'" class="p-4 bg-gray-50 rounded-lg border border-gray-200">
                        <!-- Content for Exam Analysis -->
                        @livewire('exam-analysis')
                    </div>
                    <div x-show="subTab === 'reports'" class="p-4 bg-gray-50 rounded-lg border border-gray-200">
                        {{-- <!-- Content for Reports --> --}}
                        @livewire('exam-report')
                    </div>
                </div>
            </div>

            <!-- Grading System Tab -->
            <div x-show="mainTab === 'grading-system'" class="tab-content">
                <!-- Sub-tabs for Grading System -->
                <ul class="nav nav-pills mb-6">
                    <li class="nav-item">
                        <a href="#" @click.prevent="subTab = 'grade-list'"
                           :class="{ 'active': subTab === 'grade-list' }"
                           class="nav-link rounded-md px-4 py-2 transition-colors duration-300"
                           :class="{ 'bg-gray-100 text-blue-600': subTab === 'grade-list', 'text-gray-500': subTab !== 'grade-list' }">
                           <i class="fas fa-graduation-cap"></i> Grade List
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#" @click.prevent="subTab = 'grade-scale'"
                           :class="{ 'active': subTab === 'grade-scale' }"
                           class="nav-link rounded-md px-4 py-2 transition-colors duration-300"
                           :class="{ 'bg-gray-100 text-blue-600': subTab === 'grade-scale', 'text-gray-500': subTab !== 'grade-scale' }">
                           <i class="fas fa-equals"></i> Grade Scale
                        </a>
                    </li>
                </ul>

                <!-- Content for Sub-tabs of Grading System -->
                <div class="tab-content transition-opacity duration-500 ease-in-out">
                    <div x-show="subTab === 'grade-list'" class="p-4 bg-gray-50 rounded-lg border border-gray-200">
                        <!-- Content for Grade List -->
                        @livewire('manage-grading')
                    </div>
                    <div x-show="subTab === 'grade-scale'" class="p-4 bg-gray-50 rounded-lg border border-gray-200">
                        <!-- Content for Grade Scale -->
                        <p>Coming soon</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

