<header class="pc-header">
    <style>
        .pc-header {
            background: #fff !important;
            border-bottom: 0.5px solid rgba(0, 0, 0, 0.08) !important;
            box-shadow: none !important;
        }

        .pc-header .header-wrapper {
            display: flex;
            align-items: center;
            padding: 0 1.25rem;
            height: 54px;
            gap: 10px;
        }

        /* Hamburger */
        .tb-toggle {
            width: 34px;
            height: 34px;
            border-radius: 8px;
            background: transparent;
            border: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #6b7280;
            font-size: 18px;
            flex-shrink: 0;
            transition: background 0.12s;
        }

        .tb-toggle:hover {
            background: #f3f4f6;
            color: #374151;
        }

        /* Search */
        .tb-search-wrap {
            position: relative;
            flex: 1;
            max-width: 280px;
        }

        .tb-search-icon {
            position: absolute;
            left: 10px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 14px;
            color: #9ca3af;
            pointer-events: none;
        }

        .tb-search {
            width: 100%;
            height: 34px;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 0 10px 0 32px;
            font-size: 13px;
            background: #f9fafb;
            color: #111827;
            outline: none;
            transition: border-color 0.12s, background 0.12s;
        }

        .tb-search:focus {
            border-color: #534AB7;
            background: #fff;
        }

        /* Right actions */
        .tb-actions {
            margin-left: auto;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .tb-btn {
            width: 34px;
            height: 34px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 17px;
            color: #6b7280;
            background: transparent;
            border: none;
            cursor: pointer;
            position: relative;
            transition: background 0.12s, color 0.12s;
            text-decoration: none;
        }

        .tb-btn:hover {
            background: #f3f4f6;
            color: #374151;
        }

        .tb-dot {
            width: 7px;
            height: 7px;
            background: #D85A30;
            border-radius: 50%;
            position: absolute;
            top: 6px;
            right: 6px;
            border: 1.5px solid #fff;
        }

        .tb-divider {
            width: 1px;
            height: 20px;
            background: #e5e7eb;
            margin: 0 6px;
            flex-shrink: 0;
        }

        /* User pill */
        .tb-user {
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            padding: 4px 8px 4px 4px;
            border-radius: 8px;
            text-decoration: none;
            transition: background 0.12s;
        }

        .tb-user:hover {
            background: #f3f4f6;
        }

        .tb-avatar {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background: #534AB7;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 11px;
            font-weight: 500;
            color: #EEEDFE;
            flex-shrink: 0;
        }

        .tb-uname {
            font-size: 13px;
            font-weight: 500;
            color: #111827;
        }

        .tb-chevron {
            font-size: 12px;
            color: #9ca3af;
        }

        /* Dropdown notification */
        .tb-dropdown {
            position: relative;
        }

        .tb-dropdown-menu {
            display: none;
            position: absolute;
            top: calc(100% + 8px);
            right: 0;
            width: 300px;
            background: #fff;
            border: 0.5px solid rgba(0, 0, 0, 0.1);
            border-radius: 12px;
            overflow: hidden;
            z-index: 9999;
        }

        .tb-dropdown:hover .tb-dropdown-menu,
        .tb-dropdown.open .tb-dropdown-menu {
            display: block;
        }

        .dd-header {
            padding: .75rem 1rem;
            border-bottom: 0.5px solid #f3f4f6;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .dd-header span {
            font-size: 13px;
            font-weight: 500;
            color: #111827;
        }

        .dd-header a {
            font-size: 11px;
            color: #534AB7;
            text-decoration: none;
        }

        .dd-item {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            padding: .75rem 1rem;
            border-bottom: 0.5px solid #f9fafb;
            text-decoration: none;
            transition: background 0.1s;
        }

        .dd-item:hover {
            background: #EEEDFE22;
        }

        .dd-item:last-child {
            border-bottom: none;
        }

        .dd-dot {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            flex-shrink: 0;
            margin-top: 5px;
        }

        .dd-dot.purple {
            background: #534AB7;
        }

        .dd-dot.teal {
            background: #0F6E56;
        }

        .dd-dot.amber {
            background: #854F0B;
        }

        .dd-text {
            font-size: 12px;
            color: #374151;
            line-height: 1.4;
        }

        .dd-time {
            font-size: 11px;
            color: #9ca3af;
            margin-top: 2px;
        }

        /* User dropdown */
        .tb-user-dd {
            position: relative;
        }

        .tb-user-dd-menu {
            display: none;
            position: absolute;
            top: calc(100% + 8px);
            right: 0;
            width: 200px;
            background: #fff;
            border: 0.5px solid rgba(0, 0, 0, 0.1);
            border-radius: 12px;
            overflow: hidden;
            z-index: 9999;
        }

        .tb-user-dd:hover .tb-user-dd-menu,
        .tb-user-dd.open .tb-user-dd-menu {
            display: block;
        }

        .udd-head {
            padding: .85rem 1rem;
            border-bottom: 0.5px solid #f3f4f6;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .udd-name {
            font-size: 13px;
            font-weight: 500;
            color: #111827;
        }

        .udd-role {
            font-size: 11px;
            color: #6b7280;
        }

        .udd-item {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: .55rem 1rem;
            font-size: 13px;
            color: #374151;
            text-decoration: none;
            transition: background 0.1s;
        }

        .udd-item:hover {
            background: #f9fafb;
        }

        .udd-item i {
            font-size: 15px;
            color: #9ca3af;
        }

        .udd-item.danger {
            color: #993C1D;
            border-top: 0.5px solid #f3f4f6;
            margin-top: 2px;
        }

        .udd-item.danger i {
            color: #D85A30;
        }

        /* Mobile search */
        .tb-mobile-search {
            display: none;
        }

        @media (max-width: 767px) {
            .tb-search-wrap {
                display: none;
            }

            .tb-mobile-search {
                display: flex;
            }

            .tb-uname {
                display: none;
            }
        }
    </style>

    <div class="header-wrapper">

        {{-- HAMBURGER --}}
        <button class="tb-toggle" id="sidebar-hide" title="Toggle sidebar">
            <i class="ti ti-menu-2"></i>
        </button>

        {{-- SEARCH --}}
        <div class="tb-search-wrap">
            <i class="ti ti-search tb-search-icon"></i>
            <input type="search" class="tb-search" placeholder="Cari sesuatu...">
        </div>

        {{-- RIGHT ACTIONS --}}
        <div class="tb-actions">

            {{-- MOBILE SEARCH --}}
            <button class="tb-btn tb-mobile-search" title="Cari">
                <i class="ti ti-search"></i>
            </button>

            {{-- NOTIFIKASI --}}
            <div class="tb-dropdown">
                <button class="tb-btn" title="Notifikasi">
                    <i class="ti ti-bell"></i>
                    <span class="tb-dot"></span>
                </button>
                <div class="tb-dropdown-menu">
                    <div class="dd-header">
                        <span>Notifikasi</span>
                        <a href="#">Tandai semua dibaca</a>
                    </div>
                    <a class="dd-item" href="#">
                        <span class="dd-dot purple"></span>
                        <div>
                            <div class="dd-text">Data dosen baru berhasil diimport.</div>
                            <div class="dd-time">2 menit lalu</div>
                        </div>
                    </a>
                    <a class="dd-item" href="#">
                        <span class="dd-dot teal"></span>
                        <div>
                            <div class="dd-text">Distribusi semester ganjil telah diperbarui.</div>
                            <div class="dd-time">1 jam lalu</div>
                        </div>
                    </a>
                    <a class="dd-item" href="#">
                        <span class="dd-dot amber"></span>
                        <div>
                            <div class="dd-text">Beban dosen melebihi batas maksimal.</div>
                            <div class="dd-time">5 jam lalu</div>
                        </div>
                    </a>
                </div>
            </div>

            <div class="tb-divider"></div>

            {{-- USER --}}
            <div class="tb-user-dd">
                @php
                    $words = explode(' ', auth()->user()->name);
                    $inisial = strtoupper(substr($words[0], 0, 1) . (isset($words[1]) ? substr($words[1], 0, 1) : ''));
                @endphp
                <div class="tb-user">
                    <div class="tb-avatar">{{ $inisial }}</div>
                    <span class="tb-uname">{{ auth()->user()->name }}</span>
                    <i class="ti ti-chevron-down tb-chevron"></i>
                </div>
                <div class="tb-user-dd-menu">
                    <div class="udd-head">
                        <div class="tb-avatar" style="width:28px;height:28px;font-size:10px;">{{ $inisial }}</div>
                        <div>
                            <div class="udd-name">{{ auth()->user()->name }}</div>
                            <div class="udd-role">{{ ucfirst(auth()->user()->role ?? 'User') }}</div>
                        </div>
                    </div>
                    <a href="#!" class="udd-item">
                        <i class="ti ti-user-edit"></i> Edit profil
                    </a>
                    <a href="#!" class="udd-item">
                        <i class="ti ti-settings"></i> Pengaturan
                    </a>
                    <a href="/logout" class="udd-item danger">
                        <i class="ti ti-logout"></i> Logout
                    </a>
                </div>
            </div>

        </div>
    </div>
</header>
