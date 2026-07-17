@extends('layout.master')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/distribusi.css') }}">
@endpush

@section('konten')
    <div class="container-fluid px-4">
        <div class="dist-page">
            <div class="dist-header mb-3">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div>
                        <div class="fw-bold" style="font-size: 18px;">Distribusi Beban Mengajar</div>
                        <div style="opacity:.9; font-size: 13px;">
                            Kelola distribusi otomatis & pantau hasil beban SKS dosen.
                        </div>
                    </div>

                    <div class="badge bg-light text-dark rounded-pill px-3 py-2">
                        Panel Distribusi
                    </div>
                </div>
            </div>

            @if (session('error'))
                <div class="alert alert-danger mt-2">{{ session('error') }}</div>
            @endif

            @if (session('success'))
                <div class="alert alert-success mt-2">{{ session('success') }}</div>
            @endif

            <div class="row g-3 mb-3">
                <div class="col-md-4">
                    <div class="kpi-card bg-white">
                        <div class="kpi-top"></div>
                        <div class="p-3">
                            <h6>Total Dosen</h6>
                            <h3 class="text-dark">{{ $totalDosen ?? 0 }}</h3>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="kpi-card bg-white">
                        <div class="kpi-top"></div>
                        <div class="p-3">
                            <h6>Dosen Lintas Prodi</h6>
                            <h3 class="text-dark">{{ $totalLintasProdi ?? 0 }}</h3>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="kpi-card bg-white">
                        <div class="kpi-top"></div>
                        <div class="p-3">
                            <h6>Ditolak Sistem</h6>
                            <h3 class="text-dark">{{ $totalDitolak ?? 0 }}</h3>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Form Distribusi Otomatis --}}
            <div class="row g-3">
                <div class="col-12">
                    <div class="card cool-card mb-0">
                        <div class="card-header d-flex align-items-center justify-content-between">
                            <b>Distribusi Otomatis</b>
                            <span class="badge bg-primary-subtle text-primary rounded-pill">Auto</span>
                        </div>

                        <div class="card-body">
                            <form id="formDistribusi" method="POST" action="{{ route('distribusi.proses') }}">
                                @csrf

                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Tahun Ajaran</label>
                                        <select id="tahun_ajaran_id" name="tahun_ajaran_id" class="form-select">
                                            <option value="">Pilih Tahun Ajaran</option>

                                            @foreach ($tahun as $ta)
                                                <option value="{{ $ta->id }}">
                                                    {{ $ta->tahun_ajaran }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Prodi</label>
                                        <select name="prodi_id" class="form-control" required>
                                            @foreach ($prodis ?? [] as $p)
                                                <option value="{{ $p->id }}"
                                                    {{ (string) $prodiId === (string) $p->id ? 'selected' : '' }}>
                                                    {{ $p->nama_prodi }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Semester</label>
                                        <select name="semester" class="form-control" required>
                                            @for ($i = 1; $i <= 8; $i++)
                                                <option value="{{ $i }}"
                                                    {{ (int) $semester === (int) $i ? 'selected' : '' }}>
                                                    Semester {{ $i }}
                                                </option>
                                            @endfor
                                        </select>
                                    </div>

                                    <div class="col-12">
                                        <button id="btnProsesDistribusi" class="btn btn-primary w-100 fw-semibold py-2"
                                            type="submit">
                                            Proses Distribusi
                                        </button>

                                        <a href="{{ route('distribusi.rekap', ['semester' => request('semester')]) }}"
                                            class="btn btn-outline-dark py-2 mt-2">
                                            Rekap Laporan
                                        </a>

                                        <div class="mt-3">
                                            <div class="progress">
                                                <div id="bar" class="progress-bar bg-info" style="width:0%"></div>
                                            </div>
                                            <small id="text">Menunggu proses...</small>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Hasil Distribusi --}}
            <div class="col-12 mt-3">
                <div class="card cool-card">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <b>Hasil Distribusi</b>
                        <span class="badge bg-dark rounded-pill">Preview</span>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered cool-table align-middle">
                                <thead>
                                    <tr>
                                        <th>Dosen</th>
                                        <th>Prodi</th>
                                        <th>MK</th>
                                        <th>SKS</th>
                                        <th>Beban SKS</th>
                                        <th>Status</th>
                                        <th>Sumber</th>
                                        <th style="min-width: 190px;">Aksi</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @forelse ($distribusi ?? [] as $d)
                                        @php
                                            $dosen = $d->dosen;
                                            $dosenId = $dosen->id ?? null;
                                            $bebanDosen = $dosen ? $dosen->bebanDosen : null;

                                            $sksAktif = $sksPerDosen[$dosenId] ?? 0;

                                            $hasBeban = !is_null($bebanDosen);
                                            $min = $bebanDosen->min_sks ?? 0;
                                            $max = $bebanDosen->max_sks ?? 0;

                                            $underload = $hasBeban ? $sksAktif < $min : false;
                                            $overload = $hasBeban && $max > 0 ? $sksAktif > $max : false;

                                            $progressWidth =
                                                $hasBeban && $max > 0 ? min(100, ($sksAktif / $max) * 100) : 0;

                                            $progressColor = $underload
                                                ? 'linear-gradient(90deg, #f59e0b, #fbbf24)'
                                                : ($overload
                                                    ? 'linear-gradient(90deg, #ef4444, #f87171)'
                                                    : 'linear-gradient(90deg, #22c55e, #06b6d4, #2563eb)');
                                        @endphp

                                        <tr class="{{ $overload ? 'table-danger' : '' }}">
                                            <td class="fw-semibold">{{ $dosen->nama_dosen ?? '-' }}</td>
                                            <td>{{ $d->prodi->nama_prodi ?? '-' }}</td>
                                            <td>{{ $d->mataKuliah->nama_mk ?? '-' }}</td>
                                            <td class="fw-bold">{{ $d->sks }} SKS</td>

                                            <td style="min-width: 220px;">
                                                <div class="fw-semibold">{{ $sksAktif }} SKS</div>

                                                @if ($hasBeban)
                                                    <small class="text-muted">
                                                        Beban: {{ $min }} - {{ $max }} SKS
                                                    </small>

                                                    <div class="progress cool-progress mt-2" style="height: 8px;">
                                                        <div class="progress-bar"
                                                            style="width: {{ $progressWidth }}%; background: {{ $progressColor }};">
                                                        </div>
                                                    </div>

                                                    <small class="text-muted d-block mt-1">
                                                        {{ $sksAktif }} / {{ $max }} SKS
                                                    </small>
                                                @else
                                                    <small class="text-muted d-block">
                                                        Beban dosen belum diatur
                                                    </small>
                                                @endif
                                            </td>

                                            <td>
                                                @if (!$hasBeban)
                                                    <span class="badge bg-secondary cool-badge">BELUM DIATUR</span>
                                                @elseif ($underload)
                                                    <span class="badge bg-warning text-dark cool-badge">KURANG BEBAN</span>
                                                @elseif ($overload)
                                                    <span class="badge bg-danger cool-badge">KELEBIHAN BEBAN</span>
                                                @else
                                                    <span class="badge bg-success cool-badge">SESUAI BEBAN</span>
                                                @endif
                                            </td>

                                            <td>
                                                @switch($d->sumber)
                                                    @case('ag')
                                                        <span class="badge-sumber badge-sumber-ag">
                                                            <i class="bi bi-cpu me-1"></i> Algoritma
                                                        </span>
                                                    @break

                                                    @case('manual')
                                                        <span class="badge-sumber badge-sumber-manual">
                                                            <i class="bi bi-pencil me-1"></i> Manual
                                                        </span>
                                                    @break

                                                    @default
                                                        <span class="badge-sumber badge-sumber-default">-</span>
                                                @endswitch
                                            </td>

                                            <td class="row-actions">
                                                <div class="d-flex gap-2 flex-wrap">
                                                    <button class="btn btn-outline-primary btn-sm" data-bs-toggle="modal"
                                                        data-bs-target="#detailModalDosen{{ $d->dosen_id }}">
                                                        Detail
                                                    </button>

                                                    <button class="btn btn-outline-warning btn-sm" data-bs-toggle="modal"
                                                        data-bs-target="#editModal{{ $d->id }}">
                                                        Edit
                                                    </button>

                                                    <form
                                                        action="{{ route('distribusi.hapus', ['distribusi' => $d->id]) }}"
                                                        method="POST" style="display:inline-block;">
                                                        @csrf
                                                        @method('DELETE')

                                                        <button type="submit" class="btn btn-outline-danger btn-sm"
                                                            onclick="return confirm('Hapus?')">
                                                            Hapus
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                        @empty
                                            <tr>
                                                <td colspan="8" class="text-center text-muted py-4">
                                                    Belum ada data distribusi.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Modal Detail dan Edit --}}
                @if (($distribusi ?? collect())->count())
                    @foreach ($distribusi->groupBy('dosen_id') as $dosenId => $items)
                        @php
                            $dosen = $items->first()->dosen;
                            $totalSks = $items->sum('sks');
                            $totalJam = $items->sum('jam');
                            $totalKelas = $items->count();

                            $bebanDosen = $dosen ? $dosen->bebanDosen : null;
                            $minSks = $bebanDosen->min_sks ?? 0;
                            $maxSks = $bebanDosen->max_sks ?? 0;
                        @endphp

                        @if ($dosen)
                            <div class="modal fade detail-modal-dosen" id="detailModalDosen{{ $dosenId }}"
                                tabindex="-1">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header"
                                            style="background: linear-gradient(135deg, #2563eb, #1d4ed8); color: #fff;">
                                            <div>
                                                <div class="fw-semibold" style="font-size: 18px; letter-spacing: .2px;">
                                                    Detail Distribusi Mengajar
                                                </div>
                                                <div style="opacity:.9; font-size: 14px;">
                                                    Ringkasan beban dosen
                                                </div>
                                            </div>

                                            <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                        </div>

                                        <div class="modal-body" style="font-size: 16px;">
                                            <div class="p-3 rounded-3 border bg-light">
                                                <div class="fw-semibold" style="font-size: 18px;">
                                                    {{ $dosen->nama_dosen ?? '-' }}
                                                </div>

                                                <div class="text-muted" style="font-size: 14px;">
                                                    {{ $dosen->nidn ?? '' }}
                                                </div>

                                                <div class="row g-2 mt-3">
                                                    <div class="col-6 col-md-3">
                                                        <div class="p-2 rounded-3 bg-white border text-center">
                                                            <div class="text-muted" style="font-size: 13px;">Total SKS</div>
                                                            <div class="fw-bold" style="font-size: 18px;">
                                                                {{ $totalSks }}
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-6 col-md-3">
                                                        <div class="p-2 rounded-3 bg-white border text-center">
                                                            <div class="text-muted" style="font-size: 13px;">Total Jam</div>
                                                            <div class="fw-bold" style="font-size: 18px;">
                                                                {{ $totalJam }}
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-6 col-md-3">
                                                        <div class="p-2 rounded-3 bg-white border text-center">
                                                            <div class="text-muted" style="font-size: 13px;">Total Kelas</div>
                                                            <div class="fw-bold" style="font-size: 18px;">
                                                                {{ $totalKelas }}
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-6 col-md-3">
                                                        <div class="p-2 rounded-3 bg-white border text-center">
                                                            <div class="text-muted" style="font-size: 13px;">Beban SKS</div>
                                                            <div class="fw-bold" style="font-size: 18px;">
                                                                {{ $minSks }} - {{ $maxSks }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <hr>

                                            <div class="table-responsive">
                                                <table class="table table-sm">
                                                    <thead>
                                                        <tr>
                                                            <th>Prodi</th>
                                                            <th>Mata Kuliah</th>
                                                            <th>Semester</th>
                                                            <th>SKS</th>
                                                            <th>Kelas</th>
                                                            <th>Sumber</th>
                                                        </tr>
                                                    </thead>

                                                    <tbody>
                                                        @foreach ($items as $it)
                                                            <tr>
                                                                <td>{{ $it->prodi->nama_prodi ?? '-' }}</td>
                                                                <td>{{ $it->mataKuliah->nama_mk ?? '-' }}</td>
                                                                <td>{{ $it->mataKuliah->semester ?? '-' }}</td>
                                                                <td>{{ $it->sks ?? 0 }}</td>
                                                                <td>
                                                                    @php
                                                                        $prodi =
                                                                            $it->prodi->kode_prodi ??
                                                                            ($it->prodi->nama_prodi ?? null);
                                                                        $tahun =
                                                                            $it->kelas->angkatan->tahun_masuk ?? null;
                                                                        $rombel = $it->kelas->rombel ?? null;
                                                                    @endphp

                                                                    {{ $prodi && $tahun && $rombel ? "{$prodi}-{$tahun} {$rombel}" : '-' }}
                                                                </td>
                                                                <td>{{ $it->sumber ?? '-' }}</td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach

                    <form action="/distribusi/simpan-history" method="POST" class="mt-3">
                        @csrf
                        <input type="hidden" name="batch_id" value="{{ session('batch_id') }}">
                        <input type="hidden" name="semester" value="{{ $semester }}">
                        <button type="submit">Simpan History</button>
                    </form>

                    @foreach ($distribusi as $d)
                        @php
                            $semesterDistribusi = $d->mataKuliah->semester ?? $semester;
                            $matkulEditList = $mataKuliahs->where('semester', $semesterDistribusi);
                        @endphp

                        <div class="modal fade" id="editModal{{ $d->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content" style="border-radius: 16px; overflow:hidden;">
                                    <div class="modal-header"
                                        style="background: linear-gradient(135deg, #f59e0b, #d97706); color:#fff;">
                                        <h5 class="modal-title">Edit Distribusi</h5>
                                        <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                    </div>

                                    <form method="POST" action="{{ route('distribusi.update', ['distribusi' => $d->id]) }}"
                                        class="form-edit-distribusi" data-distribusi-id="{{ $d->id }}"
                                        data-current-dosen-id="{{ $d->dosen_id }}"
                                        data-kandidat-url="{{ route('distribusi.kandidatEdit', ['distribusi' => $d->id, 'mataKuliah' => '__MATKUL__']) }}">
                                        @csrf
                                        @method('PUT')

                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <div class="small text-muted mb-1">Distribusi saat ini</div>

                                                <div class="p-3 rounded-3 border bg-light">
                                                    <div><b>Dosen:</b> {{ $d->dosen->nama_dosen ?? '-' }}</div>
                                                    <div><b>Mata Kuliah:</b> {{ $d->mataKuliah->nama_mk ?? '-' }}</div>
                                                    <div><b>Semester:</b> {{ $d->mataKuliah->semester ?? '-' }}</div>
                                                    <div><b>SKS:</b> {{ $d->sks ?? 0 }}</div>
                                                </div>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label fw-semibold">Pilih Mata Kuliah</label>

                                                <select name="mata_kuliah_id" class="form-select edit-matkul-select"
                                                    onchange="loadKandidatDosenEdit(this)" required>
                                                    <option value="">-- Pilih matkul --</option>

                                                    @foreach ($matkulEditList as $mk)
                                                        <option value="{{ $mk->id }}"
                                                            {{ (int) $d->mata_kuliah_id === (int) $mk->id ? 'selected' : '' }}>
                                                            {{ $mk->nama_mk }} - {{ $mk->sks }} SKS
                                                            (Semester {{ $mk->semester }})
                                                        </option>
                                                    @endforeach
                                                </select>

                                                <small class="text-muted">
                                                    Hanya mata kuliah semester {{ $semesterDistribusi }} yang ditampilkan.
                                                </small>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label fw-semibold">Dosen Kandidat</label>

                                                <div class="candidate-list border rounded-3 p-3 bg-light"
                                                    id="candidateList{{ $d->id }}">
                                                    Pilih mata kuliah untuk memuat kandidat dosen.
                                                </div>

                                                <input type="hidden" name="dosen_id" class="selected-dosen-input"
                                                    value="{{ $d->dosen_id }}">
                                            </div>
                                        </div>

                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-light border" data-bs-dismiss="modal">
                                                Batal
                                            </button>

                                            <button type="submit" class="btn btn-warning text-white fw-semibold">
                                                Simpan Perubahan
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif

                {{-- Loading Overlay --}}
                <div id="gaLoadingOverlay" class="ga-loading-overlay" aria-hidden="true">
                    <div class="ga-loading-card">
                        <div class="ga-loading-head">
                            <div class="fw-bold">Memuat Hasil Distribusi</div>
                            <div style="font-size: 13px; opacity: .9;">
                                Sistem sedang memproses algoritma genetik, mohon tunggu sebentar.
                            </div>
                        </div>

                        <div class="ga-loading-body">
                            <div class="ga-spinner"></div>

                            <div class="ga-loading-title">Sedang Memproses Distribusi...</div>
                            <div class="ga-loading-subtitle">
                                Layar dikunci sementara agar proses tidak terganggu.
                            </div>

                            <div class="ga-loading-progress">
                                <div class="bar"></div>
                            </div>

                            <div class="ga-loading-note" id="overlayLoadingText">
                                Menyiapkan proses distribusi dosen...
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endsection

    <script>
        async function loadKandidatDosenEdit(selectEl) {
            const form = selectEl.closest('form');
            const candidateList = form.querySelector('.candidate-list');
            const selectedDosenInput = form.querySelector('.selected-dosen-input');
            const urlTemplate = form.dataset.kandidatUrl;
            const distribusiId = form.dataset.distribusiId;
            const matkulId = selectEl.value;

            console.log('FORM:', form);
            console.log('URL TEMPLATE:', urlTemplate);
            console.log('MATKUL ID:', matkulId);

            if (!matkulId) {
                candidateList.innerHTML = 'Pilih mata kuliah untuk memuat kandidat dosen.';
                selectedDosenInput.value = '';
                return;
            }

            selectedDosenInput.value = '';
            candidateList.innerHTML = 'Memuat kandidat dosen...';

            try {
                const url = urlTemplate.replace('__MATKUL__', matkulId);

                console.log('FETCH URL:', url);

                const response = await fetch(url, {
                    method: 'GET',
                    credentials: 'same-origin',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                const raw = await response.text();

                console.log('STATUS:', response.status);
                console.log('RAW RESPONSE:', raw);

                let payload;

                try {
                    payload = JSON.parse(raw);
                } catch (e) {
                    candidateList.innerHTML = `
                    <div class="text-danger">
                        Response bukan JSON. Cek route/controller.
                    </div>
                `;
                    return;
                }

                if (!response.ok) {
                    candidateList.innerHTML = `
                    <div class="text-danger">
                        ${payload.message ?? 'Gagal memuat kandidat dosen.'}
                    </div>
                `;
                    return;
                }

                const candidates = Array.isArray(payload) ? payload : (payload.data ?? []);

                if (!candidates || candidates.length === 0) {
                    candidateList.innerHTML = `
                    <div class="text-muted">
                        Tidak ada kandidat dosen yang memenuhi syarat keahlian dan batas SKS.
                    </div>
                `;
                    return;
                }

                const radioName = `candidate_radio_temp_${distribusiId}`;

                candidateList.innerHTML = candidates.map(item => {
                    const badgeProdi = item.is_home ?
                        '<span class="badge bg-success-subtle text-success border">Prodi sendiri</span>' :
                        `<span class="badge bg-warning-subtle text-warning border">Dari ${item.prodi_nama}</span>`;

                    return `
                    <label class="d-block border rounded-3 p-3 mb-2 bg-white candidate-item" style="cursor:pointer;">
                        <div class="form-check">
                            <input class="form-check-input candidate-radio"
                                type="radio"
                                name="${radioName}"
                                value="${item.id}">

                            <span class="form-check-label">
                                <div class="fw-bold">${item.nama}</div>
                                <div class="small text-muted mb-1">${item.prodi_nama}</div>

                                <div class="d-flex gap-2 flex-wrap mb-1">
                                    ${badgeProdi}
                                </div>

                                <div class="small">
                                    SKS sekarang: <b>${item.sks_sekarang}</b> |
                                    Setelah dipilih: <b>${item.sks_setelah}</b> |
                                    Beban: <b>${item.sks_min} - ${item.sks_maks}</b>
                                </div>
                            </span>
                        </div>
                    </label>
                `;
                }).join('');

                candidateList.querySelectorAll('.candidate-radio').forEach(radio => {
                    radio.addEventListener('change', function() {
                        selectedDosenInput.value = this.value;
                    });
                });

            } catch (error) {
                console.error('ERROR AJAX:', error);

                candidateList.innerHTML = `
                <div class="text-danger">
                    Terjadi kesalahan saat memuat kandidat dosen.
                </div>
            `;
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.modal').forEach(modal => {
                modal.addEventListener('shown.bs.modal', function() {
                    const select = modal.querySelector('.edit-matkul-select');

                    if (select && select.value) {
                        loadKandidatDosenEdit(select);
                    }
                });
            });
        });
    </script>
