<?php

namespace App\Http\Controllers\Api\Sale;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\DTO\GetDTO;
use App\DTO\Sale\Opportunity\UpdateOpportunityDTO;
use App\Exports\Sale\ExportOpportunity;
use App\Services\Sale\OpportunityServices;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;

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

    public function getbyId($id)
    {
        $data = $this->opportunityServices->getById($id);
        return $this->response($data, 'Opportunity retrieved', 200);
    }

    public function exportToExcel()
    {
        return Excel::download(new ExportOpportunity, 'Opportunities.xlsx');
    }

    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $updateOpportunityDTO = new UpdateOpportunityDTO($request->all(), $id);
            $this->opportunityServices->update($updateOpportunityDTO, $id);
            DB::commit();
            return $this->response([], 'Opportunity updated', 200);
        } catch (\Exception $e) {
            info($e);
            DB::rollBack();
            return $this->response([], 'Algo salió mal', 500);
        }
    }
}
