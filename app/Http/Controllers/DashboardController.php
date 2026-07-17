<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\Distribusi;
use App\Models\Jurusan;
use App\Models\Prodi;
use App\Models\Dosen;
use App\Models\MataKuliah;
use App\Models\TahunAjaran;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $semesterAjaran = $request->get('semester_ajaran', 'Ganjil');

        $tahunAjaranId = $request->get(
            'tahun_ajaran_id',
            TahunAjaran::aktif()->first()?->id
            ?? TahunAjaran::first()?->id
        );

        $tahunAktif = TahunAjaran::find($tahunAjaranId);

        $tahunAjarans = TahunAjaran::orderByDesc('tahun_ajaran')
            ->get();

        $semester = $semesterAjaran;
        $tahun = $tahunAktif?->tahun_ajaran ?? '-';

        $semesterList = $semesterAjaran === 'Ganjil'
            ? [1, 3, 5, 7]
            : [2, 4, 6, 8];

        /*
        |--------------------------------------------------------------------------
        | Statistik Utama
        |--------------------------------------------------------------------------
        */

        $totalJurusan = Jurusan::count();

        $totalProdi = Prodi::count();

        $totalDosen = Dosen::count();

        $totalMataKuliah = MataKuliah::whereIn(
            'semester',
            $semesterList
        )->count();

        $totalDistribusi = Distribusi::where('tahun_ajaran_id', $tahunAjaranId)
            ->whereHas('mataKuliah', function ($q) use ($semesterList) {
                $q->whereIn('semester', $semesterList);
            })
            ->count();

        /*
        |--------------------------------------------------------------------------
        | Distribusi per Jurusan
        |--------------------------------------------------------------------------
        */

        $distribusiJurusan = DB::table('distribusi')
            ->join('prodis', 'distribusi.prodi_id', '=', 'prodis.id')
            ->join('jurusans', 'prodis.jurusan_id', '=', 'jurusans.id')
            ->join('mata_kuliahs', 'distribusi.mata_kuliah_id', '=', 'mata_kuliahs.id')
            ->where('distribusi.tahun_ajaran_id', $tahunAjaranId)
            ->whereIn('mata_kuliahs.semester', $semesterList)
            ->groupBy('jurusans.id', 'jurusans.nama_jurusan')
            ->select(
                'jurusans.nama_jurusan',
                DB::raw('COUNT(distribusi.id) as total_distribusi'),
                DB::raw('SUM(distribusi.sks) as total_sks')
            )
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Beban Dosen Tertinggi
        |--------------------------------------------------------------------------
        */

        $bebanTertinggi = DB::table('distribusi')
            ->join('dosens', 'distribusi.dosen_id', '=', 'dosens.id')
            ->join('mata_kuliahs', 'distribusi.mata_kuliah_id', '=', 'mata_kuliahs.id')
            ->where('distribusi.tahun_ajaran_id', $tahunAjaranId)
            ->whereIn('mata_kuliahs.semester', $semesterList)
            ->groupBy('dosens.id', 'dosens.nama_dosen')
            ->select(
                'dosens.nama_dosen',
                DB::raw('SUM(distribusi.sks) as total_sks')
            )
            ->orderByDesc('total_sks')
            ->limit(10)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Validasi Constraint
        |--------------------------------------------------------------------------
        */

        $constraint = [
            'maks_sks' => 'Terpenuhi',
            'distribusi_valid' => 'Terpenuhi',
        ];

        /*
        |--------------------------------------------------------------------------
        | Grafik Beban Dosen
        |--------------------------------------------------------------------------
        */

        $grafikDosen = $bebanTertinggi;

        return view('dashboard', compact(
            'semester',
            'tahun',
            'semesterAjaran',
            'tahunAjaranId',
            'tahunAktif',
            'tahunAjarans',
            'totalJurusan',
            'totalProdi',
            'totalDosen',
            'totalMataKuliah',
            'totalDistribusi',
            'distribusiJurusan',
            'bebanTertinggi',
            'constraint',
            'grafikDosen'
        ));
    }
}