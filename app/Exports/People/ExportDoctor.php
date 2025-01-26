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
            'd.nombre',
            'd.direccion_doctor',
            'dept.nombre as departamento',
            'mun.nombre as municipio',
            'p.nombre as pais',
            'd.fecha',
        )
            ->from('doctores as d')
            ->leftJoin('paises as p', 'd.id_pais', '=', 'p.id')
            ->leftJoin('departamentos as dept', 'd.id_departamento', '=', 'dept.id')
            ->leftJoin('municipio as mun', 'd.id_municipio', '=', 'mun.id')->get();
    }

    public function map($doctor): array
    {
        return [
            $doctor->nombre,
            $doctor->direccion_doctor,
            $doctor->departamento,
            $doctor->municipio,
            $doctor->pais,
            $doctor->fecha,
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
