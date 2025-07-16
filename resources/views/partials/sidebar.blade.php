<?php
// Helper function to check if a route is currently active
function isActiveRoute($route, $output = 'active') {
    if (is_array($route)) {
        foreach ($route as $r) {
            if (Route::currentRouteName() == $r) return $output;
        }
    } else {
        if (Route::currentRouteName() == $route) return $output;
    }
    return '';
}

// Helper function to check if current route contains a string
function routeContains($string, $output = 'active') {
    if (Request::is("*{$string}*")) return $output;
    return '';
}

// Define sections and their routes for easier management
$sections = [
    'dashboard' => ['dashboard', 'home'],
    'careers' => ['careers.*'],
    'applied-jobs' => ['applied-jobs.*'],
    'employees' => ['employees.*'],
    'blogs' => ['blogs.*'],
    'categories' => ['categories.*'],
    'queries' => ['queries.*'],
    'case_studies' => ['case_studies.*'],
    'settings' => ['settings.*', 'locations.*'],
    'users' => ['users.*', 'roles.*', 'permissions.*'],
    'attendances' => ['attendances.*'],
    'leaves' => ['leaves.*']
];

// Determine which section is active
$activeSection = '';
foreach ($sections as $section => $routes) {
    foreach ($routes as $route) {
        if (str_contains($route, '.*')) {
            $baseName = str_replace('.*', '', $route);
            if (strpos(Route::currentRouteName(), $baseName) === 0) {
                $activeSection = $section;
                break 2;
            }
        } else if (Route::currentRouteName() == $route) {
            $activeSection = $section;
            break 2;
        }
    }
}
?>
<div class="main-sidebar sidebar-style-2">
        <aside id="sidebar-wrapper">
          <div class="sidebar-brand">
            <a href="{{ route('dashboard') }}">
                <style>
                @keyframes gentle-bounce {
                  0%, 100% { transform: translateY(0); }
                  50% { transform: translateY(-4px); }
                }
                .gentle-bounce {
                  animation: gentle-bounce 1.6s ease-in-out infinite;
                }
                </style>
                <img alt="image" src="{{ asset('assets/img/logo-removebg-preview.png') }}" class="header-logo gentle-bounce logo-collapsed" />
                 {{-- <img alt="image" src="assets/img/eazisols.png" class="header-logo logo-expanded" /> --}}
                <span class="logo-name">Eazisols</span>
            </a>
          </div>
          <style>
            /* Add pointer cursor to dropdown toggles */
            .menu-toggle.nav-link.has-dropdown,
            .menu-toggle.nav-link.has-dropdown.toggled {
              cursor: pointer;
            }
            /* Ensure dropdowns also have pointer cursor on hover */
            .dropdown-menu li a:hover {
              cursor: pointer;
            }
          </style>
          <ul class="sidebar-menu">
            <li class="menu-header">Main</li>
            
            {{-- Dashboard --}}
            @if(auth()->user()->hasPermission('dash_dashboard'))
            <li class="dropdown {{ $activeSection == 'dashboard' ? 'active' : '' }}">
              <a href="{{ route('dashboard') }}" class="nav-link"><i data-feather="monitor"></i><span>Dashboard</span></a>
            </li>
            @endif

            {{-- Users --}}
            @if(auth()->user()->hasPermission('dash_users'))
            <li class="dropdown {{ $activeSection == 'users' ? 'active' : '' }}">
              <a class="menu-toggle nav-link has-dropdown {{ $activeSection == 'users' ? 'toggled' : '' }}" style="cursor: pointer;"><i data-feather="users"></i><span>Users</span></a>
              <ul class="dropdown-menu" style="{{ $activeSection == 'users' ? 'display: block;' : '' }}">
                <li class="{{ request()->routeIs('users.index') ? 'active' : '' }}">
                  <a class="nav-link" href="{{ route('users.index') }}">All Users</a>
                </li>
                <li class="{{ request()->routeIs('users.create') ? 'active' : '' }}">
                  <a class="nav-link" href="{{ route('users.create') }}">Add New User</a>
                </li>
                @if(auth()->user()->hasPermission('dash_roles'))
                <li class="{{ request()->routeIs('roles.index') ? 'active' : '' }}">
                  <a class="nav-link" href="{{ route('roles.index') }}">Roles</a>
                </li>
                @endif
                @if(auth()->user()->hasPermission('dash_permissions'))
                <li class="{{ request()->routeIs('permissions.index') ? 'active' : '' }}">
                  <a class="nav-link" href="{{ route('permissions.index') }}">Permissions</a>
                </li>
                @endif
              </ul>
            </li>
            @endif

            {{-- Careers --}}
            @if(auth()->user()->hasPermission('dash_careers'))
            <li class="dropdown {{ $activeSection == 'careers' ? 'active' : '' }}">
              <a class="menu-toggle nav-link has-dropdown {{ $activeSection == 'careers' ? 'toggled' : '' }}" style="cursor: pointer;"><i data-feather="briefcase"></i><span>Careers</span></a>
              <ul class="dropdown-menu" style="{{ $activeSection == 'careers' ? 'display: block;' : '' }}">
                <li class="{{ request()->routeIs('careers.index') ? 'active' : '' }}">
                  <a class="nav-link" href="{{ route('careers.index') }}">All Jobs</a>
                </li>
                @if(auth()->user()->hasPermission('dash_careers_edit'))
                <li class="{{ request()->routeIs('careers.create') ? 'active' : '' }}">
                  <a class="nav-link" href="{{ route('careers.create') }}">Add New Jobs</a>
                </li>
                @endif
              </ul>
            </li>
            @endif

            {{-- Job Requests --}}
            @if(auth()->user()->hasPermission('dash_applied_jobs'))
            <li class="dropdown {{ $activeSection == 'applied-jobs' ? 'active' : '' }}">
              <a class="menu-toggle nav-link has-dropdown {{ $activeSection == 'applied-jobs' ? 'toggled' : '' }}" style="cursor: pointer;"><i data-feather="file-text"></i><span>Job Requests</span></a>
              <ul class="dropdown-menu" style="{{ $activeSection == 'applied-jobs' ? 'display: block;' : '' }}">
                <li class="{{ request()->routeIs('applied-jobs.index') ? 'active' : '' }}">
                  <a class="nav-link" href="{{ route('applied-jobs.index') }}">All Applications</a>
                </li>
              </ul>
            </li>
            @endif

            {{-- Employees --}}
            @if(auth()->user()->hasPermission('dash_users'))
            <li class="dropdown {{ $activeSection == 'employees' ? 'active' : '' }}">
              <a class="menu-toggle nav-link has-dropdown {{ $activeSection == 'employees' ? 'toggled' : '' }}" style="cursor: pointer;"><i data-feather="users"></i><span>Employees</span></a>
              <ul class="dropdown-menu" style="{{ $activeSection == 'employees' ? 'display: block;' : '' }}">
                <li class="{{ request()->routeIs('employees.index') ? 'active' : '' }}">
                  <a class="nav-link" href="{{ route('employees.index') }}">All Employees</a>
                </li>
                {{-- <li class="{{ request()->routeIs('employees.create') ? 'active' : '' }}">
                  <a class="nav-link" href="{{ route('employees.create') }}">Add New Employee</a>
                </li> --}}
              </ul>
            </li>
            @endif

            {{-- Blogs --}}
            @if(auth()->user()->hasPermission('dash_blogs'))
            <li class="dropdown {{ $activeSection == 'blogs' ? 'active' : '' }}">
              <a class="menu-toggle nav-link has-dropdown {{ $activeSection == 'blogs' ? 'toggled' : '' }}" style="cursor: pointer;"><i data-feather="edit"></i><span>Blogs</span></a>
              <ul class="dropdown-menu" style="{{ $activeSection == 'blogs' ? 'display: block;' : '' }}">
                <li class="{{ request()->routeIs('blogs.index') ? 'active' : '' }}">
                  <a class="nav-link" href="{{ route('blogs.index') }}">All Blogs</a>
                </li>
                @if(auth()->user()->hasPermission('dash_blogs_edit'))
                <li class="{{ request()->routeIs('blogs.create') ? 'active' : '' }}">
                  <a class="nav-link" href="{{ route('blogs.create') }}">Add New Blog</a>
                </li>
                @endif
              </ul>
            </li>
            @endif

            {{-- Categories --}}
            @if(auth()->user()->hasPermission('dash_categories'))
            <li class="dropdown {{ $activeSection == 'categories' ? 'active' : '' }}">
              <a class="menu-toggle nav-link has-dropdown {{ $activeSection == 'categories' ? 'toggled' : '' }}" style="cursor: pointer;"><i data-feather="tag"></i><span>Categories</span></a>
              <ul class="dropdown-menu" style="{{ $activeSection == 'categories' ? 'display: block;' : '' }}">
                <li class="{{ request()->routeIs('categories.index') ? 'active' : '' }}">
                  <a class="nav-link" href="{{ route('categories.index') }}">All Categories</a>
                </li>
                @if(auth()->user()->hasPermission('dash_categories_edit'))
                <li class="{{ request()->routeIs('categories.create') ? 'active' : '' }}">
                  <a class="nav-link" href="{{ route('categories.create') }}">Add New Category</a>
                </li>
                @endif
              </ul>
            </li>
            @endif

            {{-- Queries --}}
            @if(auth()->user()->hasPermission('dash_queries'))
            <li class="dropdown {{ $activeSection == 'queries' ? 'active' : '' }}">
              <a class="menu-toggle nav-link has-dropdown {{ $activeSection == 'queries' ? 'toggled' : '' }}" style="cursor: pointer;"><i data-feather="message-circle"></i><span>Queries</span></a>
              <ul class="dropdown-menu" style="{{ $activeSection == 'queries' ? 'display: block;' : '' }}">
                <li class="{{ request()->routeIs('queries.index') && !request('type') ? 'active' : '' }}">
                  <a class="nav-link" href="{{ route('queries.index') }}">All Queries</a>
                </li>
                <li class="{{ request()->routeIs('queries.index') && request('type') == 'contact' ? 'active' : '' }}">
                  <a class="nav-link" href="{{ route('queries.index', ['type' => 'contact']) }}">Contact Queries</a>
                </li>
                <li class="{{ request()->routeIs('queries.index') && request('type') == 'cost_calculator' ? 'active' : '' }}">
                  <a class="nav-link" href="{{ route('queries.index', ['type' => 'cost_calculator']) }}">Cost Calculator Queries</a>
                </li>
              </ul>
            </li>
            @endif

            {{-- Case Studies --}}
            @if(auth()->user()->hasPermission('dash_case_studies'))
            <li class="dropdown {{ $activeSection == 'case_studies' ? 'active' : '' }}">
              <a class="menu-toggle nav-link has-dropdown {{ $activeSection == 'case_studies' ? 'toggled' : '' }}" style="cursor: pointer;"><i data-feather="book-open"></i><span>Case Studies</span></a>
              <ul class="dropdown-menu" style="{{ $activeSection == 'case_studies' ? 'display: block;' : '' }}">
                <li class="{{ request()->routeIs('case_studies.index') ? 'active' : '' }}">
                  <a class="nav-link" href="{{ route('case_studies.index') }}">All Case Studies</a>
                </li>
                @if(auth()->user()->hasPermission('dash_case_studies_edit'))
                <li class="{{ request()->routeIs('case_studies.create') ? 'active' : '' }}">
                  <a class="nav-link" href="{{ route('case_studies.create') }}">Add New Case Study</a>
                </li>
                @endif
              </ul>
            </li>
            @endif

            {{-- Attendance --}}
            @if(auth()->user()->hasPermission('dash_attendance'))
            <li class="dropdown {{ $activeSection == 'attendances' ? 'active' : '' }}">
              <a class="menu-toggle nav-link has-dropdown {{ $activeSection == 'attendances' ? 'toggled' : '' }}" style="cursor: pointer;"><i data-feather="clock"></i><span>Attendance</span></a>
              <ul class="dropdown-menu" style="{{ $activeSection == 'attendances' ? 'display: block;' : '' }}">
                <li class="{{ request()->routeIs('attendances.dashboard') ? 'active' : '' }}">
                  <a class="nav-link" href="{{ route('attendances.dashboard') }}">My Attendance</a>
                </li>
                <li class="{{ request()->routeIs('attendances.index') ? 'active' : '' }}">
                  <a class="nav-link" href="{{ route('attendances.index') }}">Attendance Register</a>
                </li>
                {{-- <li class="{{ request()->routeIs('attendances.report') ? 'active' : '' }}">
                  <a class="nav-link" href="{{ route('attendances.report') }}">Reports</a>
                </li> --}}
              </ul>
            </li>
            @endif

            {{-- Leave Management --}}
            @if(auth()->user()->hasPermission('dash_leaves'))
            <li class="dropdown {{ $activeSection == 'leaves' ? 'active' : '' }}">
              <a class="menu-toggle nav-link has-dropdown {{ $activeSection == 'leaves' ? 'toggled' : '' }}" style="cursor: pointer;"><i data-feather="calendar"></i><span>Leave Management</span></a>
              <ul class="dropdown-menu" style="{{ $activeSection == 'leaves' ? 'display: block;' : '' }}">
                <li class="{{ request()->routeIs('leaves.index') ? 'active' : '' }}">
                  <a class="nav-link" href="{{ route('leaves.index') }}">Leave Requests</a>
                </li>
                <li class="{{ request()->routeIs('leaves.create') ? 'active' : '' }}">
                  <a class="nav-link" href="{{ route('leaves.create') }}">Apply for Leave</a>
                </li>
                {{-- <li class="{{ request()->routeIs('leaves.calendar') ? 'active' : '' }}">
                  <a class="nav-link" href="{{ route('leaves.calendar') }}">Leave Calendar</a>
              </li> --}}
              <li class="{{ request()->routeIs('leaves.history') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('leaves.history') }}">Leave History</a>
              </li>
            </ul>
          </li>
            @endif

            <li class="menu-header">Configuration</li>
            
            

            {{-- Settings --}}
            @if(auth()->user()->hasPermission('dash_settings'))
            <li class="dropdown {{ $activeSection == 'settings' ? 'active' : '' }}">
              <a class="menu-toggle nav-link has-dropdown {{ $activeSection == 'settings' ? 'toggled' : '' }}" style="cursor: pointer;"><i data-feather="settings"></i><span>Settings</span></a>
              <ul class="dropdown-menu" style="{{ $activeSection == 'settings' ? 'display: block;' : '' }}">
                <li class="{{ request()->routeIs('settings.index') ? 'active' : '' }}">
                  <a class="nav-link" href="{{ route('settings.index') }}">Email Settings</a>
                </li>
                <li class="{{ request()->routeIs('locations.index') ? 'active' : '' }}">
                  <a class="nav-link" href="{{ route('locations.index') }}">Location Settings</a>
                </li>
              </ul>
            </li>
            @endif

          </ul>
        </aside>
      </div>