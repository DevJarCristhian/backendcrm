<?php

namespace App\Exports\Data;

use App\Models\Chain;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class ExportChain implements FromCollection, WithHeadings, WithMapping, WithEvents
{
    /**
     * @return \Illuminate\Support\Collection
     */

    public function collection()
    {
        return Chain::select(
            'c.cadena',
            'p.nombre as pais'
        )
            ->from('cadena as c')
            ->leftJoin('paises as p', 'c.id_pais', '=', 'p.id')->get();
    }

    public function map($chain): array
    {
        return [
            $chain->cadena,
            $chain->pais
        ];
    }

    public function headings(): array
    {
        return [
            'Cadena',
            'Pais',
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet;
                $sheet->autoSize();
                $sheet->getStyle('A1:B1')->getFont()->setBold(true);
            }
        ];
    }
}
