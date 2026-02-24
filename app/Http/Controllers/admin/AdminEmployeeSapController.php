<?php

namespace App\Http\Controllers\admin;

use App\Models\Employee;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminEmployeeSapController extends Controller
{
    public function index()
    {
        $idUser = Auth::user()->id;
        $user = User::find($idUser);
        $jabatan = $user->karyawan->posisi->jabatan;
        $namaUser = $user->karyawan->nama;

        return view('admin.employee-sap', [
            'jabatan' => $jabatan,
            'nama' => $namaUser,
        ]);
    }

    public function getEmployeeSapData(Request $request)
    {
        $perPage = $request->input('per_page', 25);
        $page = $request->input('page', 1);
        $search = $request->input('search', '');

        $query = Employee::query();

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('sap', 'like', "%{$search}%")
                  ->orWhere('name', 'like', "%{$search}%")
                  ->orWhere('desc_position', 'like', "%{$search}%")
                  ->orWhere('desc_org_unit', 'like', "%{$search}%")
                  ->orWhere('personnel_area', 'like', "%{$search}%")
                  ->orWhere('desc_personnel_area', 'like', "%{$search}%");
            });
        }

        $total = $query->count();
        $employees = $query->skip(($page - 1) * $perPage)
                          ->take($perPage)
                          ->get();

        return response()->json([
            'data' => $employees,
            'current_page' => $page,
            'per_page' => $perPage,
            'total' => $total,
            'last_page' => ceil($total / $perPage)
        ]);
    }
}
