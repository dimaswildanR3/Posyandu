<?php
namespace App\Http\Controllers;

use App\Models\Imunisasi;
use App\Models\Balita;
use Illuminate\Http\Request;

class ImunisasiController extends Controller
{
    public function index()
    {
        $imunisasis = Imunisasi::with('balita')->orderBy('tanggal_imunisasi', 'desc')->paginate(10);
        $balitas = Balita::all();
        return view('imunisasi.index', compact('imunisasis', 'balitas'));
    }
    

    public function create()
    {
        $balitas = Balita::all();
        return view('imunisasi.create', compact('balitas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'balita_id' => 'required|exists:balitas,id',
            'jenis_imunisasi' => 'required|string',
            'tanggal_imunisasi' => 'required|date',
            'keterangan' => 'nullable|string',
        ]);

        Imunisasi::create($request->all());
        return redirect()->route('imunisasi.index')->with('status', 'Data imunisasi berhasil ditambahkan!');
    }

    public function show($id)
    {
        $imunisasi = Imunisasi::with('balita')->findOrFail($id);
        return view('imunisasi.show', compact('imunisasi'));
    }

    public function edit($id)
    {
        $imunisasi = Imunisasi::findOrFail($id);
        $balitas = Balita::all();
        return view('imunisasi.edit', compact('imunisasi', 'balitas'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'balita_id' => 'required|exists:balitas,id',
            'jenis_imunisasi' => 'required|string',
            'tanggal_imunisasi' => 'required|date',
            'keterangan' => 'nullable|string',
        ]);

        $imunisasi = Imunisasi::findOrFail($id);
        $imunisasi->update($request->all());

        return redirect()->route('imunisasi.index')->with('status', 'Data imunisasi berhasil diperbarui!');
    }

    public function destroy($id)
    {
        Imunisasi::destroy($id);
        return redirect()->route('imunisasi.index')->with('status', 'Data imunisasi berhasil dihapus!');
    }
}
