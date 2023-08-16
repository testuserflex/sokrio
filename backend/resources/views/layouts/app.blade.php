<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Poscream | General Admin</title>

  <!-- Favicon -->
  <link rel="shortcut icon" href="{{ asset('assets/images/login/01.png') }}" />
  <link rel="stylesheet" href="{{ asset('assets/css/backend-plugin.min.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/css/fontawesome.min.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/css/fontawesome.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/css/backend.css?v=1.0.1') }}">
  <link rel="stylesheet" href="{{ asset('assets/vendor/line-awesome/dist/line-awesome/css/line-awesome.min.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/vendor/remixicon/fonts/remixicon.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/vendor/tui-calendar/tui-calendar/dist/tui-calendar.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/vendor/tui-calendar/tui-date-picker/dist/tui-date-picker.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/vendor/tui-calendar/tui-time-picker/dist/tui-time-picker.css') }}"></head>
  <style>
      input,select{
          border: 1px solid gray !important;
          height: 33px !important;
      }
      select{
          padding-bottom: 3px !important;
      }
  </style>
<body class="  ">
  <!-- loader Start -->
  {{-- <div id="loading">
        <div id="loading-center">
        </div>
  </div> --}}
  <!-- loader END -->
  <!-- Wrapper Start -->
  <div class="wrapper"
    style="background: url({{ asset('assets/images/background.png') }}); background-attachment: fixed; background-size: cover; ">
    <div class="iq-sidebar  sidebar-default ">
        <div class="iq-sidebar-logo d-flex align-items-center">
            <a href="/" class="header-logo">
                <img src="{{ asset('assets/images/login/01.png') }}" alt="logo" width="120px">
                {{-- <h2 class=" light-logo"></h2> --}}
            </a>
            <div class="iq-menu-bt-sidebar ml-0">
                <i class="las la-bars wrapper-menu"></i>
            </div>
        </div>
        <div class="data-scrollbar" data-scroll="1">
            <nav class="iq-sidebar-menu">
                <h6 class="sidebar-text text-uppercase ">General Admin</h6>
                <ul id="iq-sidebar-toggle" class="iq-menu">
                    <li class="active">
                        <a href="/" class="svg-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"
                                width="20" height="20">
                                <path
                                    d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" />
                            </svg>
                            <span class="ml-3">Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a href="#business" class="collapsed" data-toggle="collapse" aria-expanded="false">
                            <i class="fa fa-th-list"></i>
                            <span class="ml-3">Businesses</span>
                            <i class="las la-angle-right iq-arrow-right arrow-active"></i>
                            <i class="las la-angle-down iq-arrow-right arrow-hover"></i>
                        </a>
                        <ul id="business" class="iq-submenu collapse" data-parent="#iq-sidebar-toggle">
                            <li class="">
                                <a href="/businesses">
                                    <i class="las la-minus"></i><span>View Businesses</span>
                                </a>
                            </li>
                            <li class="">
                                <a href="#">
                                    <i class="las la-minus"></i><span>Business Profile</span>
                                </a>
                            </li>
                            <li class="">
                                <a href="#">
                                    <i class="las la-minus"></i><span>Business Licences</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="#branches" class="collapsed" data-toggle="collapse" aria-expanded="false">
                            <i class="fa fa-bars"></i>
                            <span class="ml-3">Branches</span>
                            <i class="las la-angle-right iq-arrow-right arrow-active"></i>
                            <i class="las la-angle-down iq-arrow-right arrow-hover"></i>
                        </a>
                        <ul id="branches" class="iq-submenu collapse" data-parent="#iq-sidebar-toggle">
                            <li class="">
                                <a href="/branches">
                                    <i class="las la-minus"></i><span>View Branches</span>
                                </a>
                            </li>
                            <li class="">
                                <a href="/permissions">
                                    <i class="las la-minus"></i><span> Branch Permissions</span>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <li class="">
                        <a href="/notification_contacts" class="svg-icon">
                            <i class="fa fa-bell"></i>
                            <span class="ml-3">Notification Contacts</span>
                        </a>
                    </li>
                    <li class="">
                        <a href="#" class="svg-icon">
                            <i class="fa fa-comments"></i>
                            <span class="ml-3">Subscription Alerts</span>
                        </a>
                    </li>
                    <li class="">
                        <a href="sms/packages" class="svg-icon">
                            <i class="fa fa-comment"></i>
                            <span class="ml-3">SMS Packages</span>
                        </a>
                    </li>
                    <li class="">
                        <a href="getusers" class="svg-icon">
                            <i class="fa fa-users"></i>
                            <span class="ml-3">Users</span>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
     <div class="iq-top-navbar">
        <div class="iq-navbar-custom">
            <nav class="navbar navbar-expand-lg navbar-light p-0">
                <div class="iq-navbar-logo d-flex align-items-center justify-content-between">
                    <i class="ri-menu-line wrapper-menu"></i>
                    <a href="#" class="header-logo">
                        <h4 class="logo-title text-uppercase">Poscream</h4>
                    </a>
                </div>
                <div class="navbar-breadcrumb">
                    <h3 class="ml-2">Point Of Sales Software</h3>
                </div>
                <div class="d-flex align-items-center">
                    <button class="navbar-toggler" type="button" data-toggle="collapse"
                        data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                        aria-label="Toggle navigation">
                        <i class="ri-menu-3-line"></i>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="navbar-nav ml-auto navbar-list align-items-center">
                            <li class="nav-item nav-icon search-content">
                                <a href="#" class="search-toggle rounded" id="dropdownSearch" data-toggle="dropdown"
                                    aria-haspopup="true" aria-expanded="false">
                                    <i class="ri-search-line"></i>
                                </a>
                                <div class="iq-search-bar iq-sub-dropdown dropdown-menu" aria-labelledby="dropdownSearch">
                                    <form action="#" class="searchbox p-2">
                                        <div class="form-group mb-0 position-relative">
                                            <input type="text" class="text search-input font-size-12"
                                                placeholder="type here to search...">
                                            <a href="#" class="search-link"><i class="las la-search"></i></a>
                                        </div>
                                    </form>
                                </div>
                            </li>
                            <li class="nav-item nav-icon nav-item-icon dropdown">
                                <a href="#" class="search-toggle dropdown-toggle" id="dropdownMenuButton"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                                        height="20px" width="20px">
                                        <path
                                            d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z" />
                                    </svg>
                                    <span class="bg-secondary dots "></span>
                                </a>
                                <div class="iq-sub-dropdown dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    <div class="card shadow-none m-0">
                                        <div class="card-body p-0 ">
                                            <div class="cust-title p-3">
                                                <div class="d-flex align-items-center justify-content-between">
                                                    <h5 class="mb-0">Notifications</h5>
                                                    <a class="badge badge-primary badge-card" href="#">3</a>
                                                </div>
                                            </div>
                                            <div class="px-3 pt-0 pb-0 sub-card">
                                                <a href="#" class="iq-sub-card">
                                                    <div class="media align-items-center cust-card py-3 border-bottom">
                                                        <div class="">
                                                            <img class="avatar-50 rounded-small"
                                                                src="{{ asset('assets/images/user/6.png') }}" alt="01">
                                                        </div>
                                                        <div class="media-body ml-3">
                                                            <div class="d-flex align-items-center justify-content-between">
                                                                <h6 class="mb-0">Emma Watson</h6>
                                                                <small class="text-dark"><b>12 : 47 pm</b></small>
                                                            </div>
                                                            <small class="mb-0">Lorem ipsum dolor sit amet</small>
                                                        </div>
                                                    </div>
                                                </a>
                                                <a href="#" class="iq-sub-card">
                                                    <div class="media align-items-center cust-card py-3 border-bottom">
                                                        <div class="">
                                                            <img class="avatar-50 rounded-small"
                                                                src="{{ asset('assets/images/user/7.png') }}" alt="02">
                                                        </div>
                                                        <div class="media-body ml-3">
                                                            <div class="d-flex align-items-center justify-content-between">
                                                                <h6 class="mb-0">Ashlynn Franci</h6>
                                                                <small class="text-dark"><b>11 : 30 pm</b></small>
                                                            </div>
                                                            <small class="mb-0">Lorem ipsum dolor sit amet</small>
                                                        </div>
                                                    </div>
                                                </a>
                                                <a href="#" class="iq-sub-card">
                                                    <div class="media align-items-center cust-card py-3">
                                                        <div class="">
                                                            <img class="avatar-50 rounded-small"
                                                                src="{{ asset('assets/images/user/08.png') }}" alt="03">
                                                        </div>
                                                        <div class="media-body ml-3">
                                                            <div class="d-flex align-items-center justify-content-between">
                                                                <h6 class="mb-0">Kianna Carder</h6>
                                                                <small class="text-dark"><b>11 : 21 pm</b></small>
                                                            </div>
                                                            <small class="mb-0">Lorem ipsum dolor sit amet</small>
                                                        </div>
                                                    </div>
                                                </a>
                                            </div>
                                            <a class="right-ic btn btn-primary btn-block position-relative p-2" href="#"
                                                role="button">
                                                View All
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li class="nav-item nav-icon dropdown caption-content">
                                <a href="#" class="search-toggle dropdown-toggle  d-flex align-items-center"
                                    id="dropdownMenuButton4" data-toggle="dropdown" aria-haspopup="true"
                                    aria-expanded="false">
                                    <div class="caption ml-3">
                                        <h6 class="mb-0 line-height">{{ Auth::user()->name}}
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2" viewBox="0 0 20 20"
                                                fill="currentColor" height="20px" width="20px">
                                                <path fill-rule="evenodd"
                                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </h6>
                                    </div>
                                </a>
                                <div class="iq-sub-dropdown dropdown-menu mt-2" aria-labelledby="dropdownMenuButton4">
                                    <div class="card mb-0">
                                        <div class="card-body">
                                            <div class="profile-header">
                                                <div class="cover-container text-center">
                                                    <div class="rounded-circle profile-icon bg-primary mx-auto d-block">M
                                                    </div>
                                                    <div class="profile-detail mt-3">
                                                        <h5><a href="#">{{ Auth::user()->name}}</a></h5>
                                                        <p>{{ Auth::user()->email}}</p>
                                                    </div>
                                                    <a href="{{ route('logout') }}" class="btn btn-primary"  onclick="event.preventDefault();
                                                    document.getElementById('logout-form').submit();">Log Out</a>
                                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                                        @csrf

                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
        </div>
    </div>    <div class="content-page">
