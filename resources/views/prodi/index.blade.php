@extends('layout.master')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/prodi.css') }}">
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
            <div class="prodi-header">
                <div>
                    <div class="prodi-header-title">
                        <i class="bi bi-diagram-3 me-2"></i>Manajemen Program Studi
                    </div>
                    <div class="prodi-header-sub">Kelola data program studi per jurusan</div>
                </div>
                <button type="button" class="btn-add-prodi" data-bs-toggle="modal" data-bs-target="#modalTambahProdi">
                    <i class="bi bi-plus-lg"></i> Tambah Prodi
                </button>
            </div>

            {{-- STAT CARD --}}
            <div class="stat-card">
                <div class="stat-icon purple"><i class="bi bi-diagram-3"></i></div>
                <div>
                    <div class="stat-val">{{ $prodis->count() }}</div>
                    <div class="stat-lbl">Total prodi</div>
                </div>
            </div>

            {{-- MAIN CARD --}}
            <div class="main-card">

                {{-- TOOLBAR --}}
                <form action="{{ route('prodi.index') }}" method="GET" class="prodi-toolbar">
                    <div class="field-group">
                        <label>Pilih Jurusan</label>
                        <select name="jurusan">
                            <option value="">Semua jurusan</option>
                            @foreach ($jurusans as $jurusan)
                                <option value="{{ $jurusan->id }}"
                                    {{ request('jurusan') == $jurusan->id ? 'selected' : '' }}>
                                    {{ $jurusan->nama_jurusan }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn-purple" style="margin-top:auto;">
                        <i class="bi bi-funnel"></i> Filter
                    </button>
                    <a href="{{ route('prodi.index') }}" class="btn-outline" style="margin-top:auto;">
                        <i class="bi bi-arrow-counterclockwise"></i> Reset
                    </a>
                </form>

                {{-- TABEL --}}
                <div class="prodi-table-wrap">
                    <table class="prodi-table">
                        <thead>
                            <tr>
                                <th style="width:44px;">No</th>
                                <th>Kode prodi</th>
                                <th>Nama prodi</th>
                                <th>Jurusan</th>
                                <th style="width:110px;">Aksi</th>
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

                            @forelse ($prodis as $item)
                                @php $c = $avatarColors[$loop->index % 4]; @endphp
                                <tr>
                                    <td style="color:#9ca3af;font-size:12px;">{{ $loop->iteration }}</td>
                                    <td>
                                        <span class="badge-code">{{ $item->kode_prodi }}</span>
                                    </td>
                                    <td>
                                        <div class="name-cell">
                                            <div class="kode-avatar"
                                                style="background:{{ $c['bg'] }};color:{{ $c['color'] }};">
                                                {{ strtoupper(substr($item->kode_prodi, 0, 2)) }}
                                            </div>
                                            {{ $item->nama_prodi }}
                                        </div>
                                    </td>
                                    <td>
                                        @if ($item->jurusan)
                                            <span class="badge-jur">{{ $item->jurusan->kode_jurusan }}</span>
                                        @else
                                            <span style="color:#d1d5db;">—</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div style="display:flex;gap:5px;">
                                            <button class="btn-edit" data-bs-toggle="modal"
                                                data-bs-target="#editProdi{{ $item->id }}">
                                                <i class="bi bi-pencil"></i> Edit
                                            </button>
                                            <button class="btn-hapus" data-bs-toggle="modal"
                                                data-bs-target="#hapusProdi{{ $item->id }}">
                                                <i class="bi bi-trash"></i> Hapus
                                            </button>
                                        </div>
                                    </td>
                                </tr>

                                {{-- MODAL EDIT --}}
                                <div class="modal fade" id="editProdi{{ $item->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content modal-content-custom">
                                            <div class="modal-header modal-header-purple">
                                                <h5 class="modal-title"
                                                    style="color:#EEEDFE;font-size:16px;font-weight:500;">
                                                    <i class="bi bi-pencil-square me-2"></i>Edit Prodi
                                                </h5>
                                                <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                            </div>
                                            <form action="{{ route('prodi.update', $item->id) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-body" style="padding:1.25rem;">
                                                    <div class="mb-3">
                                                        <label class="modal-label">Jurusan</label>
                                                        <select name="jurusan_id" class="modal-input" style="height:36px;">
                                                            @foreach ($jurusans as $jurusan)
                                                                <option value="{{ $jurusan->id }}"
                                                                    {{ $item->jurusan_id == $jurusan->id ? 'selected' : '' }}>
                                                                    {{ $jurusan->nama_jurusan }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="modal-label">Kode Prodi</label>
                                                        <input type="text" name="kode_prodi" class="modal-input"
                                                            value="{{ $item->kode_prodi }}">
                                                    </div>
                                                    <div class="mb-2">
                                                        <label class="modal-label">Nama Prodi</label>
                                                        <input type="text" name="nama_prodi" class="modal-input"
                                                            value="{{ $item->nama_prodi }}">
                                                    </div>
                                                </div>
                                                <div class="modal-footer modal-footer-custom">
                                                    <button type="button" class="btn-outline"
                                                        data-bs-dismiss="modal">Batal</button>
                                                    <button type="submit" class="btn-purple">Simpan perubahan</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                {{-- MODAL HAPUS --}}
                                <div class="modal fade" id="hapusProdi{{ $item->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content modal-content-custom">
                                            <div class="modal-header modal-header-purple">
                                                <h5 class="modal-title"
                                                    style="color:#EEEDFE;font-size:16px;font-weight:500;">
                                                    <i class="bi bi-exclamation-triangle me-2"></i>Konfirmasi Hapus
                                                </h5>
                                                <button class="btn-close btn-close-white"
                                                    data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-hapus-body">
                                                Yakin ingin menghapus prodi
                                                <span
                                                    style="font-weight:500;color:#111827;">{{ $item->nama_prodi }}</span>?
                                                Tindakan ini tidak dapat dibatalkan.
                                            </div>
                                            <div class="modal-footer modal-footer-custom">
                                                <button type="button" class="btn-outline"
                                                    data-bs-dismiss="modal">Batal</button>
                                                <form action="{{ route('prodi.destroy', $item->id) }}" method="POST"
                                                    style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn-hapus">
                                                        <i class="bi bi-trash"></i> Ya, hapus
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            @empty
                                <tr>
                                    <td colspan="5" style="text-align:center;padding:3rem 1rem;color:#9ca3af;">
                                        <i class="bi bi-inbox"
                                            style="font-size:32px;display:block;margin-bottom:8px;"></i>
                                        Data prodi belum tersedia
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>

    {{-- MODAL TAMBAH PRODI --}}
    <div class="modal fade" id="modalTambahProdi" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content modal-content-custom">
                <div class="modal-header modal-header-purple">
                    <h5 class="modal-title" style="color:#EEEDFE;font-size:16px;font-weight:500;">
                        <i class="bi bi-plus-circle me-2"></i>Tambah Prodi
                    </h5>
                    <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('prodi.store') }}" method="POST">
                    @csrf
                    <div class="modal-body" style="padding:1.25rem;">
                        <div class="mb-3">
                            <label class="modal-label">Jurusan</label>
                            <select name="jurusan_id" class="modal-input" style="height:36px;" required>
                                <option value="">-- Pilih jurusan --</option>
                                @foreach ($jurusans as $jurusan)
                                    <option value="{{ $jurusan->id }}">{{ $jurusan->nama_jurusan }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="modal-label">Kode Prodi</label>
                            <input type="text" name="kode_prodi" class="modal-input" placeholder="Contoh: IF-S1"
                                required>
                        </div>
                        <div class="mb-2">
                            <label class="modal-label">Nama Prodi</label>
                            <input type="text" name="nama_prodi" class="modal-input"
                                placeholder="Contoh: Informatika" required>
                        </div>
                    </div>
                    <div class="modal-footer modal-footer-custom">
                        <button type="button" class="btn-outline" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn-purple">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection
