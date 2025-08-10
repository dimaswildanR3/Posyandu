<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Balita;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AkunController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $akun = User::paginate(5);
        return view('akun.index',compact('akun'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // Ambil semua orangtua yang belum punya user_id
        $orangtuaList = Balita::whereNull('user_id')->get();
    
        return view('akun.create', compact('orangtuaList'));
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Atur rules validasi
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['nullable', 'string', 'max:50'],
        ];
    
        if ($request->role !== 'ortu') {
            $rules['username'] = ['required', 'string', 'max:255', 'unique:users'];
        } else {
            // Kalau role ortu, pastikan orangtua_id wajib diisi
            $rules['orangtua_id'] = ['required', 'exists:balitas,id'];
        }
    
        $request->validate($rules);
    
        $username = $request->username;
    
        if ($request->role === 'ortu') {
            $balita = Balita::find($request->orangtua_id);
    
            if (!$balita) {
                return back()->withInput()->withErrors(['orangtua_id' => 'Balita tidak ditemukan.']);
            }
    
            if (empty($balita->nik_anak)) {
                return back()->withInput()->withErrors(['orangtua_id' => 'NIK Anak pada balita yang dipilih kosong, tidak bisa membuat username.']);
            }
    
            $username = $balita->nik_anak;
    
            // Cek apakah username ini sudah ada di tabel users
            if (User::where('username', $username)->exists()) {
                return back()->withInput()->withErrors(['orangtua_id' => 'Username (NIK Anak) sudah terdaftar, silakan hubungi admin.']);
            }
        }
    
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'username' => $username,
        ]);
    
        if ($request->role === 'ortu') {
            $balita->user_id = $user->id;
            $balita->save();
        }
    
        return redirect('/akun')->with('status', 'Akun berhasil dibuat!');
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
    $akun = User::findOrFail($id);
     // Ambil orang tua yang belum memiliki user_id atau yang sudah terkait dengan akun ini
     $orangtuaList = Balita::whereNull('user_id')
     ->orWhere('user_id', $akun->id)
     ->get();
    return view('akun.edit', compact('akun', 'orangtuaList'));
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
        $akun = User::findOrFail($id);
    
        // Atur rules validasi
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $akun->id],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'role' => ['nullable', 'string', 'max:50'],
        ];
    
        if ($request->role !== 'ortu') {
            // Username wajib dan unik, kecuali untuk user ini sendiri
            $rules['username'] = ['required', 'string', 'max:255', 'unique:users,username,' . $akun->id];
        } else {
            // Kalau role ortu, pastikan orangtua_id wajib diisi
            $rules['orangtua_id'] = ['required', 'exists:balitas,id'];
        }
    
        $request->validate($rules);
    
        $username = $request->username;
    
        if ($request->role === 'ortu') {
            $balita = Balita::find($request->orangtua_id);
    
            if (!$balita) {
                return back()->withInput()->withErrors(['orangtua_id' => 'Balita tidak ditemukan.']);
            }
    
            if (empty($balita->nik_anak)) {
                return back()->withInput()->withErrors(['orangtua_id' => 'NIK Anak pada balita yang dipilih kosong, tidak bisa membuat username.']);
            }
    
            $username = $balita->nik_anak;
    
            // Cek apakah username sudah digunakan oleh user lain
            if (User::where('username', $username)->where('id', '!=', $akun->id)->exists()) {
                return back()->withInput()->withErrors(['orangtua_id' => 'Username (NIK Anak) sudah terdaftar, silakan hubungi admin.']);
            }
        }
    
        // Update data user
        $akun->name = $request->name;
        $akun->email = $request->email;
        $akun->role = $request->role;
        $akun->username = $username;
    
        if ($request->password) {
            $akun->password = Hash::make($request->password);
        }
    
        $akun->save();
    
        // Reset semua user_id di balita yang sebelumnya terkait dengan user ini
        Balita::where('user_id', $akun->id)->update(['user_id' => null]);
    
        // Hubungkan user dengan balita baru jika role ortu
        if ($request->role === 'ortu') {
            $balita->user_id = $akun->id;
            $balita->save();
        }
    
        return redirect('/akun')->with('status', 'Akun berhasil diubah!');
    }
    

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $akun = User::destroy($id);
        return redirect('/akun')->with('status','Akun berhasil dihapus!');
    }
}
