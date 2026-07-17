@extends('layout.master')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/dasboard.css') }}">
    <style>
        .dashboard-page {
            padding: 1.25rem 0;
        }

        .filter-bar {
            display: flex;
            flex-wrap: wrap;
            gap: 14px;
            align-items: end;
            background: linear-gradient(135deg, #ffffff, #f8faff);
            border: 1px solid #eef2ff;
            border-radius: 22px;
            padding: 1rem 1.1rem;
            box-shadow: 0 14px 34px rgba(15, 23, 42, 0.06);
            margin-bottom: 1.25rem;
        }

        .field-group {
            display: flex;
            flex-direction: column;
            min-width: 190px;
            flex: 1 1 190px;
        }

        .field-group label {
            font-size: 11px;
            font-weight: 800;
            color: #6b7280;
            letter-spacing: .08em;
            text-transform: uppercase;
            margin-bottom: 6px;
        }

        .field-group select,
        .field-group input {
            height: 44px;
            border: 1px solid #dbe4f0;
            border-radius: 14px;
            padding: 0 14px;
            font-size: 13px;
            background: #fff;
            outline: none;
            transition: all 0.25s ease;
        }

        .field-group select:focus,
        .field-group input:focus {
            border-color: #7c3aed;
            box-shadow: 0 0 0 4px rgba(124, 58, 237, 0.12);
        }

        .btn-purple {
            border: none;
            background: linear-gradient(135deg, #5b4ff7, #7c3aed);
            color: #fff;
            border-radius: 14px;
            padding: 0.78rem 1.1rem;
            font-weight: 700;
            height: 44px;
            box-shadow: 0 10px 22px rgba(91, 79, 247, 0.2);
            transition: all 0.25s ease;
        }

        .btn-purple:hover {
            transform: translateY(-1px);
            color: #fff;
        }

        .semester-pill {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            height: 44px;
            padding: 0 14px;
            border-radius: 999px;
            background: linear-gradient(135deg, #ede9fe, #eef6ff);
            color: #4338ca;
            font-weight: 700;
            border: 1px solid #ddd6fe;
            white-space: nowrap;
        }

        .stat-grid {
            display: grid;
            grid-template-columns: repeat(5, minmax(0, 1fr));
            gap: 14px;
            margin-bottom: 1.25rem;
        }

        .stat-card {
            background: #fff;
            border: 1px solid #eef2ff;
            border-radius: 22px;
            padding: 1rem 1rem;
            box-shadow: 0 14px 30px rgba(15, 23, 42, 0.06);
            display: flex;
            align-items: center;
            gap: 0.95rem;
            transition: all 0.25s ease;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 18px 36px rgba(15, 23, 42, 0.1);
        }

        .stat-icon,
        .info-card-icon {
            width: 52px;
            height: 52px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            flex-shrink: 0;
        }

        .si-purple {
            background: linear-gradient(135deg, #ede9fe, #ddd6fe);
            color: #5b21b6;
        }

        .si-teal {
            background: linear-gradient(135deg, #dff7f2, #ccfbf1);
            color: #0f766e;
        }

        .si-amber {
            background: linear-gradient(135deg, #fef3c7, #fde68a);
            color: #b45309;
        }

        .si-blue {
            background: linear-gradient(135deg, #dbeafe, #bfdbfe);
            color: #1d4ed8;
        }

        .si-coral {
            background: linear-gradient(135deg, #ffe4e6, #fecdd3);
            color: #be123c;
        }

        .stat-val {
            font-size: 1.45rem;
            font-weight: 800;
            line-height: 1.1;
            color: #111827;
        }

        .stat-lbl {
            margin-top: 4px;
            color: #6b7280;
            font-size: 0.9rem;
        }

        .two-col {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
            margin-bottom: 1.25rem;
        }

        .info-card,
        .constraint-card {
            background: #fff;
            border: 1px solid #eef2ff;
            border-radius: 22px;
            box-shadow: 0 14px 34px rgba(15, 23, 42, 0.06);
            overflow: hidden;
        }

        .info-card-header {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 1rem 1.1rem;
            background: linear-gradient(135deg, #fafbff, #f5f7ff);
            border-bottom: 1px solid #eef2f7;
        }

        .info-card-title {
            font-size: 1rem;
            font-weight: 800;
            color: #1f2937;
        }

        .mini-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            margin: 0;
        }

        .mini-table thead th {
            background: #111827;
            color: #fff;
            font-size: 0.82rem;
            font-weight: 700;
            padding: 12px 14px;
            border: none;
            white-space: nowrap;
        }

        .mini-table tbody td {
            padding: 13px 14px;
            border-bottom: 1px solid #f1f5f9;
            vertical-align: middle;
            color: #374151;
            font-size: 0.92rem;
            background: #fff;
        }

        .mini-table tbody tr:hover td {
            background: #fafbff;
        }

        .badge-dist,
        .badge-sks,
        .badge-valid,
        .badge-invalid {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 4px;
            border-radius: 999px;
            padding: 0.42rem 0.75rem;
            font-size: 0.78rem;
            font-weight: 700;
            white-space: nowrap;
        }

        .badge-dist {
            background: #ede9fe;
            color: #5b21b6;
        }

        .badge-sks {
            background: #e0f2fe;
            color: #0369a1;
        }

        .badge-valid {
            background: #ecfdf5;
            color: #15803d;
        }

        .badge-invalid {
            background: #fef2f2;
            color: #dc2626;
        }

        .name-cell {
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 700;
            color: #111827;
        }

        .mini-avatar {
            width: 36px;
            height: 36px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.82rem;
            font-weight: 800;
            flex-shrink: 0;
        }

        .rank-num {
            width: 28px;
            height: 28px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 999px;
            font-size: 0.8rem;
            font-weight: 800;
            background: #f3f4f6;
            color: #374151;
        }

        .rank-num.gold {
            background: linear-gradient(135deg, #fde68a, #fbbf24);
            color: #78350f;
        }

        .rank-num.silver {
            background: linear-gradient(135deg, #e5e7eb, #cbd5e1);
            color: #334155;
        }

        .rank-num.bronze {
            background: linear-gradient(135deg, #fdba74, #fb923c);
            color: #7c2d12;
        }

        .constraint-body {
            padding: 1rem 1.1rem 1.1rem;
        }

        .constraint-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 1rem;
            padding: 0.9rem 0;
            border-bottom: 1px solid #f1f5f9;
        }

        .constraint-row:last-child {
            border-bottom: none;
        }

        .constraint-key {
            display: flex;
            align-items: center;
            gap: 10px;
            color: #374151;
            font-weight: 700;
        }

        .constraint-key i {
            color: #7c3aed;
        }

        .constraint-val {
            text-align: right;
        }

        @media (max-width: 1200px) {
            .stat-grid {
                grid-template-columns: repeat(3, minmax(0, 1fr));
            }
        }

        @media (max-width: 992px) {
            .two-col {
                grid-template-columns: 1fr;
            }

            .stat-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (max-width: 576px) {
            .dashboard-page {
                padding: 0.75rem 0;
            }

            .filter-bar {
                border-radius: 18px;
                padding: 0.9rem;
            }

            .stat-grid {
                grid-template-columns: 1fr;
            }

            .stat-card,
            .info-card,
            .constraint-card {
                border-radius: 18px;
            }

            .mini-table thead th,
            .mini-table tbody td {
                padding: 10px 11px;
                font-size: 0.86rem;
            }

            .semester-pill {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
@endpush

@section('konten')
    <div class="container-fluid dashboard-page">
        {{-- ── FILTER BAR ── --}}
        <form method="GET" class="filter-bar">
            <div class="field-group">
                <label>Semester</label>
                <select name="semester">
                    <option value="Ganjil" {{ $semester == 'Ganjil' ? 'selected' : '' }}>Ganjil</option>
                    <option value="Genap" {{ $semester == 'Genap' ? 'selected' : '' }}>Genap</option>
                </select>
            </div>

            <div class="field-group">
                <label>Tahun Ajaran</label>
                <input type="text" name="tahun" value="{{ $tahun }}" placeholder="Contoh: 2024/2025">
            </div>

            <button type="submit" class="btn-purple" style="margin-top:auto;">
                <i class="bi bi-funnel"></i> Tampilkan
            </button>

            <div class="semester-pill" style="margin-top:auto;">
                <i class="bi bi-calendar3"></i>
                {{ $semester }} {{ $tahun }}
            </div>
        </form>

        {{-- ── STAT CARDS ── --}}
        <div class="stat-grid">
            <div class="stat-card">
                <div class="stat-icon si-purple"><i class="bi bi-building-fill"></i></div>
                <div>
                    <div class="stat-val">{{ $totalJurusan }}</div>
                    <div class="stat-lbl">Total jurusan</div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon si-teal"><i class="bi bi-diagram-3-fill"></i></div>
                <div>
                    <div class="stat-val">{{ $totalProdi }}</div>
                    <div class="stat-lbl">Total prodi</div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon si-amber"><i class="bi bi-mortarboard-fill"></i></div>
                <div>
                    <div class="stat-val">{{ $totalDosen }}</div>
                    <div class="stat-lbl">Total dosen</div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon si-blue"><i class="bi bi-book-fill"></i></div>
                <div>
                    <div class="stat-val">{{ $totalMataKuliah }}</div>
                    <div class="stat-lbl">Total mata kuliah</div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon si-coral"><i class="bi bi-share-fill"></i></div>
                <div>
                    <div class="stat-val">{{ $totalDistribusi }}</div>
                    <div class="stat-lbl">Total distribusi</div>
                </div>
            </div>
        </div>

        {{-- ── TWO COLUMN: Distribusi Jurusan + Beban Tertinggi ── --}}
        <div class="two-col">
            {{-- Distribusi Per Jurusan --}}
            <div class="info-card">
                <div class="info-card-header">
                    <div class="info-card-icon si-purple"><i class="bi bi-building"></i></div>
                    <span class="info-card-title">Distribusi Per Jurusan</span>
                </div>

                <table class="mini-table">
                    <thead>
                        <tr>
                            <th>Jurusan</th>
                            <th>Distribusi</th>
                            <th>SKS</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($distribusiJurusan as $item)
                            <tr>
                                <td>{{ $item->nama_jurusan }}</td>
                                <td><span class="badge-dist">{{ $item->total_distribusi }}</span></td>
                                <td><span class="badge-sks">{{ $item->total_sks }} SKS</span></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" style="text-align:center;padding:2rem;color:#9ca3af;">
                                    <i class="bi bi-inbox" style="font-size:24px;display:block;margin-bottom:6px;"></i>
                                    Belum ada data
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Dosen Beban Tertinggi --}}
            <div class="info-card">
                <div class="info-card-header">
                    <div class="info-card-icon si-amber"><i class="bi bi-trophy"></i></div>
                    <span class="info-card-title">Dosen Beban Tertinggi</span>
                </div>

                <table class="mini-table">
                    <thead>
                        <tr>
                            <th style="width:36px;">#</th>
                            <th>Nama Dosen</th>
                            <th>SKS</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $avatarColors = [
                                ['bg' => '#EEEDFE', 'color' => '#3C3489'],
                                ['bg' => '#E1F5EE', 'color' => '#085041'],
                                ['bg' => '#FAEEDA', 'color' => '#633806'],
                                ['bg' => '#FAECE7', 'color' => '#712B13'],
                            ];
                        @endphp

                        @forelse ($bebanTertinggi as $i => $item)
                            @php
                                $words = explode(' ', $item->nama_dosen);
                                $inisial = strtoupper(
                                    substr($words[0], 0, 1) . (isset($words[1]) ? substr($words[1], 0, 1) : ''),
                                );
                                $c = $avatarColors[$i % 4];
                                $rankClass = match ($i) {
                                    0 => 'gold',
                                    1 => 'silver',
                                    2 => 'bronze',
                                    default => '',
                                };
                            @endphp
                            <tr>
                                <td>
                                    <span class="rank-num {{ $rankClass }}">{{ $i + 1 }}</span>
                                </td>
                                <td>
                                    <div class="name-cell">
                                        <div class="mini-avatar"
                                            style="background:{{ $c['bg'] }};color:{{ $c['color'] }};">
                                            {{ $inisial }}
                                        </div>
                                        {{ $item->nama_dosen }}
                                    </div>
                                </td>
                                <td><span class="badge-sks">{{ $item->total_sks }} SKS</span></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" style="text-align:center;padding:2rem;color:#9ca3af;">
                                    <i class="bi bi-inbox" style="font-size:24px;display:block;margin-bottom:6px;"></i>
                                    Belum ada data
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- ── CONSTRAINT ── --}}
        <div class="constraint-card">
            <div class="info-card-header">
                <div class="info-card-icon si-coral"><i class="bi bi-shield-check"></i></div>
                <span class="info-card-title">Ringkasan Constraint</span>
            </div>

            <div class="constraint-body">
                <div class="constraint-row">
                    <div class="constraint-key">
                        <i class="bi bi-speedometer2"></i>
                        Maksimum SKS Dosen
                    </div>
                    <div class="constraint-val">
                        <span class="badge-sks">{{ $constraint['maks_sks'] }} SKS</span>
                    </div>
                </div>

                <div class="constraint-row">
                    <div class="constraint-key">
                        <i class="bi bi-check-circle"></i>
                        Distribusi Valid
                    </div>
                    <div class="constraint-val">
                        @if ($constraint['distribusi_valid'])
                            <span class="badge-valid"><i class="bi bi-check-lg me-1"></i>Valid</span>
                        @else
                            <span class="badge-invalid"><i class="bi bi-x-lg me-1"></i>Tidak Valid</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="{{ asset('template/dist/assets/js/pages/dashboard-default.js') }}"></script>
@endsection
