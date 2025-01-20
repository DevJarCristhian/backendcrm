<?php

namespace App\Exports\Data;

use App\Models\Institution;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class ExportInstitution implements FromCollection, WithHeadings, WithMapping, WithEvents
{
    /**
     * @return \Illuminate\Support\Collection
     */

    public function collection()
    {
        return Institution::select(
            'nombre',
            'direccion',
            'fecha'
        )->get();
    }

    public function map($institution): array
    {
        return [
            $institution->nombre,
            $institution->direccion,
            $institution->fecha,
        ];
    }

    public function headings(): array
    {
        return [
            'Nombre',
            'Direccion',
            'Fecha de Creacion',
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet;
                $sheet->autoSize();
                $sheet->getStyle('A1:C1')->getFont()->setBold(true);
            }
        ];
    }
}
