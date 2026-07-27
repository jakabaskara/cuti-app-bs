<?php

namespace App\Http\Controllers\admin;

use App\Models\Karyawan;
use App\Models\Posisi;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

use App\Models\SisaCuti;
use App\Models\KaryawanCutiBersama;
use App\Models\RiwayatCuti;
use App\Models\PermintaanCuti;
use Illuminate\Support\Facades\Schema;


class AdminKaryawanController extends Controller
{
    public function index()
    {
        $positions = Posisi::with('unitKerja')->get();
        $idUser = Auth::user()->id;
        $user = User::find($idUser);
        $jabatan = $user->karyawan->posisi->jabatan;
        $namaUser = $user->karyawan->nama;
        $idPosisi = $user->karyawan->posisi->id;

        return view('admin.karyawan', [
            'jabatan' => $jabatan,
            'nama' => $namaUser,
            'positions' => $positions,
        ]);
    }

    public function getKaryawanData(Request $request)
    {
        $perPage = $request->input('per_page', 10);
        $page = $request->input('page', 1);
        $search = $request->input('search', '');

        $query = Karyawan::with('posisi.unitKerja');

        $status = $request->input('status', 'active');
        if ($status === 'trashed') {
            $query->onlyTrashed();
        }

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('nik', 'like', "%{$search}%")
                  ->orWhere('nama', 'like', "%{$search}%")
                  ->orWhere('jabatan', 'like', "%{$search}%");
            });
        }

        $total = $query->count();
        $karyawan = $query->skip(($page - 1) * $perPage)
                          ->take($perPage)
                          ->get();

        return response()->json([
            'data' => $karyawan,
            'current_page' => $page,
            'per_page' => $perPage,
            'total' => $total,
            'last_page' => ceil($total / $perPage)
        ]);
    }

    public function tambahKaryawan(Request $request)
    {
        $validate = $request->validate([
            // 'nik' => 'required|unique:karyawan,nik',
            'nik' => 'required|numeric|unique:karyawan,nik',
            'nama' => 'required',
            'jabatan' => 'required',
            'tmt_bekerja' => 'required',
            'tgl_diangkat_staf' => 'nullable',
            'id_posisi' => 'required|exists:posisi,id',
        ]);

        DB::transaction(function () use ($validate) {
            $karyawan = Karyawan::create([
                    'nik' => $validate['nik'],
                    'nama' => $validate['nama'],
                    'jabatan' => $validate['jabatan'],
                    'tmt_bekerja' => $validate['tmt_bekerja'],
                    'tgl_diangkat_staf' => $validate['tgl_diangkat_staf'],
                    'id_posisi' => $validate['id_posisi'],
            ]);
        });

        return redirect()->back()->with('message', 'Karyawan berhasil ditambahkan!');
    }


    public function edit($id)
    {
        $employee = Karyawan::with('posisi.unitKerja')->findOrFail($id);
        return response()->json($employee);
    }



    public function updateKaryawan(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:karyawan,id',
            // 'nik' => 'required|unique:karyawan,nik,' . $request->id,
            'nik' => 'required|numeric|unique:karyawan,nik,' . $request->id,
            'nama_karyawan' => 'required',
            'jabatan' => 'required',
            'tmt_bekerja' => 'required',
            'tgl_diangkat_staf' => 'nullable',
            'id_posisi' => 'required|exists:posisi,id',
        ]);

        $karyawan = Karyawan::findOrFail($request->id);
        $karyawan->nik = $request->nik;
        $karyawan->nama = $request->nama_karyawan;
        $karyawan->jabatan = $request->jabatan;
        $karyawan->tmt_bekerja = $request->tmt_bekerja;
        $karyawan->tgl_diangkat_staf = $request->tgl_diangkat_staf;
        $karyawan->id_posisi = $request->id_posisi;
        $karyawan->save();

        return redirect()->route('admin.karyawan.index')->with('message', 'Data karyawan diperbarui, ganti id_karyawan di user jika karyawan mempunyai akun');
    }



