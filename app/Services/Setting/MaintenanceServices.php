<?php

namespace App\Services\Setting;

use App\DTO\Setting\Maintenance\StoreMaintenanceDTO;
use Illuminate\Support\Facades\DB;
use App\DTO\GetDTO;


class MaintenanceServices
{
    public function get()
    {
        $query = DB::table('mantenimiento_padres')
            ->select(
                'id as value',
                'descripcion as label',
            )->orderBy('id', 'desc')->get();
        return $query;
    }

    public function store(StoreMaintenanceDTO $dto)
    {
        $id = DB::table('mantenimiento_padres')->insertGetId([
            'descripcion' => $dto->description,
            'estado' => 1,
        ]);
        $new = (object) [
            'value' => (int)$id,
            'label' => $dto->description,
        ];
        return $new;
    }

    public function update(StoreMaintenanceDTO $dto, $id)
    {
        DB::table('mantenimiento_padres')
            ->where('id', $id)
            ->update([
                'descripcion' => $dto->description,
                'estado' => 1,
            ]);

        $new = (object) [
            'value' => (int)$id,
            'label' => $dto->description,
        ];
        return $new;
    }

    public function getChild(GetDTO $dto)
    {
        $query = DB::table('mantenimiento_hijos as mh')
            ->select(
                'mh.id',
                'mh.id_cab as maintenanceId',
                'mp.descripcion as category',
                'mh.descripcion as description',
                'mh.estado as status'
            )
            ->join('mantenimiento_padres as mp', 'mp.id', '=', 'mh.id_cab');

        if ($dto->search) {
            $query->where(function ($query) use ($dto) {
                $query->where('mh.descripcion', 'like', '%' . $dto->search . '%')
                    ->orWhere('mp.descripcion', 'like', '%' . $dto->search . '%');
            });
        }

        $data = $query->orderBy('mp.descripcion', 'asc')->orderBy('mh.descripcion', 'asc')
            ->paginate($dto->perPage, $dto->page);
        return $data;
    }

    public function storeChild(StoreMaintenanceDTO $dto)
    {
        $gid = DB::table('mantenimiento_hijos')
            ->where('id_cab', $dto->maintenanceId)
            ->orderBy('id', 'DESC')
            ->value('id');

        $gid = $gid ? $gid + 1 : 1;

        DB::table('mantenimiento_hijos')->insert([
            'id_cab' => $dto->maintenanceId,
            'descripcion' => $dto->description,
            'estado' => $dto->status ? 1 : 0,
            'id' => $gid
        ]);

        return true;
    }

    public function updateChild(StoreMaintenanceDTO $dto, $id)
    {
        DB::table('mantenimiento_hijos')
            ->where('id_cab', $dto->maintenanceId)
            ->where('id', $id)
            ->update([
                'descripcion' => $dto->description,
                'estado' => $dto->status ? 1 : 0,
            ]);

        return true;
    }
}
