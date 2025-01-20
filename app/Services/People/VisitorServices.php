<?php

namespace App\Services\People;

use App\DTO\People\Visitor\StoreVisitorDTO;
use App\DTO\GetDTO;
use App\Models\Visitor;
use Illuminate\Support\Facades\DB;

class VisitorServices
{
    public function get(GetDTO $dto)
    {
        $query = Visitor::select(
            'v.id',
            'p.id as country',
            (DB::raw('COALESCE(p.nombre, "-") as countryName')),
            'v.nombre as name',
            'v.fecha as date',
            'v.updated_at as updatedAt',
        )
            ->from('visitadores as v')
            ->leftJoin('paises as p', 'v.id_pais', '=', 'p.id');

        if ($dto->search) {
            $query->where(function ($query) use ($dto) {
                $query->where('d.nombre', 'like', '%' . $dto->search . '%');
                // ->orWhere('mun.nombre', 'like', '%' . $dto->search . '%');
            });
        }

        $data = $query->orderBy('id', 'desc')->paginate($dto->perPage, $dto->page);

        return $data;
    }

    public function store(StoreVisitorDTO $dto)
    {
        $visitor = new Visitor();
        $visitor->name = $dto->description;
        $visitor->save();
    }

    public function update(StoreVisitorDTO $dto, $id)
    {
        $visitor = Visitor::find($id);
        $visitor->name = $dto->description;
        // $visitor->status = $dto->status == false ? "inactive" : "active";
        $visitor->save();
    }

    public function getRoles()
    {
        // return Role::select('id as value', 'description as label')->get();
    }
}
