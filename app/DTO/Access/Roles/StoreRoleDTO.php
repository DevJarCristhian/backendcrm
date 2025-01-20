<?php

namespace App\DTO\Access\Roles;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class StoreRoleDTO
{
    public string|null $description;
    public array|null $permissions;
    public function __construct(
        $request
    ) {
        $this->validate($request);
        $this->description = $request['description'];
        $this->permissions = $request['permissions'];
    }

    private function validate(array $request): void
    {
        $validator = Validator::make($request, [
            'description' => 'string|nullable',
            'permissions' => 'array|nullable',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors()->toArray();
            throw new ValidationException($validator, response()->json($errors, 422));
        }
    }
}
