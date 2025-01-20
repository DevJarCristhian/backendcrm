<?php

namespace App\Services\Data;

use App\DTO\Data\Institution\StoreInstitutionDTO;
use App\DTO\GetDTO;
use App\Models\Institution;
use Illuminate\Support\Facades\DB;

class InstitutionServices
{
    public function get(GetDTO $dto)
    {
        $query = Institution::select(
            'id',
            'nombre as name',
            (DB::raw('COALESCE(NULLIF(direccion, ""), "-") as address')),
            'fecha as date',
            'updated_at as updatedAt',
        );

        if ($dto->search) {
            $query->where(function ($query) use ($dto) {
                $query->where('nombre', 'like', '%' . $dto->search . '%');
            });
        }

        $data = $query->orderBy('nombre', 'asc')->paginate($dto->perPage, $dto->page);

        return $data;
    }

    public function store(StoreInstitutionDTO $dto)
    {
        $institution = new Institution();
        $institution->name = $dto->description;
        $institution->save();
    }

    public function update(StoreInstitutionDTO $dto, $id)
    {
        $institution = Institution::find($id);
        $institution->name = $dto->description;
        // $institution->status = $dto->status == false ? "inactive" : "active";
        $institution->save();
    }

    public function getRoles()
    {
        // return Role::select('id as value', 'description as label')->get();
    }
}
