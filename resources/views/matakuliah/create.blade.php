@extends('layout.master')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/createMK.css') }}">
@endpush

@section('konten')
    <div class="row">
        <div class="col-12">

            <div class="main-card mk-form-card">

                <div class="mk-header">
                    <div class="mk-header-icon">
                        <i class="bi bi-plus-circle"></i>
                    </div>
                    <div>
                        <div class="mk-header-title">Tambah Mata Kuliah</div>
                        <div class="mk-header-sub">Form input data mata kuliah baru</div>
                    </div>
                </div>

                <form action="{{ route('matakuliah.store') }}" method="POST">
                    @csrf

                    <div class="p-4">

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0 ps-3">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="modal-label">Prodi <span class="text-danger">*</span></label>
                                <select name="prodi_id" class="modal-input" required>
                                    <option value="">-- Pilih Prodi --</option>
                                    @foreach ($prodis as $p)
                                        <option value="{{ $p->id }}"
                                            {{ old('prodi_id') == $p->id ? 'selected' : '' }}>
                                            {{ $p->nama_prodi }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="modal-label">Bidang</label>
                                <select name="bidang_id" class="modal-input">
                                    <option value="">-- Pilih Bidang --</option>
                                    @foreach ($bidangs as $b)
                                        <option value="{{ $b->id }}"
                                            {{ old('bidang_id') == $b->id ? 'selected' : '' }}>
                                            {{ $b->nama }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="modal-label">Kode MK <span class="text-danger">*</span></label>
                                <input type="text" name="kode_mk" class="modal-input" value="{{ old('kode_mk') }}"
                                    placeholder="Contoh: TI101" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="modal-label">Nama Mata Kuliah <span class="text-danger">*</span></label>
                                <input type="text" name="nama_mk" class="modal-input" value="{{ old('nama_mk') }}"
                                    placeholder="Contoh: Pemrograman Web" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="modal-label">SKS <span class="text-danger">*</span></label>
                                <input type="number" name="sks" class="modal-input" value="{{ old('sks') }}"
                                    min="1" required>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="modal-label">Jam</label>
                                <input type="number" name="jam" class="modal-input" value="{{ old('jam') }}"
                                    min="0">
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="modal-label">Semester</label>
                                <input type="number" name="semester" class="modal-input" value="{{ old('semester') }}"
                                    min="1" max="14">
                            </div>
                        </div>

                        <div class="mk-form-actions">
                            <a href="{{ route('matakuliah.index') }}" class="btn-outline">
                                <i class="bi bi-arrow-left me-1"></i> Kembali
                            </a>
                            <button type="submit" class="btn-purple">
                                <i class="bi bi-save me-1"></i> Simpan
                            </button>
                        </div>

                    </div>
                </form>

            </div>

        </div>
    </div>
@endsection
