<?php

namespace App\Http\Controllers\Api\Data;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\DTO\GetDTO;
use App\Exports\Data\ExportChain;
use App\Services\Data\ChainServices;
use Maatwebsite\Excel\Facades\Excel;

class ChainController extends Controller
{
    private $chainServices;

    public function __construct()
    {
        $this->chainServices = new ChainServices();
    }

    public function get(Request $request)
    {
        $getDoctorsDTO = new GetDTO($request->all());
        $data = $this->chainServices->get($getDoctorsDTO);
        return $this->response($data, 'Chains retrieved', 200);
    }

    public function exportToExcel()
    {
        return Excel::download(new ExportChain, 'Chains.xlsx');
    }
}
