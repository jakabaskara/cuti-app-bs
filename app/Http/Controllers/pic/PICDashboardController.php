<?php

namespace App\Http\Controllers\pic;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PICDashboardController extends Controller
{
    public function index()
    {
        // Get API SAP
        // $response = Http::get('https://api.ptpn13.id/api/karpel', [
        //     'token' => 'OeUs6Z5YBEIlPKlR1R2HxwzOL11JQP232323IK8lzQzcaXI65nz9yYJYTzYEqRfGhExWIlvyM3K5SpgEy11MOc2DzrwJl4uhbtu50mXkrAW5NeG4SUFZq3rFbuWQomNi83uZuLdMDhUNPxpVk2r1Z5rHBou50GvEnewkjh9GgtWJIdqdEA5WulTDcmXFRIlwlPZbBFbDskl5JhzYyQ0Za8KCUsIxU4p8HZXsNObzZ23KAnuunzGAhWVMFXQJwejY'
        // ]);
        return view('pic.index');
    }
}
