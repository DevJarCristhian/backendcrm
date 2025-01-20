<?php

namespace App\Exports\Sale;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class ExportProduct implements FromCollection, WithHeadings, WithMapping, WithEvents
{
    /**
     * @return \Illuminate\Support\Collection
     */

    public function collection()
    {
        return Product::select(
            'nombre',
            'descripcion',
            'descripcion_larga',
            'recibe',
            'condicion',
            'maximo_canjes',
            'linea',
            'estado',
            'guatemala',
            'honduras',
            'panama',
            'nicaragua',
            'costarica',
            // 'observacion',
            // 'unidad_medida'
        )->get();
        // ->from('cadena as c')
        // ->leftJoin('paises as p', 'c.id_pais', '=', 'p.id')->get();
    }

    public function map($product): array
    {
        return [
            $product->nombre,
            $product->descripcion,
            $product->descripcion_larga,
            $product->recibe,
            $product->condicion,
            $product->maximo_canjes,
            $product->linea,
            $product->estado == 1 ? 'Activo' : 'Inactivo',
            $product->guatemala,
            $product->honduras,
            $product->panama,
            $product->nicaragua,
            $product->costarica,
        ];
    }

    public function headings(): array
    {
        return [
            'Nombre',
            'Descripcion',
            'Descripcion Larga',
            'Recibe',
            'Condicion',
            'Maximo Canjes',
            'Linea',
            'Estado',
            'Guatemala',
            'Honduras',
            'Panama',
            'Nicaragua',
            'Costarica',
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet;
                $sheet->autoSize();
                $sheet->getStyle('A1:M1')->getFont()->setBold(true);
            }
        ];
    }
}
