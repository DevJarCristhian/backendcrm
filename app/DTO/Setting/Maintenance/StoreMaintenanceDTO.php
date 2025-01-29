<?php

namespace App\DTO\Setting\Maintenance;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class StoreMaintenanceDTO
{
    public int|null  $maintenanceId;
    public string|null $description;
    public bool $status;

    public function __construct(
        $request
    ) {
        $this->validate($request);
        $this->maintenanceId = $request['maintenanceId'] ?? null;
        $this->description = $request['description'];
        $this->status = $request['status'] ?? true;
    }

    private function validate(array $request): void
    {
        $validator = Validator::make($request, [
            'maintenanceId' => 'integer|nullable',
            'description' => 'string|nullable',
            'status' => 'boolean|nullable',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors()->toArray();
            throw new ValidationException($validator, response()->json($errors, 422));
        }
    }
}
