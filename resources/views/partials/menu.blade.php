<!-- Sidebar -->
<div x-data="{ 
    isSidebarOpen: true,
    isMobileMenuOpen: false,
    activeSubmenu: null
}" 
class="bg-gray-800 text-white w-64 min-h-screen fixed left-0 top-0 transform transition-transform duration-200 ease-in-out"
:class="{'translate-x-0': isSidebarOpen || isMobileMenuOpen, '-translate-x-full': !isSidebarOpen && !isMobileMenuOpen}">
    
    <!-- Mobile Toggle -->
    <div class="lg:hidden flex items-center justify-between p-4 border-b border-gray-700">
        <span class="font-semibold text-lg">Navigation</span>
        <button @click="isMobileMenuOpen = !isMobileMenuOpen" class="text-gray-300 hover:text-white">
            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path x-show="!isMobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                <path x-show="isMobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>

    <!-- Sidebar Content -->
    <div class="overflow-y-auto h-full py-4">
        <!-- User Profile (Uncomment if needed) -->
        {{-- <div class="px-4 py-3 mb-6 bg-gray-700 rounded-lg mx-4">
            <div class="flex items-center space-x-4">
                    @php
                        $user = Auth::user();
                        if (!$user) {
                            header('Location: ' . route('landing'));
                            exit();
                        }
                        $userPhoto = $user->photo
                            ? asset('storage/' . $user->photo)
                            : asset('images/default-photo.jpg');
                    @endphp

                    <div class="mr-3">
                        <a href="{{ route('my_account') }}">
                            <img src="{{ $userPhoto }}" width="38" height="38" class="rounded-circle"
                                alt="User Photo">
                        </a>
                    </div>

                    <div class="media-body">
                        <div class="media-title font-weight-semibold">
                            {{ $user->name }}
                        </div>
                        <div class="font-size-xs opacity-50">
                            <i class="icon-user font-size-sm"></i>
                            &nbsp;{{ ucwords(str_replace('_', ' ', $user->userType->title ?? 'User')) }}
                        </div>
                    </div>

                    <div class="ml-3 align-self-center">
                        <a href="{{ route('my_account') }}" class="text-white"><i class="icon-cog3"></i></a>
                </div>
            </div>
        </div> --}}

        <!-- Navigation Menu -->
        <nav class="space-y-1 px-2">
                <!-- Dashboard -->
            <a href="{{ route('dashboard') }}" 
               class="flex items-center px-4 py-2 text-sm font-medium rounded-md transition-colors duration-150 {{ Route::is('dashboard') ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                <svg class="mr-3 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
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
                <div x-data="{ open: false }" class="space-y-1">
                    <button @click="open = !open" 
                            class="flex items-center w-full px-4 py-2 text-sm font-medium text-gray-300 rounded-md hover:bg-gray-700 hover:text-white focus:outline-none focus:bg-gray-700">
                        <svg class="mr-3 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                        <span class="flex-1 text-left">
                                @if (Qs::userIsParent())
                                    Student Registration
                                @else
                                    Registration
                                @endif
                            </span>
                        <svg class="ml-2 h-5 w-5 transform transition-transform duration-150" 
                             :class="{'rotate-90': open}"
                             xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </button>

                    <div x-show="open" 
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="transform opacity-0 scale-95"
                         x-transition:enter-end="transform opacity-100 scale-100"
                         class="pl-6 space-y-1">
                        
                        <!-- Classes Link -->
                        <a href="{{ Qs::userIsParent() || Qs::userIsStudent() ? route('parent.child-class') : route('classes.index') }}"
                           class="flex items-center px-4 py-2 text-sm font-medium rounded-md {{ in_array(Route::currentRouteName(), ['classes.index', 'classes.edit', 'parent.child.classes']) ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                                    <span>
                                        @if (Qs::userIsParent() || Qs::userIsStudent())
                                            Class Information
                                        @else
                                            Classes
                                        @endif
                                    </span>
                                </a>

                        <!-- Dormitories Link -->
                                <a href="{{ Qs::userIsParent() || Qs::userIsStudent() ? route('parent.child-dorm') : route('dorms.index') }}"
                           class="flex items-center px-4 py-2 text-sm font-medium rounded-md {{ in_array(Route::currentRouteName(), ['dorms.index', 'dorms.edit']) ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                                    <span>
                                        @if (Qs::userIsParent())
                                            Student Dormitory
                                        @else
                                            Dormitories
                                        @endif
                                    </span>
                                </a>

                        <!-- Admissions Link -->
                            @if (Qs::userIsTeamSA() || Qs::userIsParent())
                                    <a href="{{ route('students.manage-students') }}"
                               class="flex items-center px-4 py-2 text-sm font-medium rounded-md {{ Route::is('students.manage-students') ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                                        <span>
                                            @if (Qs::userIsParent())
                                                Student Admissions
                                            @else
                                                Admissions
                                            @endif
                                        </span>
                                    </a>
                            @endif

                        <!-- Promotions Link -->
                            @if (Qs::userIsTeamSA() || Qs::userIsParent())
                                    <a href="{{ Qs::userIsParent() || Qs::userIsStudent() ? route('parent.child-transition-status') : route('students.promotions_demotions') }}"
                               class="flex items-center px-4 py-2 text-sm font-medium rounded-md {{ in_array(Route::currentRouteName(), ['students.promotions_demotions']) ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                                        <span>
                                            @if (Qs::userIsParent())
                                                Student Promotions
                                            @else
                                                Promotions & Demotions
                                            @endif
                                        </span>
                                    </a>

                                    <a href="{{ Qs::userIsParent() || Qs::userIsStudent() ? route('parent.child-graduation') : route('students.graduation') }}"
                               class="flex items-center px-4 py-2 text-sm font-medium rounded-md {{ in_array(Route::currentRouteName(), ['students.graduation']) ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
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

            <!-- Classes Direct Link -->
            @if (Qs::userIsTeamSAT())
                <a href="{{ route('classes.index') }}"
                   class="flex items-center px-4 py-2 text-sm font-medium rounded-md {{ in_array(Route::currentRouteName(), ['classes.index', 'classes.edit']) ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                    <svg class="mr-3 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                    <span>Classes</span>
                </a>
                @endif

            <!-- Academics Section -->
                @if (Qs::userIsAcademic() || Qs::userIsTeamSA() || Qs::userIsParent())
                <div x-data="{ open: false }" class="space-y-1">
                    <button @click="open = !open"
                            class="flex items-center w-full px-4 py-2 text-sm font-medium text-gray-300 rounded-md hover:bg-gray-700 hover:text-white focus:outline-none focus:bg-gray-700">
                        <svg class="mr-3 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                        <span class="flex-1 text-left">
                                @if (Qs::userIsParent())
                                    Student Academics
                                @elseif (Qs::userIsStudent())
                                    My Academics
                                @else
                                    Academics
                                @endif
                            </span>
                        <svg class="ml-2 h-5 w-5 transform transition-transform duration-150"
                             :class="{'rotate-90': open}"
                             xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </button>

                    <div x-show="open"
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="transform opacity-0 scale-95"
                         x-transition:enter-end="transform opacity-100 scale-100"
                         class="pl-6 space-y-1">
                        
                        <!-- Subjects Link -->
                                <a href="{{ route('subjects.index') }}"
                           class="flex items-center px-4 py-2 text-sm font-medium rounded-md {{ in_array(Route::currentRouteName(), ['subjects.index', 'subjects.edit']) ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
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

                            <!-- Grading System -->
                                <a href="{{ route('grading_system.index') }}"
                           class="flex items-center px-4 py-2 text-sm font-medium rounded-md {{ in_array(Route::currentRouteName(), ['grading_system.index', 'grading_system.edit', 'grading_system.create']) ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                            <span>Grading System</span>
                        </a>

                            <!-- Exam Management -->
                                <a href="{{ Qs::userIsParent() ? route('parent.child-exams') : (Qs::userIsStudent() ? route('student.my-exams') : route('exams.set')) }}"
                           class="flex items-center px-4 py-2 text-sm font-medium rounded-md {{ in_array(Route::currentRouteName(), ['exams.set', 'parent.child-exams', 'student.my-exams']) ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                            <span>Exam Management</span>
                        </a>

                            <!-- Marks Allocation -->
                                <a href="{{ Qs::userIsParent() ? route('parent.child-marks') : (Qs::userIsStudent() ? route('student.my-marks') : route('exams.assignExamMarks')) }}"
                           class="flex items-center px-4 py-2 text-sm font-medium rounded-md {{ in_array(Route::currentRouteName(), ['exams.assignExamMarks', 'parent.child-marks', 'student.my-marks']) ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                            <span>Marks Allocation</span>
                        </a>
                    </div>
                </div>
                @endif

            <!-- Finance Section -->
                @if (Qs::userIsAdministrative() || Qs::userIsParent())
                <div x-data="{ open: false }" class="space-y-1">
                    <button @click="open = !open"
                            class="flex items-center w-full px-4 py-2 text-sm font-medium text-gray-300 rounded-md hover:bg-gray-700 hover:text-white focus:outline-none focus:bg-gray-700">
                        <svg class="mr-3 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span class="flex-1 text-left">
                                @if (Qs::userIsParent())
                                    Student Finance
                                @else
                                    Finance
                                @endif
                            </span>
                        <svg class="ml-2 h-5 w-5 transform transition-transform duration-150"
                             :class="{'rotate-90': open}"
                             xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </button>

                    <!-- Finance Submenu -->
                    <div x-show="open"
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="transform opacity-0 scale-95"
                         x-transition:enter-end="transform opacity-100 scale-100"
                         class="pl-6 space-y-1">
                            <!-- Payments Section -->
                            @if (Qs::userIsTeamAccount() || Qs::userIsAdministrative() || Qs::userIsParent())
                            <div x-data="{ open: false }" class="space-y-1">
                                <button @click="open = !open"
                                        class="flex items-center w-full px-4 py-2 text-sm font-medium text-gray-300 rounded-md hover:bg-gray-700 hover:text-white focus:outline-none focus:bg-gray-700">
                                    <svg class="mr-3 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <span class="flex-1 text-left">
                                        @if (Qs::userIsParent())
                                            Student Payments
                                        @else
                                            Payments
                                        @endif
                                    </span>
                                    <svg class="ml-2 h-5 w-5 transform transition-transform duration-150"
                                         :class="{'rotate-90': open}"
                                         xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </button>

                                <div x-show="open"
                                     x-transition:enter="transition ease-out duration-100"
                                     x-transition:enter-start="transform opacity-0 scale-95"
                                     x-transition:enter-end="transform opacity-100 scale-100"
                                     class="pl-6 space-y-1">
                                        <!-- Create Payment (Visible to Accountants Only) -->
                                        @if (Qs::userIsTeamAccount())
                                                <a href="{{ route('payments.create') }}"
                                           class="flex items-center px-4 py-2 text-sm font-medium rounded-md {{ Route::is('payments.create') ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                                            <i class="mr-3 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6a1 1 0 100-2 1 1 0 000 2zM12 13a1 1 0 100-2 1 1 0 000 2zM12 20a1 1 0 100-2 1 1 0 000 2z"/>
                                            </i>
                                            <span>Create Payment</span>
                                        </a>
                                        @endif

                                        <!-- Manage Payments (Visible to Accountants and Admins) -->
                                        @if (Qs::userIsTeamAccount() || Qs::userIsAdministrative())
                                                <a href="{{ route('payments.index') }}"
                                           class="flex items-center px-4 py-2 text-sm font-medium rounded-md {{ in_array(Route::currentRouteName(), ['payments.index', 'payments.edit', 'payments.show']) ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                                            <i class="mr-3 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6a1 1 0 100-2 1 1 0 000 2zM12 13a1 1 0 100-2 1 1 0 000 2zM12 20a1 1 0 100-2 1 1 0 000 2z"/>
                                            </i>
                                            <span>Manage Payments</span>
                                        </a>
                                        @endif

                                        <!-- Student Payments (Visible to Accountants, Admins, and Parents) -->
                                            <a href="{{ route('payments.manage') }}"
                                       class="flex items-center px-4 py-2 text-sm font-medium rounded-md {{ in_array(Route::currentRouteName(), ['payments.manage', 'payments.invoice', 'payments.receipts']) ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                                        <i class="mr-3 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6a1 1 0 100-2 1 1 0 000 2zM12 13a1 1 0 100-2 1 1 0 000 2zM12 20a1 1 0 100-2 1 1 0 000 2z"/>
                                        </i>
                                        <span>Student Payments</span>
                                    </a>
                                </div>
                            </div>
                            @endif
                    </div>
                </div>
                @endif

            <!-- System Settings -->
                @if (Qs::userIsAdministrative())
                <div x-data="{ open: false }" class="space-y-1">
                    <button @click="open = !open"
                            class="flex items-center w-full px-4 py-2 text-sm font-medium text-gray-300 rounded-md hover:bg-gray-700 hover:text-white focus:outline-none focus:bg-gray-700">
                        <svg class="mr-3 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <span class="flex-1 text-left">System Settings</span>
                        <svg class="ml-2 h-5 w-5 transform transition-transform duration-150"
                             :class="{'rotate-90': open}"
                             xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </button>

                    <div x-show="open"
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="transform opacity-0 scale-95"
                         x-transition:enter-end="transform opacity-100 scale-100"
                         class="pl-6 space-y-1">
                        
                            @if (Qs::userIsTeamSA() || Qs::userIsAdmin())
                                    <a href="{{ route('users.index') }}"
                               class="flex items-center px-4 py-2 text-sm font-medium rounded-md {{ in_array(Route::currentRouteName(), ['users.index', 'users.show', 'users.edit']) ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                                        <span>Users</span>
                                    </a>
                            @endif

                            @include('pages.' . Qs::getUserType() . '.menu')

                                <a href="{{ route('my_account') }}"
                           class="flex items-center px-4 py-2 text-sm font-medium rounded-md {{ Route::is('my_account') ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                                    <span>My Account</span>
                                </a>
                    </div>
                </div>
                @endif
        </nav>
    </div>
</div>

<!-- Backdrop -->
<div x-show="isMobileMenuOpen" 
     @click="isMobileMenuOpen = false"
     class="fixed inset-0 z-20 bg-black bg-opacity-50 lg:hidden"></div>

<!-- Toggle Button -->
<button @click="isSidebarOpen = !isSidebarOpen" 
        class="fixed bottom-4 right-4 p-2 rounded-full bg-gray-800 text-white shadow-lg hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 lg:hidden">
    <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
    </svg>
</button>
