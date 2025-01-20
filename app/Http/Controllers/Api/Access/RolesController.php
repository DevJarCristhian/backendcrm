<?php

namespace App\Http\Controllers\Api\Access;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\DTO\Access\Roles\StoreRoleDTO;
use App\Services\Access\RolesServices;
use App\DTO\GetDTO;
use Illuminate\Support\Facades\DB;

class RolesController extends Controller
{
    public $rolesServices;

    public function __construct()
    {
        $this->rolesServices = new RolesServices();
    }
    public function get(Request $request)
    {
        $getRoleDTO = new GetDTO($request->all());
        $roles = $this->rolesServices->get($getRoleDTO);
        return $this->response($roles, 'Roles retrieved', 200);
    }
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $storeRoleDTO = new StoreRoleDTO($request->all());
            $this->rolesServices->store($storeRoleDTO);
            DB::commit();
            return $this->response([], 'Role created', 201);
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
            $storeRoleDTO = new StoreRoleDTO($request->all());
            $this->rolesServices->update($storeRoleDTO, $id);
            DB::commit();
            return $this->response([], 'Role updated', 200);
        } catch (\Exception $e) {
            info($e);
            DB::rollBack();
            return $this->response([], 'Algo salió mal', 500);
        }
    }

    public function getPermissions()
    {
        $roles = $this->rolesServices->getPermissions();
        return $this->response($roles, 'Permissions retrieved', 200);
    }
}
