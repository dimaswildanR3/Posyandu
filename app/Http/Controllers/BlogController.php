<?php

namespace App\Http\Controllers;

use App\Models\Jadwal;
use App\Models\Bidan;
use App\Models\Penimbangan;
use Illuminate\Http\Request;


class BlogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $jadwal = Jadwal::all();
        $bidans = Bidan::all();
     
    return view('blog.index', compact('jadwal', 'bidans'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'tanggal_kegiatan'=>'required',
            'bidan_id'=>'required|exists:bidans,id',
            'waktu' => 'required|date_format:H:i',
        ]);
    
        $bidan = \App\Models\Bidan::find($request->bidan_id);
        
        Jadwal::create([
            'user_id' => $request->user_id,
            'bidan_id' => $bidan->id,
            'nama_kegiatan' => $bidan->nama_lengkap,
            'tanggal_kegiatan' => $request->tanggal_kegiatan,
            'waktu' => $request->waktu,
            'waktu_akhir' => $request->waktu_akhir,

        ]);
    
        return redirect('/blog')->with('status','Data Jadwal Pelayanan berhasil ditambahkan!');
    }
    

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $jadwal = Jadwal::find($id);
        return view('blog.edit',compact('jadwal'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_kegiatan' =>'required',
            'tanggal_kegiatan' =>'required',
            'waktu' => 'required|date_format:H:i',
        ]);
        
        Jadwal::where('id',$id)
                ->update([
                    'nama_kegiatan'=>$request->nama_kegiatan,
                    'tanggal_kegiatan'=>$request->tanggal_kegiatan,
                    'waktu'=>$request->waktu,
                    'waktu_akhir' => $request->waktu_akhir,
                    
                ]);
        return redirect('/blog')->with('status','Data Jadwal Pelayanan berhasil diupdate!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Jadwal::destroy($id);
        return redirect('/blog')->with('status','Data Jadwal Pelayanan berhasil dihapus!');
    }
}
