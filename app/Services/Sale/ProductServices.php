<?php

namespace App\Services\Sale;

use App\DTO\Sale\Product\StoreProductDTO;
use App\DTO\GetDTO;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class ProductServices
{
    public function get(GetDTO $dto)
    {
        $query = Product::select(
            'condicion as condition',
            'descripcion as description',
            'descripcion_larga as longDescription',
            'estado as status',
            'costarica',
            'nicaragua',
            'panama',
            'honduras',
            'guatemala',
            'id',
            'linea as line',
            'maximo_canjes as maxRedemptions',
            'nombre as name',
            'observacion as observation',
            'recibe as receive',
            'unidad_medida as unitMeasure'
        );

        if ($dto->search) {
            $query->where(function ($query) use ($dto) {
                $query->where('nombre', 'like', '%' . $dto->search . '%')
                    ->orWhere('descripcion', 'like', '%' . $dto->search . '%')
                    ->orWhere('descripcion_larga', 'like', '%' . $dto->search . '%');
            });
        }

        $data = $query->orderBy('nombre', 'asc')->paginate($dto->perPage, $dto->page);

        return $data;
    }

    public function store(StoreProductDTO $dto)
    {
        $product = new Product();
        $product->name = $dto->description;
        $product->save();
    }

    public function update(StoreProductDTO $dto, $id)
    {
        $product = Product::find($id);
        $product->name = $dto->description;
        // $product->status = $dto->status == false ? "inactive" : "active";
        $product->save();
    }

    public function getRoles()
    {
        // return Role::select('id as value', 'description as label')->get();
    }
}
