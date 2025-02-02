<?php

namespace App\Services\People;

use App\DTO\People\Dependent\StoreDependentDTO;
use App\DTO\GetDTO;
use App\Models\Dependent;
use Illuminate\Support\Facades\DB;

class DependentServices
{
    public function get(GetDTO $dto)
    {

        $query = Dependent::select(
            'd.id',
            // 'dept.id as departmentId',
            'dept.nombre as departmentName',
            // 'p.id as country',
            'p.nombre as countryName',
            DB::raw('CONCAT(d.nombre, " ", d.apellido) as fullName'),
            'd.sexo as gender',
            'd.direccion as address',
            'd.correo_electronico as email',
            'd.celular as phone',
            (DB::raw('COALESCE(d.fecha_nacimiento, "-") as birthDate')),
            'd.numero_documento as documentNumber',
            'd.fecha_inscripcion as enrollmentDate',
            // 'd.updated_at as updatedAt'
        )
            ->from('dependientes as d')
            ->leftJoin('paises as p', 'd.pais', '=', 'p.id')
            ->leftJoin('departamentos as dept', 'd.id_departamento', '=', 'dept.id');

        if ($dto->search) {
            $query->where(function ($query) use ($dto) {
                $query->where('d.nombre', 'like', '%' . $dto->search . '%')
                    ->orWhere('d.apellido', 'like', '%' . $dto->search . '%')
                    ->orWhere('dept.nombre', 'like', '%' . $dto->search . '%')
                    ->orWhere('d.numero_documento', 'like', '%' . $dto->search . '%');
            });
        }

        $data = $query->orderBy('id', 'desc')->paginate($dto->perPage, $dto->page);

        return $data;
    }

    public function store(StoreDependentDTO $dto)
    {
        $dependent = new Dependent();
        $dependent->name = $dto->description;
        $dependent->save();
    }

    public function update(StoreDependentDTO $dto, $id)
    {
        $dependent = Dependent::find($id);
        $dependent->name = $dto->description;
        // $dependent->status = $dto->status == false ? "inactive" : "active";
        $dependent->save();
    }

    public function getRoles()
    {
        // return Role::select('id as value', 'description as label')->get();
    }
}
