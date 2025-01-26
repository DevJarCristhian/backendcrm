<?php

namespace App\Services;

use App\Models\User;
use App\DTO\Auth\CreateTokenAuthDTO;
use App\Models\Permission;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class AuthServices
{
    public function createToken(CreateTokenAuthDTO $dto)
    {
        $user = User::where('email', $dto->email)->first();
        if (!$user || !Hash::check($dto->password, $user->password)) {
            return null;
        }
        Log::info("User: $user->name logged in");
        $token = $user->createToken('auth_token')->plainTextToken;
        Log::info("No paso Token: $token");
        return $token;
    }

    public function getUser()
    {
        $data = User::selectFields()
            ->with('role')
            ->where('status', 'active')
            ->where('id', auth()->user()->id)
            // ->where('id', Auth::id())
            ->get()
            ->map(function ($user) {
                $permissions = Permission::with(['children' => function ($query) {
                    $query->select('id', 'parent', 'name');
                }])
                    ->whereNull('parent')
                    ->whereHas('roles', function ($query) {
                        $query->where('role_id', auth()->user()->role_id);
                    })
                    ->get(['id', 'name'])
                    ->map(function ($permission) {
                        $permission->actions = $permission->children->pluck('name');
                        unset($permission->children);
                        return $permission;
                    });

                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->role->description,
                    'permissions' => $permissions,
                ];
            });

        return $data->first();
    }

    public function logout()
    {
        DB::table('personal_access_tokens')
            ->where('tokenable_id', auth()->user()->id)
            ->delete();
    }
}
