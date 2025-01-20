<?php

namespace App\DTO\Access\Users;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rule;

class UpdateUserDTO
{
    public string $name;
    public string $email;
    public string|null $password;
    public int $role_id;
    public bool $status;
    public function __construct(
        $request,
        $userId
    ) {
        $this->validate($request, $userId);

        $this->name = $request['name'];
        $this->email = $request['email'];
        $this->password = $request['password'] ?? null;
        $this->role_id = $request['role_id'];
        $this->status = $request['status'] ?? 'active';
    }

    private function validate(array $request, $userId): void
    {
        $validator = Validator::make($request, [
            'name' => 'required|string',
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($userId)
            ],
            'password' => 'nullable|string|min:6',
            'role_id' => 'required|integer|exists:roles,id',
            'status' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors()->toArray();
            throw new ValidationException($validator, response()->json($errors, 422));
        }
    }
}
