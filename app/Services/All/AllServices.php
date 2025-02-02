<?php

namespace App\Services\All;

use Illuminate\Support\Facades\DB;

class AllServices
{
    public function getMaintenance()
    {
        $query = DB::table('mantenimiento_padres as mcab')
            ->select('mcab.id', 'mcab.descripcion', DB::raw('(
        SELECT JSON_ARRAYAGG(JSON_OBJECT("label", mdet.descripcion, "value", mdet.id))
        FROM mantenimiento_hijos AS mdet 
        WHERE mdet.id_cab = mcab.id
    ) as detalles'))
            ->groupBy('mcab.id', 'mcab.descripcion')
            ->get()->map(function ($item) {
                $item->detalles = json_decode($item->detalles, true);
                return $item;
            })->toArray();
        return $query;
    }
}
