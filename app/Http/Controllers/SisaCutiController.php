<?php

namespace App\Http\Controllers;

use App\Models\SisaCuti;
use App\Models\User;
use App\Models\Karyawan;
use App\Models\JenisCuti;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use League\CommonMark\Extension\Autolink\UrlAutolinkParser;
use Yajra\DataTables\DataTables;
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

        $karyawans = Karyawan::all();
        $jenisCutis = JenisCuti::all();

        $sisaCuti = SisaCuti::select(
            'id_karyawan',
            \DB::raw('SUM(CASE WHEN id_jenis_cuti = 1 THEN jumlah ELSE 0 END) AS total_cuti_tahunan'),
            \DB::raw('SUM(CASE WHEN id_jenis_cuti = 2 THEN jumlah ELSE 0 END) AS total_cuti_panjang')
        )
            ->groupBy('id_karyawan')
            ->get();

        return view('admin.sisacuti', [
            'nama' => $namaUser,
            'jabatan' => $jabatan,
            'sisaCutis' => $sisaCuti,
            'karyawans' => $karyawans, // Pass the variable to the view
            'jenisCutis' => $jenisCutis, // Make sure you also pass jenisCutis to the view
            'karyawan' => $karyawan,
        ]);
    }

    public function sisaCutiData(Request $request)
    {
        return DataTables::of(SisaCuti::with('karyawan.posisi.unitKerja'))
            ->addIndexColumn()
            ->addColumn('NIK', function ($row) {
                return $row->karyawan->NIK;
            })
            ->addColumn('ID', function ($row) {
                return $row->id;
            })
            ->addColumn('Nama', function ($row) {
                return $row->karyawan->nama;
            })
            ->addColumn('UnitKerja', function ($row) {
                return $row->karyawan->posisi->unitKerja->nama_unit_kerja;
            })
            ->addColumn('Posisi', function ($row) {
                return $row->karyawan->posisi->jabatan;
            })
            ->addColumn('PeriodeCuti', function ($row) {
                return date('d M Y', strtotime($row->periode_mulai)) . ' s.d ' . date('d M Y', strtotime($row->periode_akhir));
            })
            ->addColumn('JenisCuti', function ($row) {
                return $row->jenisCuti->jenis_cuti;
            })
            ->addColumn('SisaCuti', function ($row) {
                return $row->jumlah;
            })
            ->setRowClass(function ($row) {
                return 'text-center'; // Menambahkan kelas 'text-center' pada setiap baris
            })
            ->setRowData([
                'data-placement' => 'center', // Menambahkan data-placement 'center' pada setiap baris
            ])
            ->rawColumns(['NIK', 'ID', 'Nama', 'UnitKerja', 'Posisi', 'PeriodeCuti', 'JenisCuti', 'SisaCuti']) // Aktifkan pencarian dan pengurutan untuk setiap kolom
            ->make(true);
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
