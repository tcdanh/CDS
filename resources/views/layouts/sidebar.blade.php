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
                            <li class="nav-item menu-open">
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
                            </li>
                            <!-- Project mgt -->
                            <li class="nav-item">
                                <a href="#" class="nav-link">
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
                            <li class="nav-item">
                                <a href="{{ route('admin_setting.index') }}" class="nav-link">
                                <i class="nav-icon bi bi-patch-check-fill"></i>
                                <p>Admin config</p>
                                </a>
                            </li>
                            <!-- End admin config --> 
                        </ul>
                        <!--end::Sidebar Menu-->
                    </nav>
                </div>
                <!--end::Sidebar Wrapper-->   
            </aside>
            <!--end::Sidebar-->