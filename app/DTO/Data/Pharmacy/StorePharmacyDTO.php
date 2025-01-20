<?php

namespace App\DTO\Data\Pharmacy;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class StorePharmacyDTO
{
    public string|null $description;
    public function __construct(
        $request
    ) {
        $this->validate($request);

        $this->description = $request['description'];
    }

    private function validate(array $request): void
    {
        $validator = Validator::make($request, [
            'description' => 'string|nullable',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors()->toArray();
            throw new ValidationException($validator, response()->json($errors, 422));
        }
    }
}
