{{-- resources/views/rekap-distribusi/show.blade.php --}}
@extends('layout.master') {{-- sesuaikan layout kamu --}}

@section('konten')
    <style>
        .hero {
            border-radius: 18px;
            padding: 18px 18px;
            color: #f4f3ff;
            background:
                radial-gradient(circle at top left, rgba(124, 58, 237, .35), transparent 42%),
                radial-gradient(circle at bottom right, rgba(6, 182, 212, .18), transparent 38%),
                linear-gradient(135deg, #221d55 0%, #1d184c 100%);
            box-shadow: 0 18px 40px rgba(18, 15, 45, .18);
            border: 1px solid rgba(255, 255, 255, .08);
        }

        .hero h4 {
            margin: 0;
            font-weight: 900;
            letter-spacing: .2px;
        }

        .hero .sub {
            margin-top: 6px;
            font-size: 13px;
            color: rgba(233, 231, 255, .82);
        }

        .chip {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 6px 10px;
            border-radius: 999px;
            font-size: 11px;
            font-weight: 800;
            background: rgba(255, 255, 255, .10);
            border: 1px solid rgba(255, 255, 255, .10);
            color: #efeefe;
        }

        .btn-gradient {
            border: none;
            color: #fff !important;
            font-weight: 900;
            border-radius: 12px;
            padding: 10px 14px;
            background: linear-gradient(135deg, #5f55db, #4e46b9);
            box-shadow: 0 14px 25px rgba(79, 70, 201, .22);
            transition: transform .15s ease, box-shadow .15s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-gradient:hover {
            transform: translateY(-1px);
            box-shadow: 0 18px 32px rgba(79, 70, 201, .28);
        }

        .card-soft {
            border-radius: 18px;
            border: 1px solid rgba(20, 16, 54, .08);
            box-shadow: 0 14px 35px rgba(18, 15, 45, .08);
            overflow: hidden;
        }

        .card-head {
            padding: 14px 16px;
            background: linear-gradient(90deg, rgba(124, 58, 237, .10), rgba(6, 182, 212, .06));
            border-bottom: 1px solid rgba(18, 15, 45, .06);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            flex-wrap: wrap;
        }

        .table-soft thead th {
            background: rgba(95, 85, 219, .10);
            color: #2b285f;
            font-weight: 900;
            border-bottom: 1px solid rgba(18, 15, 45, .08) !important;
            vertical-align: middle;
        }

        .table-soft th,
        .table-soft td {
            vertical-align: middle;
        }

        .muted {
            color: #6b7280;
            font-size: 12px;
        }

        .num {
            text-align: center;
            font-weight: 800;
            color: #111827;
        }

        .keterangan {
            font-size: 12px;
            color: #374151;
        }

        .badge-ok {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 6px 10px;
            border-radius: 999px;
            font-size: 11px;
            font-weight: 900;
            color: #065f46;
            background: rgba(34, 197, 94, .12);
            border: 1px solid rgba(34, 197, 94, .18);
            white-space: nowrap;
        }

        .badge-info {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 6px 10px;
            border-radius: 999px;
            font-size: 11px;
            font-weight: 900;
            color: #1f2937;
            background: rgba(99, 102, 241, .10);
            border: 1px solid rgba(99, 102, 241, .18);
            white-space: nowrap;
        }

        .btn-mini {
            border-radius: 12px;
            padding: 8px 10px;
            font-weight: 900;
        }
    </style>

    <div class="container py-3">

        {{-- HEADER --}}
        <div class="hero mb-3">
            <div class="d-flex align-items-start justify-content-between flex-wrap gap-2">
                <div>
                    <h4>Rekapitulasi Beban Mengajar (History)</h4>
                    <div class="sub">
                        Tahun Ajaran:
                        <strong>{{ $meta['tahun_ajaran']->nama ?? ($meta['tahun_ajaran']->tahun_ajaran ?? '-') }}</strong>
                        · Semester: <strong>{{ $meta['semester'] ?? '-' }}</strong>
                    </div>
                    <div class="sub">
                        Batch: <span class="chip">{{ $meta['batch_id'] }}</span>
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <a href="{{ route('rekap-distribusi.index') }}" class="btn btn-light"
                        style="border-radius:12px;font-weight:900;">
                        Kembali
                    </a>

                    {{-- kalau kamu punya halaman print khusus, ganti route-nya --}}
                    {{-- <a href="{{ route('rekap-distribusi.print', ['batch'=>$meta['batch_id']]) }}" class="btn-gradient">Print</a> --}}
                </div>
            </div>
        </div>

        {{-- TABLE --}}
        <div class="card card-soft">
            <div class="card-head">
                <div style="font-weight:900;color:#2b285f;">
                    FORMULIR · REKAPITULASI BEBAN MENGAJAR
                </div>
                <div class="muted">
                    Total Dosen: <strong style="color:#2b285f;">{{ $rekap->count() }}</strong>
                </div>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-soft align-middle">
                        <thead>
                            <tr>
                                <th rowspan="2" style="width:60px;" class="text-center">No</th>
                                <th rowspan="2">Dosen</th>
                                <th rowspan="2" style="width:190px;" class="text-center">NIP</th>

                                <th colspan="2" class="text-center">PRODI TRKJ</th>
                                <th colspan="2" class="text-center">PRODI TI</th>
                                <th colspan="2" class="text-center">PRODI TRMM</th>
                                <th colspan="2" class="text-center">PRODI LAIN</th>

                                <th colspan="2" class="text-center">TOTAL</th>
                                <th rowspan="2" style="width:220px;" class="text-center">Keterangan</th>
                                <th rowspan="2" style="width:120px;" class="text-center">Aksi</th>
                            </tr>

                            <tr>
                                <th class="text-center" style="width:70px;">SKS</th>
                                <th class="text-center" style="width:70px;">Jam</th>

                                <th class="text-center" style="width:70px;">SKS</th>
                                <th class="text-center" style="width:70px;">Jam</th>

                                <th class="text-center" style="width:70px;">SKS</th>
                                <th class="text-center" style="width:70px;">Jam</th>

                                <th class="text-center" style="width:70px;">SKS</th>
                                <th class="text-center" style="width:70px;">Jam</th>

                                <th class="text-center" style="width:70px;">SKS</th>
                                <th class="text-center" style="width:70px;">Jam</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($rekap as $i => $r)
                                <tr>
                                    <td class="num">{{ $i + 1 }}</td>

                                    <td>
                                        <div style="font-weight:900;color:#111827;">{{ $r['nama'] }}</div>
                                        <div class="muted">MK: <strong>{{ $r['mk_count'] }}</strong></div>
                                    </td>

                                    <td class="text-center">
                                        <div style="font-weight:900;">{{ $r['nip'] }}</div>
                                    </td>

                                    {{-- TRKJ --}}
                                    <td class="num">{{ $r['per']['TRKJ']['sks'] }}</td>
                                    <td class="num">{{ $r['per']['TRKJ']['jam'] }}</td>

                                    {{-- TI --}}
                                    <td class="num">{{ $r['per']['TI']['sks'] }}</td>
                                    <td class="num">{{ $r['per']['TI']['jam'] }}</td>

                                    {{-- TRMM --}}
                                    <td class="num">{{ $r['per']['TRMM']['sks'] }}</td>
                                    <td class="num">{{ $r['per']['TRMM']['jam'] }}</td>

                                    {{-- LAIN --}}
                                    <td class="num">{{ $r['per']['LAIN']['sks'] }}</td>
                                    <td class="num">{{ $r['per']['LAIN']['jam'] }}</td>

                                    {{-- TOTAL --}}
                                    <td class="num"><strong>{{ $r['total_sks'] }}</strong></td>
                                    <td class="num"><strong>{{ $r['total_jam'] }}</strong></td>

                                    <td class="keterangan">
                                        <div class="d-flex flex-wrap gap-2">
                                            <span class="badge-info">MK: {{ $r['mk_count'] }}</span>

                                            {{-- Placeholder status (silakan ganti logika sesuai aturan beban kamu) --}}
                                            {{-- Contoh: jika total_sks <= 16 maka "Sesuai Beban" --}}
                                            @if (($r['total_sks'] ?? 0) <= 16)
                                                <span class="badge-ok">Sesuai Beban</span>
                                            @else
                                                <span class="badge-info">Melebihi Beban</span>
                                            @endif
                                        </div>
                                        <div class="muted mt-2">
                                            Beban: 0–16 SKS (contoh)
                                        </div>
                                    </td>

                                    <td class="text-center">
                                        <a class="btn btn-primary btn-sm btn-mini"
                                            href="{{ route('rekap-distribusi.dosen-detail', ['batch' => $meta['batch_id'], 'dosen' => $r['dosen_id']]) }}">
                                            Detail
                                        </a>
                                    </td>
                                </tr>
                            @endforeach

                            @if ($rekap->isEmpty())
                                <tr>
                                    <td colspan="15" class="text-center text-muted py-4">
                                        Data rekap tidak ditemukan untuk batch ini.
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>

                <div class="muted mt-2">
                    Catatan: Data ini diambil dari history distribusi berdasarkan <strong>batch_id</strong>. Tombol
                    <strong>Detail</strong> menampilkan MK & kelas per dosen.
                </div>
            </div>
        </div>

    </div>
@endsection
