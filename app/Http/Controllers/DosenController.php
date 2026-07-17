<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Jurusan;
use App\Models\Prodi;
use App\Models\Dosen;
use App\Models\BebanDosen;
use App\Models\Bidang;
use App\Models\DosenBidang;
use App\Imports\DosenImport;
use Maatwebsite\Excel\Facades\Excel;

class DosenController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $query = Dosen::query()->with('prodi');

        if ($user->role === 'kajur') {
            $query->whereHas('prodi', function ($q) use ($user) {
                $q->where('jurusan_id', $user->jurusan_id);
            });
        }

        $dosens = $query->get();
        return view('dosen.index', compact('dosens'));
    }


    public function create()
    {
        $jurusans = Jurusan::all();

        $bebanDosens = BebanDosen::all();

        $bidangs = Bidang::orderBy('nama')->get();

        return view(
            'dosen.create',
            compact(
                'jurusans',
                'bebanDosens',
                'bidangs'
            )
        );
    }
    public function edit($id)
    {
        $dosen = Dosen::with('bidangs')->findOrFail($id);
        $jurusans = Jurusan::all();
        $bebanDosens = BebanDosen::all();
        $bidangs = Bidang::all();

        return view('dosen.edit', compact('dosen', 'jurusans', 'bebanDosens', 'bidangs'));
    }
    public function getProdi($id)
    {
        $prodi = Prodi::where('jurusan_id', $id)->get();
        return response()->json($prodi);
    }
    public function getBidang($jurusan_id)
    {
        $bidang = Bidang::where('jurusan_id', $jurusan_id)->get();

        return response()->json($bidang);
    }
    public function update(Request $request, $id)
    {
        $dosen = Dosen::findOrFail($id);

        $dosen->update([
            'nip' => $request->nip,
            'keahlian' => $request->keahlian,
            'nama_dosen' => $request->nama_dosen,
            'status' => $request->status,
            'kategori_mengajar' => $request->kategori_mengajar,
            'jurusan_id' => $request->jurusan_id,
            'beban_dosen_id' => $request->beban_dosen_id,
        ]);

        // PIVOT UPDATE
        $dosen->bidangs()->sync($request->bidang_ids ?? []);

        return redirect('/dosen')->with('success', 'Data dosen berhasil diupdate');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv'
        ]);

        Excel::import(
            new DosenImport,
            $request->file('file')
        );

        return redirect('/dosen')
            ->with('success', 'Data dosen berhasil diimport');
    }
    public function store(Request $request)
    {
        $dosen = Dosen::create([
            'nip' => $request->nip,
            'nama_dosen' => $request->nama_dosen,
            'jabatan' => $request->jabatan,
            'status' => $request->status,
            'jurusan_id' => $request->jurusan_id,
            'prodi_id' => $request->prodi_id,
            'beban_dosen_id' => $request->beban_dosen_id,
        ]);

        foreach ($request->bidang as $bidang_id) {
            DosenBidang::create([
                'dosen_id' => $dosen->id,
                'bidang_id' => $bidang_id
            ]);
        }
        return redirect('/dosen')->with('success', 'Data dosen berhasil ditambahkan');
    }
    public function show(Prodi $prodi)
    {
        $user = auth()->user();

        if ($user->role === 'kajur' && (int) $prodi->jurusan_id !== (int) $user->jurusan_id) {
            abort(403);
        }

        return view('prodi.show', compact('prodi'));
    }


}