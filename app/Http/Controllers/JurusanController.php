<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Jurusan;

class JurusanController extends Controller
{

    public function index(Request $request)
    {
        // Ambil semua jurusan untuk dropdown
        $jurusans = Jurusan::all();

        // Query data jurusan
        $query = Jurusan::query();

        // Filter jurusan
        if ($request->jurusan) {

            $query->where('id', $request->jurusan);
        }

        // Ambil data
        $data = $query->get();

        return view(
            'jurusan.index',
            compact('jurusans', 'data')
        );
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_jurusan' => 'required',
            'nama_jurusan' => 'required',
        ]);

        Jurusan::create([
            'kode_jurusan' => $request->kode_jurusan,
            'nama_jurusan' => $request->nama_jurusan,
        ]);

        return redirect()->route('jurusan.index')
            ->with('success', 'Jurusan berhasil ditambahkan');
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'kode_jurusan' => 'required',
            'nama_jurusan' => 'required',
        ]);

        $jurusan = Jurusan::findOrFail($id);

        $jurusan->update([
            'kode_jurusan' => $request->kode_jurusan,
            'nama_jurusan' => $request->nama_jurusan,
        ]);

        return redirect()->route('jurusan.index')
            ->with('success', 'Jurusan berhasil diupdate');
    }
    public function destroy($id)
    {
        Jurusan::findOrFail($id)->delete();

        return redirect()->route('jurusan.index')
            ->with('success', 'Jurusan berhasil dihapus');
    }
}
