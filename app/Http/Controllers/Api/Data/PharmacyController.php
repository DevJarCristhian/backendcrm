<?php

namespace App\Http\Controllers\Api\Data;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\DTO\GetDTO;
use App\Exports\Data\ExportPharmacy;
use App\Services\Data\PharmacyServices;
use Maatwebsite\Excel\Facades\Excel;

class PharmacyController extends Controller
{
    private $pharmacyServices;

    public function __construct()
    {
        $this->pharmacyServices = new PharmacyServices();
    }

    public function get(Request $request)
    {
        $getDoctorsDTO = new GetDTO($request->all());
        $data = $this->pharmacyServices->get($getDoctorsDTO);
        return $this->response($data, 'Pharmacies retrieved', 200);
    }

    public function exportToExcel()
    {
        return Excel::download(new ExportPharmacy, 'Pharmacies.xlsx');
    }
}
