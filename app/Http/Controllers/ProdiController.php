<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Prodi;
use App\Models\Jurusan;

class ProdiController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        $query = Prodi::with('jurusan');

        if (($user->role ?? null) === 'kajur') {
            // WAJIB: Kunci ke jurusan Kajur
            $query->where('jurusan_id', $user->jurusan_id);

            // Jurusan list dibatasi (opsional, tapi bagus)
            $jurusans = Jurusan::where('id', $user->jurusan_id)->get();
        } else {
            $jurusans = Jurusan::all();

            if ($request->filled('jurusan')) {
                $query->where('jurusan_id', $request->jurusan);
            }
        }

        $prodis = $query->get();

        return view('prodi.index', compact('prodis', 'jurusans'));
    }



    public function store(Request $request)
    {
        $request->validate([
            'jurusan_id' => 'required',
            'kode_prodi' => 'required',
            'nama_prodi' => 'required',

        ]);

        Prodi::create([
            'jurusan_id' => $request->jurusan_id,
            'kode_prodi' => $request->kode_prodi,
            'nama_prodi' => $request->nama_prodi,

        ]);

        return redirect()
            ->route('prodi.index')
            ->with('success', 'Data berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $request->validate([

            'jurusan_id' => 'required',
            'kode_prodi' => 'required',
            'nama_prodi' => 'required',
        ]);

        $prodi = Prodi::findOrFail($id);
        $prodi->update([
            'jurusan_id' => $request->jurusan_id,
            'kode_prodi' => $request->kode_prodi,
            'nama_prodi' => $request->nama_prodi,

        ]);

        return redirect()
            ->route('prodi.index')
            ->with('success', 'Data berhasil diupdate');
    }
    public function destroy($id)
    {
        $prodi = Prodi::findOrFail($id);
        $prodi->delete();
        return redirect()
            ->route('prodi.index')
            ->with('success', 'Data berhasil dihapus');
    }
}