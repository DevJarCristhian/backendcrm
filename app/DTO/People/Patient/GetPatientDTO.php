<?php

namespace App\DTO\People\Patient;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class GetPatientDTO
{
    public string|null $search;
    public int $perPage;
    public int $page;
    public function __construct($request)
    {
        $this->validate($request);

        $this->search = $request['search'] ?? null;
        $this->perPage = $request['perPage'] ?? null;
        $this->page = $request['page'] ?? null;
    }

    public function validate(array $request): void
    {
        $validator = Validator::make($request, [
            'search' => 'nullable|string',
            'perPage' => 'required|integer',
            'page' => 'required|integer',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors()->toArray();
            throw new ValidationException($validator, response()->json($errors, 422));
        }
    }
}
