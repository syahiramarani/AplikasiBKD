<?php

namespace App\Http\Controllers;

use App\Models\BebanDosen;
use Illuminate\Http\Request;

class BebanDosenController extends Controller
{
    public function index()
    {
        $bebans = BebanDosen::all();

        return view('beban_dosen.index', compact('bebans'));
    }

    public function store(Request $request)
    {
        BebanDosen::create($request->all());

        return redirect()->back()
            ->with('success', 'Data berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $beban = BebanDosen::findOrFail($id);

        $beban->update($request->all());

        return redirect()->back()
            ->with('success', 'Data berhasil diupdate');
    }

    public function destroy($id)
    {
        BebanDosen::findOrFail($id)->delete();

        return redirect()->back()
            ->with('success', 'Data berhasil dihapus');
    }
}