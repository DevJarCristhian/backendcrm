<?php

namespace App\Http\Controllers\Api\People;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// use App\DTO\People\Patient\StorePatientDTO;
use App\DTO\People\Patient\GetPatientDTO;
use App\Services\People\PatientServices;
use App\Exports\People\ExportPatient;
use Maatwebsite\Excel\Facades\Excel;

class PatientController extends Controller
{
    private $patientServices;

    public function __construct()
    {
        $this->patientServices = new PatientServices();
    }

    public function get(Request $request)
    {
        $getPatientsDTO = new GetPatientDTO($request->all());
        $data = $this->patientServices->get($getPatientsDTO);
        return $this->response($data, 'Patients retrieved', 200);
    }

    public function exportToExcel()
    {
        return Excel::download(new ExportPatient, 'Patients.xlsx');
    }

    // public function store(Request $request)
    // {
    //     DB::beginTransaction();
    //     try {
    //         $storeDependentDTO = new StoreDependentDTO($request->all());
    //         $this->dependentServices->store($storeDependentDTO);
    //         DB::commit();
    //         return $this->response([], 'Dependent created', 201);
    //     } catch (\Exception $e) {
    //         info($e);
    //         DB::rollBack();
    //         return $this->response([], 'Algo salió mal', 500);
    //     }
    // }

    // public function update(Request $request, $id)
    // {
    //     DB::beginTransaction();
    //     try {
    //         $updateDependentDTO = new StoreDependentDTO($request->all(), $id);
    //         $this->dependentServices->update($updateDependentDTO, $id);
    //         DB::commit();
    //         return $this->response([], 'Dependent updated', 200);
    //     } catch (\Exception $e) {
    //         info($e);
    //         DB::rollBack();
    //         return $this->response([], 'Algo salió mal', 500);
    //     }
    // }

    // public function getRoles()
    // {
    //     $roles = $this->dependentServices->getRoles();
    //     return $this->response($roles, 'Roles retrieved', 200);
    // }
}
