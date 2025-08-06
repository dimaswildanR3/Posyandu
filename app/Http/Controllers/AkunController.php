<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\OrangTua;
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
        $orangtuaList = OrangTua::whereNull('user_id')->get();
    
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
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['nullable', 'string', 'max:50'], 
            'username' => ['required', 'string', 'max:255', 'unique:users'],
        ]);
    
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'username' => $request->username,
        ]);
    
        // Jika role ortu dan pilih orangtua, hubungkan
        if ($request->role === 'ortu' && $request->orangtua_id) {
            $orangtua = OrangTua::findOrFail($request->orangtua_id);
            $orangtua->user_id = $user->id;
            $orangtua->save();
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
     $orangtuaList = OrangTua::whereNull('user_id')
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
    
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'role' => ['nullable', 'string', 'max:50'], 
            'username' => ['required', 'string', 'max:255', 'unique:users'],
        ]);
    
        // Update data user
        $akun->name = $request->name;
        $akun->email = $request->email;
        $akun->role = $request->role;
        $akun->username = $request->username;
    
        if ($request->password) {
            $akun->password = Hash::make($request->password);
        }
    
        $akun->save();
    
        // Reset semua user_id di orangtua yang sebelumnya terkait dengan user ini (opsional, jika hanya satu ke satu)
        Orangtua::where('user_id', $akun->id)->update(['user_id' => null]);
    
        // Jika role ortu dan pilih orangtua, hubungkan
        if ($request->role === 'ortu' && $request->orangtua_id) {
            $orangtua = Orangtua::findOrFail($request->orangtua_id);
            $orangtua->user_id = $akun->id;
            $orangtua->save();
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
