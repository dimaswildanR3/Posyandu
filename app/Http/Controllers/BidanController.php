<?php

namespace App\Http\Controllers;

use App\Models\Bidan;
use App\Models\User;
use Illuminate\Http\Request;

class BidanController extends Controller
{
    public function index()
    {
        $bidans = Bidan::paginate(10);
        return view('bidans.index', compact('bidans'));
    }

    public function create()
    {
        $users = User::all();
        return view('bidans.create', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'tempat_lahir' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'no_hp' => 'required|string|max:20',
            'pendidikan_terakhir' => 'required|string|max:255',
            'user_id' => 'required|exists:users,id',
        ]);

        Bidan::create($request->all());

        return redirect()->route('bidans.index')->with('status', 'Data Bidan berhasil ditambahkan!');
    }

    public function edit(Bidan $bidan)
    {
        $users = User::all();
        return view('bidans.edit', compact('bidan', 'users'));
    }

    public function update(Request $request, Bidan $bidan)
    {
        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'tempat_lahir' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'no_hp' => 'required|string|max:20',
            'pendidikan_terakhir' => 'required|string|max:255',
            'user_id' => 'required|exists:users,id',
        ]);

        $bidan->update($request->all());

        return redirect()->route('bidans.index')->with('status', 'Data Bidan berhasil diperbarui!');
    }

    public function destroy(Bidan $bidan)
    {
        $bidan->delete();
        return redirect()->route('bidans.index')->with('status', 'Data Bidan berhasil dihapus!');
    }
}
