<?php

namespace App\DTO\Auth;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class CreateTokenAuthDTO
{
    public string $email;
    public string $password;
    public function __construct(
        $request
    ) {
        $this->validate($request);

        $this->email = $request['email'];
        $this->password = $request['password'];
    }

    private function validate(array $request): void
    {
        $validator = Validator::make($request, [
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors()->toArray();
            throw new ValidationException($validator, response()->json($errors, 422));
        }
    }
}
