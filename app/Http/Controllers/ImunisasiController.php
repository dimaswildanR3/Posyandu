<?php
namespace App\Http\Controllers;

use App\Models\Imunisasi;
use App\Models\Balita;
use App\Models\OrangTua;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ImunisasiController extends Controller
{
    public function index()
    {
        // Ambil user yang sedang login
        $user = Auth::user();

        // Cek apakah user adalah orang tua
        $orangTua = OrangTua::where('user_id', $user->id)->first();

        if ($user->role === 'ortu') {
            // Ambil semua id balita milik orang tua
            $balita_ids = Balita::where('orang_tua_id', $orangTua->id)->pluck('id');

            // Ambil imunisasi hanya untuk anak-anaknya
            $imunisasis = Imunisasi::with('balita')
                ->whereIn('balita_id', $balita_ids)
                ->orderBy('tanggal_imunisasi', 'desc')
                ->paginate(10);

            // Juga ambil data balita-nya untuk dropdown dsb
            $balitas = Balita::where('orang_tua_id', $orangTua->id)->get();
        } else {
            // Kalau bukan ortu (misal admin), tampilkan semua
            $imunisasis = Imunisasi::with('balita')->orderBy('tanggal_imunisasi', 'desc')->paginate(10);
            $balitas = Balita::all();
        }

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
            'vitamin' => 'nullable|string', // jika sudah ada di form
        ]);

        // Ambil data balita berdasarkan balita_id
        $balita = Balita::findOrFail($request->balita_id);

        // Hitung umur (bulan) = tanggal imunisasi - tgl_lahir balita
        $tglLahir = Carbon::parse($balita->tgl_lahir);
        $tglImunisasi = Carbon::parse($request->tanggal_imunisasi);
        $umur = $tglLahir->diffInMonths($tglImunisasi);

        // Simpan data dengan menambahkan umur
        Imunisasi::create(array_merge($request->all(), [
            'umur' => $umur,
        ]));

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
            'vitamin' => 'nullable|string', // jika ada
        ]);

        $imunisasi = Imunisasi::findOrFail($id);

        $balita = Balita::findOrFail($request->balita_id);

        $tglLahir = Carbon::parse($balita->tgl_lahir);
        $tglImunisasi = Carbon::parse($request->tanggal_imunisasi);
        $umur = $tglLahir->diffInMonths($tglImunisasi);

        $imunisasi->update(array_merge($request->all(), [
            'umur' => $umur,
        ]));
        // var_dump($request);
        // die;
        return redirect()->route('imunisasi.index')->with('status', 'Data imunisasi berhasil diperbarui!');
    }

    public function destroy($id)
    {
        Imunisasi::destroy($id);
        return redirect()->route('imunisasi.index')->with('status', 'Data imunisasi berhasil dihapus!');
    }
}
