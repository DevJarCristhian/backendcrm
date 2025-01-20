<?php

namespace App\Exports\Data;

use App\Models\Pharmacy;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class ExportPharmacy implements FromCollection, WithHeadings, WithMapping, WithEvents
{
    /**
     * @return \Illuminate\Support\Collection
     */

    public function collection()
    {
        return Pharmacy::select(
            'f.sucursal',
            'ca.cadena',
            'dept.nombre',
            'f.telefono',
            'f.email',
            'f.direccion',
        )
            ->from('farmacias as f')
            ->leftJoin('cadena as ca', 'f.id_cadena', '=', 'ca.id')
            ->leftJoin('departamentos as dept', 'f.id_departamento', '=', 'dept.id')->get();
    }

    public function map($pharmacy): array
    {
        return [
            $pharmacy->sucursal,
            $pharmacy->cadena,
            $pharmacy->nombre,
            $pharmacy->telefono,
            $pharmacy->email,
            $pharmacy->direccion,
        ];
    }

    public function headings(): array
    {
        return [
            'Sucursal',
            'Cadena',
            'Departamento',
            'Telefono',
            'Correo Electronico',
            'Direccion',
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
