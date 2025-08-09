<?php

namespace App\Http\Controllers;

use App\Models\Balita;
use App\Models\OrangTua;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BalitaController extends Controller
{
   
    //Menampilkan View Index
    public function index()
    {
        $user = Auth::user();
    
        if ($user->role === 'ortu') {
            // Ambil data balita berdasarkan user_id dari user ortu
            $balita = Balita::where('user_id', $user->id)
                            ->orderBy('created_at', 'ASC')
                            ->paginate(10);
        } else {
            // Untuk selain ortu, tampilkan semua balita
            $balita = Balita::orderBy('created_at', 'ASC')
                            ->paginate(10);
        }
    
        return view('balita.index', compact('balita'));
    }
    

  
    //Menampilkan View Create
    public function create()
    {
        $orangTua = OrangTua::all();
        return view('balita.create',compact('orangTua'));
    }

 
    //Melakukan Eksekusi Penyimpanan Ke Database
    public function store(Request $request)
    {
        $request->validate([
            'nik_anak' => 'nullable|string|max:50',
            'nama_balita' => 'required',
            'tpt_lahir' => 'required',
            'tgl_lahir' => 'required|date_format:Y-m-d',
            'orang_tua_id' => 'required|string|max:255',
            'alamat' => 'nullable|string',
            'ket' => 'nullable|string',
            'jenis_kelamin' => 'required',
        ]);
    
        Balita::create($request->all());
    
        return redirect('/balita')->with('status', 'Data Anak berhasil ditambahkan!');
    }

    
    public function show($id)
    {
        //
    }


    public function edit($id)
    {   
        $balita = Balita::findOrFail($id);
        $orangTua = OrangTua::all();
        return view('balita.edit',compact('orangTua','balita'));
    }

 
    public function update(Request $request, $id)
    {
        $request->validate([
            'nik_anak' => 'nullable|string|max:50',
            'nama_balita' => 'required',
            'tpt_lahir' => 'required',
            'tgl_lahir' => 'required|date_format:Y-m-d',
            'orang_tua_id' => 'required|string|max:255',
            'alamat' => 'nullable|string',
            'ket' => 'nullable|string',
            'jenis_kelamin' => 'required',
        ]);
    
        Balita::where('id', $id)
            ->update([
                'nama_balita' => $request->nama_balita,
                'tpt_lahir' => $request->tpt_lahir,
                'tgl_lahir' => $request->tgl_lahir,
                'orang_tua_id' => $request->orang_tua_id,
                'alamat' => $request->alamat,
                'nik_anak' => $request->nik_anak,
                'ket' => $request->ket,
                'jenis_kelamin' => $request->jenis_kelamin,
            ]);
    
        return redirect('/balita')->with('status', 'Data Anak berhasil diupdate!');
    }


    public function destroy($id)
    {
        Balita::destroy($id);
        return redirect('/balita')->with('status','Data Anak berhasil dihapus!');
    }
}
