<div wire:replace class="sidebar sidebar-dark sidebar-main sidebar-expand-md fixed-top">
    <!-- Sidebar mobile toggler -->
    <div class="sidebar-mobile-toggler text-center">
        <a href="#" class="sidebar-mobile-main-toggle">
            <i class="icon-arrow-left8"></i>
        </a>
        Navigation
        <a href="#" class="sidebar-mobile-expand">
            <i class="icon-screen-full"></i>
            <i class="icon-screen-normal"></i>
        </a>
    </div>
    <!-- /sidebar mobile toggler -->

    <!-- Sidebar content -->
    <div class="sidebar-content">

        <div class="sidebar-user">
            <div class="card-body">
                <div class="media">
                    <div class="mr-3">
                        @php
                        $userPhoto = Auth::user()->photo;
                        @endphp
                        @if ($userPhoto)
                        <a href="{{ route('my_account') }}"><img src="{{ $userPhoto }}" width="38" height="38"
                                class="rounded-circle" alt="photo"></a>
                        @else
                        <div>Photo Not Found</div>
                        @endif
                    </div>

                    <div class="media-body">
                        <div class="media-title font-weight-semibold">{{ Auth::user()->name }}</div>
                        <div class="font-size-xs opacity-50">
                            <i class="icon-user font-size-sm"></i>
                            &nbsp;{{ ucwords(str_replace('_', ' ', Auth::user()->user_type)) }}
                        </div>
                    </div>

                    <div class="ml-3 align-self-center">
                        <a href="{{ route('my_account') }}" class="text-white"><i class="icon-cog3"></i></a>
                    </div>
                </div>
            </div>
        </div>


        <!-- Main navigation -->
        <div class="card card-sidebar-mobile">
            <ul class="nav nav-sidebar" data-nav-type="accordion">

                <!-- Main -->
                <li class="nav-item">
                    <a href="{{ route('dashboard') }}" class="nav-link {{ (Route::is('dashboard')) ? 'active' : '' }}">
                        <i class="icon-home4"></i>
                        <span>Dashboard</span>
                    </a>
                </li>

                {{--Register Students--}}
                @if(Qs::userIsTeamSAT())
                <li
                    class="nav-item nav-item-submenu {{ in_array(Route::currentRouteName(), ['students.create', 'students.list', 'students.edit', 'students.show', 'students.promotion', 'students.promotion_manage', 'students.graduated']) ? 'nav-item-expanded nav-item-open' : '' }} ">
                    <a href="#" class="nav-link"><i class="icon-users"></i> <span> Registration</span></a>
                    <ul class="nav nav-group-sub" data-submenu-title="Manage Students">
                        {{--Manage Classes--}}
                        <li class="nav-item">
                            <a  href="{{ route('classes.index') }}"
                                class="nav-link {{ in_array(Route::currentRouteName(), ['classes.index','classes.edit']) ? 'active' : '' }}"><span>
                                    Classes</span></a>
                        </li>
                        {{--Manage Sections--}}
                        <li class="nav-item">
                            <a href="{{ route('sections.index') }}"
                                class="nav-link {{ in_array(Route::currentRouteName(), ['sections.index','sections.edit',]) ? 'active' : '' }}"><span>Streams</span></a>
                        </li>
                        {{--Manage Dorms--}}
                        <li class="nav-item">
                            <a href="{{ route('dorms.index') }}"
                                class="nav-link {{ in_array(Route::currentRouteName(), ['dorms.index','dorms.edit']) ? 'active' : '' }}"><span>
                                    Dormitories</span></a>
                        </li>
                        {{--Admit Student--}}
                        @if(Qs::userIsTeamSA())
                        <li class="nav-item">
                            <a  href="{{ route('students.create') }}"
                                class="nav-link {{ (Route::is('students.create')) ? 'active' : '' }}">Manage
                                Students</a>
                        </li>
                        @endif

                        {{--Student Information--}}
                        <li
                            class="nav-item nav-item-submenu {{ in_array(Route::currentRouteName(), ['students.list', 'students.edit', 'students.show']) ? 'nav-item-expanded' : '' }}">
                            <a href="#"
                                class="nav-link {{ in_array(Route::currentRouteName(), ['students.list', 'students.edit', 'students.show']) ? 'active' : '' }}">Student
                                Information</a>
                            <ul class="nav nav-group-sub">
                                @foreach(App\Models\MyClass::orderBy('name')->get() as $c)
                                <li class="nav-item"><a href="{{ route('students.list', $c->id) }}" class="nav-link ">{{
                                        $c->name }}</a></li>
                                @endforeach
                            </ul>
                        </li>

                        @if(Qs::userIsTeamSA())

                        {{--Student Promotion--}}

                        <li class="nav-item"><a href="{{ route('students.promotions_demotions') }}"
                                class="nav-link {{ in_array(Route::currentRouteName(), ['students.promotions_demotions']) ? 'active' : '' }}">Promotions
                                & Demotions</a>
                        </li>


                        {{--Student Graduated--}}
                        <li class="nav-item"><a href="{{ route('students.graduated') }}"
                                class="nav-link {{ in_array(Route::currentRouteName(), ['students.graduated' ]) ? 'active' : '' }}">Students
                                Graduated</a></li>
                        @endif

                    </ul>
                </li>
                @endif

                {{--Academics--}}
                @if(Qs::userIsAcademic())
                <li
                    class="nav-item nav-item-submenu {{ in_array(Route::currentRouteName(), ['tt.index', 'ttr.edit', 'ttr.show', 'ttr.manage']) ? 'nav-item-expanded nav-item-open' : '' }} ">
                    <a href="#" class="nav-link"><i class="icon-graduation2"></i> <span> Academics</span></a>

                    <ul class="nav nav-group-sub" data-submenu-title="Manage Academics">
                        {{--Manage Subjects--}}
                        <li class="nav-item">
                            <a href="{{ route('subjects.index') }}"
                                class="nav-link {{ in_array(Route::currentRouteName(), ['subjects.index','subjects.edit',]) ? 'active' : '' }}">
                                <span>Subjects</span></a>

                        </li>
                        {{--Grades list--}}
                        <li class="nav-item">
                            <a href="{{ route('grades.index') }}"
                                class="nav-link {{ in_array(Route::currentRouteName(), ['grades.index', 'grades.edit']) ? 'active' : '' }}">
                                <span></span>Grading</a>
                        </li>


                        {{--Grades list--}}
                        <li class="nav-item">
                            <a href="{{ route('grading_system.index') }}"
                                class="nav-link {{ in_array(Route::currentRouteName(), ['grading_system.index', 'grading_system.edit','grading_system.create']) ? 'active' : '' }}">
                                <span></span>Grading System</a>
                        </li>


                        <li
                            class="nav-item {{ in_array(Route::currentRouteName(), ['exams.set']) ? 'nav-item-expanded nav-item-open' : '' }}">
                            <a href="{{ route('exams.set') }}" class="nav-link">
                                <span> Exam Management</span>
                            </a>
                        </li>
                        <li
                            class="nav-item {{ in_array(Route::currentRouteName(), ['exams.assignExamMarks']) ? 'nav-item-expanded nav-item-open' : '' }}">
                            <a href="{{ route('exams.assignExamMarks') }}" class="nav-link">
                                <span> Marks Allocation</span>
                            </a>
                        </li>
                        <li
                            class="nav-item {{ in_array(Route::currentRouteName(), ['exams.grades']) ? 'nav-item-expanded nav-item-open' : '' }}">
                            <a href="{{ route('exams.grades') }}" class="nav-link">
                                <span> Grades Management</span>
                            </a>
                        </li>


                    </ul>
                </li>
                @endif

                {{--Administrative--}}
                @if(Qs::userIsAdministrative())
                <li
                    class="nav-item nav-item-submenu {{ in_array(Route::currentRouteName(), ['payments.index', 'payments.create', 'payments.invoice', 'payments.receipts', 'payments.edit', 'payments.manage', 'payments.show',]) ? 'nav-item-expanded nav-item-open' : '' }} ">
                    <a href="#" class="nav-link"><i class="icon-office"></i> <span> Finance</span></a>

                    <ul class="nav nav-group-sub" data-submenu-title="Administrative">

                        {{--Payments--}}
                        @if(Qs::userIsTeamAccount())
                        <li
                            class="nav-item nav-item-submenu {{ in_array(Route::currentRouteName(), ['payments.index', 'payments.create', 'payments.edit', 'payments.manage', 'payments.show', 'payments.invoice']) ? 'nav-item-expanded' : '' }}">

                            <a href="#"
                                class="nav-link {{ in_array(Route::currentRouteName(), ['payments.index', 'payments.edit', 'payments.create', 'payments.manage', 'payments.show', 'payments.invoice']) ? 'active' : '' }}">Payments</a>

                            <ul class="nav nav-group-sub">
                                <li class="nav-item"><a href="{{ route('payments.create') }}"
                                        class="nav-link {{ Route::is('payments.create') ? 'active' : '' }}">Create
                                        Payment</a></li>
                                <li class="nav-item"><a href="{{ route('payments.index') }}"
                                        class="nav-link {{ in_array(Route::currentRouteName(), ['payments.index', 'payments.edit', 'payments.show']) ? 'active' : '' }}">Manage
                                        Payments</a></li>
                                <li class="nav-item"><a href="{{ route('payments.manage') }}"
                                        class="nav-link {{ in_array(Route::currentRouteName(), ['payments.manage', 'payments.invoice', 'payments.receipts']) ? 'active' : '' }}">Student
                                        Payments</a></li>

                            </ul>

                        </li>
                        @endif
                    </ul>
                </li>
                @endif

                {{--System Settings--}}
                @if(Qs::userIsAdministrative())
                <li
                    class="nav-item nav-item-submenu {{ in_array(Route::currentRouteName(), ['payments.index', 'payments.create', 'payments.invoice', 'payments.receipts', 'payments.edit', 'payments.manage', 'payments.show',]) ? 'nav-item-expanded nav-item-open' : '' }} ">
                    <a href="#" class="nav-link"><i class="icon-office"></i> <span>System Settings</span></a>

                    <ul class="nav nav-group-sub" data-submenu-title="Administrative">

                        {{--Users--}}
                        @if(Qs::userIsTeamAccount())
                        <li
                            class="nav-item nav-item-submenu {{ in_array(Route::currentRouteName(), ['payments.index', 'payments.create', 'payments.edit', 'payments.manage', 'payments.show', 'payments.invoice']) ? 'nav-item-expanded' : '' }}">

                            <a href="#"
                                class="nav-link {{ in_array(Route::currentRouteName(), ['payments.index', 'payments.edit', 'payments.create', 'payments.manage', 'payments.show', 'payments.invoice']) ? 'active' : '' }}">General
                                Settings</a>

                            <ul class="nav nav-group-sub">
                                {{--Create User Types--}}
                                <li class="nav-item">
                                    <a href="#"
                                        class="nav-link {{ in_array(Route::currentRouteName(), ['dorms.index','dorms.edit']) ? 'active' : '' }}"><i
                                            class="icon-home9"></i> <span> Dormitories</span></a>
                                </li>
                                {{--Create User Roles--}}
                                <li class="nav-item">
                                    <a href="#"
                                        class="nav-link {{ in_array(Route::currentRouteName(), ['dorms.index','dorms.edit']) ? 'active' : '' }}"><i
                                            class="icon-home9"></i> <span> Dormitories</span></a>
                                </li>
                                {{--Manage Users--}}
                                <li class="nav-item">
                                    <a href="{{ route('users.index') }}"
                                        class="nav-link {{ in_array(Route::currentRouteName(), ['users.index', 'users.show', 'users.edit']) ? 'active' : '' }}"><i
                                            class="icon-users4"></i> <span> Users</span></a>
                                </li>
                            </ul>

                        </li>
                        @endif
                        {{--Manage Account--}}
                        @include('pages.'.Qs::getUserType().'.menu')
                        <a href="{{ route('my_account') }}"
                            class="nav-link {{ in_array(Route::currentRouteName(), ['my_account']) ? 'active' : '' }}"><i
                                class="icon-user"></i> <span>My Account</span></a>

                    </ul>
                </li>
                @endif
            </ul>
        </div>
    </div>
</div>