            <!--begin::Header-->
            <nav class="app-header navbar navbar-expand bg-body">
                <!--begin::Container-->
                <div class="container-fluid">
                    <!--begin::Start Navbar Links-->
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button">
                            <i class="bi bi-list"></i>
                            </a>
                        </li>
                        <!--li class="nav-item d-none d-md-block"><a href="#" class="nav-link">Home</a></li>
                        <li class="nav-item d-none d-md-block"><a href="#" class="nav-link">Contact</a></li-->
                    </ul>
                    <!--end::Start Navbar Links-->
                    <!--begin::End Navbar Links-->
                    <ul class="navbar-nav ms-auto">
                        <!--begin::Navbar Search-->
                        <li class="nav-item">
                            <a class="nav-link" data-widget="navbar-search" href="#" role="button">
                            <i class="bi bi-search"></i>
                            </a>
                        </li>
                        <!--end::Navbar Search-->
                        <!--begin::Notifications Dropdown Menu-->
                        <li class="nav-item dropdown">
                            <a class="nav-link" data-bs-toggle="dropdown" href="#">
                            <i class="bi bi-bell-fill"></i>
                            <span class="navbar-badge badge text-bg-warning">0</span>
                            </a>
                        </li>
                        <!--end::Notifications Dropdown Menu-->
                        <!--begin::User Menu Dropdown-->
                        <li class="nav-item dropdown user-menu">
                            <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                            <img
                            src="{{ asset(Auth::user()->image) }}"
                            class="user-image rounded-circle shadow"
                            alt="User Image"
                            />
                            <span class="d-none d-md-inline">{{ Auth::user()->name }}</span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
                                <!--begin::User Image-->
                                <li class="user-header text-bg-primary">
                                    <img
                                    src="{{ asset(Auth::user()->image) }}"
                                    class="rounded-circle shadow"
                                    alt="User Image"
                                    />
                                    <p>
                                    {{ Auth::user()->name }} - Vice Director
                                    <small>Member since Apr. 2025</small>
                                    </p>
                                </li>
                                <!--end::User Image-->
                                <!--begin::Menu Body-->
                                <li class="user-body">
                                    <!--begin::Row-->
                                    <div class="row">
                                        <div class="col-4 text-center"><a href="#">Lý lịch KH</a></div>
                                        <!--div class="col-4 text-center"><a href="#">Sales</a></div>
                                        <div class="col-4 text-center"><a href="#">Friends</a></divd-->
                                    </div>
                                    <!--end::Row--> 
                                </li>
                                <!--end::Menu Body-->
                                <!--begin::Menu Footer-->
                                <li class="user-footer">
                                    <a href="{{ route('profile.edit') }}" class="btn btn-default btn-flat">Profile </a>
                                    <!--x-dropdown-link :href="route('profile.edit')">
                                    {{ __('Profile') }}
                                    </x-dropdown-link -->
                                    <!--a href="{{ route('logout') }}" class="btn btn-default btn-flat float-end">Sign out</a-->
                                    <!-- Authentication -->
                                    <a href="{{ route('logout') }}"
                                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                                        class="dropdown-item">
                                        {{ __('Log Out') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                         @csrf
                                    </form>

                                    <!--form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        
                                        <x-dropdown-link :href="route('logout')"
                                        onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                        {{ __('Log Out') }}
                                        </x-dropdown-link>
                                    </form -->
                                    
                                </li>
                                <!--end::Menu Footer-->
                            </ul>
                        </li>
                        <!--end::User Menu Dropdown-->
                    </ul>
                    <!--end::End Navbar Links-->
                </div>
                <!--end::Container-->
            </nav>
            <!--end::Header-->
            


