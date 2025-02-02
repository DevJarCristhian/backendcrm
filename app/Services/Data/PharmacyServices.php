<?php

namespace App\Services\Data;

use App\DTO\Data\Pharmacy\StorePharmacyDTO;
use App\DTO\GetDTO;
use App\Models\Pharmacy;
use Illuminate\Support\Facades\DB;

class PharmacyServices
{
    public function get(GetDTO $dto)
    {
        $query = Pharmacy::select(
            'f.id',
            // 'f.id_farmacia as pharmacyId',
            // 'ca.id as chainId',
            (DB::raw('COALESCE(ca.cadena, "-") as chainName')),
            // 'dept.id as departmentId',
            (DB::raw('COALESCE(dept.nombre, "-") as departmentName')),
            'f.sucursal as branch',
            'f.telefono as phone',
            (DB::raw('COALESCE(NULLIF(f.direccion, ""), "-") as address')),
            'f.email as email',
            'f.municipio as municipality',
            // 'f.fecha as date',
            // 'f.updated_at as updatedAt',
        )
            ->from('farmacias as f')
            ->leftJoin('cadena as ca', 'f.id_cadena', '=', 'ca.id')
            ->leftJoin('departamentos as dept', 'f.id_departamento', '=', 'dept.id');

        if ($dto->search) {
            $query->where(function ($query) use ($dto) {
                $query->where('f.sucursal', 'like', '%' . $dto->search . '%')
                    ->orWhere('f.id', 'like', '%' . $dto->search . '%');
            });
        }

        $data = $query->orderBy('id', 'desc')->paginate($dto->perPage, $dto->page);

        return $data;
    }

    public function store(StorePharmacyDTO $dto)
    {
        $pharmacy = new Pharmacy();
        $pharmacy->name = $dto->description;
        $pharmacy->save();
    }

    public function update(StorePharmacyDTO $dto, $id)
    {
        $pharmacy = Pharmacy::find($id);
        $pharmacy->name = $dto->description;
        // $pharmacy->status = $dto->status == false ? "inactive" : "active";
        $pharmacy->save();
    }
}
