<?php

namespace App\Exports\People;

use App\Models\Visitor;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class ExportVisitor implements FromCollection, WithHeadings, WithMapping, WithEvents
{
    /**
     * @return \Illuminate\Support\Collection
     */

    public function collection()
    {
        return Visitor::select(
            'v.nombre',
            'p.nombre as pais',
            'v.fecha',
        )
            ->from('visitadores as v')
            ->leftJoin('paises as p', 'v.id_pais', '=', 'p.id')->get();
    }

    public function map($visitor): array
    {
        return [
            $visitor->nombre,
            $visitor->pais,
            $visitor->fecha,
        ];
    }

    public function headings(): array
    {
        return [
            'Nombre',
            'Pais',
            'Fecha Inscripcion',
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet;
                $sheet->autoSize();
                $sheet->getStyle('A1:F1')->getFont()->setBold(true);
            }
        ];
    }
}
