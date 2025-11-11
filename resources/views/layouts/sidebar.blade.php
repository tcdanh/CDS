            <!--begin::Sidebar-->
            <aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
                <!--begin::Sidebar Brand-->
                <div class="sidebar-brand">
                    <!--begin::Brand Link-->
                    <a href="./index.html" class="brand-link">
                    <!--begin::Brand Image-->
                    <!--img
                    src="" 
                    alt="AdminiLead Logo"
                    class="brand-image opacity-75 shadow"
                    /-->
                    <!--end::Brand Image-->
                    <!--begin::Brand Text-->
                    <span class="brand-text fw-light">AdminILEAD</span>
                    <!--end::Brand Text-->
                    </a>
                    <!--end::Brand Link-->
                </div>
                <!--end::Sidebar Brand-->
                <!--begin::Sidebar Wrapper-->
                <div class="sidebar-wrapper">
                    <nav class="mt-2">
                        <!--begin::Sidebar Menu-->
                        <ul
                        class="nav sidebar-menu flex-column"
                        data-lte-toggle="treeview"
                        role="menu"
                        data-accordion="false"
                        >
                            <li class="nav-item">
                                <a href="{{ route('dashboard') }}" class="nav-link active">
                                <i class="nav-icon bi bi-speedometer"></i>
                                <p>
                                Dashboard
                                </p>
                                </a>
                            </li>
                            <!--li class="nav-item menu-open">
                                <a href="#" class="nav-link">
                                <i class="nav-icon bi bi-filetype-js"></i>
                                <p>
                                News Management
                                <i class="nav-arrow bi bi-chevron-right"></i>
                                </p>
                                </a>
                                <ul class="nav nav-treeview">
                                    <li class="nav-item">
                                        <a href="{{ route('banner_article.index') }}" class="nav-link ">
                                        <i class="nav-icon bi bi-circle"></i>
                                        <p>Banner article</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('admin.news.index') }}" class="nav-link">
                                        <i class="nav-icon bi bi-circle"></i>
                                        <p>News article</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('about.index') }}" class="nav-link">
                                        <i class="nav-icon bi bi-circle"></i>
                                        <p>About</p>
                                        </a>
                                    </li>
                                </ul>
                            </li-->
                            
                            @php
                                $personalInfoActive = request()->routeIs('scientific-profiles.show', 'scientific-profiles.edit', 'scientific-profiles.update');
                                $familyInfoActive = request()->routeIs('scientific-profiles.family');
                                $historyInfoActive = request()->routeIs('scientific-profiles.history');
                                $trainingInfoActive = request()->routeIs('scientific-profiles.training');
                                $workInfoActive = request()->routeIs('scientific-profiles.work');
                                $planningInfoActive = request()->routeIs('scientific-profiles.planning');
                                $compensationInfoActive = request()->routeIs('scientific-profiles.compensation', 'scientific-profiles.compensation.edit');
                                $recognitionInfoActive = request()->routeIs('scientific-profiles.recognition', 'scientific-profiles.recognition.edit');
                                $teachingInfoActive = request()->routeIs('scientific-profiles.teaching', 'scientific-profiles.teaching.edit');
                                $researchInfoActive = request()->routeIs('scientific-profiles.research', 'scientific-profiles.research.edit');
                                $awardsInfoActive = request()->routeIs('scientific-profiles.awards', 'scientific-profiles.awards.edit');
                                $publicationsInfoActive = request()->routeIs('scientific-profiles.publications', 'scientific-profiles.publications.edit');
                                $profileMenuOpen = $personalInfoActive || $familyInfoActive || $historyInfoActive || $trainingInfoActive || $workInfoActive || $planningInfoActive || $teachingInfoActive || $researchInfoActive || $awardsInfoActive || $publicationsInfoActive || $compensationInfoActive || $recognitionInfoActive;
                            @endphp
                            <li class="nav-item {{ $profileMenuOpen ? 'menu-open' : '' }}">
                                <!--a href="#" class="nav-link"-->
                                <a href="#" class="nav-link {{ $profileMenuOpen ? 'active' : '' }}">
                                <i class="nav-icon bi bi-person-square"></i>
                                <p>
                                    Sơ yếu lý lịch
                                <i class="nav-arrow bi bi-chevron-right"></i>
                                </p>
                                </a> 
                                <ul class="nav nav-treeview">
                                    <li class="nav-item">
                                        <!--a href="{{ route('scientific-profiles.show') }}" class="nav-link {{ request()->routeIs('scientific-profiles.*') ? 'active' : '' }}"-->
                                        <a href="{{ route('scientific-profiles.show') }}" class="nav-link {{ $personalInfoActive ? 'active' : '' }}">
                                        <i class="nav-icon bi bi-circle"></i>
                                        <p>Thông tin cá nhân</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <!--a href="{{ route('scientific-profiles.family') }}" class="nav-link {{ request()->routeIs('profiles.family') || request()->routeIs('scientific-profiles.family') ? 'active' : ''  }}"-->
                                        <a href="{{ route('scientific-profiles.family') }}" class="nav-link {{ $familyInfoActive ? 'active' : '' }}">
                                        <i class="nav-icon bi bi-circle"></i>
                                        <p>Gia đình</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('scientific-profiles.history') }}" class="nav-link {{ $historyInfoActive ? 'active' : '' }}">
                                        <i class="nav-icon bi bi-circle"></i>
                                        <p>Lịch sử bản thân</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('scientific-profiles.training') }}" class="nav-link {{ $trainingInfoActive ? 'active' : '' }}">
                                        <i class="nav-icon bi bi-circle"></i>
                                        <p>Quá trình đào tạo</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('scientific-profiles.work') }}" class="nav-link {{ $workInfoActive ? 'active' : '' }}">
                                        <i class="nav-icon bi bi-circle"></i>
                                        <p>Quá trình công tác</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('scientific-profiles.planning') }}" class="nav-link {{ $planningInfoActive ? 'active' : '' }}">
                                        <i class="nav-icon bi bi-circle"></i>
                                        <p>Quy hoạch</p>
                                        </a>
                                    </li>
                                    <li class="nav-header">----- Giảng dạy và Nghiên cứu</li>
                                    <li class="nav-item">
                                        <a href="{{ route('scientific-profiles.teaching') }}" class="nav-link {{ $teachingInfoActive ? 'active' : '' }}">
                                        <i class="nav-icon bi bi-circle"></i>
                                        <p>Giảng dạy</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('scientific-profiles.research') }}" class="nav-link {{ $researchInfoActive ? 'active' : '' }}">
                                        <i class="nav-icon bi bi-circle"></i>
                                        <p>Đề tài dự án</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                         <a href="{{ route('scientific-profiles.awards') }}" class="nav-link {{ $awardsInfoActive ? 'active' : '' }}">
                                        <i class="nav-icon bi bi-circle"></i>
                                        <p>Giải thưởng</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('scientific-profiles.publications') }}" class="nav-link {{ $publicationsInfoActive ? 'active' : '' }}">
                                        <i class="nav-icon bi bi-circle"></i>
                                        <p>Xuất bản & SHTT</p>
                                        </a>
                                    </li>
                                    <li class="nav-header">----- Nghiệp vụ </li>
                                    <li class="nav-item">
                                        <a href="{{ route('scientific-profiles.compensation') }}" class="nav-link {{ $compensationInfoActive ? 'active' : '' }}">
                                        <i class="nav-icon bi bi-circle"></i>
                                        <p>Lương - Phụ cấp</p>
                                        </a>
                                    </li>
                                    
                                    <li class="nav-item">
                                        <a href="{{ route('scientific-profiles.recognition') }}" class="nav-link {{ $recognitionInfoActive ? 'active' : '' }}">
                                        <i class="nav-icon bi bi-circle"></i>
                                        <p>Khen thưởng - Kỷ luật</p>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <!-- TCHC mgt -->
                            @php
                                $workScheduleActive = request()->routeIs('work-schedules.*');
                            @endphp
                            <li class="nav-item {{ $workScheduleActive ? 'menu-open' : '' }}">
                                <a href="#" class="nav-link {{ $workScheduleActive ? 'active' : '' }}">
                                <i class="nav-icon bi bi-box-seam-fill"></i>
                                <p>
                                    Administration
                                <i class="nav-arrow bi bi-chevron-right"></i>
                                </p>
                                </a> 
                                <ul class="nav nav-treeview">
                                    <li class="nav-item">
                                        <a href="{{ route('work-schedules.index') }}" class="nav-link {{ $workScheduleActive ? 'active' : '' }}">
                                        <i class="nav-icon bi bi-circle"></i>
                                        <p>Lịch công tác</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="#" class="nav-link">
                                        <i class="nav-icon bi bi-circle"></i>
                                        <p>Đơn xin nghỉ phép</p>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <!-- End: TCHC mgt -->
                            <!-- Project mgt -->
                            <li class="nav-item">
                                <a href="{{ route('project-management.index') }}" class="nav-link {{ request()->routeIs('project-management.*') ? 'active' : '' }}">
                                <i class="nav-icon bi bi-box-seam-fill"></i>
                                <p>
                                    Project Management
                                <i class="nav-arrow bi bi-chevron-right"></i>
                                </p>
                                </a> 
                            </li>
                            <!-- End: Project mgt -->
                            <!-- Education mgt -->
                            <li class="nav-item">
                                <a href="#" class="nav-link">
                                <i class="nav-icon bi bi-palette"></i>
                                <p>
                                    Education Management
                                <i class="nav-arrow bi bi-chevron-right"></i>
                                </p>
                                </a> 
                            </li>
                            <!-- End: Education mgt -->

                            <!-- Admin config -->
                            @if (auth()->check() && auth()->user()->isAdmin())
                            <li class="nav-item">
                                <a href="{{ route('admin_setting.index') }}" class="nav-link">
                                <i class="nav-icon bi bi-patch-check-fill"></i>
                                <p>Admin config</p>
                                </a>
                            </li>
                            @endif
                            <!-- End admin config --> 
                        </ul>
                        <!--end::Sidebar Menu-->
                    </nav>
                </div>
                <!--end::Sidebar Wrapper-->   
            </aside>
            <!--end::Sidebar-->