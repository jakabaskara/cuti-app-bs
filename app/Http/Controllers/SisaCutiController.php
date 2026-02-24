<?php

namespace App\Http\Controllers;

use App\Models\SisaCuti;
use App\Models\User;
use App\Models\Karyawan;
use App\Models\JenisCuti;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class SisaCutiController extends Controller
{
    public function index()
    {
        $idUser = Auth::user()->id;
        $user = User::find($idUser);
        $karyawan = $user->karyawan;

        $namaUser = $karyawan->nama;
        $jabatan = $karyawan->posisi->jabatan;

        $jenisCutis = JenisCuti::all();

        return view('admin.sisacuti', [
            'nama' => $namaUser,
            'jabatan' => $jabatan,
            'jenisCutis' => $jenisCutis,
            'karyawan' => $karyawan,
        ]);
    }

    public function getKaryawanForSelect()
    {
        $karyawans = Karyawan::select('id', 'nik', 'nama')->get();
        return response()->json($karyawans);
    }

    public function getSisaCutiData(Request $request)
    {
        $perPage = $request->input('per_page', 25);
        $page = $request->input('page', 1);
        $search = $request->input('search', '');

        $baseQuery = SisaCuti::select(
            'id_karyawan',
            DB::raw('SUM(CASE WHEN id_jenis_cuti = 1 THEN jumlah ELSE 0 END) AS total_cuti_panjang'),
            DB::raw('SUM(CASE WHEN id_jenis_cuti = 2 THEN jumlah ELSE 0 END) AS total_cuti_tahunan')
        )
        ->groupBy('id_karyawan');

        if ($search) {
            $baseQuery->whereHas('karyawan', function($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('nik', 'like', "%{$search}%")
                  ->orWhereHas('posisi', function($subQ) use ($search) {
                      $subQ->where('jabatan', 'like', "%{$search}%")
                           ->orWhereHas('unitKerja', function($unitQ) use ($search) {
                               $unitQ->where('nama_unit_kerja', 'like', "%{$search}%");
                           });
                  });
            });
        }

        $total = DB::table(DB::raw("({$baseQuery->toSql()}) as grouped"))
            ->mergeBindings($baseQuery->getQuery())
            ->count();

        $sisaCutis = $baseQuery->with('karyawan.posisi.unitKerja')
            ->skip(($page - 1) * $perPage)
            ->take($perPage)
            ->get();

        $formattedData = $sisaCutis->map(function($item) {
            $karyawan = $item->karyawan;
            $cutiTahunan = $item->total_cuti_tahunan ?? 0;
            $cutiPanjang = $item->total_cuti_panjang ?? 0;
            
            if ($cutiTahunan < 0 && $cutiPanjang < 0) {
                $totalCuti = $cutiTahunan + $cutiPanjang;
            } elseif ($cutiTahunan > 0 && $cutiPanjang <= 0) {
                $totalCuti = $cutiTahunan;
            } elseif ($cutiTahunan <= 0 && $cutiPanjang > 0) {
                $totalCuti = $cutiPanjang;
            } elseif ($cutiTahunan == 0 && $cutiPanjang < 0) {
                $totalCuti = $cutiPanjang;
            } elseif ($cutiTahunan < 0 && $cutiPanjang == 0) {
                $totalCuti = $cutiTahunan;
            } elseif ($cutiTahunan >= 0 && $cutiPanjang >= 0) {
                $totalCuti = $cutiTahunan + $cutiPanjang;
            } else {
                $totalCuti = 0;
            }

            return [
                'id_karyawan' => $item->id_karyawan,
                'nik' => $karyawan->nik ?? '',
                'nama' => $karyawan->nama ?? '',
                'unit_kerja' => $karyawan->posisi->unitKerja->nama_unit_kerja ?? '',
                'total_cuti_tahunan' => $cutiTahunan,
                'total_cuti_panjang' => $cutiPanjang,
                'total_cuti' => $totalCuti,
            ];
        });

        return response()->json([
            'data' => $formattedData,
            'current_page' => $page,
            'per_page' => $perPage,
            'total' => $total,
            'last_page' => ceil($total / $perPage)
        ]);
    }



    public function tambahCuti(Request $request)
    {
        $request->validate([
            'id_karyawan' => 'required|exists:karyawan,id',
            'id_jenis_cuti' => 'required|array|min:1',
            'id_jenis_cuti.*' => 'nullable|exists:jenis_cuti,id',
            'periode_mulai' => 'required',
            'periode_mulai.*' => 'nullable|date',
            'periode_akhir' => 'required|array',
            'periode_akhir.*' => 'nullable|date|after_or_equal:periode_mulai.*',
            'jumlah' => 'required',
            'jumlah.*' => 'nullable',
        ]);

        foreach ($request->id_jenis_cuti as $jenisCutiId) {
            $existingCuti = SisaCuti::where('id_karyawan', $request->id_karyawan)
                ->where('id_jenis_cuti', $jenisCutiId)
                ->exists();

            if ($existingCuti) {
                return redirect()->back()->withErrors("Karyawan sudah memiliki jenis cuti ini.");
            }
        }

        DB::transaction(function () use ($request) {
            $id_karyawan = $request->id_karyawan;
            $id_jenis_cuti = $request->id_jenis_cuti;
            $periode_mulai = $request->periode_mulai;
            $periode_akhir = $request->periode_akhir;
            $jumlah = $request->jumlah;

            foreach ($id_jenis_cuti as $index => $jenisCutiId) {
                SisaCuti::create([
                    'id_karyawan' => $id_karyawan,
                    'id_jenis_cuti' => $jenisCutiId,
                    'periode_mulai' => $periode_mulai[$index],
                    'periode_akhir' => $periode_akhir[$index],
                    'jumlah' => $jumlah[$index],
                ]);
            }
        });

        return redirect()->back()->with('message', 'Data cuti karyawan berhasil ditambahkan!');
    }





    public function updateCuti(Request $request)
{
    $id_karyawan = $request->input('id_karyawan');
    $request->validate([
        'id_karyawan' => 'required|exists:karyawan,id',
        'id_jenis_cuti' => 'required|array|min:1',
        'id_jenis_cuti.*' => 'nullable|exists:jenis_cuti,id',
        'periode_mulai' => 'required',
        'periode_mulai.*' => 'nullable|date',
        'periode_akhir' => 'required|array',
        'periode_akhir.*' => 'nullable|date|after_or_equal:periode_mulai.*',
        'jumlah' => 'required',
        'jumlah.*' => 'nullable',
    ]);

    // Iterasi melalui setiap jenis cuti yang ingin diupdate
    foreach ($request->id_jenis_cuti as $index => $jenisCutiId) {
        $sisaCuti = SisaCuti::where('id_karyawan', $id_karyawan)
                            ->where('id_jenis_cuti', $jenisCutiId)
                            ->firstOrFail();

        $sisaCuti->update([
            'periode_mulai' => $request->periode_mulai[$index],
            'periode_akhir' => $request->periode_akhir[$index],
            'jumlah' => $request->jumlah[$index],
        ]);
    }

    return redirect()->route('admin.sisacuti.index')->with('message', 'Sisa cuti berhasil diupdate!');
}



    public function edit($id_karyawan)
    {
        // Ambil semua data sisa cuti untuk karyawan tersebut
        $sisaCutis = SisaCuti::where('id_karyawan', $id_karyawan)->get();

        // Ubah menjadi array of objects untuk memudahkan penanganan di frontend
        $cutiData = $sisaCutis->map(function ($cuti) {
            return [
                'id' => $cuti->id,
                'id_karyawan' =>$cuti->id_karyawan,
                'id_jenis_cuti' => $cuti->id_jenis_cuti,
                'periode_mulai' => $cuti->periode_mulai,
                'periode_akhir' => $cuti->periode_akhir,
                'jumlah' => $cuti->jumlah,
            ];
        })->toArray();

        return response()->json($cutiData);
    }

    public function delete($id_karyawan)
    {
        $sisaCutis = SisaCuti::where('id_karyawan', $id_karyawan)->get();
        // dump($sisaCutis); // Mengecek apakah data dapat diperoleh

        DB::transaction(function () use ($id_karyawan) {
            $sisaCutis = SisaCuti::where('id_karyawan', $id_karyawan)->get();
            foreach ($sisaCutis as $sisaCuti) {
                $sisaCuti->delete();
            }
        });

        return redirect()->back()->with('error_message', 'Semua data cuti karyawan tersebut berhasil dihapus!');
    }


}