@yield('content')
<!-- Page end  -->

    </div>
    <footer class="iq-footer">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-6 text-left"><a href="https://poscream.com/">Poscream Website</a></div>
                <div class="col-lg-6 text-right">
                    <span class="mr-1"><script>document.write(new Date().getFullYear())</script>©</span> <a href="https://nugsoft.com/" class="">Nugsoft Technologies</a>.
                </div>
            </div>
        </div>
    </footer>
  </div>
  <!-- Wrapper End-->

  <!-- Modal list start -->

  <!-- Backend Bundle JavaScript -->
  <script src="{{ asset('assets/js/backend-bundle.min.js') }}"></script>

  <!-- Table Treeview JavaScript -->
  <script src="{{ asset('assets/js/table-treeview.js') }}"></script>

  <!-- Chart Custom JavaScript -->
  <script src="{{ asset('assets/js/customizer.js') }}"></script>
  <!-- Chart Custom JavaScript -->
  <script async src="{{ asset('assets/js/fontawesome.min.js') }}"></script>
  <script async src="{{ asset('assets/js/fontawesome.js') }}"></script>
  <script async src="{{ asset('assets/js/chart-custom.js') }}"></script>
  <script src="{{ asset('assets/js/charts/01.js?v=1.0.1') }}"></script>
  <script src="{{ asset('assets/js/charts/02.js?v=1.0.1') }}"></script>
  <!-- Chart Custom JavaScript -->
  <script async src="{{ asset('assets/js/slider.js') }}"></script>

  <!-- app JavaScript -->
  <script src="{{ asset('assets/js/app.js?v=1.0.1') }}"></script>
</body>
</html>
