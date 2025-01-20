<?php

namespace App\Services\Data;

use App\DTO\Data\Chain\StoreChainDTO;
use App\DTO\GetDTO;
use App\Models\Chain;
use Illuminate\Support\Facades\DB;

class ChainServices
{
    public function get(GetDTO $dto)
    {
        $query = Chain::select(
            'c.id',
            'c.cadena as chain',
            (DB::raw('COALESCE(p.nombre, "-") as countryName')),
        )
            ->from('cadena as c')
            ->leftJoin('paises as p', 'c.id_pais', '=', 'p.id');

        if ($dto->search) {
            $query->where(function ($query) use ($dto) {
                $query->where('c.cadena', 'like', '%' . $dto->search . '%');
            });
        }

        $data = $query->orderBy('cadena', 'asc')->paginate($dto->perPage, $dto->page);

        return $data;
    }

    public function store(StoreChainDTO $dto)
    {
        $chain = new Chain();
        $chain->name = $dto->description;
        $chain->save();
    }

    public function update(StoreChainDTO $dto, $id)
    {
        $chain = Chain::find($id);
        $chain->name = $dto->description;
        // $chain->status = $dto->status == false ? "inactive" : "active";
        $chain->save();
    }

    public function getRoles()
    {
        // return Role::select('id as value', 'description as label')->get();
    }
}
