<?php

namespace App\Services\Access;

use App\DTO\Access\Roles\StoreRoleDTO;
use App\Models\Role;
use App\DTO\GetDTO;
use Illuminate\Support\Facades\DB;

class RolesServices
{
    public function get(GetDTO $dto)
    {
        $query = Role::select('id', 'description', 'created_at', 'updated_at')
            ->with(['permissions' => function ($query) {
                $query->join('permissions as p', 'role_permission.permission_id', '=', 'p.id')
                    ->select('role_permission.permission_id');
            }])
            ->get()
            ->map(function ($role) {
                return [
                    'id' => $role->id,
                    'description' => $role->description,
                    'created_at' => $role->created_at,
                    'updated_at' => $role->updated_at,
                    'permissions' => $role->permissions->pluck('permission_id'),
                ];
            });

        // $data = $query->orderBy('created_at', 'desc')->paginate($dto->perPage, $dto->page);
        return $query;
    }

    public function store(StoreRoleDTO $dto)
    {
        $role = new Role();
        $role->description = $dto->description;
        $role->save();

        $permissions = array_filter($dto->permissions, 'is_int');
        foreach ($permissions as $permission) {
            DB::table('role_permission')->insert([
                'role_id' => $role->id,
                'permission_id' => $permission,
            ]);
        }
    }

    public function update(StoreRoleDTO $dto, $id)
    {
        DB::table('role_permission')->where('role_id', $id)->delete();

        $role = Role::find($id);
        $role->description = $dto->description;
        $role->save();

        $permissions = array_filter($dto->permissions, 'is_int');
        foreach ($permissions as $permission) {
            DB::table('role_permission')->insert([
                'role_id' => $id,
                'permission_id' => $permission,
            ]);
        }
    }

    public function getPermissions()
    {
        $query = DB::table('permissions as p')
            ->select('p.id', 'p.description as label', 'p.description as key')
            ->addSelect(
                DB::raw(
                    '(
                SELECT JSON_ARRAYAGG(
                    JSON_OBJECT(
                        "key", dp.id,
                        "label", dp.description
                    )
                )
                FROM permissions as dp
                WHERE dp.parent = p.id
            ) AS children'
                )
            )
            ->whereNull('p.parent')
            ->groupBy('p.id', 'p.description', 'p.parent')
            ->get();


        return $query;
    }
}
