@php
    $role = strtolower($role ?? 'user');
    $currentUri = request()->route()->uri;

    $menus = [
        'admin' => [
            ['route' => 'admin.index', 'uri' => 'admin', 'icon' => 'dashboard', 'label' => 'Dashboard'],
            [
                'route' => 'admin.karyawan.index',
                'uri' => 'admin/karyawan',
                'icon' => 'groups_2',
                'label' => 'Kelola Karyawan',
            ],
            [
                'route' => 'admin.employee-sap.index',
                'uri' => 'admin/employee-sap',
                'icon' => 'badge',
                'label' => 'Data Employee SAP',
            ],
            ['route' => 'admin.user.index', 'uri' => 'admin/user', 'icon' => 'person', 'label' => 'Kelola User'],
            [
                'route' => 'admin.sisacuti.index',
                'uri' => 'admin/sisacuti',
                'icon' => 'cloud_queue',
                'label' => 'Kelola Sisa Cuti',
            ],
            [
                'route' => 'leave-balance-report.index',
                'uri' => 'auth/leave-balance-report',
                'icon' => 'assessment',
                'label' => 'Laporan Sisa Cuti',
            ],
            [
                'route' => 'organizational-chart.index',
                'uri' => 'auth/organizational-chart',
                'icon' => 'account_tree',
                'label' => 'Struktur Organisasi',
            ],
            [
                'route' => 'admin.pairing.index',
                'uri' => 'admin/pairing',
                'icon' => 'account_tree',
                'label' => 'Pairing',
            ],
            [
                'route' => 'admin.pairing.keanggotaan',
                'uri' => 'admin/keanggotaan',
                'icon' => 'account_tree',
                'label' => 'Keanggotaan',
            ],
            [
                'route' => 'admin.kalender.index',
                'uri' => 'admin/kalender',
                'icon' => 'calendar_month',
                'label' => 'Kalender Libur',
            ],
            [
                'route' => 'admin.cuti.index',
                'uri' => 'admin/cuti',
                'icon' => 'event',
                'label' => 'Berita Karyawan Cuti',
            ],
            [
                'route' => 'riwayat-cuti.index',
                'uri' => 'admin/riwayat-cuti',
                'icon' => 'history',
                'label' => 'Riwayat Karyawan Cuti',
            ],
        ],
        'asisten' => [
            ['route' => 'asisten.index', 'uri' => 'asisten', 'icon' => 'dashboard', 'label' => 'Dashboard'],
            [
                'route' => 'leave-balance-report.index',
                'uri' => 'auth/leave-balance-report',
                'icon' => 'assessment',
                'label' => 'Laporan Sisa Cuti',
            ],
            [
                'route' => 'organizational-chart.index',
                'uri' => 'auth/organizational-chart',
                'icon' => 'account_tree',
                'label' => 'Struktur Organisasi',
            ],
            [
                'route' => 'asisten.cuti.index',
                'uri' => 'asisten/cuti',
                'icon' => 'inbox',
                'label' => 'Berita Karyawan Cuti',
            ],
            [
                'route' => 'asisten.pengajuan-cuti',
                'uri' => 'asisten/pengajuan-cuti',
                'icon' => 'event',
                'label' => 'Pengajuan Cuti',
            ],
            [
                'route' => 'asisten.cuti-bersama.index',
                'uri' => 'asisten/cuti-bersama',
                'icon' => 'event_busy',
                'label' => 'Cuti Bersama',
            ],
        ],
        'kerani' => [
            ['route' => 'kerani.index', 'uri' => 'kerani', 'icon' => 'dashboard', 'label' => 'Dashboard'],
            [
                'route' => 'kerani.cuti.index',
                'uri' => 'kerani/cuti',
                'icon' => 'inbox',
                'label' => 'Berita Karyawan Cuti',
            ],
            [
                'route' => 'kerani.cuti-bersama',
                'uri' => 'kerani/cuti-bersama',
                'icon' => 'event_busy',
                'label' => 'Cuti Bersama',
            ],
        ],
        'manajer' => [
            ['route' => 'manajer.index', 'uri' => 'manajer', 'icon' => 'dashboard', 'label' => 'Dashboard'],
            [
                'route' => 'leave-balance-report.index',
                'uri' => 'auth/leave-balance-report',
                'icon' => 'assessment',
                'label' => 'Laporan Sisa Cuti',
            ],
            [
                'route' => 'organizational-chart.index',
                'uri' => 'auth/organizational-chart',
                'icon' => 'account_tree',
                'label' => 'Struktur Organisasi',
            ],
        ],
        'kabag' => [
            ['route' => 'kabag.index', 'uri' => 'kabag', 'icon' => 'dashboard', 'label' => 'Dashboard'],
            [
                'route' => 'leave-balance-report.index',
                'uri' => 'auth/leave-balance-report',
                'icon' => 'assessment',
                'label' => 'Laporan Sisa Cuti',
            ],
            [
                'route' => 'organizational-chart.index',
                'uri' => 'auth/organizational-chart',
                'icon' => 'account_tree',
                'label' => 'Struktur Organisasi',
            ],
        ],
        'gm' => [
            ['route' => 'gm.index', 'uri' => 'gm', 'icon' => 'dashboard', 'label' => 'Dashboard'],
            [
                'route' => 'leave-balance-report.index',
                'uri' => 'auth/leave-balance-report',
                'icon' => 'assessment',
                'label' => 'Laporan Sisa Cuti',
            ],
            [
                'route' => 'organizational-chart.index',
                'uri' => 'auth/organizational-chart',
                'icon' => 'account_tree',
                'label' => 'Struktur Organisasi',
            ],
        ],
        'sevp' => [
            ['route' => 'sevp.index', 'uri' => 'sevp', 'icon' => 'dashboard', 'label' => 'Dashboard'],
            [
                'route' => 'leave-balance-report.index',
                'uri' => 'auth/leave-balance-report',
                'icon' => 'assessment',
                'label' => 'Laporan Sisa Cuti',
            ],
            [
                'route' => 'organizational-chart.index',
                'uri' => 'auth/organizational-chart',
                'icon' => 'account_tree',
                'label' => 'Struktur Organisasi',
            ],
            ['route' => 'sevp.cuti.index', 'uri' => 'sevp/cuti', 'icon' => 'event', 'label' => 'Berita Karyawan Cuti'],
        ],
        'pic' => [['route' => 'pic.index', 'uri' => 'pic', 'icon' => 'dashboard', 'label' => 'Dashboard']],
    ];

    $roleMenus = $menus[$role] ?? [];
