<?php

namespace App\Http\Controllers;


use App\Models\Keuangan;
use App\Models\OrangTua;
use Carbon\Carbon as CarbonCarbon;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;


class OrangTuaController extends Controller{

    //Menampilkan View Index
    public function index()
    {
        $orangTua = OrangTua::orderBy('created_at','ASC')->paginate(10);
        return view('orang_tua.index', compact('orangTua'));
    }

      //Menampilkan View Create
      public function create()
      {
          $orangTua = OrangTua::all();
          return view('orang_tua.create',compact('orangTua'));
      }
  
   
      //Melakukan Eksekusi Penyimpanan Ke Database
      public function store(Request $request)
      {
          $request->validate([
              'nama' => 'required',
              'pendidikan' => 'required',
              'pekerjaan' => 'required',
              'alamat' => 'required',
              // 'ket' => 'required',
          ]);
      
          $data = $request->all();
      
          // Format ulang tanggal ke Y-m-d sebelum disimpan
          $data['tanggal_lahir_ibu'] = $request->tanggal_lahir_ibu 
              ? Carbon::createFromFormat('d-m-Y', $request->tanggal_lahir_ibu)->format('Y-m-d') 
              : null;
      
          $data['tanggal_lahir_suami'] = $request->tanggal_lahir_suami 
              ? Carbon::createFromFormat('d-m-Y', $request->tanggal_lahir_suami)->format('Y-m-d') 
              : null;
      
          OrangTua::create($data);
      
          return redirect('/orangtua')->with('status', 'Data Ibu berhasil ditambahkan!');
      }
  
      
      public function show($id)
      {
          //
      }
  
  
      public function edit($id)
      {   
          $orangTua = OrangTua::findOrFail($id);
          return view('orang_tua.edit',compact('orangTua'));
      }
  
   
      public function update(Request $request, $id)
{
    $request->validate([
        'nama' => 'required',
        'pendidikan' => 'required',
        'pekerjaan' => 'required',
        'alamat' => 'required',
        // 'ket' => 'required', // opsional
    ]);

    // Konversi tanggal ke format Y-m-d
    $tanggal_lahir_ibu = $request->tanggal_lahir_ibu 
        ? Carbon::createFromFormat('d-m-Y', $request->tanggal_lahir_ibu)->format('Y-m-d') 
        : null;

    $tanggal_lahir_suami = $request->tanggal_lahir_suami 
        ? Carbon::createFromFormat('d-m-Y', $request->tanggal_lahir_suami)->format('Y-m-d') 
        : null;

    OrangTua::where('id', $id)->update([
        'tempat_lahir_ibu' => $request->tempat_lahir_ibu,
        'tanggal_lahir_ibu' => $tanggal_lahir_ibu,
        'pendidikan' => $request->pendidikan,
        'pekerjaan' => $request->pekerjaan,
        'nama_suami' => $request->nama_suami,
        'tempat_lahir_suami' => $request->tempat_lahir_suami,
        'tanggal_lahir_suami' => $tanggal_lahir_suami,
        'pendidikan_suami' => $request->pendidikan_suami,
        'pekerjaan_suami' => $request->pekerjaan_suami,
        'alamat' => $request->alamat,
        'kota' => $request->kota,
        'kecamatan' => $request->kecamatan,
        'no_tlpn' => $request->no_tlpn,
        'ket' => $request->ket,
    ]);

    return redirect('/orangtua')->with('status', 'Data Ibu berhasil diupdate!');
}
  
  
      public function destroy($id)
      {
            OrangTua::destroy($id);
            return redirect('/orangtua')->with('status','Data Ibu berhasil dihapus!');
      }
}