@extends('layout.master')

@section('konten')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4>Tambah Beban Dosen</h4>
                </div>

                <div class="card-body">
                    <form action="/beban/store" method="POST">
                        @csrf

                        {{-- PILIH DOSEN --}}
                        <div class="mb-3">
                            <label class="form-label">Dosen</label>
                            <select name="dosen_id" class="form-control">
                                <option value="">-- Pilih Dosen --</option>
                                @foreach ($dosens as $dosen)
                                    <option value="{{ $dosen->id }}">
                                        {{ $dosen->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- SEMESTER --}}
                        <div class="mb-3">
                            <label class="form-label">Semester</label>
                            <select name="semester" class="form-control">
                                <option value="2024_Ganjil">2024 Ganjil</option>
                                <option value="2024_Genap">2024 Genap</option>
                            </select>
                        </div>
                        {{-- SKS --}}
                        <div class="mb-3">
                            <label class="form-label">SKS</label>
                            <input type="number" name="sks" class="form-control" placeholder="Masukkan SKS">
                        </div>

                        {{-- JAM --}}
                        <div class="mb-3">
                            <label class="form-label">Jam</label>
                            <input type="number" name="jam" class="form-control" placeholder="Masukkan Jam">
                        </div>

                        {{-- TOMBOL --}}
                        <div class="mt-3">
                            <button type="submit" class="btn btn-success">
                                Simpan
                            </button>
                            <a href="/dashboard" class="btn btn-secondary">
                                Kembali
                            </a>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
