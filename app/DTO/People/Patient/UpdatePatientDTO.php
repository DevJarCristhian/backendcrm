<?php

namespace App\DTO\People\Patient;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class UpdatePatientDTO
{
    public int|null $gender;
    public int|null $civilStatus;
    public int|null $patientStatus;
    public int|null $patientType;
    public int|null $contactName;
    public int|null $description;

    public function __construct(
        $request
    ) {
        $this->validate($request);
        $this->gender = $request['gender'];
        $this->civilStatus = $request['civilStatus'];
        $this->patientStatus = $request['patientStatus'];
        $this->patientType = $request['patientType'];
        $this->contactName = $request['contactName'];
        $this->description = $request['description'];
    }

    private function validate(array $request): void
    {
        $validator = Validator::make($request, [
            'gender' => 'integer|nullable',
            'civilStatus' => 'integer|nullable',
            'patientStatus' => 'integer|nullable',
            'patientType' => 'integer|nullable',
            'contactName' => 'integer|nullable',
            'description' => 'integer|nullable',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors()->toArray();
            throw new ValidationException($validator, response()->json($errors, 422));
        }
    }
}
