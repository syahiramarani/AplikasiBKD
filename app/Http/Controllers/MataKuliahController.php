<?php

namespace App\Http\Controllers;

use App\Models\MataKuliah;
use App\Models\Prodi;
use App\Models\Bidang;
use App\Imports\MataKuliahImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;

class MataKuliahController extends Controller
{
    // Tampilkan daftar mata kuliah dengan filter.
    public function index(Request $request)
    {
        $matakuliahs = MataKuliah::all();
        $prodis = Prodi::all();
        $bidangs = Bidang::all(); // 🔥 INI YANG KURANG

        $semesters = MataKuliah::select('semester')->distinct()->pluck('semester');

        return view('matakuliah.index', compact(
            'matakuliahs',
            'prodis',
            'bidangs',   // 🔥 WAJIB DITAMBAH
            'semesters'
        ));
    }
    //  Import data mata kuliah dari file Excel.

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls'
        ]);

        try {
            $import = new MataKuliahImport();
            Excel::import($import, $request->file('file'));

            return redirect()->back()->with('success', 'Data berhasil diimport!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal import: ' . $e->getMessage());
        }
    }
    public function create()
    {
        $prodis = Prodi::all();
        $bidangs = Bidang::all();

        return view('matakuliah.create', compact('prodis', 'bidangs'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'prodi_id' => 'required',
            'kode_mk' => 'required',
            'nama_mk' => 'required',
            'sks' => 'required|integer',
            'jam' => 'required|integer',
            'semester' => 'required|integer',
            'bidang_id' => 'required',
        ]);

        MataKuliah::create($request->all());

        return redirect()->back()->with('success', 'Mata kuliah berhasil ditambahkan');
    }
    public function update(Request $request, $id)
    {
        $mk = MataKuliah::findOrFail($id);

        $mk->update([
            'prodi_id' => $request->prodi_id,
            'kode_mk' => $request->kode_mk,
            'nama_mk' => $request->nama_mk,
            'sks' => $request->sks,
            'jam' => $request->jam,
            'semester' => $request->semester,
            'bidang_id' => $request->bidang_id,
        ]);

        return redirect()->back()->with('success', 'Data berhasil diupdate');
    }
    public function destroy($id)
    {
        MataKuliah::findOrFail($id)->delete();

        return redirect()->back()->with('success', 'Data berhasil dihapus');
    }
}