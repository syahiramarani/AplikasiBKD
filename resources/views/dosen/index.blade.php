@extends('layout.master')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/dosen.css') }}">
@endpush

@section('konten')
    <div class="row">
        <div class="col-12">

            {{-- NOTIFIKASI --}}
            @if (session('success'))
                <div class="alert-sukses">
                    <i class="bi bi-check-circle-fill"></i>
                    {{ session('success') }}
                </div>
            @endif

            {{-- HEADER --}}
            <div class="dosen-header">
                <div>
                    <div class="dosen-header-title">
                        <i class="bi bi-mortarboard me-2"></i>Manajemen Dosen
                    </div>
                    <div class="dosen-header-sub">Kelola data dosen jurusan dan MKDU</div>
                </div>
                <a href="{{ route('dosen.create') }}" class="btn-add-dosen">
                    <i class="bi bi-plus-lg"></i> Tambah Dosen
                </a>
            </div>

            {{-- STAT CARDS --}}
            <div class="stat-grid">
                <div class="stat-card">
                    <div class="stat-icon purple"><i class="bi bi-people"></i></div>
                    <div>
                        <div class="stat-val">{{ $dosens->count() }}</div>
                        <div class="stat-lbl">Total dosen</div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon teal"><i class="bi bi-person-badge"></i></div>
                    <div>
                        <div class="stat-val">{{ $dosens->filter(fn($d) => $d->status == 'DT')->count() }}</div>
                        <div class="stat-lbl">DT</div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon amber"><i class="bi bi-person-check"></i></div>
                    <div>
                        <div class="stat-val">{{ $dosens->filter(fn($d) => $d->status == 'DS')->count() }}</div>
                        <div class="stat-lbl">DS</div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon coral"><i class="bi bi-building"></i></div>
                    <div>
                        <div class="stat-val">{{ $dosens->pluck('jurusan.kode_jurusan')->unique()->filter()->count() }}
                        </div>
                        <div class="stat-lbl">Jurusan</div>
                    </div>
                </div>
            </div>

            {{-- MAIN CARD --}}
            <div class="main-card">

                {{-- TOOLBAR --}}
                <div class="dosen-toolbar">

                    {{-- FILTER --}}
                    <form action="{{ url('/dosen') }}" method="GET" class="toolbar-left">
                        <div class="field-group">
                            <select name="status" style="min-width:130px;">
                                <option value="">Semua status</option>
                                <option value="DT" {{ request('status') == 'DT' ? 'selected' : '' }}>DT</option>
                                <option value="DS" {{ request('status') == 'DS' ? 'selected' : '' }}>DS</option>
                            </select>
                        </div>
                        <div class="field-group">
                            <select name="kategori_mengajar" style="min-width:155px;">
                                <option value="">Semua kategori</option>
                                <option value="Jurusan" {{ request('kategori_mengajar') == 'Jurusan' ? 'selected' : '' }}>
                                    Jurusan</option>
                                <option value="MKDU" {{ request('kategori_mengajar') == 'MKDU' ? 'selected' : '' }}>
                                    MKDU</option>
                            </select>
                        </div>
                        <button type="submit" class="btn-purple" style="margin-top:auto;">
                            <i class="bi bi-funnel"></i> Filter
                        </button>
                        <a href="/dosen" class="btn-outline" style="margin-top:auto;">
                            <i class="bi bi-arrow-counterclockwise"></i> Reset
                        </a>
                    </form>

                    {{-- IMPORT --}}
                    <form action="{{ route('dosen.import') }}" method="POST" enctype="multipart/form-data" id="formImport"
                        class="toolbar-right">
                        @csrf
                        <div class="field-group">
                            <input type="file" name="file" accept=".xlsx,.xls,.csv" style="min-width:170px;" required>
                        </div>
                        <button type="submit" class="btn-teal" style="margin-top:auto;">
                            <i class="bi bi-upload"></i> Import
                        </button>
                    </form>

                </div>

                {{-- TABEL --}}
                <div class="dosen-table-wrap">
                    <table class="dosen-table">
                        <thead>
                            <tr>
                                <th style="width:44px;">No</th>
                                <th>NIP</th>
                                <th>Nama dosen</th>
                                <th>Jurusan</th>
                                <th>Status</th>
                                <th>Jabatan</th>
                                <th>Keahlian</th>
                                <th style="width:100px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($dosens as $dosen)
                                @php
                                    $words = explode(' ', $dosen->nama_dosen);
                                    $inisial = strtoupper(
                                        substr($words[0], 0, 1) . (isset($words[1]) ? substr($words[1], 0, 1) : ''),
                                    );
                                    $colors = [
                                        ['bg' => '#EEEDFE', 'color' => '#3C3489'],
                                        ['bg' => '#E1F5EE', 'color' => '#085041'],
                                        ['bg' => '#FAEEDA', 'color' => '#633806'],
                                        ['bg' => '#FAECE7', 'color' => '#712B13'],
                                    ];
                                    $c = $colors[$loop->index % 4];
                                @endphp
                                <tr>
                                    <td style="color:#9ca3af;font-size:12px;">{{ $loop->iteration }}</td>
                                    <td class="cell-nip">{{ $dosen->nip }}</td>
                                    <td>
                                        <div class="avatar-cell">
                                            <div class="dosen-avatar"
                                                style="background:{{ $c['bg'] }};color:{{ $c['color'] }};">
                                                {{ $inisial }}
                                            </div>
                                            {{ $dosen->nama_dosen }}
                                        </div>
                                    </td>
                                    <td>
                                        @if ($dosen->jurusan)
                                            <span class="badge badge-jur">{{ $dosen->jurusan->kode_jurusan }}</span>
                                        @else
                                            <span style="color:#d1d5db;">—</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($dosen->status == 'DT')
                                            <span class="badge badge-dt">DT</span>
                                        @elseif($dosen->status == 'DS')
                                            <span class="badge badge-ds">DS</span>
                                        @else
                                            <span class="badge"
                                                style="background:#f3f4f6;color:#6b7280;">{{ $dosen->status }}</span>
                                        @endif
                                    </td>
                                    <td>{{ $dosen->jabatan }}</td>
                                    <td>{{ $dosen->keahlian }}</td>
                                    <td>
                                        <div style="display:flex;gap:5px;">

                                            {{-- VIEW BUTTON (TAMBAHAN BARU) --}}
                                            <button class="btn-view"
                                                onclick="viewDosen(
                                                    '{{ $dosen->nip }}',
                                                    '{{ $dosen->nama_dosen }}',
                                                    '{{ $dosen->jabatan }}',
                                                    '{{ $dosen->jurusan->nama_jurusan ?? '-' }}',
                                                    '{{ $dosen->jurusan->prodi->nama_prodi ?? '-' }}',
                                                    [
                                                        @foreach ($dosen->dosenBidang as $db)
                                                            '{{ $db->bidang->nama }}', @endforeach
                                                    ]
                                                )">
                                                <i class="bi bi-eye"></i> View
                                            </button>

                                            <a href="/dosen/edit/{{ $dosen->id }}" class="btn-edit">
                                                <i class="bi bi-pencil"></i> Edit
                                            </a>

                                            <a href="/dosen/delete/{{ $dosen->id }}" class="btn-hapus"
                                                onclick="return confirm('Yakin hapus data dosen ini?')">
                                                <i class="bi bi-trash"></i> Hapus
                                            </a>

                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" style="text-align:center;padding:3rem 1rem;color:#9ca3af;">
                                        <i class="bi bi-inbox"
                                            style="font-size:32px;display:block;margin-bottom:8px;"></i>
                                        Data dosen belum tersedia
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>

    {{-- MODAL VIEW DOSEN --}}
    <div class="modal fade" id="viewDosenModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content modal-content-custom">
                <div class="modal-header modal-header-purple">
                    <h5 class="modal-title" style="color:#EEEDFE;font-size:16px;font-weight:500;">
                        <i class="bi bi-person-circle me-2"></i>Detail Dosen
                    </h5>
                    <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" style="padding:1.25rem;">
                    {{-- Avatar besar --}}
                    <div
                        style="display:flex;align-items:center;gap:12px;margin-bottom:1.25rem;padding-bottom:1rem;border-bottom:0.5px solid #f3f4f6;">
                        <div id="vd_avatar"
                            style="width:46px;height:46px;border-radius:50%;background:#EEEDFE;color:#3C3489;display:flex;align-items:center;justify-content:center;font-size:16px;font-weight:500;flex-shrink:0;">
                        </div>
                        <div>
                            <div id="vd_name" style="font-size:15px;font-weight:500;color:#111827;"></div>
                            <div id="vd_nip" style="font-size:12px;color:#6b7280;margin-top:2px;"></div>
                        </div>
                    </div>

                    <div class="detail-row">
                        <span class="detail-key">Jabatan</span>
                        <span class="detail-val" id="vd_jabatan"></span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-key">Jurusan</span>
                        <span class="detail-val" id="vd_jurusan"></span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-key">Prodi</span>
                        <span class="detail-val" id="vd_prodi"></span>
                    </div>

                    <div style="margin-top:1rem;">
                        <span class="detail-key" style="display:block;margin-bottom:0.5rem;">Keahlian</span>
                        <div id="vd_keahlian" style="display:flex;flex-wrap:wrap;gap:6px;"></div>
                    </div>
                </div>
                <div class="modal-footer-custom">
                    <button class="btn-outline" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>


    {{-- LOADING OVERLAY --}}
    <div id="loadingOverlay">
        <div class="inner">
            <div class="spinner-border" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <div class="label">Sedang mengimport data...</div>
        </div>
    </div>

    <script>
        function viewDosen(nip, name, jabatan, jurusan, prodi, keahlian) {

            document.getElementById('vd_nip').innerText = nip;
            document.getElementById('vd_name').innerText = name;
            document.getElementById('vd_jabatan').innerText = jabatan;
            document.getElementById('vd_jurusan').innerText = jurusan;
            document.getElementById('vd_prodi').innerText = prodi;

            // keahlian (array)
            let html = '';
            if (keahlian && keahlian.length > 0) {
                keahlian.forEach(k => {
                    html += `<span class="badge" style="margin-right:5px;">${k}</span>`;
                });
            } else {
                html = '-';
            }

            document.getElementById('vd_keahlian').innerHTML = html;

            new bootstrap.Modal(document.getElementById('viewDosenModal')).show();
        }
        document.getElementById('formImport').addEventListener('submit', function() {
            document.getElementById('loadingOverlay').style.display = 'block';
        });
    </script>
@endsection
