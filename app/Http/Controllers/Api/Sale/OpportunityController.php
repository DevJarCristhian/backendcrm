<?php

namespace App\Http\Controllers\Api\Sale;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\DTO\GetDTO;
use App\Exports\Sale\ExportOpportunity;
use App\Services\Sale\OpportunityServices;
use Maatwebsite\Excel\Facades\Excel;

class OpportunityController extends Controller
{
    private $opportunityServices;

    public function __construct()
    {
        $this->opportunityServices = new OpportunityServices();
    }

    public function get(Request $request)
    {
        $getDoctorsDTO = new GetDTO($request->all());
        $data = $this->opportunityServices->get($getDoctorsDTO);
        return $this->response($data, 'Opportunities retrieved', 200);
    }
    public function exportToExcel()
    {
        return Excel::download(new ExportOpportunity, 'Opportunities.xlsx');
    }
}
