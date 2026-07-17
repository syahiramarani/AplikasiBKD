<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DistribusiHistory;
use App\Models\TahunAjaran;
use App\Models\Dosen;

class DistribusiHistoryController extends Controller
{
    public function index(Request $request)
    {
        $tahun = TahunAjaran::orderBy('id', 'desc')->get();

        if (!$request->filled('tahun_ajaran_id')) {
            return view('rekap-distribusi.index', compact('tahun'));
        }

        $q = DistribusiHistory::query()
            ->where('tahun_ajaran_id', $request->tahun_ajaran_id);

        // sangat disarankan pakai semester juga
        if ($request->filled('semester')) {
            $q->where('semester', $request->semester);
        }

        $batch = (clone $q)->orderByDesc('created_at')->value('batch_id');

        if (!$batch) {
            return view('rekap-distribusi.index', compact('tahun'))
                ->with('error', 'Belum ada history distribusi untuk Tahun Ajaran / Semester tersebut.');
        }

        return redirect()->route('rekap-distribusi.show', ['batch' => $batch]);
    }
    public function show($batch)
    {
        $rows = DistribusiHistory::with(['dosen', 'prodi', 'tahunAjaran'])
            ->where('batch_id', $batch)
            ->get();

        if ($rows->isEmpty())
            abort(404);

        $meta = [
            'batch_id' => $batch,
            'tahun_ajaran' => $rows->first()->tahunAjaran,
            'semester' => $rows->first()->semester,
        ];

        $rekap = $rows->groupBy('dosen_id')->map(function ($items) use ($batch) {
            $dosen = $items->first()->dosen;

            $per = [
                'TRKJ' => ['sks' => 0, 'jam' => 0],
                'TI' => ['sks' => 0, 'jam' => 0],
                'TRMM' => ['sks' => 0, 'jam' => 0],
                'LAIN' => ['sks' => 0, 'jam' => 0],
            ];

            foreach ($items as $it) {
                $kode = strtoupper($it->prodi->kode_prodi ?? '');
                $k = in_array($kode, ['TRKJ', 'TI', 'TRMM']) ? $kode : 'LAIN';
                $per[$k]['sks'] += (int) $it->sks;
                $per[$k]['jam'] += (int) $it->jam;
            }

            return [
                'batch_id' => $batch,
                'dosen_id' => $items->first()->dosen_id,
                'nama' => $dosen->nama ?? $dosen->name ?? '-',
                'nip' => $dosen->nip ?? '-',
                'per' => $per,
                'total_sks' => (int) $items->sum('sks'),
                'total_jam' => (int) $items->sum('jam'),
                'mk_count' => $items->unique('mata_kuliah_id')->count(),
            ];
        })->values();

        return view('rekap-distribusi.show', compact('meta', 'rekap'));
    }
    public function detailDosen($batch, $dosen)
    {
        $rows = DistribusiHistory::with(['dosen', 'prodi', 'tahunAjaran', 'mataKuliah', 'kelas'])
            ->where('batch_id', $batch)
            ->where('dosen_id', $dosen)
            ->orderBy('mata_kuliah_id')
            ->orderBy('kelas_id')
            ->get();

        if ($rows->isEmpty())
            abort(404);

        $meta = [
            'batch_id' => $batch,
            'dosen' => $rows->first()->dosen,
            'tahun_ajaran' => $rows->first()->tahunAjaran,
            'semester' => $rows->first()->semester,
            'total_sks' => (int) $rows->sum('sks'),
            'total_jam' => (int) $rows->sum('jam'),
        ];

        $detail = $rows->groupBy('mata_kuliah_id')->map(function ($items) {
            $mk = $items->first()->mataKuliah;

            return [
                'mk_nama' => $mk->nama_mk ?? $mk->nama ?? '-',
                'total_sks' => (int) $items->sum('sks'),
                'total_jam' => (int) $items->sum('jam'),
                'kelas' => $items->map(function ($it) {
                    return [
                        'kelas' => $it->kelas->nama_kelas ?? $it->kelas->nama ?? $it->kelas->kode ?? '-',
                        'prodi' => ($it->prodi->kode_prodi ?? '-') . ' - ' . ($it->prodi->nama_prodi ?? '-'),
                        'sks' => (int) $it->sks,
                        'jam' => (int) $it->jam,
                    ];
                })->values(),
            ];
        })->values();

        return view('rekap-distribusi.detail-dosen', compact('meta', 'detail'));
    }
    private function kategoriProdi($namaProdi)
    {
        $nama = strtoupper($namaProdi);

        if (str_contains($nama, 'TRKJ')) {
            return 'TRKJ';
        }

        if (str_contains($nama, 'TRMM')) {
            return 'TRMM';
        }

        if (
            str_contains($nama, 'TI') ||
            str_contains($nama, 'TEKNIK INFORMATIKA') ||
            str_contains($nama, 'INFORMATIKA')
        ) {
            return 'TI';
        }

        return 'LAIN';
    }

    private function keteranganBeban($totalSks)
    {
        if ($totalSks < 4) {
            return 'Kurang Beban';
        }

        if ($totalSks <= 16) {
            return 'Sesuai Beban';
        }

        return 'Melebihi Beban';
    }
}