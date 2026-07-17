@extends('layout.master')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/matakuliah.css') }}">
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
            <div class="mk-header">
                <div>
                    <div class="mk-header-title">
                        <i class="bi bi-book me-2"></i>Manajemen Mata Kuliah
                    </div>
                    <div class="mk-header-sub">Kelola data mata kuliah per prodi dan semester</div>
                </div>

                {{-- TAMBAHAN BUTTON (SEPERTI JURUSAN) --}}
                < <a href="{{ route('matakuliah.create') }}" class="btn-purple">
                    <i class="bi bi-plus-lg"></i> Tambah Mata Kuliah
                    </a>
            </div>

            {{-- STAT CARD --}}
            <div class="stat-card">
                <div class="stat-icon"><i class="bi bi-book"></i></div>
                <div>
                    <div class="stat-val">{{ $matakuliahs->count() }}</div>
                    <div class="stat-lbl">Total mata kuliah</div>
                </div>
            </div>

            {{-- MAIN CARD --}}
            <div class="main-card">

                {{-- TOOLBAR --}}
                <div class="mk-toolbar">

                    {{-- FILTER --}}
                    <form action="{{ route('matakuliah.index') }}" method="GET" class="toolbar-left">
                        <div class="field-group">
                            <label>Prodi</label>
                            <select name="prodi" style="min-width:170px;">
                                <option value="">Semua prodi</option>
                                @foreach ($prodis as $p)
                                    <option value="{{ $p->id }}" {{ request('prodi') == $p->id ? 'selected' : '' }}>
                                        {{ $p->nama_prodi }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="field-group">
                            <label>Semester</label>
                            <select name="semester" style="min-width:140px;">
                                <option value="">Semua semester</option>
                                @foreach ($semesters as $s)
                                    <option value="{{ $s }}" {{ request('semester') == $s ? 'selected' : '' }}>
                                        Semester {{ $s }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn-purple" style="margin-top:auto;">
                            <i class="bi bi-funnel"></i> Filter
                        </button>
                        <a href="{{ route('matakuliah.index') }}" class="btn-outline" style="margin-top:auto;">
                            <i class="bi bi-arrow-counterclockwise"></i> Reset
                        </a>
                    </form>

                    {{-- IMPORT --}}
                    <form action="{{ route('matakuliah.import') }}" method="POST" enctype="multipart/form-data"
                        id="formImportMk" class="toolbar-right">
                        @csrf
                        <div class="field-group">
                            <label>Import Excel</label>
                            <input type="file" name="file" accept=".xlsx,.xls,.csv" style="min-width:170px;" required>
                        </div>
                        <button type="submit" class="btn-teal" style="margin-top:auto;">
                            <i class="bi bi-upload"></i> Import
                        </button>
                    </form>

                </div>

                {{-- TABEL --}}
                <div class="mk-table-wrap">
                    <table class="mk-table">
                        <thead>
                            <tr>
                                <th style="width:44px;">No</th>
                                <th>Kode MK</th>
                                <th>Nama Mata Kuliah</th>
                                <th>Prodi</th>
                                <th>SKS</th>
                                <th>Jam</th>
                                <th>Bidang</th>
                                <th style="width:170px;">Aksi</th>
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

                            @forelse ($matakuliahs as $item)
                                @php
                                    $c = $avatarColors[$loop->index % 4];
                                @endphp
                                <tr>
                                    <td style="color:#9ca3af;font-size:12px;">{{ $loop->iteration }}</td>
                                    <td><span class="badge-code">{{ $item->kode_mk }}</span></td>
                                    <td>
                                        <div class="name-cell">
                                            <div class="mk-avatar"
                                                style="background:{{ $c['bg'] }}; color:{{ $c['color'] }};">
                                                {{ strtoupper(substr($item->kode_mk, 0, 2)) }}
                                            </div>
                                            {{ $item->nama_mk }}
                                        </div>
                                    </td>
                                    <td>
                                        @if ($item->prodi)
                                            <span class="badge-prodi">{{ $item->prodi->nama_prodi }}</span>
                                        @else
                                            <span style="color:#d1d5db;">—</span>
                                        @endif
                                    </td>
                                    <td><span class="badge-sks">{{ $item->sks }} SKS</span></td>
                                    <td style="color:#6b7280;">{{ $item->jam ?? '—' }}</td>
                                    <td>
                                        @if ($item->bidang)
                                            <span class="badge-bidang">{{ $item->bidang->nama }}</span>
                                        @else
                                            <span style="color:#d1d5db;">—</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div style="display:flex;gap:5px;">
                                            <button class="btn-view" data-bs-toggle="modal"
                                                data-bs-target="#viewMatkul{{ $item->id }}">
                                                <i class="bi bi-eye"></i> View
                                            </button>
                                            <div style="display:flex;gap:5px;">
                                                <button class="btn-edit" data-bs-toggle="modal"
                                                    data-bs-target="#editMatkul{{ $item->id }}">
                                                    <i class="bi bi-pencil"></i> Edit
                                                </button>
                                                <button class="btn-hapus" data-bs-toggle="modal"
                                                    data-bs-target="#hapusMatkul{{ $item->id }}">
                                                    <i class="bi bi-trash"></i> Hapus
                                                </button>
                                            </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" style="text-align:center;padding:3rem 1rem;color:#9ca3af;">
                                        <i class="bi bi-inbox" style="font-size:32px;display:block;margin-bottom:8px;"></i>
                                        Data mata kuliah belum tersedia
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- MODALS (ditempatkan di luar tabel agar valid HTML) --}}
                @foreach ($matakuliahs as $item)
                    {{-- MODAL VIEW --}}
                    <div class="modal fade" id="viewMatkul{{ $item->id }}" tabindex="-1">
                        <div class="modal-dialog modal-dialog-centered modal-lg">
                            <div class="modal-content modal-content-custom"
                                style="border-radius:18px;overflow:hidden;border:none;box-shadow:0 15px 40px rgba(0,0,0,0.2);">

                                <div class="modal-header modal-header-purple"
                                    style="background:linear-gradient(135deg,#6d28d9,#a06bff 60%,#c084fc);padding:1.25rem 1.5rem;">
                                    <h5 class="modal-title" style="color:#EEEDFE;font-size:18px;font-weight:600;">
                                        <i class="bi bi-eye me-2"></i> Detail Mata Kuliah
                                    </h5>
                                    <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                </div>

                                <div class="modal-body" style="padding:1.75rem;background:#faf8ff;">

                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <div class="mk-detail-box">
                                                <div class="mk-detail-label"><i class="bi bi-upc-scan me-1"></i> Kode MK
                                                </div>
                                                <div class="mk-detail-value">{{ $item->kode_mk }}</div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <div class="mk-detail-box">
                                                <div class="mk-detail-label"><i class="bi bi-journal-text me-1"></i> Nama
                                                    Mata Kuliah</div>
                                                <div class="mk-detail-value">{{ $item->nama_mk }}</div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-4 mb-3">
                                            <div class="mk-detail-box">
                                                <div class="mk-detail-label"><i class="bi bi-stack me-1"></i> SKS</div>
                                                <div class="mk-detail-value">{{ $item->sks }}</div>
                                            </div>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <div class="mk-detail-box">
                                                <div class="mk-detail-label"><i class="bi bi-clock me-1"></i> Jam</div>
                                                <div class="mk-detail-value">{{ $item->jam ?? '-' }}</div>
                                            </div>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <div class="mk-detail-box">
                                                <div class="mk-detail-label"><i class="bi bi-calendar3 me-1"></i> Semester
                                                </div>
                                                <div class="mk-detail-value">{{ $item->semester ?? '-' }}</div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <div class="mk-detail-box">
                                                <div class="mk-detail-label"><i class="bi bi-mortarboard me-1"></i> Prodi
                                                </div>
                                                <div class="mk-detail-value">{{ $item->prodi->nama_prodi ?? '-' }}</div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <div class="mk-detail-box">
                                                <div class="mk-detail-label"><i class="bi bi-diagram-3 me-1"></i> Bidang
                                                </div>
                                                <div class="mk-detail-value">{{ $item->bidang->nama ?? '-' }}</div>
                                            </div>
                                        </div>
                                    </div>

                                </div>

                                <div class="modal-footer modal-footer-custom"
                                    style="background:#faf8ff;border-top:1px dashed #e5d9fb;padding:1rem 1.75rem;">
                                    <button class="btn-outline" data-bs-dismiss="modal">
                                        <i class="bi bi-x-lg me-1"></i> Tutup
                                    </button>
                                </div>

                            </div>
                        </div>
                    </div>

                    {{-- MODAL EDIT --}}
                    <div class="modal fade" id="editMatkul{{ $item->id }}" tabindex="-1">
                        <div class="modal-dialog modal-dialog-centered modal-lg">
                            <div class="modal-content modal-content-custom"
                                style="border-radius:18px;overflow:hidden;border:none;box-shadow:0 15px 40px rgba(0,0,0,0.2);">
                                <div class="modal-header modal-header-purple"
                                    style="background:linear-gradient(135deg,#6d28d9,#a06bff 60%,#c084fc);padding:1.25rem 1.5rem;">
                                    <h5 class="modal-title" style="color:#EEEDFE;font-size:18px;font-weight:600;">
                                        <i class="bi bi-pencil-square me-2"></i> Edit Mata Kuliah
                                    </h5>
                                    <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                </div>
                                <form action="{{ route('matakuliah.update', $item->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-body" style="padding:1.75rem;background:#faf8ff;">
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="modal-label">Kode MK</label>
                                                <input type="text" name="kode_mk" class="modal-input"
                                                    value="{{ $item->kode_mk }}" required>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="modal-label">Nama Mata Kuliah</label>
                                                <input type="text" name="nama_mk" class="modal-input"
                                                    value="{{ $item->nama_mk }}" required>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-4 mb-3">
                                                <label class="modal-label">SKS</label>
                                                <input type="number" name="sks" class="modal-input"
                                                    value="{{ $item->sks }}" required>
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label class="modal-label">Jam</label>
                                                <input type="number" name="jam" class="modal-input"
                                                    value="{{ $item->jam }}">
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label class="modal-label">Semester</label>
                                                <input type="number" name="semester" class="modal-input"
                                                    value="{{ $item->semester }}">
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="modal-label">Bidang</label>
                                                <select name="bidang_id" class="modal-input" style="height:42px;">
                                                    @foreach ($bidangs as $bidang)
                                                        <option value="{{ $bidang->id }}"
                                                            {{ $item->bidang_id == $bidang->id ? 'selected' : '' }}>
                                                            {{ $bidang->nama }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="modal-label">Prodi</label>
                                                <select name="prodi_id" class="modal-input" style="height:42px;">
                                                    @foreach ($prodis as $prodi)
                                                        <option value="{{ $prodi->id }}"
                                                            {{ $item->prodi_id == $prodi->id ? 'selected' : '' }}>
                                                            {{ $prodi->nama_prodi }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer modal-footer-custom"
                                        style="background:#faf8ff;border-top:1px dashed #e5d9fb;padding:1rem 1.75rem;">
                                        <button type="button" class="btn-outline" data-bs-dismiss="modal">Batal</button>
                                        <button type="submit" class="btn-purple">Simpan Perubahan</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    {{-- MODAL HAPUS --}}
                    <div class="modal fade" id="hapusMatkul{{ $item->id }}" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content modal-content-custom">
                                <div class="modal-header modal-header-purple">
                                    <h5 class="modal-title" style="color:#EEEDFE;font-size:16px;font-weight:500;">
                                        <i class="bi bi-exclamation-triangle me-2"></i> Konfirmasi Hapus
                                    </h5>
                                    <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-hapus-body">
                                    Yakin ingin menghapus mata kuliah
                                    <span style="font-weight:500;color:#111827;">{{ $item->nama_mk }}</span>?
                                    Tindakan ini tidak dapat dibatalkan.
                                </div>
                                <div class="modal-footer modal-footer-custom">
                                    <button type="button" class="btn-outline" data-bs-dismiss="modal">Batal</button>
                                    <form action="{{ route('matakuliah.destroy', $item->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-hapus"><i class="bi bi-trash"></i> Ya,
                                            Hapus</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach

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
        document.getElementById('formImportMk').addEventListener('submit', function() {
            document.getElementById('loadingOverlay').style.display = 'block';
        });
    </script>
@endsection
