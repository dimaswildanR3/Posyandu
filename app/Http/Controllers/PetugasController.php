<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Petugas;
use App\Models\User;

class PetugasController extends Controller
{

   public function index()
{
    $petugas = Petugas::paginate(10);
    return view('petugas.index', compact('petugas'));
}

public function create()
{
    $users = User::all();
    $jabatans = ['Ketua Kader', 'Sekretaris', 'Bendahara', 'Anggota'];
    return view('petugas.create', compact('users', 'jabatans'));
}

public function store(Request $request)
{
    $request->validate([
        'nama_petugas' => 'required',
        'tempat_lahir' => 'required',
        'tanggal_lahir' => 'required|date',
        'no_hp' => 'required',
        'jabatan' => 'required',
        'pendidikan' => 'required',
        'lama_kerja' => 'required',
        'user_id' => 'required|exists:users,id'
    ]);

    Petugas::create($request->all());

    return redirect()->route('petugas.index')->with('status', 'Data petugas berhasil ditambahkan.');
}

public function edit(Petugas $petuga)
{
    $users = User::all();
    $jabatans = ['Ketua Kader', 'Sekretaris', 'Bendahara', 'Anggota'];
    return view('petugas.edit', [
        'petugas' => $petuga,
        'users' => $users,
        'jabatans' => $jabatans
    ]);
}

public function update(Request $request, Petugas $petuga)
{
    $request->validate([
        'nama_petugas' => 'required',
        'tempat_lahir' => 'required',
        'tanggal_lahir' => 'required|date',
        'no_hp' => 'required',
        'jabatan' => 'required',
        'pendidikan' => 'required',
        'lama_kerja' => 'required',
        'user_id' => 'required|exists:users,id'
    ]);

    $petuga->update($request->all());

    return redirect()->route('petugas.index')->with('status', 'Data petugas berhasil diperbarui.');
}
}