//soft delete
    public function delete($id)
    {
        $karyawan = Karyawan::find($id);
        if (!$karyawan) {
            return redirect()->back()->with('warning_message', 'Data karyawan tidak ditemukan');
        }

        DB::transaction(function () use ($id) {
            $deletedAt = now();

            User::where('id_karyawan', $id)->get()->each(function (User $user) use ($deletedAt) {
                $user->deleted_at = $deletedAt;
                $user->save();
            });

            SisaCuti::where('id_karyawan', $id)->whereNull('deleted_at')->get()->each(function ($row) use ($deletedAt) {
                $row->deleted_at = $deletedAt;
                $row->save();
            });

            KaryawanCutiBersama::where('id_karyawan', $id)->whereNull('deleted_at')->get()->each(function ($row) use ($deletedAt) {
                $row->deleted_at = $deletedAt;
                $row->save();
            });

            $permintaan = PermintaanCuti::where('id_karyawan', $id)->whereNull('deleted_at')->get();
            $permintaanIds = $permintaan->pluck('id');

            RiwayatCuti::whereIn('id_permintaan_cuti', $permintaanIds)->whereNull('deleted_at')->get()->each(function ($row) use ($deletedAt) {
                $row->deleted_at = $deletedAt;
                $row->save();
            });

            $permintaan->each(function ($row) use ($deletedAt) {
                $row->deleted_at = $deletedAt;
                $row->save();
            });

            if (Schema::hasTable('log_pengurangan_cuti') && Schema::hasColumn('log_pengurangan_cuti', 'deleted_at')) {
                DB::table('log_pengurangan_cuti')
                    ->where('id_karyawan', $id)
                    ->whereNull('deleted_at')
                    ->update(['deleted_at' => $deletedAt]);
            }

            $karyawan = Karyawan::find($id);
            $karyawan->deleted_at = $deletedAt;
            $karyawan->save();
        });

        return redirect()->back()->with('error_message', 'Data karyawan berhasil dihapus (soft delete)');
    }

    public function restore($id)
    {
        $karyawan = Karyawan::withTrashed()->find($id);
        if (!$karyawan || !$karyawan->trashed()) {
            return redirect()->back()->with('warning_message', 'Data karyawan terhapus tidak ditemukan');
        }

        DB::transaction(function () use ($karyawan) {
            $marker = $karyawan->deleted_at;
            $from = $marker->copy()->subSeconds(2);
            $to = $marker->copy()->addSeconds(2);

            User::withTrashed()
                ->where('id_karyawan', $karyawan->id)
                ->whereBetween('deleted_at', [$from, $to])
                ->restore();

            SisaCuti::withTrashed()
                ->where('id_karyawan', $karyawan->id)
                ->whereBetween('deleted_at', [$from, $to])
                ->restore();

            KaryawanCutiBersama::withTrashed()
                ->where('id_karyawan', $karyawan->id)
                ->whereBetween('deleted_at', [$from, $to])
                ->restore();

            $permintaanIds = PermintaanCuti::withTrashed()
                ->where('id_karyawan', $karyawan->id)
                ->whereBetween('deleted_at', [$from, $to])
                ->pluck('id');

            RiwayatCuti::withTrashed()
                ->whereIn('id_permintaan_cuti', $permintaanIds)
                ->whereBetween('deleted_at', [$from, $to])
                ->restore();

            PermintaanCuti::withTrashed()
                ->where('id_karyawan', $karyawan->id)
                ->whereBetween('deleted_at', [$from, $to])
                ->restore();

            if (Schema::hasTable('log_pengurangan_cuti') && Schema::hasColumn('log_pengurangan_cuti', 'deleted_at')) {
                DB::table('log_pengurangan_cuti')
                    ->where('id_karyawan', $karyawan->id)
                    ->whereBetween('deleted_at', [$from, $to])
                    ->update(['deleted_at' => null]);
            }

            $karyawan->restore();
        });

        return redirect()->back()->with('message', 'Data karyawan berhasil dipulihkan');
    }
}
