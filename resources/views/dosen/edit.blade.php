@extends('layout.master')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/createdosen.css') }}">
@endpush

@section('konten')
    <div class="modal-overlay">
        <div class="modal-box">

            {{-- HEADER --}}
            <div class="modal-header-custom">
                <h5 class="modal-header-title">
                    <i class="bi bi-person-gear"></i>
                    Edit Dosen
                </h5>
                <a href="{{ url('/dosen') }}" class="modal-close-btn">×</a>
            </div>

            {{-- BODY --}}
            <div class="modal-body-custom">

                <form action="{{ url('/dosen/' . $dosen->id) }}" method="POST" id="formEditDosen">
                    @csrf
                    @method('PUT')

                    {{-- IDENTITAS --}}
                    <div class="form-section-label">Identitas dosen</div>

                    <div class="form-grid">

                        <div class="field-wrap">
                            <label for="nip">NIP</label>
                            <input type="text" name="nip" value="{{ old('nip', $dosen->nip) }}">
                        </div>

                        <div class="field-wrap">
                            <label for="keahlian">Keahlian</label>
                            <input type="text" name="keahlian" value="{{ old('keahlian', $dosen->keahlian) }}">
                        </div>

                        <div class="field-wrap full">
                            <label for="nama_dosen">Nama dosen</label>
                            <input type="text" name="nama_dosen" value="{{ old('nama_dosen', $dosen->nama_dosen) }}">
                        </div>

                    </div>

                    {{-- DETAIL MENGAJAR --}}
                    <div class="form-section-label" style="margin-top:8px;">Detail mengajar</div>

                    <div class="form-grid">

                        <div class="field-wrap">
                            <label>Status</label>
                            <select name="status">
                                <option value="DT" {{ old('status', $dosen->status) == 'DT' ? 'selected' : '' }}>DT
                                </option>
                                <option value="DS" {{ old('status', $dosen->status) == 'DS' ? 'selected' : '' }}>DS
                                </option>
                            </select>
                        </div>

                        <div class="field-wrap">
                            <label>Kategori mengajar</label>
                            <select name="kategori_mengajar">
                                <option value="Jurusan"
                                    {{ old('kategori_mengajar', $dosen->kategori_mengajar) == 'Jurusan' ? 'selected' : '' }}>
                                    Jurusan
                                </option>
                                <option value="MKDU"
                                    {{ old('kategori_mengajar', $dosen->kategori_mengajar) == 'MKDU' ? 'selected' : '' }}>
                                    MKDU
                                </option>
                            </select>
                        </div>

                        {{-- JURUSAN --}}
                        <div class="field-wrap">
                            <label>Jurusan</label>
                            <select name="jurusan_id">
                                <option value="">-- Pilih jurusan --</option>
                                @foreach ($jurusans as $jurusan)
                                    <option value="{{ $jurusan->id }}"
                                        {{ old('jurusan_id', $dosen->jurusan_id) == $jurusan->id ? 'selected' : '' }}>
                                        {{ $jurusan->nama_jurusan }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- BEBAN DOSEN --}}
                        <div class="field-wrap">
                            <label>Beban dosen</label>
                            <select name="beban_dosen_id">
                                <option value="">-- Pilih beban --</option>
                                @foreach ($bebanDosens as $beban)
                                    <option value="{{ $beban->id }}"
                                        {{ old('beban_dosen_id', $dosen->beban_dosen_id) == $beban->id ? 'selected' : '' }}>
                                        {{ $beban->nama_beban }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                    </div>

                    {{-- BIDANG (PIVOT MANY TO MANY) --}}
                    <div class="form-section-label">Bidang Keahlian</div>

                    <div class="form-grid">
                        <div class="field-wrap full">

                            <label>Bidang</label>

                            <select name="bidang_ids[]" multiple>
                                @foreach ($bidangs as $bidang)
                                    <option value="{{ $bidang->id }}"
                                        {{ $dosen->bidangs->contains($bidang->id) ? 'selected' : '' }}>
                                        {{ $bidang->nama_bidang }}
                                    </option>
                                @endforeach
                            </select>

                        </div>
                    </div>

                    {{-- FOOTER --}}
                    <div class="modal-footer-custom">
                        <a href="{{ url('/dosen') }}" class="btn-cancel-modal">Batal</a>

                        <button type="submit" class="btn-save-modal">
                            <i class="bi bi-check-lg"></i>
                            Update
                        </button>
                    </div>

                </form>

            </div>

        </div>
    </div>
@endsection
