<?php

namespace App\Http\Controllers\Api\All;

use App\Http\Controllers\Controller;
// use Illuminate\Http\Request;
// use App\DTO\GetDTO;
use App\Services\All\AllServices;

class AllController extends Controller
{
    private $allServices;

    public function __construct()
    {
        $this->allServices = new AllServices();
    }

    public function getMaintenance()
    {
        $data = $this->allServices->getMaintenance();
        return $this->response($data, 'Maintenance retrieved', 200);
    }
}
