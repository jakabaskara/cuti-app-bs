<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\EmployeePosition;
use App\Models\Posisi;
use App\Models\UnitKerja;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrganizationalChartController extends Controller
{
    /**
     * Display the organizational chart page
     */
    public function index()
    {
        $user = Auth::user();
        $isAdmin = $user->karyawan->posisi->role->nama_role === 'admin';

        // Get units - for regional (2nd char = R), group by bagian, otherwise by kode_unit only
        if ($isAdmin) {
            $units = UnitKerja::select('kode_unit_kerja', 'nama_unit_kerja', 'bagian')
                ->get()
                ->groupBy(function ($unit) {
                // Regional units (2nd char = R): group by kode + bagian
                if (strlen($unit->kode_unit_kerja) >= 2 && $unit->kode_unit_kerja[1] === 'R') {
                    return $unit->kode_unit_kerja . '|' . $unit->bagian;
                }
                // Non-regional: group by kode only
                return $unit->kode_unit_kerja;
            })
                ->map(function ($group) {
                $unit = $group->first();
                // For regional, use bagian as display name
                if (strlen($unit->kode_unit_kerja) >= 2 && $unit->kode_unit_kerja[1] === 'R') {
                    $unit->display_name = $unit->bagian;
                }
                else {
                    $unit->display_name = $unit->nama_unit_kerja;
                }
                return $unit;
            })
                ->sortBy('display_name')
                ->values();
        }
        else {
            $units = UnitKerja::select('kode_unit_kerja', 'nama_unit_kerja', 'bagian')
                ->where('kode_unit_kerja', $user->kode_unit)
                ->get()
                ->groupBy(function ($unit) {
                if (strlen($unit->kode_unit_kerja) >= 2 && $unit->kode_unit_kerja[1] === 'R') {
                    return $unit->kode_unit_kerja . '|' . $unit->bagian;
                }
                return $unit->kode_unit_kerja;
            })
                ->map(function ($group) {
                $unit = $group->first();
                if (strlen($unit->kode_unit_kerja) >= 2 && $unit->kode_unit_kerja[1] === 'R') {
                    $unit->display_name = $unit->bagian;
                }
                else {
                    $unit->display_name = $unit->nama_unit_kerja;
                }
                return $unit;
            })
                ->values();
        }

        return view('organizational-chart', compact('units', 'isAdmin'));
    }

    /**
     * Get organizational chart data
     */
    public function getChartData(Request $request)
    {
        $kodeUnit = $request->get('kode_unit');

        if (!$kodeUnit) {
            return response()->json([
                'success' => false,
                'message' => 'Kode unit harus dipilih'
            ], 400);
        }

        // Get all positions for this unit by kode_unit_kerja (not by id!)
        // One kode_unit_kerja can have multiple id_unit_kerja with different bagian
        $positions = Posisi::join('unit_kerja', 'posisi.id_unit_kerja', '=', 'unit_kerja.id')
            ->where('unit_kerja.kode_unit_kerja', $kodeUnit)
            ->select('posisi.*')
            ->with(['role', 'employeePositions.employee'])
            ->get();

        // Build chart structure
        $chartData = $this->buildChartStructure($positions, $kodeUnit);

        return response()->json([
            'success' => true,
            'data' => $chartData
        ]);
    }

    /**
     * Build chart structure - returns flat array grouped by level
     */
    private function buildChartStructure($positions, $kodeUnit)
    {
        $chartData = [
            'brm1' => [],
            'brm2' => [],
            'brm3' => []
        ];

        // BRM-1: All positions with role GM, Manajer, or Kabag
        $brm1Positions = $positions->filter(function ($pos) {
            $roleName = strtolower($pos->role->nama_role ?? '');
            return in_array($roleName, ['gm', 'manajer', 'kabag']);
        });

        foreach ($brm1Positions as $position) {
            $chartData['brm1'][] = $this->buildPositionCard($position, 'BRM-1', true);
        }

        // BRM-2: Employees from Employee table with level BRM-2
        $brm2Employees = $this->getBRM2Employees($kodeUnit);

        if ($brm2Employees->isNotEmpty()) {
            foreach ($brm2Employees as $emp) {
                $chartData['brm2'][] = [
                    'id' => 'brm2-' . $emp->sap,
                    'name' => $emp->name,
                    'title' => $emp->desc_position,
                    'level' => 'BRM-2',
                    'sap' => $emp->sap,
                    'isEmpty' => false,
                    'canAssign' => false, // BRM-2 tidak bisa di-assign manual
                ];
            }
        }
        else {
            // Jika tidak ada BRM-2, tampilkan card kosong (tidak bisa assign)
            $chartData['brm2'][] = [
                'id' => 'brm2-empty',
                'name' => 'Tidak ada data BRM-2',
                'title' => 'BRM-2',
                'level' => 'BRM-2',
                'isEmpty' => true,
                'canAssign' => false,
            ];
        }

        // BRM-3: All positions with role Asisten
        $brm3Positions = $positions->filter(function ($pos) {
            $roleName = strtolower($pos->role->nama_role ?? '');
            return $roleName === 'asisten';
        });

        foreach ($brm3Positions as $position) {
            $chartData['brm3'][] = $this->buildPositionCard($position, 'BRM-3', true);
        }

        return $chartData;
    }

    /**
     * Build a position card
     */
    private function buildPositionCard($position, $level, $canAssign = true)
    {
        $employeePosition = $position->employeePositions->first();

        $card = [
            'id' => 'pos-' . $position->id,
            'position_id' => $position->id,
            'title' => $position->jabatan,
            'level' => $level,
            'role' => $position->role->nama_role ?? '',
            'isEmpty' => !$employeePosition,
            'canAssign' => $canAssign,
        ];

        if ($employeePosition && $employeePosition->employee) {
            $emp = $employeePosition->employee;
            $card['name'] = $emp->name;
            $card['sap'] = $emp->sap;
            $card['employee_position_id'] = $employeePosition->id;
            $card['desc_position'] = $emp->desc_position;
        }
        else {
            $card['name'] = 'Posisi Kosong';
        }

        return $card;
    }

    /**
     * Get BRM-2 employees from Employee table
     */
    private function getBRM2Employees($kodeUnit)
    {
        // Get employees with level BRM-2 matching the unit
        return Employee::where('level', 'BRM-2')
            ->where(function ($query) use ($kodeUnit) {
            // Regional units (2nd char = R): match by kode_ring (first 4 digits)
            $query->where(function ($q) use ($kodeUnit) {
                    $q->whereRaw("SUBSTRING(personnel_subarea, 2, 1) = 'R'")
                        ->whereRaw("SUBSTRING(kode_ring, 1, 4) = ?", [$kodeUnit]);
                }
                )
                    // Non-regional units: match by personnel_subarea = kode_unit
                    ->orWhere(function ($q) use ($kodeUnit) {
                $q->whereRaw("SUBSTRING(personnel_subarea, 2, 1) != 'R'")
                    ->where('personnel_subarea', $kodeUnit);
            }
            );
        })
            ->get();
    }

    /**
     * Build BRM-2 level nodes
     */
    private function buildBRM2Nodes($brm2Employees, $brm3Positions)
    {
        $nodes = [];

        if ($brm2Employees->isEmpty()) {
            return $nodes;
        }

        foreach ($brm2Employees as $emp) {
            $nodes[] = [
                'id' => 'brm2-' . $emp->sap,
                'name' => $emp->name,
                'title' => $emp->desc_position,
                'level' => 'BRM-2',
                'sap' => $emp->sap,
                'isEmpty' => false,
                'children' => $this->buildBRM3Nodes($brm3Positions)
            ];
        }

        return $nodes;
    }

    /**
     * Build BRM-3 level nodes
     */
    private function buildBRM3Nodes($brm3Positions)
    {
        $nodes = [];

        if (!$brm3Positions || $brm3Positions->isEmpty()) {
            return $nodes;
        }

        foreach ($brm3Positions as $position) {
            $employeePosition = $position->employeePositions->first();

            $node = [
                'id' => 'pos-' . $position->id,
                'position_id' => $position->id,
                'title' => $position->jabatan,
                'level' => 'BRM-3',
                'role' => $position->role->nama_role ?? '',
                'isEmpty' => !$employeePosition,
                'children' => []
            ];

            if ($employeePosition && $employeePosition->employee) {
                $emp = $employeePosition->employee;
                $node['name'] = $emp->name;
                $node['sap'] = $emp->sap;
                $node['employee_position_id'] = $employeePosition->id;
                $node['desc_position'] = $emp->desc_position;
            }
            else {
                $node['name'] = 'Posisi Kosong';
            }

            $nodes[] = $node;
        }

        return $nodes;
    }

    /**
     * Get available employees for assignment (BRM-1 to BRM-3)
     */
    public function getAvailableEmployees(Request $request)
    {
        $kodeUnit = $request->get('kode_unit');
        $level = $request->get('level'); // BRM-1, BRM-2, or BRM-3

        if (!$kodeUnit) {
            return response()->json([
                'success' => false,
                'message' => 'Kode unit harus dipilih'
            ], 400);
        }

        // Get employees from Employee table matching the unit
        $query = Employee::whereIn('level', ['BRM-1', 'BRM-2', 'BRM-3'])
            ->where(function ($q) use ($kodeUnit) {
            // Regional units
            $q->whereRaw("SUBSTRING(personnel_subarea, 2, 1) = 'R'")
                ->whereRaw("SUBSTRING(kode_ring, 1, 4) = ?", [$kodeUnit]);
        });

        if ($level) {
            $query->where('level', $level);
        }

        $employees = $query->select('sap', 'name', 'level', 'desc_position')
            ->orderBy('name')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $employees
        ]);
    }

    /**
     * Store new employee position assignment
     */
    public function storeEmployeePosition(Request $request)
    {
        $request->validate([
            'id_position' => 'required|exists:posisi,id',
            'nik' => 'required|exists:employees,sap'
        ]);

        try {
            // Check if this position already has an employee
            $existing = EmployeePosition::where('id_position', $request->id_position)->first();

            if ($existing) {
                return response()->json([
                    'success' => false,
                    'message' => 'Posisi ini sudah terisi. Silakan hapus terlebih dahulu.'
                ], 400);
            }

            $employeePosition = EmployeePosition::create([
                'id_position' => $request->id_position,
                'nik' => $request->nik
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Employee berhasil ditambahkan ke posisi',
                'data' => $employeePosition->load('employee', 'posisi')
            ]);
        }
        catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan employee: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update employee position assignment
     */
    public function updateEmployeePosition(Request $request, $id)
    {
        $request->validate([
            'nik' => 'required|exists:employees,sap'
        ]);

        try {
            $employeePosition = EmployeePosition::findOrFail($id);

            $employeePosition->update([
                'nik' => $request->nik
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Employee position berhasil diupdate',
                'data' => $employeePosition->load('employee', 'posisi')
            ]);
        }
        catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengupdate employee position: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete employee position assignment
     */
    public function destroyEmployeePosition($id)
    {
        try {
            $employeePosition = EmployeePosition::findOrFail($id);
            $employeePosition->delete();

            return response()->json([
                'success' => true,
                'message' => 'Employee position berhasil dihapus'
            ]);
        }
        catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus employee position: ' . $e->getMessage()
            ], 500);
        }
    }
}