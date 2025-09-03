<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>@yield('title', 'Admin Dashboard') - Academic Funding Gateway</title>
    <meta content="width=device-width, initial-scale=1.0, shrink-to-fit=no" name="viewport" />
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- Fonts and icons -->
    <script src="{{asset('assets/js/plugin/webfont/webfont.min.js')}}"></script>
    <script>
      WebFont.load({
        google: { families: ["Public Sans:300,400,500,600,700"] },
        custom: {
          families: [
            "Font Awesome 5 Solid",
            "Font Awesome 5 Regular",
            "Font Awesome 5 Brands",
            "simple-line-icons",
          ],
          urls: ["{{ asset('assets/css/fonts.min.css') }}"],
        },
        active: function () {
          sessionStorage.fonts = true;
        },
      });
    </script>

    <!-- CSS Files -->
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css')}}" />
    <link rel="stylesheet" href="{{ asset('assets/css/plugins.min.css')}}" />
    <link rel="stylesheet" href="{{ asset('assets/css/kaiadmin.min.css')}}" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css" rel="stylesheet">

    <!-- CSS Just for demo purpose, don't include it in your project -->
    <link rel="stylesheet" href="{{ asset('assets/css/demo.css')}}" />
   
    <style>
      .app-name {
        font-weight: 600;
        color: #4e73df;
        margin-left: 10px;
      }
      .sidebar[data-background-color="dark"] .nav-secondary > li > a.active {
        background-color: rgba(255, 255, 255, 0.1);
      }
      .main-header-logo .logo img {
        height: 35px;
      }
      .welcome-text {
        font-size: 1.1rem;
        margin-right: 15px;
      }
      .card-stats {
        transition: all 0.3s ease;
      }
      .card-stats:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
      }
    </style>
  </head>
  <body>
    <div class="wrapper">
      <!-- Sidebar -->
      <div class="sidebar" data-background-color="dark">
        <div class="sidebar-logo">
          <!-- Logo Header -->
          <div class="logo-header" data-background-color="dark">
            <a href="{{ route('admin.dashboard') }}" class="logo d-flex align-items-center">
              <!-- <img
                src="{{ asset('assets/img/logo.png') }}"
                alt="AFG Logo"
                class="navbar-brand"
                height="40"
                width="40"
              /> -->
              <span class="app-name ms-2">AFG Admin</span>
            </a>
            <div class="nav-toggle">
              <button class="btn btn-toggle toggle-sidebar">
                <i class="gg-menu-right"></i>
              </button>
              <button class="btn btn-toggle sidenav-toggler">
                <i class="gg-menu-left"></i>
              </button>
            </div>
            <button class="topbar-toggler more">
              <i class="gg-more-vertical-alt"></i>
            </button>
          </div>
          <!-- End Logo Header -->
        </div>
        <div class="sidebar-wrapper scrollbar scrollbar-inner">
          <div class="sidebar-content">
            <ul class="nav nav-secondary">
              <li class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <a href="{{ route('admin.dashboard') }}">
                  <i class="fas fa-home"></i>
                  <p>Dashboard</p>
                </a>
              </li>
              <li class="nav-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                <a href="{{ route('admin.users.index') }}">
                  <i class="fas fa-users"></i>
                  <p>Students</p>
                </a>
              </li>
              <li class="nav-item {{ request()->routeIs('admin.import.*') ? 'active' : '' }}">
                <a href="{{ route('admin.import.index') }}">
                  <i class="fas fa-file-import"></i>
                  <p>Import Data</p>
                </a>
              </li>
            </ul>
          </div>
        </div>
      </div>
      <!-- End Sidebar -->

      <div class="main-panel">
        <div class="main-header">
          <div class="main-header-logo">
            <!-- Logo Header -->
            <div class="logo-header" data-background-color="dark">
              <a href="{{ route('admin.dashboard') }}" class="logo">
                <img
                  src="{{ asset('assets/img/logo.png') }}"
                  alt="AFG Logo"
                  class="navbar-brand"
                  height="30"
                />
                <span class="app-name ms-2">AFG Admin</span>
              </a>
              <div class="nav-toggle">
                <button class="btn btn-toggle toggle-sidebar">
                  <i class="gg-menu-right"></i>
                </button>
                <button class="btn btn-toggle sidenav-toggler">
                  <i class="gg-menu-left"></i>
                </button>
              </div>
              <button class="topbar-toggler more">
                <i class="gg-more-vertical-alt"></i>
              </button>
            </div>
            <!-- End Logo Header -->
          </div>
          <!-- Navbar Header -->
          <nav class="navbar navbar-header navbar-header-transparent navbar-expand-lg border-bottom">
            <div class="container-fluid">
              <nav class="navbar navbar-header-left navbar-expand-lg navbar-form nav-search p-0 d-none d-lg-flex">
                <div class="input-group">
                  <div class="input-group-prepend">
                    <button type="submit" class="btn btn-search pe-1">
                      <i class="fa fa-search search-icon"></i>
                    </button>
                  </div>
                  <input
                    type="text"
                    placeholder="Search students, applications..."
                    class="form-control"
                  />
                </div>
              </nav>

              <ul class="navbar-nav topbar-nav ms-md-auto align-items-center">
                <li class="nav-item topbar-user dropdown hidden-caret">
                  <a
                    class="dropdown-toggle profile-pic"
                    data-bs-toggle="dropdown"
                    href="#"
                    aria-expanded="false"
                  >                   
                    <span class="profile-username">                      
                      <span class="fw-bold">{{ Auth::guard('admin')->user()->name ?? 'Admin' }}</span>
                    </span>
                  </a>
                  <ul class="dropdown-menu dropdown-user animated fadeIn">
                    <div class="dropdown-user-scroll scrollbar-outer">
                      <li>
                        <div class="user-box">
                          <div class="avatar-lg">                            
                          </div>
                          <div class="u-text">
                            <h4>{{ Auth::guard('admin')->user()->name ?? 'Admin User' }}</h4>
                            <p class="text-muted">{{ Auth::guard('admin')->user()->email ?? 'admin@afg.com' }}</p>
                            <!-- <a
                              href="#"
                              class="btn btn-xs btn-secondary btn-sm"
                              >View Profile</a
                            > -->
                          </div>
                        </div>
                      </li>
                      <li>
                        <!-- <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="#"><i class="fas fa-user me-2"></i>My Profile</a>
                        <a class="dropdown-item" href="#"><i class="fas fa-cog me-2"></i>Account Settings</a>
                        <div class="dropdown-divider"></div> -->
                        <form action="{{ route('admin.logout') }}" method="POST" id="logout-form">
                            @csrf
                            <a class="dropdown-item" href="#"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="fas fa-sign-out-alt me-2"></i>Logout</a>
                        </form>
                      </li>
                    </div>
                  </ul>
                </li>
              </ul>
            </div>
          </nav>
          <!-- End Navbar -->
        </div>

        <div class="container">
          <div class="page-inner">
            <div class="page-header">
              <h4 class="page-title">@yield('page-title', 'Dashboard')</h4>
              <ul class="breadcrumbs">
                <li class="nav-home">
                  <a href="{{ route('admin.dashboard') }}">
                    <i class="icon-home"></i>
                  </a>
                </li>
                <li class="separator">
                  <i class="icon-arrow-right"></i>
                </li>
                <li class="nav-item">
                  <a href="{{ route('admin.dashboard') }}">Academic Funding Gateway</a>
                </li>
                @yield('breadcrumbs')
              </ul>
            </div>
            
            <div class="page-category">
              <!-- Display alerts from session -->
              @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                  <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                  <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
              @endif

              @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                  <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                  <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
              @endif

              @if(session('warning'))
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                  <i class="fas fa-exclamation-triangle me-2"></i>{{ session('warning') }}
                  <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
              @endif

              {{-- Fixed: Handle import_errors array properly --}}
              @if(session('import_errors') && is_array(session('import_errors')) && count(session('import_errors')) > 0)
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                  <i class="fas fa-exclamation-triangle me-2"></i><strong>Import Errors:</strong>
                  <ul class="mb-0 mt-2">
                    @foreach(session('import_errors') as $error)
                      <li>{{ $error }}</li>
                    @endforeach
                  </ul>
                  <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
              @endif

              {{-- Handle Laravel validation errors (MessageBag) --}}
              @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                  <i class="fas fa-exclamation-circle me-2"></i><strong>Validation Errors:</strong>
                  <ul class="mb-0 mt-2">
                    @foreach ($errors->all() as $error)
                      <li>{{ $error }}</li>
                    @endforeach
                  </ul>
                  <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
              @endif
              
              <!-- Main content -->
              @yield('content')
            </div>
          </div>
        </div>

        <footer class="footer">
          <div class="container-fluid d-flex justify-content-between">
            <nav class="pull-left">
              <ul class="nav">
                <li class="nav-item">
                  <a class="nav-link" href="{{ route('admin.dashboard') }}">
                    Academic Funding Gateway
                  </a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="#"> Help </a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="#"> Privacy Policy </a>
                </li>
              </ul>
            </nav>
            <div class="copyright">
              {{ date('Y') }}, made with <i class="fa fa-heart heart text-danger"></i> for
              <a href="#">Academic Funding Gateway</a>
            </div>
          </div>
        </footer>
      </div>
    </div>
    <!--   Core JS Files   -->
    <script src="{{asset('assets/js/core/jquery-3.7.1.min.js')}}"></script>
    <script src="{{asset('assets/js/core/popper.min.js')}}"></script>
    <script src="{{asset('assets/js/core/bootstrap.min.js')}}"></script>

    <!-- jQuery Scrollbar -->
    <script src="{{asset('assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js')}}"></script>

    <!-- Chart JS -->
    <script src="{{asset('assets/js/plugin/chart.js/chart.min.js')}}"></script>

    <!-- jQuery Sparkline -->
    <script src="{{asset('assets/js/plugin/jquery.sparkline/jquery.sparkline.min.js')}}"></script>

    <!-- Chart Circle -->
    <script src="{{asset('assets/js/plugin/chart-circle/circles.min.js')}}"></script>

    <!-- Datatables -->
    <script src="{{asset('assets/js/plugin/datatables/datatables.min.js')}}"></script>

    <!-- Bootstrap Notify -->
    <script src="{{asset('assets/js/plugin/bootstrap-notify/bootstrap-notify.min.js')}}"></script>

    <!-- Sweet Alert -->
    <script src="{{asset('assets/js/plugin/sweetalert/sweetalert.min.js')}}"></script>

    <!-- Kaiadmin JS -->
    <script src="{{asset('assets/js/kaiadmin.min.js')}}"></script>
    
    <script>
      // Activate current menu item
      document.addEventListener('DOMContentLoaded', function() {
        const currentRoute = "{{ Route::currentRouteName() }}";
        const menuItems = document.querySelectorAll('.nav-secondary > li');
        
        menuItems.forEach(item => {
          const link = item.querySelector('a');
          if (link) {
            // Remove any existing active classes
            item.classList.remove('active');
            
            // Check if current route matches
            if (currentRoute.includes('dashboard') && link.href.includes('dashboard')) {
              item.classList.add('active');
            } else if (currentRoute.includes('users') && link.href.includes('users')) {
              item.classList.add('active');
            } else if (currentRoute.includes('import') && link.href.includes('import')) {
              item.classList.add('active');
            }
          }
        });

        // Auto-hide alerts after 5 seconds
        setTimeout(function() {
          const alerts = document.querySelectorAll('.alert');
          alerts.forEach(alert => {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
          });
        }, 5000);
      });

      // CSRF token setup for AJAX requests
      $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
      });
    </script>
    
    @stack('scripts')
  </body>
</html>