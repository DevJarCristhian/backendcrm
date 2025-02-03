<?php

namespace App\DTO\Sale\Opportunity;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class UpdateOpportunityDTO
{
    public int|null $reasonBuyId;
    public int|null $reasonAnulationId;
    public int|null $diagnosisId;
    public int|null $doseId;
    public int|null $treatmentTimeId;
    public string|null $lastDateTaken;
    public string|null $dateAbandonTreatment;
    public string|null $observations;

    public function __construct(
        $request
    ) {
        $this->validate($request);
        $this->reasonBuyId = $request['reasonBuyId'];
        $this->reasonAnulationId = $request['reasonAnulationId'];
        $this->diagnosisId = $request['diagnosisId'];
        $this->doseId = $request['doseId'];
        $this->treatmentTimeId = $request['treatmentTimeId'];
        $this->lastDateTaken = $request['lastDateTaken'];
        $this->dateAbandonTreatment = $request['dateAbandonTreatment'];
        $this->observations = $request['observations'];
    }

    private function validate(array $request): void
    {
        $validator = Validator::make($request, [
            'reasonBuyId' => 'integer|nullable',
            'reasonAnulationId' => 'integer|nullable',
            'diagnosisId' => 'integer|nullable',
            'doseId' => 'integer|nullable',
            'treatmentTimeId' => 'integer|nullable',
            'lastDateTaken' => 'string|nullable',
            'dateAbandonTreatment' => 'string|nullable',
            'observations' => 'string|nullable',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors()->toArray();
            throw new ValidationException($validator, response()->json($errors, 422));
        }
    }
}
