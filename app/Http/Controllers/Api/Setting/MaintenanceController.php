<?php

namespace App\Http\Controllers\Api\Setting;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\DTO\GetDTO;
use App\DTO\Setting\Maintenance\StoreMaintenanceDTO;
use App\Services\Setting\MaintenanceServices;
use Illuminate\Support\Facades\DB;

class MaintenanceController extends Controller
{
    private $maintenanceServices;

    public function __construct()
    {
        $this->maintenanceServices = new MaintenanceServices();
    }

    public function get()
    {
        $data = $this->maintenanceServices->get();
        return $this->response($data, 'Maintenances retrieved', 200);
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $storeMaintenanceDTO = new StoreMaintenanceDTO($request->all());
            $data =  $this->maintenanceServices->store($storeMaintenanceDTO);
            DB::commit();
            return $this->response($data, 'Maintenance created', 201);
        } catch (\Exception $e) {
            info($e);
            DB::rollBack();
            return $this->response([], 'Algo salió mal', 500);
        }
    }

    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $storeMaintenanceDTO = new StoreMaintenanceDTO($request->all(), $id);
            $data = $this->maintenanceServices->update($storeMaintenanceDTO, $id);
            DB::commit();
            return $this->response($data, 'Maintenance updated', 200);
        } catch (\Exception $e) {
            info($e);
            DB::rollBack();
            return $this->response([], 'Algo salió mal', 500);
        }
    }

    public function getChild(Request $request)
    {
        $getmaintenanceDTO = new GetDTO($request->all());
        $data = $this->maintenanceServices->getChild($getmaintenanceDTO);
        return $this->response($data, 'Maintenances Child retrieved', 200);
    }

    public function storeChild(Request $request)
    {
        DB::beginTransaction();
        try {
            $storeMaintenanceDTO = new StoreMaintenanceDTO($request->all());
            $this->maintenanceServices->storeChild($storeMaintenanceDTO);
            DB::commit();
            return $this->response([], 'Maintenance Child created', 201);
        } catch (\Exception $e) {
            info($e);
            DB::rollBack();
            return $this->response([], 'Algo salió mal', 500);
        }
    }

    public function updateChild(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $storeMaintenanceDTO = new StoreMaintenanceDTO($request->all(), $id);
            $this->maintenanceServices->updateChild($storeMaintenanceDTO, $id);
            DB::commit();
            return $this->response([], 'Maintenance Child updated', 200);
        } catch (\Exception $e) {
            info($e);
            DB::rollBack();
            return $this->response([], 'Algo salió mal', 500);
        }
    }
}
