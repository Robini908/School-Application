<!-- Main Sidebar Container -->
<aside class="fixed inset-y-4 left-4 bg-white w-64 rounded-xl shadow-xl transition-transform duration-300 transform md:translate-x-0 z-50" 
       :class="{'translate-x-0': sidebarOpen, '-translate-x-full': !sidebarOpen}"
       x-data="{ activeMenu: null }"
       x-cloak>
    
    <!-- Brand Logo -->
    <div class="flex items-center justify-between h-16 bg-green-700 px-4 rounded-t-xl">
        <a href="{{ route('dashboard') }}" class="flex items-center">
            <span class="text-white text-lg font-semibold">MBUKU ERP-1.0</span>
        </a>
        <button @click="sidebarOpen = false" class="md:hidden text-white hover:text-gray-200">
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>

    <!-- Sidebar Menu -->
    <div class="overflow-y-auto h-[calc(100vh-8rem)] rounded-b-xl">
        <nav class="mt-2 px-2 space-y-1">
            <!-- Dashboard -->
            <a href="{{ route('dashboard') }}" 
               class="{{ Request::is('dashboard*') ? 'bg-green-100 text-green-900' : 'text-gray-600 hover:bg-green-50 hover:text-green-900' }} group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                <svg class="{{ Request::is('dashboard*') ? 'text-green-500' : 'text-gray-400 group-hover:text-green-500' }} mr-3 h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                <span>
                    @if (Qs::userIsTeamSA())
                        Super Admin Dashboard
                    @elseif (Qs::userIsAdmin())
                        Admin Dashboard
                    @elseif (Qs::userIsTeacher())
                        Teacher Dashboard
                    @elseif (Qs::userIsParent())
                        Parent Dashboard
                    @else
                        Dashboard
                    @endif
                </span>
            </a>

            <!-- Registration Section -->
            @if (Qs::userIsTeamSAT() || Qs::userIsParent() || Qs::userIsStudent())
            <div>
                <button @click="activeMenu = activeMenu === 'registration' ? null : 'registration'" 
                        class="text-gray-600 hover:bg-green-50 hover:text-green-900 group w-full flex items-center px-2 py-2 text-sm font-medium rounded-md">
                    <svg class="text-gray-400 group-hover:text-green-500 mr-3 h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                    <span class="flex-1">
                        @if (Qs::userIsParent())
                            Student Registration
                        @else
                            Registration
                        @endif
                    </span>
                    <svg class="text-gray-400 ml-3 h-5 w-5 transform group-hover:text-green-500 transition-colors ease-in-out duration-150" 
                         :class="{'rotate-90': activeMenu === 'registration'}"
                         viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                    </svg>
                </button>
                <div x-show="activeMenu === 'registration'"
                     x-transition:enter="transition ease-out duration-100"
                     x-transition:enter-start="transform opacity-0 scale-95"
                     x-transition:enter-end="transform opacity-100 scale-100"
                     class="mt-1 pl-4 space-y-1">
                    <a href="{{ Qs::userIsParent() || Qs::userIsStudent() ? route('parent.child-class') : route('classes.index') }}"
                       class="{{ in_array(Route::currentRouteName(), ['classes.index', 'classes.edit', 'parent.child.classes']) ? 'bg-green-100 text-green-900' : 'text-gray-600 hover:bg-green-50 hover:text-green-900' }} group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                        <span>
                            @if (Qs::userIsParent() || Qs::userIsStudent())
                                Class Information
                            @else
                                Classes
                            @endif
                        </span>
                    </a>

                    <a href="{{ Qs::userIsParent() || Qs::userIsStudent() ? route('parent.child-dorm') : route('dorms.index') }}"
                       class="{{ in_array(Route::currentRouteName(), ['dorms.index', 'dorms.edit']) ? 'bg-green-100 text-green-900' : 'text-gray-600 hover:bg-green-50 hover:text-green-900' }} group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                        <span>
                            @if (Qs::userIsParent())
                                Student Dormitory
                            @else
                                Dormitories
                            @endif
                        </span>
                    </a>

                    @if (Qs::userIsTeamSA() || Qs::userIsParent())
                    <a href="{{ route('students.manage-students') }}"
                       class="{{ Route::is('students.manage-students') ? 'bg-green-100 text-green-900' : 'text-gray-600 hover:bg-green-50 hover:text-green-900' }} group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                        <span>
                            @if (Qs::userIsParent())
                                Student Admissions
                            @else
                                Admissions
                            @endif
                        </span>
                    </a>

                    <a href="{{ Qs::userIsParent() || Qs::userIsStudent() ? route('parent.child-transition-status') : route('students.promotions_demotions') }}"
                       class="{{ Route::is('students.promotions_demotions') ? 'bg-green-100 text-green-900' : 'text-gray-600 hover:bg-green-50 hover:text-green-900' }} group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                        <span>
                            @if (Qs::userIsParent())
                                Student Promotions
                            @else
                                Promotions & Demotions
                            @endif
                        </span>
                    </a>

                    <a href="{{ Qs::userIsParent() || Qs::userIsStudent() ? route('parent.child-graduation') : route('students.graduation') }}"
                       class="{{ Route::is('students.graduation') ? 'bg-green-100 text-green-900' : 'text-gray-600 hover:bg-green-50 hover:text-green-900' }} group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                        <span>
                            @if (Qs::userIsParent())
                                Student Graduation
                            @else
                                Graduation
                            @endif
                        </span>
                    </a>
                    @endif
                </div>
            </div>
            @endif

            <!-- Academics Section -->
            @if (Qs::userIsAcademic() || Qs::userIsTeamSA() || Qs::userIsParent())
            <div>
                <button @click="activeMenu = activeMenu === 'academics' ? null : 'academics'"
                        class="text-gray-600 hover:bg-green-50 hover:text-green-900 group w-full flex items-center px-2 py-2 text-sm font-medium rounded-md">
                    <svg class="text-gray-400 group-hover:text-green-500 mr-3 h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                    <span class="flex-1">
                        @if (Qs::userIsParent())
                            Student Academics
                        @elseif (Qs::userIsStudent())
                            My Academics
                        @else
                            Academics
                        @endif
                    </span>
                    <svg class="text-gray-400 ml-3 h-5 w-5 transform group-hover:text-green-500 transition-colors ease-in-out duration-150" 
                         :class="{'rotate-90': activeMenu === 'academics'}"
                         viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                    </svg>
                </button>
                <div x-show="activeMenu === 'academics'"
                     x-transition:enter="transition ease-out duration-100"
                     x-transition:enter-start="transform opacity-0 scale-95"
                     x-transition:enter-end="transform opacity-100 scale-100"
                     class="mt-1 pl-4 space-y-1">
                    <a href="{{ route('subjects.index') }}"
                       class="{{ in_array(Route::currentRouteName(), ['subjects.index', 'subjects.edit']) ? 'bg-green-100 text-green-900' : 'text-gray-600 hover:bg-green-50 hover:text-green-900' }} group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                        <span>
                            @if (Qs::userIsParent())
                                Student Subjects
                            @elseif (Qs::userIsStudent())
                                My Subjects
                            @else
                                Subjects
                            @endif
                        </span>
                    </a>

                    <a href="{{ route('grading_system.index') }}"
                       class="{{ in_array(Route::currentRouteName(), ['grading_system.index', 'grading_system.edit']) ? 'bg-green-100 text-green-900' : 'text-gray-600 hover:bg-green-50 hover:text-green-900' }} group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                        <span>Grading System</span>
                    </a>

                    <a href="{{ Qs::userIsParent() ? route('parent.child-exams') : (Qs::userIsStudent() ? route('student.my-exams') : route('exams.set')) }}"
                       class="{{ in_array(Route::currentRouteName(), ['exams.set', 'parent.child-exams', 'student.my-exams']) ? 'bg-green-100 text-green-900' : 'text-gray-600 hover:bg-green-50 hover:text-green-900' }} group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                        <span>Exam Management</span>
                    </a>

                    <a href="{{ Qs::userIsParent() ? route('parent.child-marks') : (Qs::userIsStudent() ? route('student.my-marks') : route('exams.assignExamMarks')) }}"
                       class="{{ in_array(Route::currentRouteName(), ['exams.assignExamMarks', 'parent.child-marks', 'student.my-marks']) ? 'bg-green-100 text-green-900' : 'text-gray-600 hover:bg-green-50 hover:text-green-900' }} group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                        <span>Marks Allocation</span>
                    </a>
                </div>
            </div>
            @endif

            <!-- Finance Section -->
            @if (Qs::userIsAdministrative() || Qs::userIsParent())
            <div>
                <button @click="activeMenu = activeMenu === 'finance' ? null : 'finance'"
                        class="text-gray-600 hover:bg-green-50 hover:text-green-900 group w-full flex items-center px-2 py-2 text-sm font-medium rounded-md">
                    <svg class="text-gray-400 group-hover:text-green-500 mr-3 h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span class="flex-1">
                        @if (Qs::userIsParent())
                            Student Finance
                        @else
                            Finance
                        @endif
                    </span>
                    <svg class="text-gray-400 ml-3 h-5 w-5 transform group-hover:text-green-500 transition-colors ease-in-out duration-150" 
                         :class="{'rotate-90': activeMenu === 'finance'}"
                         viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                    </svg>
                </button>
                <div x-show="activeMenu === 'finance'"
                     x-transition:enter="transition ease-out duration-100"
                     x-transition:enter-start="transform opacity-0 scale-95"
                     x-transition:enter-end="transform opacity-100 scale-100"
                     class="mt-1 pl-4 space-y-1">
                    @if (Qs::userIsTeamAccount())
                    <a href="{{ route('payments.create') }}"
                       class="{{ Route::is('payments.create') ? 'bg-green-100 text-green-900' : 'text-gray-600 hover:bg-green-50 hover:text-green-900' }} group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                        <span>Create Payment</span>
                    </a>
                    @endif

                    @if (Qs::userIsTeamAccount() || Qs::userIsAdministrative())
                    <a href="{{ route('payments.index') }}"
                       class="{{ in_array(Route::currentRouteName(), ['payments.index', 'payments.edit', 'payments.show']) ? 'bg-green-100 text-green-900' : 'text-gray-600 hover:bg-green-50 hover:text-green-900' }} group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                        <span>Manage Payments</span>
                    </a>
                    @endif

                    <a href="{{ route('payments.manage') }}"
                       class="{{ in_array(Route::currentRouteName(), ['payments.manage', 'payments.invoice', 'payments.receipts']) ? 'bg-green-100 text-green-900' : 'text-gray-600 hover:bg-green-50 hover:text-green-900' }} group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                        <span>Student Payments</span>
                    </a>
                </div>
            </div>
            @endif

            <!-- Settings -->
            @if (Qs::userIsAdministrative())
            <div>
                <button @click="activeMenu = activeMenu === 'settings' ? null : 'settings'"
                        class="text-gray-600 hover:bg-green-50 hover:text-green-900 group w-full flex items-center px-2 py-2 text-sm font-medium rounded-md">
                    <svg class="text-gray-400 group-hover:text-green-500 mr-3 h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    <span class="flex-1">Settings</span>
                    <svg class="text-gray-400 ml-3 h-5 w-5 transform group-hover:text-green-500 transition-colors ease-in-out duration-150" 
                         :class="{'rotate-90': activeMenu === 'settings'}"
                         viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                    </svg>
                </button>
                <div x-show="activeMenu === 'settings'"
                     x-transition:enter="transition ease-out duration-100"
                     x-transition:enter-start="transform opacity-0 scale-95"
                     x-transition:enter-end="transform opacity-100 scale-100"
                     class="mt-1 pl-4 space-y-1">
                    @if (Qs::userIsTeamSA() || Qs::userIsAdmin())
                    <a href="{{ route('users.index') }}"
                       class="{{ in_array(Route::currentRouteName(), ['users.index', 'users.show', 'users.edit']) ? 'bg-green-100 text-green-900' : 'text-gray-600 hover:bg-green-50 hover:text-green-900' }} group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                        <span>Users</span>
                    </a>
                    @endif

                    <a href="{{ route('settings') }}"
                       class="{{ Route::is('settings') ? 'bg-green-100 text-green-900' : 'text-gray-600 hover:bg-green-50 hover:text-green-900' }} group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                        <span>School Settings</span>
                    </a>

                    <a href="{{ route('my_account') }}"
                       class="{{ Route::is('my_account') ? 'bg-green-100 text-green-900' : 'text-gray-600 hover:bg-green-50 hover:text-green-900' }} group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                        <span>My Account</span>
                    </a>
                </div>
            </div>
            @endif
        </nav>
    </div>
</aside>

<!-- Mobile menu backdrop -->
<div x-show="sidebarOpen" 
     class="fixed inset-0 z-40 bg-gray-600 bg-opacity-75 md:hidden" 
     @click="sidebarOpen = false"
     x-cloak></div>