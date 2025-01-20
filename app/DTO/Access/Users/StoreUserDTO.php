<?php

namespace App\DTO\Access\Users;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class StoreUserDTO
{
    public string $name;
    public string $email;
    public string $password;
    public int $role_id;
    public function __construct(
        $request
    ) {
        $this->validate($request);

        $this->name = $request['name'];
        $this->email = $request['email'];
        $this->password = $request['password'];
        $this->role_id = $request['role_id'];
    }

    private function validate(array $request): void
    {
        $validator = Validator::make($request, [
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'role_id' => 'required|integer|exists:roles,id',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors()->toArray();
            throw new ValidationException($validator, response()->json($errors, 422));
        }
    }
}
