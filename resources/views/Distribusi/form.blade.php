@extends('layout.master')

@section('konten')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-info text-white">Tambah Distribusi Manual</div>
                <div class="card-body">
                    <form action="{{ route('distribusi.storeManual') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label>Dosen</label>
                            <select name="dosen_id" class="form-select" required>
                                @foreach ($dosens as $d)
                                    <option value="{{ $d->id }}">{{ $d->nama_dosen }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label>Mata Kuliah</label>
                            <select name="mata_kuliah_id" class="form-select" required>
                                @foreach ($mataKuliahs as $mk)
                                    <option value="{{ $mk->id }}">{{ $mk->kode_mk }} - {{ $mk->nama_mk }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label>Prodi</label>
                            <select name="prodi_id" class="form-select" required>
                                @foreach ($prodis as $p)
                                    <option value="{{ $p->id }}">{{ $p->nama_prodi }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label>Semester Ajaran (ganjil/genap)</label>
                            <input type="text" name="semester_ajaran" class="form-control" required
                                placeholder="Ganjil 2026/2027">
                        </div>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <a href="{{ route('distribusi.index') }}" class="btn btn-secondary">Kembali</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
