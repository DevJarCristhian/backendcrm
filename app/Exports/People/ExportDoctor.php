<?php

namespace App\Exports\People;

use App\Models\Doctor;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class ExportDoctor implements FromCollection, WithHeadings, WithMapping, WithEvents
{
    /**
     * @return \Illuminate\Support\Collection
     */

    public function collection()
    {
        return Doctor::select(
            'd.nombre as Nombre',
            'd.direccion_doctor as Direccion',
            'dept.nombre as Departamento',
            'mun.nombre as Municipio',
            'p.nombre as Pais',
            'd.fecha as Fecha',
        )
            ->from('doctores as d')
            ->leftJoin('paises as p', 'd.id_pais', '=', 'p.id')
            ->leftJoin('departamentos as dept', 'd.id_departamento', '=', 'dept.id')
            ->leftJoin('municipio as mun', 'd.id_municipio', '=', 'mun.id')->get();
    }

    public function map($doctor): array
    {
        return [
            $doctor->Nombre,
            $doctor->Direccion,
            $doctor->Departamento,
            $doctor->MUnicipio,
            $doctor->Pais,
            $doctor->Fecha,
        ];
    }

    public function headings(): array
    {
        return [
            'Nombre',
            'Direccion',
            'Departamento',
            'Municipio',
            'Pais',
            'Fecha',
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
