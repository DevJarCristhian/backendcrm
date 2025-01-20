<?php

namespace App\Services\People;

use App\DTO\People\Doctor\StoreDoctorDTO;
use App\DTO\GetDTO;
use App\Models\Doctor;
use Illuminate\Support\Facades\DB;

class DoctorServices
{
    public function get(GetDTO $dto)
    {

        $query = Doctor::select(
            'd.id',
            'dept.id as departmentId',
            (DB::raw('COALESCE(dept.nombre, "-") as departmentName')),
            'p.id as country',
            (DB::raw('COALESCE(p.nombre, "-") as countryName')),
            'mun.id as cityId',
            (DB::raw('COALESCE(mun.nombre, "-") as city')),
            'd.nombre as name',
            'd.direccion_doctor as address',
            'd.fecha as date',
            'd.updated_at as updatedAt',
        )
            ->from('doctores as d')
            ->leftJoin('paises as p', 'd.id_pais', '=', 'p.id')
            ->leftJoin('departamentos as dept', 'd.id_departamento', '=', 'dept.id')
            ->leftJoin('municipio as mun', 'd.id_municipio', '=', 'mun.id');

        if ($dto->search) {
            $query->where(function ($query) use ($dto) {
                $query->where('d.nombre', 'like', '%' . $dto->search . '%')
                    ->orWhere('mun.nombre', 'like', '%' . $dto->search . '%');
            });
        }

        $data = $query->orderBy('id', 'desc')->paginate($dto->perPage, $dto->page);

        return $data;
    }

    public function store(StoreDoctorDTO $dto)
    {
        $doctor = new Doctor();
        $doctor->name = $dto->description;
        $doctor->save();
    }

    public function update(StoreDoctorDTO $dto, $id)
    {
        $doctor = Doctor::find($id);
        $doctor->name = $dto->description;
        // $doctor->status = $dto->status == false ? "inactive" : "active";
        $doctor->save();
    }

    public function getRoles()
    {
        // return Role::select('id as value', 'description as label')->get();
    }
}
