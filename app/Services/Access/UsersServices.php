<?php

namespace App\Services\Access;

use App\DTO\Access\Users\StoreUserDTO;
use App\DTO\Access\Users\UpdateUserDTO;
use App\Models\User;
use App\Models\Role;
use App\DTO\GetDTO;
use Illuminate\Support\Facades\DB;

class UsersServices
{
    public function get(GetDTO $dto)
    {
        $query = User::selectFields()->with('role');
        if ($dto->search) {
            $query->where(function ($query) use ($dto) {
                $query->where('name', 'like', '%' . $dto->search . '%')
                    ->orWhere('email', 'like', '%' . $dto->search . '%');
            });
        }
        $data = $query->orderBy('created_at', 'desc')->paginate($dto->perPage, $dto->page);
        return $data;
    }

    public function store(StoreUserDTO $dto)
    {
        $user = new User();
        $user->name = $dto->name;
        $user->email = $dto->email;
        $user->password = bcrypt($dto->password);
        $user->role_id = $dto->role_id;
        $user->save();
    }

    public function update(UpdateUserDTO $dto, $id)
    {
        $user = User::find($id);
        $user->name = $dto->name;
        $user->email = $dto->email;
        $user->password = $dto->password ? bcrypt($dto->password) : $user->password;
        $user->status = $dto->status == false ? "inactive" : "active";
        $user->role_id = $dto->role_id;
        $user->save();
    }

    public function getRoles()
    {
        return Role::select('id as value', 'description as label')->get();
    }

    // public function delete($id)
    // {
    //     $user = User::find($id);
    //     $user->status = $user->status == "active" ? "inactive" : "active";
    //     $user->save();
    // }
}
