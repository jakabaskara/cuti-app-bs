<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\MasterLiburKalender;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AdminKalenderController extends Controller
{
    public $nama;
    public $jabatan;

    public function index()
    {
        $idUser = Auth::user()->id;
        $user = User::find($idUser);
        $karyawan = $user->karyawan;
        $this->nama = $karyawan->nama;
        $this->jabatan = $karyawan->jabatan;

        return view('admin.kalender', [
            'jabatan' => $this->jabatan,
            'nama' => $this->nama,
        ]);
    }

    public function getKalenderData(Request $request)
    {
        $year = $request->input('year', date('Y'));
        $month = $request->input('month', null);

        $query = MasterLiburKalender::select('id', 'tanggal', 'description', 'jenis_libur');

        if ($month) {
            $query->whereYear('tanggal', $year)
                  ->whereMonth('tanggal', $month);
        } else {
            $query->whereYear('tanggal', $year);
        }

        $data = $query->orderBy('tanggal', 'asc')->get();

        return response()->json([
            'data' => $data
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tanggal' => 'required|date',
            'description' => 'required|string|max:255',
            'jenis_libur' => 'required|in:libur_biasa,cuti_bersama',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $existing = MasterLiburKalender::where('tanggal', $request->tanggal)->first();
        if ($existing) {
            return response()->json([
                'success' => false,
                'message' => 'Tanggal ini sudah memiliki keterangan libur/cuti bersama'
            ], 422);
        }

        $kalender = MasterLiburKalender::create([
            'tanggal' => $request->tanggal,
            'description' => $request->description,
            'jenis_libur' => $request->jenis_libur,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Data berhasil ditambahkan',
            'data' => $kalender
        ]);
    }

    public function show($id)
    {
        $kalender = MasterLiburKalender::find($id);
        
        if (!$kalender) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $kalender
        ]);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'tanggal' => 'required|date',
            'description' => 'required|string|max:255',
            'jenis_libur' => 'required|in:libur_biasa,cuti_bersama',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $kalender = MasterLiburKalender::find($id);
        
        if (!$kalender) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan'
            ], 404);
        }

        $existing = MasterLiburKalender::where('tanggal', $request->tanggal)
            ->where('id', '!=', $id)
            ->first();
        
        if ($existing) {
            return response()->json([
                'success' => false,
                'message' => 'Tanggal ini sudah memiliki keterangan libur/cuti bersama'
            ], 422);
        }

        $kalender->update([
            'tanggal' => $request->tanggal,
            'description' => $request->description,
            'jenis_libur' => $request->jenis_libur,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Data berhasil diupdate',
            'data' => $kalender
        ]);
    }

    public function destroy($id)
    {
        $kalender = MasterLiburKalender::find($id);
        
        if (!$kalender) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan'
            ], 404);
        }

        $kalender->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data berhasil dihapus'
        ]);
    }
}

