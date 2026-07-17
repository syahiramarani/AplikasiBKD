<nav class="pc-sidebar">
    <style>
        .pc-sidebar {
            background:
                radial-gradient(circle at top left, rgba(124, 58, 237, 0.22), transparent 28%),
                radial-gradient(circle at bottom right, rgba(6, 182, 212, 0.12), transparent 24%),
                linear-gradient(180deg, #221d55 0%, #1d184c 100%) !important;
            border-right: none !important;
            box-shadow: 8px 0 30px rgba(18, 15, 45, 0.18);
        }

        .pc-sidebar .navbar-wrapper {
            display: flex;
            flex-direction: column;
            height: 100%;
            position: relative;
        }

        .pc-sidebar .m-header {
            display: none;
        }

        .pc-sidebar .navbar-content {
            flex: 1;
            overflow-y: auto;
            padding: .75rem 0;
            scrollbar-width: none;
        }

        .pc-sidebar .navbar-content::-webkit-scrollbar {
            display: none;
        }

        /* Brand */
        .sb-brand {
            padding: 1.2rem 1.25rem 1rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.07);
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
            flex-shrink: 0;
            position: relative;
        }

        .sb-brand::after {
            content: '';
            position: absolute;
            left: 1.25rem;
            right: 1.25rem;
            bottom: -1px;
            height: 1px;
            background: linear-gradient(90deg, rgba(175, 169, 236, 0.28), rgba(175, 169, 236, 0));
        }

        .sb-brand-icon {
            width: 42px;
            height: 42px;
            border-radius: 13px;
            background: linear-gradient(135deg, #655ae8, #4f46c9);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            box-shadow: 0 10px 24px rgba(83, 74, 183, 0.35);
        }

        .sb-brand-text {
            font-size: 15px;
            font-weight: 700;
            color: #f3f2ff;
            letter-spacing: .01em;
            line-height: 1.2;
        }

        .sb-brand-sub {
            font-size: 10px;
            color: #9f98ef;
            margin-top: 3px;
            letter-spacing: .04em;
        }

        /* Section */
        .sb-section {
            font-size: 10px;
            font-weight: 700;
            color: #7f77dd;
            letter-spacing: .12em;
            text-transform: uppercase;
            padding: 1rem 1.25rem .45rem;
        }

        /* Nav items */
        .pc-sidebar .pc-navbar .pc-item {
            margin: 2px .75rem;
            list-style: none;
        }

        .pc-sidebar .pc-navbar .pc-item .pc-link {
            display: flex;
            align-items: center;
            gap: 11px;
            padding: .72rem .82rem;
            border-radius: 14px;
            text-decoration: none;
            transition: all 0.18s ease;
            position: relative;
            overflow: hidden;
        }

        .pc-sidebar .pc-navbar .pc-item .pc-link::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(90deg, rgba(255, 255, 255, 0.05), rgba(255, 255, 255, 0));
            opacity: 0;
            transition: opacity 0.18s ease;
            pointer-events: none;
        }

        .pc-sidebar .pc-navbar .pc-item .pc-link:hover {
            background: rgba(255, 255, 255, 0.08) !important;
            transform: translateX(3px);
        }

        .pc-sidebar .pc-navbar .pc-item .pc-link:hover::after {
            opacity: 1;
        }

        .pc-sidebar .pc-navbar .pc-item .pc-link.active {
            background: linear-gradient(135deg, #5f55db, #4e46b9) !important;
            box-shadow: 0 10px 20px rgba(79, 70, 201, 0.28);
        }

        .pc-link.active::before {
            content: '';
            position: absolute;
            left: 0;
            top: 10px;
            bottom: 10px;
            width: 4px;
            border-radius: 0 6px 6px 0;
            background: linear-gradient(180deg, #c4b5fd, #f0f9ff);
        }

        /* Icon */
        .sb-icon {
            width: 32px;
            height: 32px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            flex-shrink: 0;
            background: rgba(255, 255, 255, 0.07);
            color: #b8b2f2;
            transition: all 0.18s ease;
        }

        .pc-link:hover .sb-icon {
            background: rgba(255, 255, 255, 0.12);
            color: #f1efff;
            transform: scale(1.04);
        }

        .pc-link.active .sb-icon {
            background: rgba(255, 255, 255, 0.16);
            color: #fff;
        }

        /* Label */
        .pc-sidebar .pc-mtext {
            font-size: 13px;
            color: #b3aef0;
            font-weight: 500;
            transition: all 0.18s ease;
            white-space: nowrap;
        }

        .pc-sidebar .pc-link:hover .pc-mtext {
            color: #f3f2ff;
        }

        .pc-sidebar .pc-link.active .pc-mtext {
            color: #fff;
            font-weight: 700;
        }

        /* Badge */
        .sb-badge {
            margin-left: auto;
            font-size: 9px;
            font-weight: 700;
            background: rgba(255, 255, 255, 0.12);
            color: #e7e5ff;
            padding: 3px 8px;
            border-radius: 999px;
            letter-spacing: .03em;
        }

        .pc-link.active .sb-badge {
            background: rgba(255, 255, 255, 0.18);
            color: #fff;
        }

        /* Footer */
        .sb-footer {
            padding: .9rem 1rem 1rem;
            border-top: 1px solid rgba(255, 255, 255, 0.08);
            flex-shrink: 0;
            background: rgba(255, 255, 255, 0.02);
            backdrop-filter: blur(4px);
        }

        .sb-user {
            display: flex;
            align-items: center;
            gap: 10px;
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid rgba(255, 255, 255, 0.06);
            padding: .65rem .7rem;
            border-radius: 16px;
        }

        .sb-avatar {
            width: 38px;
            height: 38px;
            border-radius: 12px;
            background: linear-gradient(135deg, #655ae8, #4338ca);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: 700;
            color: #f5f3ff;
            flex-shrink: 0;
            box-shadow: 0 10px 20px rgba(83, 74, 183, 0.28);
        }

        .sb-uname {
            font-size: 12px;
            color: #f3f2ff;
            font-weight: 700;
            line-height: 1.2;
            max-width: 120px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .sb-urole {
            font-size: 10px;
            color: #9f98ef;
            margin-top: 2px;
            letter-spacing: .03em;
        }

        .sb-logout {
            margin-left: auto;
            width: 32px;
            height: 32px;
            border-radius: 10px;
            background: rgba(240, 153, 123, 0.14);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 15px;
            color: #f7a68b;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.18s ease;
            flex-shrink: 0;
        }

        .sb-logout:hover {
            background: rgba(240, 153, 123, 0.24);
            color: #ffb49c;
            transform: translateY(-1px);
        }

        /* Small polish */
        .pc-navbar {
            list-style: none;
            padding: 0;
            margin: 0;
        }
    </style>

    <div class="navbar-wrapper">
        {{-- BRAND --}}
        <a href="/dashboard" class="sb-brand">
            <div class="sb-brand-icon">
                <i class="ti ti-clipboard-text" style="color:#EEEDFE;font-size:18px;"></i>
            </div>
            <div>
                <div class="sb-brand-text">Aplikasi BKD</div>
                <div class="sb-brand-sub">Beban Kerja Dosen</div>
            </div>
        </a>

        {{-- NAV --}}
        <div class="navbar-content">
            <ul class="pc-navbar">
                {{-- MENU UTAMA --}}
                <li>
                    <div class="sb-section">Menu utama</div>
                </li>

                <li class="pc-item">
                    <a href="/dashboard" class="pc-link {{ request()->is('dashboard') ? 'active' : '' }}">
                        <div class="sb-icon"><i class="ti ti-layout-dashboard"></i></div>
                        <span class="pc-mtext">Dashboard</span>
                    </a>
                </li>

                @if (auth()->user()->role == 'p4m')
                    <li class="pc-item">
                        <a href="/User" class="pc-link {{ request()->is('User') ? 'active' : '' }}">
                            <div class="sb-icon"><i class="ti ti-users"></i></div>
                            <span class="pc-mtext">Users</span>
                            <span class="sb-badge">Admin</span>
                        </a>
                    </li>
                @endif

                {{-- DATA MASTER --}}
                <li>
                    <div class="sb-section">Data master</div>
                </li>
                @if (in_array(auth()->user()->role, ['kajur', 'p4m']))
                    <li class="pc-item">
                        <a href="/dosen"
                            class="pc-link {{ request()->is('dosen') || request()->is('dosen/*') ? 'active' : '' }}">
                            <div class="sb-icon"><i class="ti ti-school"></i></div>
                            <span class="pc-mtext">Data Dosen</span>
                        </a>
                    </li>
                @endif
                @if (auth()->user()->role !== 'kajur')
                    <li class="pc-item">
                        <a href="{{ route('jurusan.index') }}"
                            class="pc-link {{ request()->routeIs('jurusan.*') ? 'active' : '' }}">
                            <div class="sb-icon"><i class="ti ti-building-bank"></i></div>
                            <span class="pc-mtext">Jurusan</span>
                        </a>
                    </li>
                @endif

                @if (in_array(auth()->user()->role, ['kajur', 'p4m']))
                    <li class="pc-item">
                        <a href="{{ route('prodi.index') }}"
                            class="pc-link {{ request()->routeIs('prodi.*') ? 'active' : '' }}">
                            <div class="sb-icon"><i class="ti ti-sitemap"></i></div>
                            <span class="pc-mtext">Prodi</span>
                        </a>
                    </li>

                    <li class="pc-item">
                        <a href="{{ route('matakuliah.index') }}"
                            class="pc-link {{ request()->routeIs('matakuliah.*') ? 'active' : '' }}">
                            <div class="sb-icon"><i class="ti ti-book"></i></div>
                            <span class="pc-mtext">Mata Kuliah</span>
                        </a>
                    </li>
                @endif
                {{-- BKD --}}
                <li>
                    <div class="sb-section">BKD</div>
                </li>
                @if (auth()->user()->role !== 'kajur')
                    <li class="pc-item">
                        <a href="{{ route('beban-dosen.index') }}"
                            class="pc-link {{ request()->routeIs('beban-dosen.*') ? 'active' : '' }}">
                            <div class="sb-icon"><i class="ti ti-briefcase"></i></div>
                            <span class="pc-mtext">Beban Dosen</span>
                        </a>
                    </li>
                @endif

                @if (in_array(auth()->user()->role, ['kajur', 'p4m']))
                    <li class="pc-item">
                        <a href="{{ route('distribusi.index') }}"
                            class="pc-link {{ request()->routeIs('distribusi.*') ? 'active' : '' }}">
                            <div class="sb-icon"><i class="ti ti-share"></i></div>
                            <span class="pc-mtext">Distribusi</span>
                        </a>
                    </li>
                    <li class="pc-item">
                        <a href="{{ route('rekap-distribusi.index') }}"
                            class="pc-link {{ request()->routeIs('rekap-distribusi.*') ? 'active' : '' }}">
                            <div class="sb-icon"><i class="ti ti-report-analytics"></i></div>
                            <span class="pc-mtext">Rekap Laporan</span>
                            <span class="sb-badge">History</span>
                        </a>
                    </li>
                @endif
            </ul>
        </div>

        {{-- FOOTER USER --}}
        <div class="sb-footer">
            <div class="sb-user">
                @php
                    $words = explode(' ', auth()->user()->name);
                    $inisial = strtoupper(substr($words[0], 0, 1) . (isset($words[1]) ? substr($words[1], 0, 1) : ''));
                @endphp

                <div class="sb-avatar">{{ $inisial }}</div>

                <div>
                    <div class="sb-uname">{{ auth()->user()->name }}</div>
                    <div class="sb-urole">{{ ucfirst(auth()->user()->role ?? 'User') }}</div>
                </div>

                <a href="/logout" class="sb-logout" title="Logout">
                    <i class="ti ti-logout"></i>
                </a>
            </div>
        </div>
    </div>
</nav>
