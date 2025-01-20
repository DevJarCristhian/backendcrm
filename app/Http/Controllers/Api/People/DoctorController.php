<?php

namespace App\Http\Controllers\Api\People;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// use App\DTO\People\Doctor\StoreDoctorDTO;
use App\DTO\GetDTO;
use App\Services\People\DoctorServices;
use App\Exports\People\ExportDoctor;
use Maatwebsite\Excel\Facades\Excel;

class DoctorController extends Controller
{
    private $doctorServices;

    public function __construct()
    {
        $this->doctorServices = new DoctorServices();
    }

    public function get(Request $request)
    {
        $getDoctorsDTO = new GetDTO($request->all());
        $data = $this->doctorServices->get($getDoctorsDTO);
        return $this->response($data, 'Doctors retrieved', 200);
    }

    public function exportToExcel()
    {
        return Excel::download(new ExportDoctor, 'Doctors.xlsx');
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
