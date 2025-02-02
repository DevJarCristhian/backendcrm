<?php

namespace App\Exports\People;

use App\Models\Dependent;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class ExportDependent implements FromCollection, WithHeadings, WithMapping, WithEvents
{
    /**
     * @return \Illuminate\Support\Collection
     */

    public function collection()
    {
        return Dependent::select(
            'd.nombre',
            'd.apellido',
            'd.direccion',
            'd.correo_electronico',
            'd.sexo',
            'd.celular',
            'dept.nombre as departamento',
            'p.nombre as pais',
            'd.fecha_nacimiento',
            'd.numero_documento',
            'd.fecha_inscripcion'
        )
            ->from('dependientes as d')
            ->leftJoin('paises as p', 'd.pais', '=', 'p.id')
            ->leftJoin('departamentos as dept', 'd.id_departamento', '=', 'dept.id')->get();
    }

    public function map($dependent): array
    {
        return [
            $dependent->nombre . ', ' . $dependent->apellido,
            $dependent->direccion,
            $dependent->correo_electronico,
            $dependent->sexo == '1' ? 'Hombre' : 'Mujer',
            $dependent->celular,
            $dependent->pais,
            $dependent->departamento,
            $dependent->fecha_nacimiento,
            $dependent->numero_documento,
            $dependent->fecha_inscripcion
        ];
    }

    public function headings(): array
    {
        return [
            'Nombres',
            'Direccion',
            'Correo Electronico',
            'Sexo',
            'Celular',
            'Pais',
            'Departamento',
            'Fecha de Nacimiento',
            'Numero de Documento',
            'Fecha de Inscripcion'
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet;
                $sheet->autoSize();
                $sheet->getStyle('A1:J1')->getFont()->setBold(true);
            }
        ];
    }
}
