<div class="main-sidebar sidebar-style-2">
        <aside id="sidebar-wrapper">
          <div class="sidebar-brand">
            <a href="index.html"> <img alt="image" src="assets/img/logo.png" class="header-logo" /> <span
                class="logo-name">Otika</span>
            </a>
          </div>
          <ul class="sidebar-menu">
            <li class="menu-header">Main</li>
            <li class="dropdown active">
              <a href="{{ route('home') }}" class="nav-link"><i data-feather="monitor"></i><span>Dashboard</span></a>
            </li>
            <li class="dropdown">
              <a href="#" class="menu-toggle nav-link has-dropdown"><i data-feather="briefcase"></i><span>Careers</span></a>
              <ul class="dropdown-menu">
                <li><a class="nav-link" href="{{ route('careers.index') }}">All Careers</a></li>
                <li><a class="nav-link" href="{{ route('careers.create') }}">Add New Career</a></li>
              </ul>
            </li>
            <li class="dropdown">
              <a href="#" class="menu-toggle nav-link has-dropdown"><i data-feather="file-text"></i><span>Applied Jobs</span></a>
              <ul class="dropdown-menu">
                <li><a class="nav-link" href="#">All Applications</a></li>
              </ul>
            </li>
            <li class="dropdown">
              <a href="#" class="menu-toggle nav-link has-dropdown"><i data-feather="edit"></i><span>Blogs</span></a>
              <ul class="dropdown-menu">
                <li><a class="nav-link" href="#">All Blogs</a></li>
                <li><a class="nav-link" href="#">Add New Blog</a></li>
              </ul>
            </li>
            <li class="dropdown">
              <a href="#" class="menu-toggle nav-link has-dropdown"><i data-feather="message-circle"></i><span>Queries</span></a>
              <ul class="dropdown-menu">
                <li><a class="nav-link" href="#">All Queries</a></li>
              </ul>
            </li>
            <li class="dropdown">
              <a href="#" class="menu-toggle nav-link has-dropdown"><i data-feather="book-open"></i><span>Case Studies</span></a>
              <ul class="dropdown-menu">
                <li><a class="nav-link" href="#">All Case Studies</a></li>
                <li><a class="nav-link" href="#">Add New Case Study</a></li>
              </ul>
            </li>
          </ul>
        </aside>
      </div>