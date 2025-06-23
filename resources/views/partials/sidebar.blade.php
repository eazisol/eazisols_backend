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
          <ul class="sidebar-menu">
            <li class="menu-header">Main</li>
            <li class="dropdown active">
              <a href="{{ route('dashboard') }}" class="nav-link"><i data-feather="monitor"></i><span>Dashboard</span></a>
            </li>
            <li class="dropdown">
              <a class="menu-toggle nav-link has-dropdown"><i data-feather="briefcase"></i><span>Careers</span></a>
              <ul class="dropdown-menu">
                <li><a class="nav-link" href="{{ route('careers.index') }}">All Careers</a></li>
                <li><a class="nav-link" href="{{ route('careers.create') }}">Add New Career</a></li>
              </ul>
            </li>
            <li class="dropdown">
              <a class="menu-toggle nav-link has-dropdown"><i data-feather="file-text"></i><span>Applied Jobs</span></a>
              <ul class="dropdown-menu">
                <li><a class="nav-link" href="{{ route('applied-jobs.index') }}">All Applications</a></li>
              </ul>
            </li>
            <li class="dropdown">
              <a class="menu-toggle nav-link has-dropdown"><i data-feather="edit"></i><span>Blogs</span></a>
              <ul class="dropdown-menu">
                <li><a class="nav-link" href="{{ route('blogs.index') }}">All Blogs</a></li>
                <li><a class="nav-link" href="{{ route('blogs.create') }}">Add New Blog</a></li>
              </ul>
            </li>
            <li class="dropdown">
              <a class="menu-toggle nav-link has-dropdown"><i data-feather="tag"></i><span>Categories</span></a>
              <ul class="dropdown-menu">
                <li><a class="nav-link" href="{{ route('categories.index') }}">All Categories</a></li>
                <li><a class="nav-link" href="{{ route('categories.create') }}">Add New Category</a></li>
                {{-- <li><a class="nav-link" href="{{ route('categories.index', ['type' => 'blog']) }}">Blog Categories</a></li>
                <li><a class="nav-link" href="{{ route('categories.index', ['type' => 'career']) }}">Career Categories</a></li> --}}
              </ul>
            </li>
            <li class="dropdown">
              <a class="menu-toggle nav-link has-dropdown"><i data-feather="message-circle"></i><span>Queries</span></a>
              <ul class="dropdown-menu">
                <li><a class="nav-link" href="{{ route('queries.index') }}">All Queries</a></li>
                <li><a class="nav-link" href="{{ route('queries.index', ['type' => 'contact']) }}">Contact Queries</a></li>
                <li><a class="nav-link" href="{{ route('queries.index', ['type' => 'cost_calculator']) }}">Cost Calculator Queries</a></li>
              </ul>
            </li>
            <li class="dropdown">
              <a  class="menu-toggle nav-link has-dropdown"><i data-feather="book-open"></i><span>Case Studies</span></a>
              <ul class="dropdown-menu">
                <li><a class="nav-link" href="{{ route('case_studies.index') }}">All Case Studies</a></li>
                <li><a class="nav-link" href="{{ route('case_studies.create') }}">Add New Case Study</a></li>
              </ul>
            </li>
            
            <li class="menu-header">Configuration</li>
            <li>
              <a href="{{ route('settings.index') }}" class="nav-link"><i data-feather="settings"></i><span>Email Settings</span></a>
            </li>
          </ul>
        </aside>
      </div>