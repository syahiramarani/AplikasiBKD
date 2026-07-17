<?php

namespace App\Http\Controllers;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;


use App\Models\Dosen;
use App\Models\Distribusi;
use App\Models\DistribusiHistory;
use App\Models\Jurusan;
use App\Models\Kelas;
use App\Models\MataKuliah;
use App\Models\Prodi;
use App\Models\TahunAjaran;
use App\Services\GeneticAlgorithm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DistribusiController extends Controller
{
    private function resolveBebanDosen(Dosen $dosen): array
    {
        $beban = $dosen->bebanDosen;

        return [
            'has_beban' => !is_null($beban),
            'min_sks' => (int) ($beban->min_sks ?? 0),
            'max_sks' => (int) ($beban->max_sks ?? 0),
        ];
    }

    public function index(Request $request)
    {
        $prodiId = $request->get('prodi_id', Prodi::first()?->id);
        $semester = (int) $request->get('semester', 1);

        $jurusans = Jurusan::with('prodis')->get();
        $prodis = Prodi::orderBy('kode_prodi')->get();
        $dosens = Dosen::with(['bidangs', 'prodi', 'bebanDosen'])->get();

        $mataKuliahs = MataKuliah::with('bidang')
            ->where('prodi_id', $prodiId)
            ->where('semester', $semester)
            ->get();

        $distribusi = Distribusi::with([
            'dosen.prodi',
            'dosen.bebanDosen',
            'mataKuliah',
            'kelas.angkatan',
            'prodi'
        ])
            ->where('prodi_id', $prodiId)
            ->get();

        $sksPerDosen = $distribusi
            ->groupBy('dosen_id')
            ->map(fn($items) => (int) $items->sum('sks'))
            ->toArray();

        $totalDosen = Dosen::count();
        $totalMatkul = $mataKuliahs->count();
        $totalLintasProdi = $distribusi->where('lintas_prodi', true)->count();
        $totalDitolak = session('total_ditolak', 0);

        $tahun = TahunAjaran::all();
        $matkulEditList = collect();
        $semesterDistribusi = (int) ($request->semester ?? 1);

        if ($request->filled('prodi_id')) {
            $matkulEditList = MataKuliah::where('prodi_id', $request->prodi_id)
                ->where('semester', $semesterDistribusi)
                ->get();
        }
        return view('distribusi.index', compact(
            'jurusans',
            'prodis',
            'prodiId',
            'dosens',
            'mataKuliahs',
            'distribusi',
            'semester',
            'totalDosen',
            'totalMatkul',
            'totalLintasProdi',
            'totalDitolak',
            'sksPerDosen',
            'tahun',
            'matkulEditList',
            'semesterDistribusi'
        ));
    }

    public function dosenKandidat(Request $request, MataKuliah $mataKuliah)
    {
        $tahunAjaranId = $request->get('tahun_ajaran_id');

        $dosens = Dosen::with(['bidangs', 'prodi', 'bebanDosen'])
            ->get()
            ->filter(fn($d) => $d->cocokDenganBidang($mataKuliah->bidang_id));

        $hasil = $dosens
            ->map(function ($d) use ($mataKuliah, $tahunAjaranId) {
                $cur = (int) $d->totalSks($tahunAjaranId);
                $beban = $this->resolveBebanDosen($d);
                $next = $cur + (int) $mataKuliah->sks;

                return [
                    'id' => $d->id,
                    'nama' => $d->nama_dosen,
                    'prodi_id' => $d->prodi_id,
                    'is_home' => (int) $d->prodi_id === (int) $mataKuliah->prodi_id,
                    'sks_sekarang' => $cur,
                    'sks_setelah' => $next,
                    'sks_min' => $beban['min_sks'],
                    'sks_maks' => $beban['max_sks'],
                    'beban_diatur' => $beban['has_beban'],
                    'akan_melebihi' => $beban['max_sks'] > 0 ? $next > $beban['max_sks'] : true,
                    'status_beban' => !$beban['has_beban']
                        ? 'belum_diatur'
                        : ($next > $beban['max_sks']
                            ? 'overload'
                            : ($next < $beban['min_sks'] ? 'underload' : 'normal')),
                ];
            })
            ->sortBy(fn($d) => $d['is_home'] ? 0 : 1)
            ->values();

        return response()->json($hasil);
    }

    public function proses(Request $request)
    {
        $request->validate([
            'prodi_id' => 'required|exists:prodis,id',
            'semester' => 'required|integer|min:1|max:8',
        ]);

        $prodiId = (int) $request->prodi_id;
        $semester = (int) $request->semester;

        $prodiAktif = Prodi::findOrFail($prodiId);

        $prodiList = Prodi::where('jurusan_id', $prodiAktif->jurusan_id)
            ->pluck('id');

        Distribusi::where('prodi_id', $prodiId)
            ->where('sumber', 'ag')
            ->delete();

        $semuaDosen = Dosen::with(['bidangs', 'bebanDosen'])
            ->whereIn('prodi_id', $prodiList)
            ->get();

        if ($semuaDosen->isEmpty()) {
            return back()->with('error', 'Dosen tidak ditemukan');
        }

        $dosenTanpaBeban = $semuaDosen->filter(function ($dosen) {
            $beban = $this->resolveBebanDosen($dosen);
            return !$beban['has_beban'] || $beban['max_sks'] <= 0;
        });

        if ($dosenTanpaBeban->isNotEmpty()) {
            $namaDosen = $dosenTanpaBeban
                ->pluck('nama_dosen')
                ->filter()
                ->implode(', ');

            return back()->with(
                'error',
                'Beban dosen belum diatur atau max_sks masih kosong untuk: ' . $namaDosen
            );
        }

        $dosens = $semuaDosen
            ->map(function ($dosen) {
                $beban = $this->resolveBebanDosen($dosen);

                return [
                    'id' => (int) $dosen->id,
                    'prodi_id' => (int) $dosen->prodi_id,
                    'sks_aktif' => (int) $dosen->totalSksGlobal(),
                    'min_sks' => $beban['min_sks'],
                    'max_sks' => $beban['max_sks'],
                    'bidang_ids' => $dosen->bidangs
                        ->pluck('id')
                        ->map(fn($id) => (int) $id)
                        ->values()
                        ->toArray(),
                ];
            })
            ->toArray();

        $mataKuliahs = MataKuliah::with('bidang')
            ->where('prodi_id', $prodiId)
            ->where('semester', $semester)
            ->get()
            ->map(fn($mk) => [
                'id' => (int) $mk->id,
                'sks' => (int) $mk->sks,
                'jam' => (int) $mk->jam,
                'prodi_id' => (int) $mk->prodi_id,
                'bidang_id' => (int) $mk->bidang_id,
            ])
            ->toArray();

        if (empty($mataKuliahs)) {
            return back()->with('error', 'Matkul tidak ditemukan');
        }

        $kelasList = Kelas::with('angkatan')
            ->whereHas('angkatan', function ($q) use ($prodiList) {
                $q->whereIn('prodi_id', $prodiList);
            })
            ->get()
            ->map(function ($kelas) {
                return [
                    'id' => (int) $kelas->id,
                    'prodi_id' => (int) ($kelas->angkatan->prodi_id ?? 0),
                ];
            })
            ->filter(fn($kelas) => $kelas['prodi_id'] > 0)
            ->values()
            ->toArray();

        if (empty($kelasList)) {
            return back()->with('error', 'Kelas tidak ditemukan');
        }

        $ag = new GeneticAlgorithm($dosens, $mataKuliahs, $kelasList);

        $ag->setParameter(
            populasiSize: 100,
            maxGenerasi: 150,
            crossoverRate: 0.8,
            mutationRate: 0.05,
            targetFitness: 0.95,
            tournamentK: 3
        );

        Log::info('[GA] Controller memanggil run()');

        $hasil = $ag->run();

        Log::info('[GA] Controller menerima hasil', [
            'fitness' => $hasil['fitness'] ?? null,
            'generasi' => $hasil['generasi'] ?? null,
            'jumlah_gen' => is_array($hasil['kromosom'] ?? null) ? count($hasil['kromosom']) : null,
        ]);

        if (empty($hasil['kromosom'])) {
            return back()->with('error', 'Distribusi gagal dibuat. Tidak ditemukan kromosom valid.');
        }
        $tahunAjaranId = $request->tahun_ajaran_id;
        $batchId = (string) Str::uuid();

        DB::transaction(function () use ($hasil, $tahunAjaranId, $batchId) {
            foreach ($hasil['kromosom'] as $gen) {
                Distribusi::create([
                    'batch_id' => $batchId,
                    'dosen_id' => $gen['dosen_id'],
                    'prodi_id' => $gen['prodi_id'],
                    'tahun_ajaran_id' => $tahunAjaranId,
                    'mata_kuliah_id' => $gen['matkul_id'],
                    'kelas_id' => $gen['kelas_id'],
                    'sks' => $gen['sks'],
                    'jam' => $gen['jam'],
                    'sumber' => 'ag',
                ]);
            }
        });

        return back()
            ->with('success', 'Distribusi berhasil dijalankan')
            ->with('batch_id', $batchId);
    }

    public function simpan(Request $request)
    {
        $request->validate([
            'dosen_id' => 'required|exists:dosens,id',
            'mata_kuliah_id' => 'required|exists:mata_kuliahs,id',
            'kelas_id' => 'required|exists:kelas,id',
            'tahun_ajaran_id' => 'required|exists:tahun_ajarans,id',
        ]);

        $dosen = Dosen::with(['bidangs', 'prodi', 'bebanDosen'])
            ->findOrFail($request->dosen_id);

        $mk = MataKuliah::with(['bidang', 'prodi'])
            ->findOrFail($request->mata_kuliah_id);

        if (!$dosen->cocokDenganBidang($mk->bidang_id)) {
            return back()->with(
                'error',
                'Ditolak — bidang dosen tidak cocok dengan mata kuliah.'
            );
        }

        $beban = $this->resolveBebanDosen($dosen);

        if (!$beban['has_beban'] || $beban['max_sks'] <= 0) {
            return back()->with(
                'error',
                "Beban dosen {$dosen->nama_dosen} belum diatur."
            );
        }

        $curSks = (int) $dosen->totalSks($request->tahun_ajaran_id);
        $nextSks = $curSks + (int) $mk->sks;

        if ($nextSks > $beban['max_sks']) {
            session(['total_ditolak' => session('total_ditolak', 0) + 1]);

            return back()->with(
                'error',
                "Ditolak — total SKS {$dosen->nama_dosen} menjadi {$nextSks}, melebihi batas {$beban['max_sks']} SKS."
            );
        }

        Distribusi::create([
            'dosen_id' => $dosen->id,
            'prodi_id' => $mk->prodi_id,
            'tahun_ajaran_id' => $request->tahun_ajaran_id,
            'mata_kuliah_id' => $mk->id,
            'kelas_id' => $request->kelas_id,
            'sks' => $mk->sks,
            'jam' => $mk->jam,
            'sumber' => 'manual',
        ]);

        $lintas = (int) $dosen->prodi_id !== (int) $mk->prodi_id
            ? ' (lintas prodi)'
            : '';

        return back()->with(
            'success',
            "{$mk->nama_mk} berhasil didistribusikan ke {$dosen->nama_dosen}{$lintas}. Rentang beban dosen: {$beban['min_sks']} - {$beban['max_sks']} SKS."
        );
    }

    public function hapus(Distribusi $distribusi)
    {
        try {
            $distribusi->delete();
            return back()->with('success', 'Data distribusi berhasil dihapus.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus data distribusi: ' . $e->getMessage());
        }
    }

    public function kandidatEdit(Distribusi $distribusi, MataKuliah $mataKuliah)
    {
        $distribusi->load(['mataKuliah', 'dosen', 'prodi']);

        if (!$distribusi->mataKuliah) {
            return response()->json([
                'message' => 'Data mata kuliah distribusi tidak ditemukan.'
            ], 422);
        }

        if ((int) $mataKuliah->semester !== (int) $distribusi->mataKuliah->semester) {
            return response()->json([
                'message' => 'Mata kuliah yang dipilih harus dari semester yang sama.'
            ], 422);
        }

        $tahunAjaranId = $distribusi->tahun_ajaran_id;
        $prodiDistribusiId = $distribusi->prodi_id;

        // TARUH DI SINI
        $dosens = Dosen::with(['bidangs', 'prodi', 'bebanDosen'])
            ->whereHas('bidangs', function ($q) use ($mataKuliah) {
                $q->where('bidangs.id', $mataKuliah->bidang_id);
            })
            ->get();

        $hasil = $dosens->map(function ($d) use ($mataKuliah, $distribusi, $tahunAjaranId, $prodiDistribusiId) {
            $beban = $this->resolveBebanDosen($d);

            $curSks = (int) Distribusi::where('dosen_id', $d->id)
                ->where('tahun_ajaran_id', $tahunAjaranId)
                ->where('id', '!=', $distribusi->id)
                ->sum('sks');

            $nextSks = $curSks + (int) $mataKuliah->sks;

            $maxSks = (int) ($beban['max_sks'] ?? 0);
            $hasBeban = (bool) ($beban['has_beban'] ?? false);

            return [
                'id' => $d->id,
                'nama' => $d->nama_dosen,
                'prodi_id' => $d->prodi_id,
                'prodi_nama' => $d->prodi->nama_prodi ?? '-',
                'is_home' => (int) $d->prodi_id === (int) $prodiDistribusiId,
                'sks_sekarang' => $curSks,
                'sks_setelah' => $nextSks,
                'sks_min' => (int) ($beban['min_sks'] ?? 0),
                'sks_maks' => $maxSks,
                'beban_diatur' => $hasBeban,
                'akan_melebihi' => ($maxSks > 0) ? ($nextSks > $maxSks) : true,
            ];
        })
            ->filter(function ($item) {
                return $item['beban_diatur'] && !$item['akan_melebihi'];
            })
            ->sortBy(function ($item) {
                return ($item['is_home'] ? 0 : 1000) + $item['sks_setelah'];
            })
            ->values();

        return response()->json($hasil);
    }
    public function update(Request $request, Distribusi $distribusi)
    {
        $request->validate([
            'mata_kuliah_id' => ['required', 'exists:mata_kuliahs,id'],
            'dosen_id' => ['required', 'exists:dosens,id'],
        ]);

        $mataKuliah = MataKuliah::findOrFail($request->mata_kuliah_id);

        $dosen = Dosen::with(['bidangs', 'bebanDosen'])
            ->findOrFail($request->dosen_id);

        /*
        |--------------------------------------------------------------------------
        | Validasi bidang dosen
        |--------------------------------------------------------------------------
        | Dosen boleh lintas prodi, tetapi bidang harus sama dengan bidang mata kuliah.
        */
        $sesuaiBidang = $dosen->bidangs()
            ->where('bidangs.id', $mataKuliah->bidang_id)
            ->exists();

        if (!$sesuaiBidang) {
            return back()->with('error', 'Dosen tidak sesuai dengan bidang mata kuliah yang dipilih.');
        }

        /*
        |--------------------------------------------------------------------------
        | Validasi beban SKS
        |--------------------------------------------------------------------------
        | Distribusi yang sedang diedit tidak ikut dihitung.
        */
        $beban = $this->resolveBebanDosen($dosen);

        $hasBeban = (bool) ($beban['has_beban'] ?? false);
        $maxSks = (int) ($beban['max_sks'] ?? 0);

        if (!$hasBeban || $maxSks <= 0) {
            return back()->with('error', 'Beban SKS dosen belum diatur.');
        }

        $sksSekarang = (int) Distribusi::where('dosen_id', $dosen->id)
            ->where('tahun_ajaran_id', $distribusi->tahun_ajaran_id)
            ->where('id', '!=', $distribusi->id)
            ->sum('sks');

        $sksSetelah = $sksSekarang + (int) $mataKuliah->sks;

        if ($sksSetelah > $maxSks) {
            return back()->with('error', 'Dosen tidak dapat dipilih karena beban SKS akan melebihi batas maksimal.');
        }

        /*
        |--------------------------------------------------------------------------
        | Update distribusi
        |--------------------------------------------------------------------------
        */
        $distribusi->update([
            'mata_kuliah_id' => $mataKuliah->id,
            'dosen_id' => $dosen->id,
            'sks' => $mataKuliah->sks,
            'sumber' => 'manual',
        ]);

        return redirect()
            ->route('distribusi.index', [
                'prodi_id' => $distribusi->prodi_id,
                'semester' => $mataKuliah->semester,
            ])
            ->with('success', 'Distribusi berhasil diperbarui.');
    }
    public function destroy($id)
    {
        $data = Distribusi::findOrFail($id);
        $data->delete();

        return back()->with('success', 'Data berhasil dihapus');
    }

    public function rekap(Request $request)
    {
        $semester = $request->semester;

        $query = Distribusi::with([
            'dosen.prodi',
            'dosen.bebanDosen',
            'prodi',
            'mataKuliah',
        ]);

        if (!empty($semester)) {
            $query->whereHas('mataKuliah', function ($q) use ($semester) {
                $q->where('semester', $semester);
            });
        }

        $distribusi = $query
            ->orderBy('dosen_id')
            ->get();

        $rekap = $distribusi
            ->groupBy('dosen_id')
            ->map(function ($items) {
                $first = $items->first();
                $dosen = $first->dosen;

                $homebaseId = $dosen->prodi_id ?? null;
                $beban = $dosen->bebanDosen ?? null;
                $minSks = $beban->min_sks ?? 0;
                $maxSks = $beban->max_sks ?? 0;

                $row = [
                    'dosen_id' => $dosen->id ?? null,
                    'nama_dosen' => $dosen->nama_dosen ?? '-',
                    'nip' => $dosen->nip ?? '-',
                    'homebase' => $dosen->prodi->nama_prodi ?? '-',

                    'trkjsks' => 0,
                    'trkjjam' => 0,

                    'tisks' => 0,
                    'tijam' => 0,

                    'trmmsks' => 0,
                    'trmmjam' => 0,

                    'lainsks' => 0,
                    'lainjam' => 0,

                    'total_sks' => 0,
                    'total_jam' => 0,

                    'lintas_prodi' => false,
                    'jumlah_mk' => 0,
                    'jumlah_mk_lintas' => 0,
                    'sks_lintas' => 0,

                    'min_sks' => $minSks,
                    'max_sks' => $maxSks,
                    'status_beban' => 'Sesuai Beban',
                ];

                foreach ($items as $item) {
                    $sks = (float) ($item->sks ?? 0);
                    $jam = (float) ($item->jam ?? ($item->mataKuliah->jam ?? 0));
                    $namaProdi = strtoupper(trim($item->prodi->nama_prodi ?? ''));
                    $prodiIdDistribusi = $item->prodi_id;

                    $row['jumlah_mk']++;
                    $row['total_sks'] += $sks;
                    $row['total_jam'] += $jam;

                    $kolom = $this->mapProdiLaporan($namaProdi);

                    if ($kolom === 'TRKJ') {
                        $row['trkjsks'] += $sks;
                        $row['trkjjam'] += $jam;
                    } elseif ($kolom === 'TI') {
                        $row['tisks'] += $sks;
                        $row['tijam'] += $jam;
                    } elseif ($kolom === 'TRMM') {
                        $row['trmmsks'] += $sks;
                        $row['trmmjam'] += $jam;
                    } else {
                        $row['lainsks'] += $sks;
                        $row['lainjam'] += $jam;
                    }

                    if (!is_null($homebaseId) && (int) $homebaseId !== (int) $prodiIdDistribusi) {
                        $row['lintas_prodi'] = true;
                        $row['jumlah_mk_lintas']++;
                        $row['sks_lintas'] += $sks;
                    }
                }

                if ($maxSks > 0 && $row['total_sks'] > $maxSks) {
                    $row['status_beban'] = 'Lebih Beban';
                } elseif ($minSks > 0 && $row['total_sks'] < $minSks) {
                    $row['status_beban'] = 'Kurang Beban';
                }

                $keterangan = [];
                $keterangan[] = $row['lintas_prodi'] ? 'Lintas Prodi' : 'Sesuai Homebase';
                $keterangan[] = $row['status_beban'];
                $row['keterangan'] = implode(' | ', $keterangan);

                return $row;
            })
            ->sortBy('nama_dosen')
            ->values();

        return view('Distribusi.rekap', compact('rekap', 'semester'));
    }

    private function mapProdiLaporan(string $namaProdi): string
    {
        if (str_contains($namaProdi, 'TRKJ')) {
            return 'TRKJ';
        }

        if (
            $namaProdi === 'TI' ||
            str_contains($namaProdi, 'TEKNIK INFORMATIKA') ||
            str_contains($namaProdi, 'INFORMATIKA')
        ) {
            return 'TI';
        }

        if (str_contains($namaProdi, 'TRMM')) {
            return 'TRMM';
        }

        return 'LAIN';
    }
    public function simpanHistory(Request $request)
    {
        $request->validate([
            'batch_id' => 'required',
            'semester' => 'required',
        ]);

        $data = Distribusi::where('batch_id', $request->batch_id)->get();

        if ($data->isEmpty()) {
            return back()->with('error', 'Data distribusi untuk batch ini kosong');
        }

        foreach ($data as $item) {
            DistribusiHistory::create([
                'batch_id' => $request->batch_id,
                'tahun_ajaran_id' => $item->tahun_ajaran_id,
                'prodi_id' => $item->prodi_id,
                'semester' => $request->semester,
                'dosen_id' => $item->dosen_id,
                'mata_kuliah_id' => $item->mata_kuliah_id,
                'kelas_id' => $item->kelas_id,
                'sks' => $item->sks,
                'jam' => $item->jam,
                'sumber' => $item->sumber,
            ]);
        }

        return back()->with('success', 'History berhasil disimpan');
    }
}