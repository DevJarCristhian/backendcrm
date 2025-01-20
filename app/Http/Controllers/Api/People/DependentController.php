<?php

namespace App\Http\Controllers\Api\People;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\DTO\People\Dependent\StoreDependentDTO;
use App\DTO\GetDTO;
use App\Services\People\DependentServices;
use Illuminate\Support\Facades\DB;
use App\Exports\People\ExportDependent;
use Maatwebsite\Excel\Facades\Excel;

class DependentController extends Controller
{
    private $dependentServices;

    public function __construct()
    {
        $this->dependentServices = new DependentServices();
    }

    public function get(Request $request)
    {
        // info($request->all());
        $getDependentsDTO = new GetDTO($request->all());
        $data = $this->dependentServices->get($getDependentsDTO);
        return $this->response($data, 'Dependents retrieved', 200);
    }

    public function exportToExcel()
    {
        return Excel::download(new ExportDependent, 'Dependents.xlsx');
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $storeDependentDTO = new StoreDependentDTO($request->all());
            $this->dependentServices->store($storeDependentDTO);
            DB::commit();
            return $this->response([], 'Dependent created', 201);
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
            $updateDependentDTO = new StoreDependentDTO($request->all(), $id);
            $this->dependentServices->update($updateDependentDTO, $id);
            DB::commit();
            return $this->response([], 'Dependent updated', 200);
        } catch (\Exception $e) {
            info($e);
            DB::rollBack();
            return $this->response([], 'Algo salió mal', 500);
        }
    }

    public function getRoles()
    {
        $roles = $this->dependentServices->getRoles();
        return $this->response($roles, 'Roles retrieved', 200);
    }
}
