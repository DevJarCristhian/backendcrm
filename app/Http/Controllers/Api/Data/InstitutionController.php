<?php

namespace App\Http\Controllers\Api\Data;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\DTO\GetDTO;
use App\Exports\Data\ExportInstitution;
use App\Services\Data\InstitutionServices;
use Maatwebsite\Excel\Facades\Excel;

class InstitutionController extends Controller
{
    private $institutionServices;

    public function __construct()
    {
        $this->institutionServices = new InstitutionServices();
    }

    public function get(Request $request)
    {
        $getDoctorsDTO = new GetDTO($request->all());
        $data = $this->institutionServices->get($getDoctorsDTO);
        return $this->response($data, 'Institution retrieved', 200);
    }

    public function exportToExcel()
    {
        return Excel::download(new ExportInstitution, 'Institutions.xlsx');
    }
}