@endphp

<div class="app-sidebar">
    <div class="logo">
        <a href="index.html" class="logo-icon"><span class="logo-text">Relico</span></a>
        <div class="sidebar-user-switcher user-activity-online">
            <a href="#">
                <img src="{{ asset('assets/images/avatars/avatar4.png') }}">
                <span class="activity-indicator"></span>
                <span class="user-info-text">{{ Str::limit($nama ?? 'User', 15) }}<br>
                    <span class="user-state-info">{{ Str::limit($jabatan ?? 'Position', 15) }}</span>
                </span>
            </a>
        </div>
    </div>
    <div class="app-menu">
        <ul class="accordion-menu">
            @foreach ($roleMenus as $menu)
                <li class="{{ $currentUri == $menu['uri'] ? 'active-page' : '' }}">
                    <a href="{{ route($menu['route']) }}" class="{{ $currentUri == $menu['uri'] ? 'active' : '' }}">
                        <i class="material-icons-two-tone">{{ $menu['icon'] }}</i>{{ $menu['label'] }}
                        @if (in_array($menu['label'], ['Berita Karyawan Cuti', 'Pengajuan Cuti', 'Cuti Bersama']))
                            <span class="badge rounded-pill badge-danger float-end"></span>
                        @endif
                    </a>
                </li>
            @endforeach

            <li>
                <a href=""><i class="material-icons-two-tone">person</i>Akun<i
                        class="material-icons has-sub-menu">keyboard_arrow_right</i></a>
                <ul class="sub-menu">
                    <li>
                        <a href="{{ route('password.change') }}">Ganti Password</a>
                    </li>
                    <li>
                        <a href="{{ route('logout') }}">Log Out</a>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
</div>
