@extends('layout.master')

@section('title', 'Rekapitulasi Beban Mengajar')

@push('styles')
    <style>
        .rekap-wrapper {
            background: #fff;
            border-radius: 14px;
            box-shadow: 0 10px 25px rgba(15, 23, 42, 0.08);
            padding: 20px;
        }

        .rekap-title {
            text-align: center;
            margin-bottom: 20px;
            line-height: 1.5;
        }

        .rekap-title h4,
        .rekap-title h5,
        .rekap-title h6 {
            margin: 0;
            font-weight: 700;
        }

        .rekap-meta {
            font-size: 14px;
            color: #475569;
        }

        .rekap-table {
            font-size: 13px;
        }

        .rekap-table th,
        .rekap-table td {
            border: 1px solid #334155 !important;
            vertical-align: middle;
        }

        .rekap-table thead th {
            background: #dbeafe;
            text-align: center;
            font-weight: 700;
        }

        .rekap-table .subhead {
            background: #eff6ff;
        }

        .text-small {
            font-size: 12px;
        }

        .badge-home {
            background: #dcfce7;
            color: #166534;
            padding: 4px 8px;
            border-radius: 999px;
            font-size: 11px;
            font-weight: 700;
        }

        .badge-lintas {
            background: #fef3c7;
            color: #92400e;
            padding: 4px 8px;
            border-radius: 999px;
            font-size: 11px;
            font-weight: 700;
        }

        @media print {
            .no-print {
                display: none !important;
            }

            .rekap-wrapper {
                box-shadow: none;
                border: none;
                padding: 0;
            }

            body {
                background: #fff !important;
            }
        }
    </style>
@endpush

@section('konten')
    <div class="container-fluid px-4">
        <div class="rekap-wrapper">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3 no-print">
                <div>
                    <h4 class="mb-1">Rekap Laporan Distribusi Beban Mengajar</h4>
                    <div class="rekap-meta">
                        Laporan per dosen, mendukung distribusi lintas prodi sesuai bidang dan sisa SKS.
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <a href="{{ route('distribusi.index') }}" class="btn btn-outline-secondary">
                        Kembali
                    </a>
                    <button onclick="window.print()" class="btn btn-primary">
                        Print
                    </button>
                </div>
            </div>

            <form method="GET" action="{{ route('distribusi.rekap') }}" class="row g-3 mb-4 no-print">
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Filter Semester</label>
                    <select name="semester" class="form-select">
                        <option value="">Semua Semester</option>
                        @for ($i = 1; $i <= 8; $i++)
                            <option value="{{ $i }}"
                                {{ (string) request('semester') === (string) $i ? 'selected' : '' }}>
                                Semester {{ $i }}
                            </option>
                        @endfor
                    </select>
                </div>

                <div class="col-md-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-success w-100">Tampilkan Rekap</button>
                </div>
            </form>

            <div class="rekap-title">
                <h5>FORMULIR</h5>
                <h4>REKAPITULASI BEBAN MENGAJAR</h4>
                <h6>SEMESTER GENAP TAHUN AKADEMIK 2024-2025</h6>
                <h6>JURUSAN TEKNOLOGI INFORMASI DAN KOMPUTER</h6>
            </div>

            <div class="table-responsive">
                <table class="table rekap-table table-bordered align-middle">
                    <thead>
                        <tr>
                            <th rowspan="2" style="width: 50px;">NO</th>
                            <th rowspan="2" style="min-width: 220px;">DOSEN</th>
                            <th rowspan="2" style="min-width: 140px;">NIP</th>

                            <th colspan="2">PRODI TRKJ</th>
                            <th colspan="2">PRODI TI</th>
                            <th colspan="2">PRODI TRMM</th>
                            <th colspan="2">PRODI LAIN</th>

                            <th colspan="2">TOTAL</th>
                            <th rowspan="2" style="min-width: 180px;">KETERANGAN</th>
                        </tr>
                        <tr class="subhead">
                            <th>SKS</th>
                            <th>Jam</th>

                            <th>SKS</th>
                            <th>Jam</th>

                            <th>SKS</th>
                            <th>Jam</th>

                            <th>SKS</th>
                            <th>Jam</th>

                            <th>SKS</th>
                            <th>Jam</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($rekap as $index => $row)
                            <tr>
                                <td class="text-center">{{ $index + 1 }}</td>

                                <td>
                                    <div class="fw-semibold">{{ $row['nama_dosen'] }}</div>
                                    <div class="text-muted text-small">Homebase: {{ $row['homebase'] }}</div>
                                </td>

                                <td class="text-center">{{ $row['nip'] }}</td>

                                <td class="text-center">
                                    {{ rtrim(rtrim(number_format($row['trkjsks'], 2, '.', ''), '0'), '.') }}</td>
                                <td class="text-center">
                                    {{ rtrim(rtrim(number_format($row['trkjjam'], 2, '.', ''), '0'), '.') }}</td>

                                <td class="text-center">
                                    {{ rtrim(rtrim(number_format($row['tisks'], 2, '.', ''), '0'), '.') }}</td>
                                <td class="text-center">
                                    {{ rtrim(rtrim(number_format($row['tijam'], 2, '.', ''), '0'), '.') }}</td>

                                <td class="text-center">
                                    {{ rtrim(rtrim(number_format($row['trmmsks'], 2, '.', ''), '0'), '.') }}</td>
                                <td class="text-center">
                                    {{ rtrim(rtrim(number_format($row['trmmjam'], 2, '.', ''), '0'), '.') }}</td>

                                <td class="text-center">
                                    {{ rtrim(rtrim(number_format($row['lainsks'], 2, '.', ''), '0'), '.') }}</td>
                                <td class="text-center">
                                    {{ rtrim(rtrim(number_format($row['lainjam'], 2, '.', ''), '0'), '.') }}</td>

                                <td class="text-center fw-bold">
                                    {{ rtrim(rtrim(number_format($row['total_sks'], 2, '.', ''), '0'), '.') }}</td>
                                <td class="text-center fw-bold">
                                    {{ rtrim(rtrim(number_format($row['total_jam'], 2, '.', ''), '0'), '.') }}</td>

                                <td>
                                    <div class="mb-1">
                                        @if ($row['lintas_prodi'])
                                            <span class="badge-lintas">Lintas Prodi</span>
                                        @else
                                            <span class="badge-home">Sesuai Homebase</span>
                                        @endif
                                    </div>

                                    <div class="text-small">
                                        {{ $row['status_beban'] }}
                                    </div>

                                    <div class="text-small text-muted mt-1">
                                        MK: {{ $row['jumlah_mk'] }}
                                        @if ($row['jumlah_mk_lintas'] > 0)
                                            <br>MK lintas: {{ $row['jumlah_mk_lintas'] }}
                                            <br>SKS lintas:
                                            {{ rtrim(rtrim(number_format($row['sks_lintas'], 2, '.', ''), '0'), '.') }}
                                        @endif
                                        @if (($row['min_sks'] ?? 0) || ($row['max_sks'] ?? 0))
                                            <br>Beban: {{ $row['min_sks'] }} - {{ $row['max_sks'] }} SKS
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="14" class="text-center text-muted py-4">
                                    Belum ada data distribusi untuk direkap.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
