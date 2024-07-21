<div class="app-header">
    <nav class="navbar navbar-light navbar-expand-lg">
        <div class="container-fluid">
            <div class="navbar-nav" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link hide-sidebar-toggle-button" href="#"><i
                                class="material-icons">first_page</i></a>
                    </li>
                    {{-- <li class="nav-item dropdown hidden-on-mobile">
                        <a class="nav-link dropdown-toggle" href="#" id="addDropdownLink" role="button"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="material-icons">add</i>
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="addDropdownLink">
                            <li><a class="dropdown-item" href="#">New Workspace</a></li>
                            <li><a class="dropdown-item" href="#">New Board</a></li>
                            <li><a class="dropdown-item" href="#">Create Project</a></li>
                        </ul>
                    </li> --}}
                    {{-- <li class="nav-item dropdown hidden-on-mobile">
                        <a class="nav-link dropdown-toggle" href="#" id="exploreDropdownLink" role="button"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="material-icons-outlined">explore</i>
                        </a>
                        <ul class="dropdown-menu dropdown-lg large-items-menu" aria-labelledby="exploreDropdownLink">
                            <li>
                                <h6 class="dropdown-header">Repositories</h6>
                            </li>
                            <li>
                                <a class="dropdown-item" href="#">
                                    <h5 class="dropdown-item-title">
                                        Neptune iOS
                                        <span class="badge badge-warning">1.0.2</span>
                                        <span class="hidden-helper-text">switch<i
                                                class="material-icons">keyboard_arrow_right</i></span>
                                    </h5>
                                    <span class="dropdown-item-description">Lorem Ipsum is simply dummy text of the
                                        printing and typesetting industry.</span>
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="#">
                                    <h5 class="dropdown-item-title">
                                        Neptune Android
                                        <span class="badge badge-info">dev</span>
                                        <span class="hidden-helper-text">switch<i
                                                class="material-icons">keyboard_arrow_right</i></span>
                                    </h5>
                                    <span class="dropdown-item-description">Lorem Ipsum is simply dummy text of the
                                        printing and typesetting industry.</span>
                                </a>
                            </li>
                            <li class="dropdown-btn-item d-grid">
                                <button class="btn btn-primary">Create new repository</button>
                            </li>
                        </ul>
                    </li> --}}
                </ul>

            </div>
            <div class="d-flex">
                <ul class="navbar-nav">
                    {{-- <li class="nav-item hidden-on-mobile">
                        <a class="nav-link active" href="#">Beranda</a>
                    </li> --}}
                    <li class="nav-item hidden-on-mobile">
                        <a class="nav-link active" href="#">
                            @if (request()->route()->uri == 'kerani')
                                Dashboard
                            @elseif(request()->route()->uri == 'kerani/cuti')
                                Berita Karyawan Cuti
                            @elseif(request()->route()->uri == 'kerani/cuti-bersama')
                                Cuti Bersama
                            @else
                                Ganti Password
                            @endif
                        </a>
                    </li>



                    {{-- <li class="nav-item hidden-on-mobile">
                        <a class="nav-link" href="#">Reports</a>
                    </li>
                    <li class="nav-item hidden-on-mobile">
                        <a class="nav-link" href="#">Projects</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link toggle-search" href="#"><i class="material-icons">search</i></a>
                    </li>
                    <li class="nav-item hidden-on-mobile">
                        <a class="nav-link language-dropdown-toggle" href="#" id="languageDropDown"
                            data-bs-toggle="dropdown"><img src="{{ asset('assets/images/avatars/avatar.png') }}" alt=""></a>
                        <ul class="dropdown-menu dropdown-menu-end language-dropdown"
                            aria-labelledby="languageDropDown">
                            <li><a class="dropdown-item" href="#"><img
                                        src="{{ asset('assets/images/avatars/avatar.png') }}" alt="">German</a></li>
                            <li><a class="dropdown-item" href="#"><img src="{{ asset('assets/images/avatars/avatar.png') }}"
                                        alt="">Italian</a></li>
                            <li><a class="dropdown-item" href="#"><img src="{{ asset('assets/images/avatars/avatar.png') }}"
                                        alt="">Chinese</a></li>
                        </ul>
                    </li> --}}

                    <li class="nav-item hidden-on-mobile">
                        <a class="nav-link nav-notifications-toggle" id="notificationsDropDown" href="#"
                            data-bs-toggle="dropdown"><span class="material-icons pt-2 pb-1">
                                person
                            </span></a>
                        <div class="dropdown-menu dropdown-menu-end notifications-dropdown"
                            aria-labelledby="notificationsDropDown">
                            <h6 class="dropdown-header">Akun</h6>
                            <div class="notifications-dropdown-list">
                                {{-- <a href="#">
                                    <div class="notifications-dropdown-item">
                                        <div class="notifications-dropdown-item-image">
                                            <span class="notifications-badge bg-info text-white">
                                                <i class="material-icons-outlined">account_circle</i>
                                            </span>
                                            <div class="notifications-dropdown-item-text">
                                                <p class="bold-notifications-text">Profile</p>
                                            </div>
                                        </div>
                                    </div>
                                </a> --}}
                                <a href="{{ route('password.change') }}">
                                    <div class="notifications-dropdown-item">
                                        <div class="notifications-dropdown-item-image">
                                            <span class="notifications-badge bg-danger text-white">
                                                <i class="material-icons-outlined">lock</i>
                                            </span>
                                            <div class="notifications-dropdown-item-text">
                                                <p class="bold-notifications-text">Ganti Password</p>
                                                {{-- <small>18:00</small> --}}
                                            </div>
                                        </div>
                                    </div>
                                </a>
                                <a href="{{ route('logout') }}">
                                    <div class="notifications-dropdown-item">
                                        <div class="notifications-dropdown-item-image">
                                            <span class="notifications-badge bg-success text-white">
                                                <i class="material-icons-outlined">logout</i>
                                            </span>
                                            <div class="notifications-dropdown-item-text">
                                                <p>Logout</p>
                                                {{-- <small>yesterday</small> --}}
                                            </div>
                                        </div>
                                    </div>
                                </a>
                                {{-- <a href="#">
                                    <div class="notifications-dropdown-item">
                                        <div class="notifications-dropdown-item-image">
                                            <span class="notifications-badge">
                                                <img src="{{ asset('assets/images/avatars/avatar.png') }}" alt="">
                                            </span>
                                        </div>
                                        <div class="notifications-dropdown-item-text">
                                            <p>Praesent sodales lobortis velit ac pellentesque</p>
                                            <small>yesterday</small>
                                        </div>
                                    </div>
                                </a>
                                <a href="#">
                                    <div class="notifications-dropdown-item">
                                        <div class="notifications-dropdown-item-image">
                                            <span class="notifications-badge">
                                                <img src="{{ asset('assets/images/avatars/avatar.png') }}" alt="">
                                            </span>
                                        </div>
                                        <div class="notifications-dropdown-item-text">
                                            <p>Praesent lacinia ante eget tristique mattis. Nam sollicitudin velit sit
                                                amet auctor porta</p>
                                            <small>yesterday</small>
                                        </div>
                                    </div>
                                </a> --}}
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</div>
