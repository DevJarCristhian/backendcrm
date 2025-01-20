<?php

namespace App\Http\Controllers\Api\Access;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\DTO\GetDTO;
use App\DTO\Access\Users\StoreUserDTO;
use App\DTO\Access\Users\UpdateUserDTO;
use App\Exports\Access\ExportUser;
use App\Services\Access\UsersServices;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class UsersController extends Controller
{
    private $usersServices;

    public function __construct()
    {
        $this->usersServices = new UsersServices();
    }

    public function get(Request $request)
    {
        $getUsersDTO = new GetDTO($request->all());
        $data = $this->usersServices->get($getUsersDTO);
        return $this->response($data, 'Users retrieved', 200);
    }

    public function exportToExcel()
    {
        return Excel::download(new ExportUser, 'User.xlsx');
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $storeUserDTO = new StoreUserDTO($request->all());
            $this->usersServices->store($storeUserDTO);
            DB::commit();
            return $this->response([], 'User created', 201);
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
            $updateUserDTO = new UpdateUserDTO($request->all(), $id);
            $this->usersServices->update($updateUserDTO, $id);
            DB::commit();
            return $this->response([], 'User updated', 200);
        } catch (\Exception $e) {
            info($e);
            DB::rollBack();
            return $this->response([], 'Algo salió mal', 500);
        }
    }

    public function getRoles()
    {
        $roles = $this->usersServices->getRoles();
        return $this->response($roles, 'Roles retrieved', 200);
    }

    // public function delete($id)
    // {
    //     DB::beginTransaction();
    //     try {
    //         $this->usersServices->delete($id);
    //         DB::commit();
    //         return $this->response([], 'User deleted', 200);
    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         return $this->response([], 'Algo salió mal', 500);
    //     }
    // }
}
