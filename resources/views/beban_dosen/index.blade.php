@extends('layout.master')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/jurusan.css') }}">
@endpush

@section('konten')
    <div class="row">
        <div class="col-12">

            ```
            <div class="jur-header">
                <div>
                    <div class="jur-header-title">
                        <i class="bi bi-journal-bookmark-fill me-2"></i>
                        Data Beban Dosen
                    </div>
                    <div class="jur-header-sub">
                        Kelola batas minimal dan maksimal SKS dosen
                    </div>
                </div>

                <button class="btn-add-jurusan" data-bs-toggle="modal" data-bs-target="#modalTambahBeban">

                    <i class="bi bi-plus-lg"></i>
                    Tambah Beban
                </button>
            </div>

            <div class="stat-card" style="max-width:200px;margin-bottom:1.25rem;">
                <div class="stat-icon purple">
                    <i class="bi bi-journal-bookmark"></i>
                </div>

                <div>
                    <div class="stat-val">
                        {{ $bebans->count() }}
                    </div>

                    <div class="stat-lbl">
                        Total Beban
                    </div>
                </div>
            </div>

            <div class="main-card">

                <div class="jur-table-wrap">

                    <table class="jur-table">

                        <thead>
                            <tr>
                                <th style="width:60px;">No</th>
                                <th>Nama Beban</th>
                                <th>Minimal SKS</th>
                                <th>Maksimal SKS</th>
                                <th style="width:140px;">Aksi</th>
                            </tr>
                        </thead>

                        <tbody>

                            @forelse($bebans as $item)
                                <tr>

                                    <td style="color:#9ca3af;font-size:12px;">
                                        {{ $loop->iteration }}
                                    </td>

                                    <td>
                                        {{ $item->nama }}
                                    </td>

                                    <td>
                                        {{ $item->min_sks }}
                                    </td>

                                    <td>
                                        {{ $item->max_sks }}
                                    </td>

                                    <td>

                                        <div style="display:flex;gap:5px;">

                                            <button class="btn-edit" data-bs-toggle="modal"
                                                data-bs-target="#modalEditBeban{{ $item->id }}">
                                                <i class="bi bi-pencil"></i>
                                                Edit
                                            </button>

                                            <button class="btn-hapus" data-bs-toggle="modal"
                                                data-bs-target="#modalHapusBeban{{ $item->id }}">
                                                <i class="bi bi-trash"></i>
                                                Hapus
                                            </button>

                                        </div>

                                    </td>

                                </tr>

                            @empty

                                <tr>
                                    <td colspan="5" style="text-align:center;padding:3rem 1rem;color:#9ca3af;">

                                        <i class="bi bi-inbox" style="font-size:32px;display:block;margin-bottom:8px;">
                                        </i>

                                        Data Beban Dosen Tidak Ada

                                    </td>
                                </tr>
                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>

        </div>
        ```

    </div>

    <div class="modal fade" id="modalTambahBeban" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content" style="border-radius:12px;border:0.5px solid rgba(0,0,0,0.1);">
                <form action="{{ route('beban-dosen.store') }}" method="POST">
                    @csrf
                    <div class="modal-header" style="background:#534AB7;border-radius:12px 12px 0 0;border-bottom:none;">
                        <h5 class="modal-title" style="color:#EEEDFE;font-size:16px;font-weight:500;">
                            <i class="bi bi-plus-circle me-2"></i>
                            Tambah Beban Dosen
                        </h5>
                        <button class="btn-close btn-close-white" data-bs-dismiss="modal">
                        </button>
                    </div>

                    <div class="modal-body" style="padding:1.25rem;">
                        <div class="mb-3">
                            <label
                                style="font-size:11px;font-weight:500;color:#6b7280;letter-spacing:.04em;text-transform:uppercase;display:block;margin-bottom:5px;">
                                Nama Beban
                            </label>

                            <input type="text" name="nama"
                                style="width:100%;height:36px;border:1px solid #e5e7eb;border-radius:8px;padding:0 10px;font-size:13px;outline:none;"
                                required>

                        </div>

                        <div class="mb-3">

                            <label
                                style="font-size:11px;font-weight:500;color:#6b7280;letter-spacing:.04em;text-transform:uppercase;display:block;margin-bottom:5px;">

                                Minimal SKS

                            </label>

                            <input type="number" name="min_sks"
                                style="width:100%;height:36px;border:1px solid #e5e7eb;border-radius:8px;padding:0 10px;font-size:13px;outline:none;"
                                required>

                        </div>

                        <div class="mb-2">

                            <label
                                style="font-size:11px;font-weight:500;color:#6b7280;letter-spacing:.04em;text-transform:uppercase;display:block;margin-bottom:5px;">

                                Maksimal SKS

                            </label>

                            <input type="number" name="max_sks"
                                style="width:100%;height:36px;border:1px solid #e5e7eb;border-radius:8px;padding:0 10px;font-size:13px;outline:none;"
                                required>

                        </div>

                    </div>

                    <div class="modal-footer" style="border-top:0.5px solid #f3f4f6;padding:.75rem 1.25rem;">

                        <button type="button" class="btn-outline" data-bs-dismiss="modal">

                            Batal

                        </button>

                        <button type="submit" class="btn-purple">

                            Simpan

                        </button>

                    </div>

                </form>

            </div>

        </div>
        ```

    </div>

    @foreach ($bebans as $item)
        <div class="modal fade" id="modalEditBeban{{ $item->id }}" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content" style="border-radius:12px;border:0.5px solid rgba(0,0,0,0.1);">

                    <form action="{{ route('beban-dosen.update', $item->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="modal-header"
                            style="background:#534AB7;border-radius:12px 12px 0 0;border-bottom:none;">
                            <h5 class="modal-title" style="color:#EEEDFE;font-size:16px;font-weight:500;">
                                <i class="bi bi-pencil-square me-2"></i>
                                Edit Beban Dosen
                            </h5>

                            <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>

                        <div class="modal-body" style="padding:1.25rem;">

                            <div class="mb-3">
                                <label>Nama Beban</label>
                                <input type="text" name="nama" value="{{ $item->nama }}" class="form-control"
                                    required>
                            </div>

                            <div class="mb-3">
                                <label>Minimal SKS</label>
                                <input type="number" name="min_sks" value="{{ $item->min_sks }}" class="form-control"
                                    required>
                            </div>

                            <div class="mb-2">
                                <label>Maksimal SKS</label>
                                <input type="number" name="max_sks" value="{{ $item->max_sks }}" class="form-control"
                                    required>
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
    @endforeach

    @foreach ($bebans as $item)
        <div class="modal fade" id="modalHapusBeban{{ $item->id }}" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content" style="border-radius:12px;border:0.5px solid rgba(0,0,0,0.1);">

                    <div class="modal-header" style="background:#534AB7;border-radius:12px 12px 0 0;border-bottom:none;">
                        <h5 class="modal-title" style="color:#EEEDFE;font-size:16px;">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            Hapus Data
                        </h5>
                        <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body" style="padding:1.25rem;">
                        Yakin ingin menghapus
                        <b>{{ $item->nama }}</b> ?
                    </div>

                    <div class="modal-footer">
                        <button class="btn-outline" data-bs-dismiss="modal">Batal</button>

                        <form action="{{ route('beban-dosen.destroy', $item->id) }}" method="POST">
                            @csrf
                            @method('DELETE')

                            <button type="submit" class="btn-hapus">
                                Hapus
                            </button>
                        </form>

                    </div>

                </div>
            </div>
        </div>
    @endforeach
@endsection
