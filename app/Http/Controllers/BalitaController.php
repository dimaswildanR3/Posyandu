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
    // Ambil user login
    $user = Auth::user();

    // Cek jika role-nya adalah ortu (ganti sesuai field kamu)
    if ($user->role === 'ortu') {
        // Ambil data orang tua berdasarkan user_id
        $orangtua = OrangTua::where('user_id', $user->id)->first();

        // Pastikan data orangtua ditemukan
        if ($orangtua) {
            // Ambil data balita berdasarkan orang_tua_id
            $balita = Balita::where('orang_tua_id', $orangtua->id)
                        ->orderBy('created_at', 'ASC')
                        ->with('orangtua')
                        ->paginate(10);
        } else {
            // Jika tidak ditemukan, tampilkan kosong
            $balita = collect([]);
        }
    } else {
        // Jika bukan ortu, tampilkan semua data
        $balita = Balita::orderBy('created_at','ASC')
                        ->with('orangtua')
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
            'nama_balita'=>'required',
            'tpt_lahir'=>'required',
            'tgl_lahir' => 'required|date_format:Y-m-d',
            'orang_tua_id'=>'required',
            'ket'=>'nullable|string',
            'jenis_kelamin'=>'required',
        ]);         
        Balita::create($request->all());
        return redirect('/balita')->with('status','Data Anak berhasil ditambahkan!');

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
        'nama_balita'=>'required',
        'tpt_lahir'=>'required',
        'tgl_lahir' => 'required|date_format:Y-m-d',
        'orang_tua_id'=>'required',
        'ket'=>'nullable|string',
        'jenis_kelamin'=>'required',
        ]);
        Balita::where('id',$id)
                ->update([
                    'nama_balita'=>$request->nama_balita,
                    'tpt_lahir'=>$request->tpt_lahir,
                    'tgl_lahir'=>$request->tgl_lahir,
                    'orang_tua_id'=>$request->orang_tua_id,
                    'nik_anak'=>$request->nik_anak,
        
                    'ket'=>$request->ket,
                    'jenis_kelamin'=>$request->jenis_kelamin,
                ]);
        return redirect('/balita')->with('status','Data Anak berhasil diupdate!');
    }


    public function destroy($id)
    {
        Balita::destroy($id);
        return redirect('/balita')->with('status','Data Anak berhasil dihapus!');
    }
}
