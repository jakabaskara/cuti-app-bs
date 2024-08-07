<div class="app-sidebar">
    <div class="logo">
        <a href="index.html" class="logo-icon"><span class="logo-text">Relico</span></a>
        <div class="sidebar-user-switcher user-activity-online">
            <a href="#">
                <img src="{{ asset('assets/images/avatars/avatar4.png') }}">
                <span class="activity-indicator"></span>
                <span class="user-info-text">{{ Str::limit($nama, 15) }}<br><span class="user-state-info">
                        {{ Str::limit($jabatan, 15) }}</span>
            </a>
        </div>
    </div>
    <div class="app-menu">
        <ul class="accordion-menu">
            {{-- <li class="sidebar-title">
                Apps
            </li> --}}
            <li class="{{ request()->route()->uri == 'admin' ? 'active-page' : '' }}">
                {{-- <a href="index.html" class="{{ if(R)route ? "active" : "" }}"><i class="material-icons-two-tone">dashboard</i>Dashboard</a> --}}
                <a href="{{ route('admin.index') }}"
                    class="{{ request()->route()->uri == 'admin/' ? 'active' : '' }}"><i
                        class="material-icons-two-tone">dashboard</i>Dashboard</a>
            </li>
            <li class="{{ request()->route()->uri == 'admin/karyawan' ? 'active-page' : '' }}">
                <a href="{{ route('admin.karyawan.index') }}"
                    class="{{ request()->route()->uri == 'admin/karyawan' ? 'active' : '' }}"><i
                        class="material-icons-two-tone">groups_2</i>Kelola Karyawan<span
                        class="badge rounded-pill badge-danger float-end"></span></a>
            </li>
            <li class="{{ request()->route()->uri == 'admin/user' ? 'active-page' : '' }}">
                <a href="{{ route('admin.user.index') }}"
                    class="{{ request()->route()->uri == 'admin/user' ? 'active' : '' }}"><i
                        class="material-icons-two-tone">person</i>Kelola User</a>
            </li>
            <li class="{{ request()->route()->uri == 'admin/sisacuti' ? 'active-page' : '' }}">
                <a href="{{ route('admin.sisacuti.index') }}"
                    class="{{ request()->route()->uri == 'admin/sisacuti' ? 'active' : '' }}"><i
                        class="material-icons-two-tone">cloud_queue</i>Kelola Sisa Cuti</a>
            </li>
            <li class="{{ request()->route()->uri == 'admin/pairing' ? 'active-page' : '' }}">
                <a href="{{ route('admin.pairing.index') }}"
                    class="{{ request()->route()->uri == 'admin/pairing' ? 'active' : '' }}"><i
                        class="material-icons-two-tone"><span class="material-icons">
                            account_tree
                        </span></i>Pairing<span class="badge rounded-pill badge-danger float-end"></span></a>
            </li>
            <li class="{{ request()->route()->uri == 'admin/keanggotaan' ? 'active-page' : '' }}">
                <a href="{{ route('admin.pairing.keanggotaan') }}"
                    class="{{ request()->route()->uri == 'admin/keanggotaan' ? 'active' : '' }}"><i
                        class="material-icons-two-tone"><span class="material-icons">
                            account_tree
                        </span></i>Keanggotaan<span class="badge rounded-pill badge-danger float-end"></span></a>
            </li>
            <li class="{{ request()->route()->uri == 'admin/cuti' ? 'active-page' : '' }}">
                <a href="{{ route('admin.cuti.index') }}"
                    class="{{ request()->route()->uri == 'admin/cuti' ? 'active' : '' }}"><i
                        class="material-icons-two-tone">event</i>Berita Karyawan Cuti</a>
            </li>
            <li class="{{ request()->route()->uri == 'admin/riwayat-cuti' ? 'active-page' : '' }}">
                <a href="{{ route('riwayat-cuti.index') }}"
                    class="{{ request()->route()->uri == 'admin/riwayat-cuti' ? 'active' : '' }}"><i
                        class="material-icons-two-tone">history</i>Riwayat Karyawan Cuti</a>
            </li>
            <li>
                <a href=""><i class="material-icons-two-tone">person</i>Akun<i
                        class="material-icons has-sub-menu">keyboard_arrow_right</i></a>
                <ul class="sub-menu">
                    {{-- <li>
                        <a href="#">Profile</a>
                    </li> --}}
                    <li>
                        <a href="{{ route('password.change') }}">Ganti Password</a>
                    </li>
                    <li>
                        <a href="{{ route('logout') }}">Log Out</a>
                    </li>
                </ul>
            </li>
            {{-- <li>
                <a href="calendar.html"><i class="material-icons-two-tone">calendar_today</i>Calendar<span
                        class="badge rounded-pill badge-success float-end">14</span></a>
            </li>
            <li>
                <a href="todo.html"><i class="material-icons-two-tone">done</i>Todo</a>
            </li>
            <li>
                <a href=""><i class="material-icons-two-tone">star</i>Pages<i
                        class="material-icons has-sub-menu">keyboard_arrow_right</i></a>
                <ul class="sub-menu">
                    <li>
                        <a href="pricing.html">Pricing</a>
                    </li>
                    <li>
                        <a href="invoice.html">Invoice</a>
                    </li>
                    <li>
                        <a href="settings.html">Settings</a>
                    </li>
                    <li>
                        <a href="#">Authentication<i
                                class="material-icons has-sub-menu">keyboard_arrow_right</i></a>
                        <ul class="sub-menu">
                            <li>
                                <a href="sign-in.html">Sign In</a>
                            </li>
                            <li>
                                <a href="sign-up.html">Sign Up</a>
                            </li>
                            <li>
                                <a href="lock-screen.html">Lock Screen</a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="error.html">Error</a>
                    </li>
                </ul>
            </li>
            <li class="sidebar-title">
                UI Elements
            </li>
            <li>
                <a href="#"><i class="material-icons-two-tone">color_lens</i>Styles<i
                        class="material-icons has-sub-menu">keyboard_arrow_right</i></a>
                <ul class="sub-menu">
                    <li>
                        <a href="styles-typography.html">Typography</a>
                    </li>
                    <li>
                        <a href="styles-code.html">Code</a>
                    </li>
                    <li>
                        <a href="styles-icons.html">Icons</a>
                    </li>
                </ul>
            </li>
            <li>
                <a href="#"><i class="material-icons-two-tone">grid_on</i>Tables<i
                        class="material-icons has-sub-menu">keyboard_arrow_right</i></a>
                <ul class="sub-menu">
                    <li>
                        <a href="tables-basic.html">Basic</a>
                    </li>
                    <li>
                        <a href="tables-datatable.html">DataTable</a>
                    </li>
                </ul>
            </li>
            <li>
                <a href=""><i class="material-icons-two-tone">sentiment_satisfied_alt</i>Elements<i
                        class="material-icons has-sub-menu">keyboard_arrow_right</i></a>
                <ul class="sub-menu">
                    <li>
                        <a href="ui-alerts.html">Alerts</a>
                    </li>
                    <li>
                        <a href="ui-avatars.html">Avatars</a>
                    </li>
                    <li>
                        <a href="ui-badge.html">Badge</a>
                    </li>
                    <li>
                        <a href="ui-breadcrumbs.html">Breadcrumbs</a>
                    </li>
                    <li>
                        <a href="ui-buttons.html">Buttons</a>
                    </li>
                    <li>
                        <a href="ui-button-groups.html">Button Groups</a>
                    </li>
                    <li>
                        <a href="ui-collapse.html">Collapse</a>
                    </li>
                    <li>
                        <a href="ui-dropdown.html">Dropdown</a>
                    </li>
                    <li>
                        <a href="ui-images.html">Images</a>
                    </li>
                    <li>
                        <a href="ui-pagination.html">Pagination</a>
                    </li>
                    <li>
                        <a href="ui-popovers.html">Popovers</a>
                    </li>
                    <li>
                        <a href="ui-progress.html">Progress</a>
                    </li>
                    <li>
                        <a href="ui-spinners.html">Spinners</a>
                    </li>
                    <li>
                        <a href="ui-toast.html">Toast</a>
                    </li>
                    <li>
                        <a href="ui-tooltips.html">Tooltips</a>
                    </li>
                </ul>
            </li>
            <li>
                <a href="#"><i class="material-icons-two-tone">card_giftcard</i>Components<i
                        class="material-icons has-sub-menu">keyboard_arrow_right</i></a>
                <ul class="sub-menu">
                    <li>
                        <a href="components-accordions.html">Accordions</a>
                    </li>
                    <li>
                        <a href="components-block-ui.html">Block UI</a>
                    </li>
                    <li>
                        <a href="components-cards.html">Cards</a>
                    </li>
                    <li>
                        <a href="components-carousel.html">Carousel</a>
                    </li>
                    <li>
                        <a href="components-countdown.html">Countdown</a>
                    </li>
                    <li>
                        <a href="components-lightbox.html">Lightbox</a>
                    </li>
                    <li>
                        <a href="components-lists.html">Lists</a>
                    </li>
                    <li>
                        <a href="components-modals.html">Modals</a>
                    </li>
                    <li>
                        <a href="components-tabs.html">Tabs</a>
                    </li>
                    <li>
                        <a href="components-session-timeout.html">Session Timeout</a>
                    </li>
                </ul>
            </li>
            <li>
                <a href="widgets.html"><i class="material-icons-two-tone">widgets</i>Widgets</a>
            </li>
            <li>
                <a href="#"><i class="material-icons-two-tone">edit</i>Forms<i
                        class="material-icons has-sub-menu">keyboard_arrow_right</i></a>
                <ul class="sub-menu">
                    <li>
                        <a href="forms-basic.html">Basic</a>
                    </li>
                    <li>
                        <a href="forms-input-groups.html">Input Groups</a>
                    </li>
                    <li>
                        <a href="forms-input-masks.html">Input Masks</a>
                    </li>
                    <li>
                        <a href="forms-layouts.html">Form Layouts</a>
                    </li>
                    <li>
                        <a href="forms-validation.html">Form Validation</a>
                    </li>
                    <li>
                        <a href="forms-file-upload.html">File Upload</a>
                    </li>
                    <li>
                        <a href="forms-text-editor.html">Text Editor</a>
                    </li>
                    <li>
                        <a href="forms-datepickers.html">Datepickers</a>
                    </li>
                    <li>
                        <a href="forms-select2.html">Select2</a>
                    </li>
                </ul>
            </li>
            <li>
                <a href="#"><i class="material-icons-two-tone">analytics</i>Charts<i
                        class="material-icons has-sub-menu">keyboard_arrow_right</i></a>
                <ul class="sub-menu">
                    <li>
                        <a href="charts-apex.html">Apex</a>
                    </li>
                    <li>
                        <a href="charts-chartjs.html">ChartJS</a>
                    </li>
                </ul>
            </li>
            <li class="sidebar-title">
                Layout
            </li>
            <li>
                <a href="#"><i class="material-icons-two-tone">view_agenda</i>Content<i
                        class="material-icons has-sub-menu">keyboard_arrow_right</i></a>
                <ul class="sub-menu">
                    <li>
                        <a href="content-page-headings.html">Page Headings</a>
                    </li>
                    <li>
                        <a href="content-section-headings.html">Section Headings</a>
                    </li>
                    <li>
                        <a href="content-left-menu.html">Left Menu</a>
                    </li>
                    <li>
                        <a href="content-right-menu.html">Right Menu</a>
                    </li>
                    <li>
                        <a href="content-boxed-content.html">Boxed Content</a>
                    </li>
                </ul>
            </li>
            <li>
                <a href="#"><i class="material-icons-two-tone">menu</i>Menu<i
                        class="material-icons has-sub-menu">keyboard_arrow_right</i></a>
                <ul class="sub-menu">
                    <li>
                        <a href="menu-off-canvas.html">Off-Canvas</a>
                    </li>
                    <li>
                        <a href="menu-standard.html">Standard</a>
                    </li>
                    <li>
                        <a href="menu-dark-sidebar.html">Dark Sidebar</a>
                    </li>
                    <li>
                        <a href="menu-hover-menu.html">Hover Menu</a>
                    </li>
                    <li>
                        <a href="menu-colored-sidebar.html">Colored Sidebar</a>
                    </li>
                </ul>
            </li>
            <li>
                <a href="#"><i class="material-icons-two-tone">view_day</i>Header<i
                        class="material-icons has-sub-menu">keyboard_arrow_right</i></a>
                <ul class="sub-menu">
                    <li>
                        <a href="header-basic.html">Basic</a>
                    </li>
                    <li>
                        <a href="header-full-width.html">Full-width</a>
                    </li>
                    <li>
                        <a href="header-transparent.html">Transparent</a>
                    </li>
                    <li>
                        <a href="header-large.html">Large</a>
                    </li>
                    <li>
                        <a href="header-colorful.html">Colorful</a>
                    </li>
                </ul>
            </li>
            <li class="sidebar-title">
                Other
            </li>
            <li>
                <a href="#"><i class="material-icons-two-tone">bookmark</i>Documentation</a>
            </li>
            <li>
                <a href="#"><i class="material-icons-two-tone">access_time</i>Change Log</a>
            </li> --}}
        </ul>
    </div>
</div>
