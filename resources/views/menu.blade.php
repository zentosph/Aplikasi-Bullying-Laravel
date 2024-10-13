<!-- <script>
let inactivityTime = function () {
    let time;

    // Reset the timer whenever there's user activity
    window.onload = resetTimer;
    document.onmousemove = resetTimer;
    document.onkeypress = resetTimer;
    document.ontouchstart = resetTimer; // For mobile devices
    document.onclick = resetTimer;
    document.onscroll = resetTimer;

    function logout() {
        alert("You have been logged out due to inactivity.");
        window.location.href = logoutUrl;
    }

    function resetTimer() {
        clearTimeout(time);
        time = setTimeout(logout, 300000);  // 5 minutes in milliseconds
        // Optionally, update a cookie for the last active time
        document.cookie = "lastActivity=" + new Date().getTime() + "; path=/";
    }
};

inactivityTime();
</script> -->
<div id="main-wrapper" data-theme="light" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
    data-sidebar-position="fixed" data-header-position="fixed" data-boxed-layout="full">
    <!-- ============================================================== -->
    <!-- Topbar header - style you can find in pages.scss -->
    <!-- ============================================================== -->
    <header class="topbar" data-navbarbg="skin6">
        <nav class="navbar top-navbar navbar-expand-md">
            <div class="navbar-header" data-logobg="skin6">
                <!-- This is for the sidebar toggle which is visible on mobile only -->
                <a class="nav-toggler waves-effect waves-light d-block d-md-none" href="javascript:void(0)"><i
                        class="ti-menu ti-close"></i></a>
                <!-- ============================================================== -->
                <!-- Logo -->
                <!-- ============================================================== -->
                <div class="navbar-brand">
                    <!-- Logo icon -->
                    <a href="{{ url('/') }}">
                        <b class="logo-icon">
                            <!-- Dark Logo icon -->
                            <img src="{{ asset('images/'.$setting->icon_menu) }}" alt="homepage" class="dark-logo" width="150px" height="120px"/>
                            <!-- Light Logo icon -->
                            <img src="{{ asset('images/'.$setting->icon_menu) }}" alt="homepage" class="light-logo" width="150px" height="150px"/>
                        </b>
                    </a>
                </div>
                <!-- ============================================================== -->
                <!-- End Logo -->
                <!-- ============================================================== -->
                <!-- ============================================================== -->
                <!-- Toggle which is visible on mobile only -->
                <!-- ============================================================== -->
                <a class="topbartoggler d-block d-md-none waves-effect waves-light" href="javascript:void(0)"
                    data-toggle="collapse" data-target="#navbarSupportedContent"
                    aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><i
                        class="ti-more"></i></a>
            </div>
            <!-- ============================================================== -->
            <!-- End Logo -->
            <!-- ============================================================== -->
            <div class="navbar-collapse collapse" id="navbarSupportedContent">
                <!-- ============================================================== -->
                <!-- toggle and nav items -->
                <!-- ============================================================== -->
                <ul class="navbar-nav float-left mr-auto ml-3 pl-1">
                    <!-- Notification -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle pl-md-3 position-relative" href="javascript:void(0)"
                            id="bell" role="button" data-toggle="dropdown" aria-haspopup="true"
                            aria-expanded="false">
                            <span><i data-feather="bell" class="svg-icon"></i></span>
                            <span class="badge badge-primary notify-no rounded-circle">5</span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-left mailbox animated bounceInDown">
                            <ul class="list-style-none">
                                <li>
                                    <div class="message-center notifications position-relative">
                                        <!-- Message -->
                                        <a href="javascript:void(0)"
                                            class="message-item d-flex align-items-center border-bottom px-3 py-2">
                                            <div class="btn btn-danger rounded-circle btn-circle"><i
                                                    data-feather="airplay" class="text-white"></i></div>
                                            <div class="w-75 d-inline-block v-middle pl-2">
                                                <h6 class="message-title mb-0 mt-1">Launch Admin</h6>
                                                <span class="font-12 text-nowrap d-block text-muted">Just see
                                                    the my new admin!</span>
                                                <span class="font-12 text-nowrap d-block text-muted">9:30 AM</span>
                                            </div>
                                        </a>
                                        <!-- More message items can be added similarly -->
                                    </div>
                                </li>
                                <li>
                                    <a class="nav-link pt-3 text-center text-dark" href="javascript:void(0);">
                                        <strong>Check all notifications</strong>
                                        <i class="fa fa-angle-right"></i>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    <!-- End Notification -->
                </ul>
                <!-- ============================================================== -->
                <!-- Right side toggle and nav items -->
                <!-- ============================================================== -->
                <ul class="navbar-nav float-right">
                    <!-- ============================================================== -->
                    <!-- User profile and search -->
                    <!-- ============================================================== -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="javascript:void(0)" data-toggle="dropdown"
                            aria-haspopup="true" aria-expanded="false">
                            <img src="{{ asset('images/'.session()->get('foto')) }}" class="rounded-circle"
                                width="40"> 
                            <span class="ml-2 d-none d-lg-inline-block"><span
                                    class="text-dark">{{ session('nama') }} </span> <i data-feather="chevron-down"
                                    class="svg-icon"></i></span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right user-dd animated flipInY">
                            <a class="dropdown-item" href="{{ url('home/profile') }}"><i data-feather="user"
                                    class="svg-icon mr-2 ml-1"></i>
                                My Profile</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="{{ url('home/logout') }}"><i data-feather="power"
                                    class="svg-icon mr-2 ml-1"></i>
                                Logout</a>
                            <div class="dropdown-divider"></div>
                            <div class="pl-4 p-3"><a href="{{ url('home/profile') }}" class="btn btn-sm btn-info">View
                                    Profile</a></div>
                        </div>
                    </li>
                    <!-- ============================================================== -->
                    <!-- End User profile -->
                    <!-- ============================================================== -->
                </ul>
            </div>
        </nav>
    </header>
    <!-- ============================================================== -->
    <!-- End Topbar header -->
    <!-- ============================================================== -->
    <!-- ============================================================== -->
    <!-- Left Sidebar - style you can find in sidebar.scss  -->
    <!-- ============================================================== -->
    <aside class="left-sidebar" data-sidebarbg="skin6">
        <!-- Sidebar scroll-->
        <div class="scroll-sidebar" data-sidebarbg="skin6">

            <nav class="sidebar-nav">
                    <ul id="sidebarnav">
                    <?php if ($menu->dashboard == 1) { ?>
                        <li class="sidebar-item"> <a class="sidebar-link sidebar-link" href="{{ url('home/index') }}"
                                aria-expanded="false"><i data-feather="home" class="feather-icon"></i><span
                                    class="hide-menu">Dashboard</span></a></li>
                    <?php } ?>
                    <?php if ($menu->kasus == 1) { ?>
                        <li class="list-divider"></li>

                        <li class="sidebar-item"> <a class="sidebar-link sidebar-link" href="{{ url('home/dkasus/'. session()->get('id')) }}"
                                aria-expanded="false"><i  class="feather-icon icon-note"></i><span
                                    class="hide-menu">Kasus</span></a></li>
                    <?php } ?>
                    <?php if ($menu->data == 1) { ?>
                        <li class="list-divider"></li>
                        <li class="nav-small-cap"><span class="hide-menu">Data</span></li>

                        <li class="sidebar-item"> <a class="sidebar-link has-arrow" href="javascript:void(0)"
                                aria-expanded="false"><i class="feather-icon fas fa-database"></i><span
                                    class="hide-menu">Tables </span></a>
                            <ul aria-expanded="false" class="collapse  first-level base-level-line">
                                <li class="sidebar-item"><a href="{{ url('home/datakasus') }}" class="sidebar-link"><span
                                            class="hide-menu"> Kasus
                                        </span></a>
                                </li>
                                <li class="sidebar-item"><a href="{{ url('home/duser') }}" class="sidebar-link"><span
                                            class="hide-menu"> User
                                        </span></a>
                                </li>
                                </ul>
                        </li>
                                
                   

                        <li class="sidebar-item"> <a class="sidebar-link has-arrow" href="javascript:void(0)"
                                aria-expanded="false"><i  class="feather-icon fas fa-trash"></i><span
                                    class="hide-menu">Recycle Bin </span></a>
                            <ul aria-expanded="false" class="collapse  first-level base-level-line">
                                <li class="sidebar-item"><a href="{{ url('home/skasus') }}" class="sidebar-link"><span
                                            class="hide-menu"> Kasus
                                        </span></a>
                                </li>
                                <li class="sidebar-item"><a href="{{ url('home/suser') }}" class="sidebar-link"><span
                                            class="hide-menu"> User
                                        </span></a>
                                </li>
                            </ul>
                        </li>
                        <li class="sidebar-item"> <a class="sidebar-link" href="{{ url('home/laporan') }}"
                                aria-expanded="false"><i  class="feather-icon fas fa-newspaper"></i><span
                                    class="hide-menu">Laporan Kasus
                                </span></a>
                        </li>
                        <?php } ?>

                    <?php if ($menu->website == 1) { ?>
                        <li class="list-divider"></li>
                        <li class="nav-small-cap"><span class="hide-menu">Website</span></li>
                        <li class="sidebar-item"> <a class="sidebar-link has-arrow" href="javascript:void(0)"
                                aria-expanded="false"><i data-feather="bar-chart" class="feather-icon"></i><span
                                    class="hide-menu">Manage</span></a>
                            <ul aria-expanded="false" class="collapse  first-level base-level-line">
                                <li class="sidebar-item"><a href="{{ url('home/setting') }}" class="sidebar-link"><span
                                            class="hide-menu">Setting
                                        </span></a>
                                </li>
                                <li class="sidebar-item"><a href="{{ url('home/managemenu') }}" class="sidebar-link"><span
                                            class="hide-menu">Menu Manage
                                        </span></a>
                                </li>
                                
                            </ul>
                        </li>

                    <?php } ?>
                       

         </ul>
                </nav>
            <!-- End Sidebar navigation -->
        </div>
        <!-- End Sidebar scroll-->
    </aside>

