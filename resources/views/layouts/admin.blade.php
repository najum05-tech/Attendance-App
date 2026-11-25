<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>{{ $title ?? 'Admin Dashboard' }} - AttendanceApp</title>

  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">

  <style>
    /* --- Global Typography --- */
    body {
      font-family: 'Inter', sans-serif;
      background-color: #f4f6f9;
    }

    /* --- Navbar Styling --- */
    .main-header {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      border-bottom: none;
      box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }
    .main-header .nav-link {
      color: rgba(255,255,255,0.9) !important;
    }
    .main-header .nav-link:hover {
      color: #fff !important;
    }

    /* --- Sidebar Container --- */
    .main-sidebar {
      background: #1a1c23; /* Darker, modern background */
      box-shadow: 4px 0 10px rgba(0,0,0,0.1);
    }
    .brand-link {
      background: #1a1c23;
      border-bottom: 1px solid rgba(255,255,255,0.1);
    }

    /* --- Sidebar Buttons (The part you asked to customize) --- */
    .nav-sidebar .nav-item {
      margin-bottom: 4px;
    }

    .nav-sidebar .nav-link {
      border-radius: 8px;
      padding: 12px 15px;
      font-weight: 500;
      color: #b0b3b8;
      transition: all 0.3s ease;
    }

    /* Hover State */
    .nav-sidebar .nav-link:hover {
      background-color: rgba(255, 255, 255, 0.05);
      color: #fff;
      transform: translateX(5px); /* Slide effect */
    }

    /* Active State (Selected Menu Item) */
    .nav-sidebar .nav-link.active {
      background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);
      color: #fff;
      box-shadow: 0 4px 15px rgba(118, 75, 162, 0.4);
    }

    .nav-icon {
      font-size: 1.1rem;
      width: 30px;
      text-align: center;
      margin-right: 10px;
      transition: transform 0.3s;
    }

    .nav-link:hover .nav-icon {
      transform: scale(1.1);
    }

    /* --- Content Wrapper (Right Side) --- */
    .content-wrapper {
      background: #f4f6f9;
      transition: margin-left .3s ease-in-out;
    }
    
    .content-header {
      padding: 25px 20px;
    }

    /* --- Footer --- */
    .main-footer {
      background: #fff;
      border-top: none;
      font-size: 0.9rem;
      padding: 15px;
    }
  </style>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

  <nav class="main-header navbar navbar-expand navbar-dark">
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="{{ route('admin.dashboard') }}" class="nav-link">Home</a>
      </li>
    </ul>

    <ul class="navbar-nav ml-auto">
      <li class="nav-item">
        <form method="POST" action="{{ route('logout') }}" class="d-inline">
            @csrf
            <button type="submit" class="btn btn-sm btn-danger shadow-sm" style="border-radius: 20px; padding: 5px 15px;">
                <i class="fas fa-sign-out-alt mr-1"></i> Logout
            </button>
        </form>
      </li>
    </ul>
  </nav>

  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="{{ route('admin.dashboard') }}" class="brand-link">
      <span class="brand-text font-weight-bold ml-3" style="font-size: 1.2rem; background: linear-gradient(90deg, #fff, #ccc); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
        <i class="fas fa-graduation-cap mr-2"></i> AttendanceApp
      </span>
    </a>

    <div class="sidebar mt-3">
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          
          <li class="nav-item">
            <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>Dashboard</p>
            </a>
          </li>

          <li class="nav-header text-uppercase font-weight-bold text-muted mt-3 mb-1" style="font-size: 0.75rem; letter-spacing: 1px;">Management</li>

          <li class="nav-item">
            <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
              <i class="nav-icon fas fa-users"></i>
              <p>Users</p>
            </a>
          </li>

          <li class="nav-item">
            <a href="{{ route('admin.attendance.index') }}" class="nav-link {{ request()->routeIs('admin.attendance.*') ? 'active' : '' }}">
              <i class="nav-icon fas fa-calendar-check"></i>
              <p>Attendance</p>
            </a>
          </li>

          <li class="nav-item">
            <a href="{{ route('admin.leaves.index') }}" class="nav-link {{ request()->routeIs('admin.leaves.*') ? 'active' : '' }}">
              <i class="nav-icon fas fa-envelope-open-text"></i>
              <p>Leaves</p>
            </a>
          </li>

          <li class="nav-header text-uppercase font-weight-bold text-muted mt-3 mb-1" style="font-size: 0.75rem; letter-spacing: 1px;">Academic</li>

          <li class="nav-item">
            <a href="{{ route('admin.tasks.index') }}" class="nav-link {{ request()->routeIs('admin.tasks.*') ? 'active' : '' }}">
              <i class="nav-icon fas fa-tasks"></i>
              <p>Tasks</p>
            </a>
          </li>

          <li class="nav-item">
            <a href="{{ route('admin.grades.index') }}" class="nav-link {{ request()->routeIs('admin.grades.*') ? 'active' : '' }}">
              <i class="nav-icon fas fa-star"></i>
              <p>Grades</p>
            </a>
          </li>
          
          <li class="nav-item">
            <a href="{{ route('admin.reports.index') }}" class="nav-link {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
              <i class="nav-icon fas fa-chart-pie"></i>
              <p>Reports</p>
            </a>
          </li>

           <li class="nav-item">
            <a href="{{ route('admin.roles.index') }}" class="nav-link {{ request()->routeIs('admin.roles.*') ? 'active' : '' }}">
              <i class="nav-icon fas fa-user-shield"></i>
              <p>Roles</p>
            </a>
          </li>

        </ul>
      </nav>
    </div>
  </aside>

  <div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            @yield('header') 
        </div>
    </div>

    <section class="content">
      <div class="container-fluid">
        @yield('content')
      </div>
    </section>
  </div>

  <footer class="main-footer text-center">
    <strong>Copyright &copy; {{ date('Y') }} AttendanceApp.</strong> All rights reserved.
  </footer>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
</body>
</html>