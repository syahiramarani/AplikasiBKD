@extends('layout.master')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/createdosen.css') }}">
@endpush

@section('konten')
    <div class="page-wrap">
        {{-- HEADER HALAMAN --}}
        {{-- TOP BANNER (seperti gambar) --}}
        <div class="top-banner">
            <div class="top-banner__left">
                <div class="top-banner__icon">
                    <i class="bi bi-person-plus"></i>
                </div>
                <div class="top-banner__text">
                    <div class="top-banner__title">Tambah Dosen</div>
                    <div class="top-banner__subtitle">Buat data dosen baru dan lengkapi identitasnya</div>
                </div>
            </div>

            <a href="{{ url('/dosen') }}" class="top-banner__back">
                <i class="bi bi-arrow-left"></i>
                Kembali
            </a>
        </div>

        {{-- CARD FORM --}}
        <div class="page-card">
            <form action="{{ route('dosen.store') }}" method="POST" id="formTambahDosen">
                @csrf

                <div class="form-section-label">Identitas dosen</div>

                <div class="form-grid">
                    {{-- NIP --}}
                    <div class="field-wrap">
                        <label for="nip">NIP</label>
                        <input type="text" id="nip" name="nip" value="{{ old('nip') }}"
                            placeholder="19850101 201001 1 001" class="{{ $errors->has('nip') ? 'is-invalid' : '' }}">
                        @error('nip')
                            <span class="field-error">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- NAMA DOSEN --}}
                    <div class="field-wrap full">
                        <label for="nama_dosen">Nama dosen</label>
                        <input type="text" id="nama_dosen" name="nama_dosen" value="{{ old('nama_dosen') }}"
                            placeholder="Dr. Ahmad Fauzi, M.T."
                            class="{{ $errors->has('nama_dosen') ? 'is-invalid' : '' }}">
                        @error('nama_dosen')
                            <span class="field-error">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- JABATAN --}}
                    <div class="field-wrap">
                        <label for="jabatan">Jabatan</label>
                        <input type="text" id="jabatan" name="jabatan" value="{{ old('jabatan') }}"
                            placeholder="Lektor / Asisten Ahli" class="{{ $errors->has('jabatan') ? 'is-invalid' : '' }}">
                        @error('jabatan')
                            <span class="field-error">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- STATUS --}}
                    <div class="field-wrap">
                        <label for="status">Status</label>
                        <select id="status" name="status" class="{{ $errors->has('status') ? 'is-invalid' : '' }}">
                            <option value="DT" {{ old('status') == 'DT' ? 'selected' : '' }}>DT</option>
                            <option value="DS" {{ old('status') == 'DS' ? 'selected' : '' }}>DS</option>
                        </select>
                        @error('status')
                            <span class="field-error">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- JURUSAN --}}
                    <div class="field-wrap">
                        <label for="jurusan_id">Jurusan</label>
                        <select id="jurusan_id" name="jurusan_id"
                            class="{{ $errors->has('jurusan_id') ? 'is-invalid' : '' }}">
                            <option value="">-- Pilih jurusan --</option>
                            @foreach ($jurusans as $jurusan)
                                <option value="{{ $jurusan->id }}"
                                    {{ old('jurusan_id') == $jurusan->id ? 'selected' : '' }}>
                                    {{ $jurusan->nama_jurusan }}
                                </option>
                            @endforeach
                        </select>
                        @error('jurusan_id')
                            <span class="field-error">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- PRODI --}}
                    <div class="field-wrap">
                        <label for="prodi_id">Prodi</label>
                        <select id="prodi_id" name="prodi_id" class="{{ $errors->has('prodi_id') ? 'is-invalid' : '' }}">
                            <option value="">-- Pilih Prodi --</option>
                            @foreach ($prodis ?? [] as $prodi)
                                <option value="{{ $prodi->id }}" {{ old('prodi_id') == $prodi->id ? 'selected' : '' }}>
                                    {{ $prodi->nama_prodi }}
                                </option>
                            @endforeach
                        </select>
                        @error('prodi_id')
                            <span class="field-error">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- BEBAN DOSEN --}}
                    <div class="field-wrap">
                        <label for="beban_dosen_id">Beban dosen</label>
                        <select name="beban_dosen_id" id="beban_dosen_id">
                            <option value="">-- Pilih Beban Dosen --</option>
                            @foreach ($bebanDosens as $beban)
                                <option value="{{ $beban->id }}"
                                    {{ old('beban_dosen_id') == $beban->id ? 'selected' : '' }}>
                                    {{ $beban->nama }}
                                </option>
                            @endforeach
                        </select>
                        @error('beban_dosen_id')
                            <span class="field-error">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- KEAHLIAN --}}
                    <div class="field-wrap full">
                        <label>Keahlian</label>
                        <div id="bidangContainer" class="bidang-box">
                            <small class="text-muted">Pilih jurusan terlebih dahulu</small>
                        </div>

                        @error('bidang')
                            <span class="field-error">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                {{-- FOOTER --}}
                <div class="form-footer">
                    <a href="{{ url('/dosen') }}" class="btn-cancel-modal">Batal</a>
                    <button type="submit" class="btn-save-modal">
                        <i class="bi bi-check-lg"></i>
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const jurusan = document.getElementById('jurusan_id');
        const prodi = document.getElementById('prodi_id');
        const container = document.getElementById('bidangContainer');

        function loadProdi(jurusanId) {
            fetch('/get-prodi/' + jurusanId)
                .then(res => res.json())
                .then(data => {
                    prodi.innerHTML = '<option value="">-- Pilih Prodi --</option>';
                    data.forEach(p => {
                        prodi.innerHTML += `<option value="${p.id}">${p.nama_prodi}</option>`;
                    });
                })
                .catch(err => console.log("ERROR:", err));
        }

        function loadBidang(jurusanId) {
            if (!jurusanId) {
                container.innerHTML = '<small class="text-muted">Pilih jurusan terlebih dahulu</small>';
                return;
            }

            container.innerHTML = '<small class="text-muted">Memuat keahlian...</small>';

            fetch('/get-bidang/' + jurusanId)
                .then(res => res.json())
                .then(data => {
                    if (!data.length) {
                        container.innerHTML = '<small class="text-muted">Tidak ada keahlian</small>';
                        return;
                    }

                    let html = '<div class="bidang-list">';
                    data.forEach(item => {
                        html += `
                        <label class="bidang-item">
                            <input type="checkbox" name="bidang[]" value="${item.id}">
                            <span>${item.nama}</span>
                        </label>
                    `;
                    });
                    html += '</div>';
                    container.innerHTML = html;
                })
                .catch(() => {
                    container.innerHTML = '<small class="text-muted">Gagal memuat keahlian</small>';
                });
        }

        // Saat jurusan berubah -> load prodi & bidang
        jurusan.addEventListener('change', function() {
            loadProdi(this.value);
            loadBidang(this.value);
        });

        // Kalau halaman dibuka dengan jurusan sudah terpilih (mis. old value), langsung load
        if (jurusan.value) {
            loadProdi(jurusan.value);
            loadBidang(jurusan.value);
        } else {
            loadBidang('');
        }
    });
</script>
