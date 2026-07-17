@extends('layout.master')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/jurusan.css') }}">
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
            <div class="jur-header">
                <div>
                    <div class="jur-header-title">
                        <i class="bi bi-building-fill me-2"></i>Manajemen Jurusan
                    </div>
                    <div class="jur-header-sub">Kelola data jurusan dan program studi</div>
                </div>
                <button type="button" class="btn-add-jurusan" data-bs-toggle="modal" data-bs-target="#modalTambahJurusan">
                    <i class="bi bi-plus-lg"></i> Tambah Jurusan
                </button>
            </div>

            {{-- STAT CARD --}}
            <div class="stat-card" style="max-width:200px;margin-bottom:1.25rem;">
                <div class="stat-icon purple"><i class="bi bi-book"></i></div>
                <div>
                    <div class="stat-val">{{ $data->count() }}</div>
                    <div class="stat-lbl">Total jurusan</div>
                </div>
            </div>

            {{-- MAIN CARD --}}
            <div class="main-card">

                {{-- TOOLBAR --}}
                <div class="jur-toolbar">
                    <form action="{{ url('/jurusan') }}" method="GET" class="search-wrap">
                        <input type="text" name="search" class="search-input" placeholder="Cari nama atau kode jurusan…"
                            value="{{ request('search') }}">
                        <button type="submit" class="btn-purple">
                            <i class="bi bi-search"></i> Cari
                        </button>
                        <a href="{{ url('/jurusan') }}" class="btn-outline">
                            <i class="bi bi-arrow-counterclockwise"></i> Reset
                        </a>
                    </form>
                </div>

                {{-- TABEL --}}
                <div class="jur-table-wrap">
                    <table class="jur-table">
                        <thead>
                            <tr>
                                <th style="width:44px;">No</th>
                                <th>Kode jurusan</th>
                                <th>Nama jurusan</th>
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

                            @forelse ($data as $item)
                                @php
                                    $c = $avatarColors[$loop->index % 4];
                                    $inisial = strtoupper(substr($item->kode_jurusan, 0, 2));
                                @endphp
                                <tr>
                                    <td style="color:#9ca3af;font-size:12px;">{{ $loop->iteration }}</td>
                                    <td>
                                        <span class="badge-code">{{ $item->kode_jurusan }}</span>
                                    </td>
                                    <td>
                                        <div class="name-cell">
                                            <div class="kode-avatar"
                                                style="background:{{ $c['bg'] }};color:{{ $c['color'] }};">
                                                {{ $inisial }}
                                            </div>
                                            {{ $item->nama_jurusan }}
                                        </div>
                                    </td>
                                    <td>
                                        <div style="display:flex;gap:5px;">
                                            <button class="btn-edit" data-bs-toggle="modal"
                                                data-bs-target="#modalEditJurusan{{ $item->id }}">
                                                <i class="bi bi-pencil"></i> Edit
                                            </button>

                                            <form action="{{ route('jurusan.destroy', $item->id) }}" method="POST"
                                                style="display:inline;">
                                                @csrf
                                                @method('DELETE')

                                                <button type="submit" class="btn-hapus"
                                                    onclick="return confirm('Yakin hapus jurusan ini?')">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" style="text-align:center;padding:3rem 1rem;color:#9ca3af;">
                                        <i class="bi bi-inbox" style="font-size:32px;display:block;margin-bottom:8px;"></i>
                                        Data jurusan belum tersedia
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>

    {{-- MODAL TAMBAH JURUSAN --}}
    <div class="modal fade" id="modalTambahJurusan" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content" style="border-radius:12px;border:0.5px solid rgba(0,0,0,0.1);">
                <div class="modal-header" style="background:#534AB7;border-radius:12px 12px 0 0;border-bottom:none;">
                    <h5 class="modal-title" style="color:#EEEDFE;font-size:16px;font-weight:500;">
                        <i class="bi bi-plus-circle me-2"></i>Tambah Jurusan
                    </h5>
                    <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('jurusan.store') }}" method="POST">
                    @csrf
                    <div class="modal-body" style="padding:1.25rem;">
                        <div class="mb-3">
                            <label
                                style="font-size:11px;font-weight:500;color:#6b7280;letter-spacing:.04em;text-transform:uppercase;display:block;margin-bottom:5px;">
                                Kode Jurusan
                            </label>
                            <input type="text" name="kode_jurusan"
                                style="width:100%;height:36px;border:1px solid #e5e7eb;border-radius:8px;padding:0 10px;font-size:13px;outline:none;"
                                placeholder="Contoh: TIK">
                        </div>
                        <div class="mb-2">
                            <label
                                style="font-size:11px;font-weight:500;color:#6b7280;letter-spacing:.04em;text-transform:uppercase;display:block;margin-bottom:5px;">
                                Nama Jurusan
                            </label>
                            <input type="text" name="nama_jurusan"
                                style="width:100%;height:36px;border:1px solid #e5e7eb;border-radius:8px;padding:0 10px;font-size:13px;outline:none;"
                                placeholder="Contoh: Teknik Informatika">
                        </div>
                    </div>
                    <div class="modal-footer" style="border-top:0.5px solid #f3f4f6;padding:.75rem 1.25rem;">
                        <button type="button" class="btn-outline" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn-purple">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- MODAL EDIT JURUSAN (per item) --}}
    @foreach ($data as $item)
        <div class="modal fade" id="modalEditJurusan{{ $item->id }}" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content" style="border-radius:12px;border:0.5px solid rgba(0,0,0,0.1);">
                    <div class="modal-header" style="background:#534AB7;border-radius:12px 12px 0 0;border-bottom:none;">
                        <h5 class="modal-title" style="color:#EEEDFE;font-size:16px;font-weight:500;">
                            <i class="bi bi-pencil-square me-2"></i>Edit Jurusan
                        </h5>
                        <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <form action="{{ route('jurusan.update', $item->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-body" style="padding:1.25rem;">
                            <div class="mb-3">
                                <label
                                    style="font-size:11px;font-weight:500;color:#6b7280;letter-spacing:.04em;text-transform:uppercase;display:block;margin-bottom:5px;">
                                    Kode Jurusan
                                </label>
                                <input type="text" name="kode_jurusan" value="{{ $item->kode_jurusan }}"
                                    style="width:100%;height:36px;border:1px solid #e5e7eb;border-radius:8px;padding:0 10px;font-size:13px;outline:none;">
                            </div>
                            <div class="mb-2">
                                <label
                                    style="font-size:11px;font-weight:500;color:#6b7280;letter-spacing:.04em;text-transform:uppercase;display:block;margin-bottom:5px;">
                                    Nama Jurusan
                                </label>
                                <input type="text" name="nama_jurusan" value="{{ $item->nama_jurusan }}"
                                    style="width:100%;height:36px;border:1px solid #e5e7eb;border-radius:8px;padding:0 10px;font-size:13px;outline:none;">
                            </div>
                        </div>
                        <div class="modal-footer" style="border-top:0.5px solid #f3f4f6;padding:.75rem 1.25rem;">
                            <button type="button" class="btn-outline" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn-purple">Simpan perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach
@endsection
