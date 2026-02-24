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

        return view('admin.user', [
            'nama' => $namaUser,
            'jabatan' => $jabatan,
        ]);
    }

    public function getUserData(Request $request)
    {
        $perPage = $request->input('per_page', 25);
        $page = $request->input('page', 1);
        $search = $request->input('search', '');

        $query = User::with('karyawan.posisi');

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('username', 'like', "%{$search}%")
                  ->orWhereHas('karyawan', function($subQ) use ($search) {
                      $subQ->where('nama', 'like', "%{$search}%")
                           ->orWhere('nik', 'like', "%{$search}%");
                  });
            });
        }

        $total = $query->count();
        $users = $query->skip(($page - 1) * $perPage)
                       ->take($perPage)
                       ->get();

        return response()->json([
            'data' => $users,
            'current_page' => $page,
            'per_page' => $perPage,
            'total' => $total,
            'last_page' => ceil($total / $perPage)
        ]);
    }

    public function getKaryawanForSelect()
    {
        $karyawans = Karyawan::select('id', 'nik', 'nama')->get();
        return response()->json($karyawans);
    }

    public function tambahUser(Request $request)
    {
        $validate = $request->validate([
            'username' => 'required',
            'password' => 'required|min:3',
            'id_karyawan' => 'required|exists:karyawan,id',
        ]);

        $existingUsername = User::where('username', $validate['username'])->first();
        if ($existingUsername) {
            return redirect()->back()->with('warning_message', 'Username sudah ada');
        }

        // Cek apakah id_karyawan sudah digunakan
        // $existingIdKaryawan = User::where('id_karyawan', $validate['id_karyawan'])->first();
        // if ($existingIdKaryawan) {
        //     return redirect()->back()->with('warning_message', 'ID Karyawan sudah digunakan');
        // }

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
            'id_karyawan' => 'required|exists:karyawan,id',
        ]);

        // Pastikan id_karyawan tidak sudah digunakan oleh user lain
        // $existingIdKaryawan = User::where('id_karyawan', $request->id_karyawan)
        //                           ->where('id', '!=', $request->id) // Pastikan pengecekan bukan untuk user yang sedang diupdate
        //                           ->first();
        // if ($existingIdKaryawan) {
        //     return redirect()->back()->with('warning_message', 'ID Karyawan sudah digunakan');
        // }

        $user = User::findOrFail($request->id);
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
