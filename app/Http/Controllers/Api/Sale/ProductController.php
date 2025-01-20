<?php

namespace App\Http\Controllers\Api\Sale;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\DTO\GetDTO;
use App\Exports\Sale\ExportProduct;
use App\Services\Sale\ProductServices;
use Maatwebsite\Excel\Facades\Excel;

class ProductController extends Controller
{
    private $productServices;

    public function __construct()
    {
        $this->productServices = new ProductServices();
    }

    public function get(Request $request)
    {
        $getDoctorsDTO = new GetDTO($request->all());
        $data = $this->productServices->get($getDoctorsDTO);
        return $this->response($data, 'Products retrieved', 200);
    }
    public function exportToExcel()
    {
        return Excel::download(new ExportProduct, 'Products.xlsx');
    }
}
