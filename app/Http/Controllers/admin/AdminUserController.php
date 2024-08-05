<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


use App\Models\Karyawan;
use App\Models\Posisi;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;



class AdminUserController extends Controller
{
    public function index()
    {
    $idUser = Auth::user()->id;
    $user = User::find($idUser);
    $jabatan = $user->karyawan->posisi->jabatan;
    $namaUser = $user->karyawan->nama;

    $users = User::with('karyawan')->get();
    $karyawans = Karyawan::all();

    return view('admin.user', [
        'karyawans' => $karyawans,
        'users' => $users,
        'nama' => $namaUser,
        'jabatan' => $jabatan,

    ]);
    }

    public function tambahUser(Request $request)
    {
        $validate = $request->validate([
            'username' => 'required|unique:users,username',
            'password' => 'required|min:3',
            'id_karyawan' => 'required|unique:users,id_karyawan|exists:karyawan,id',
        ]);

        DB::transaction(function () use ($validate) {
            $user = User::create([
                    'username' => $validate['username'],
                    'password' => bcrypt($validate['password']),
                    'id_karyawan' => $validate['id_karyawan'],
            ]);
        });

        return redirect()->back()->with('message', 'User berhasil ditambahkan!');
    }


    public function edit($id)
    {
        $user = User::findOrFail($id);
        return response()->json($user);
    }



    public function updateUser(Request $request)
    {
        $request->validate([
            // 'username' => 'required|unique:users,username',
            'id_karyawan' => 'required|unique:users,id_karyawan|exists:karyawan,id',
        ]);

        $user = User::findOrFail($request->id);
        // $user->username = $request->username;
        $user->id_karyawan = $request->id_karyawan;
        $user->save();

        return redirect()->route('admin.user.index')->with('message', 'Data user berhasil diperbarui.');
    }



//soft delete
    public function delete($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return redirect()->back()->with('error_message', 'Data user berhasil dihapus!');
    }}
