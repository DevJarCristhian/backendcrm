<?php

namespace App\Exports\People;

use App\Models\Patient;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Illuminate\Support\Facades\DB;


class ExportPatient implements FromCollection, WithHeadings, WithMapping, WithEvents
{
    /**
     * @return \Illuminate\Support\Collection
     */

    public function collection()
    {
        return Patient::select(
            DB::raw('CONCAT(p.nombre, " ", p.apellido) as nombre_completo'),
            'p.direccion',
            'p.correo',
            'p.sexo',
            'p.celular',
            'pa.nombre as pais',
            'dept.nombre as departamento',
            'p.tipo_documento',
            'p.numero_documento',
            'p.fecha_nacimiento',
            'p.fecha_inscripcion',

            'p.informacion_paciente',
            'p.consumo_medicamentos',
            'p.envio_correo',
            'p.envio_whatsapp',
            'p.envio_correo_fisico',
            'p.fecha_inicio_programa',
            'p.verificado',
            'p.cantidad_canjes',

            'd.nombre as nombre_doctor',
            'i.nombre as nombre_institucion',
            'p.id_operador',

            'p.estado_civil',
            'p.estado_paciente',
            'p.genero',
            'p.nombre_contacto',
            'p.descripcion'
            // 'p.fecha_creacion',
            // 'p.fecha_actualiza',
            // 'p.usuario_crea',
            // 'p.usuario_modifica',
        )
            ->from('pacientes as p')
            ->leftJoin('paises as pa', 'p.id_pais', '=', 'pa.id')
            ->leftJoin('departamentos as dept', 'p.id_departamento', '=', 'dept.id')
            ->leftJoin('doctores as d', 'p.id_medico', '=', 'd.id')
            ->leftJoin('instituciones as i', 'p.id_institucion', '=', 'i.id')
            ->where('p.eliminado', 0)->get();
    }

    public function map($patient): array
    {
        return [
            $patient->nombre_completo,
            $patient->direccion,
            $patient->correo,
            $patient->sexo,
            $patient->celular,
            $patient->pais,
            $patient->departamento,
            $patient->tipo_documento,
            $patient->numero_documento,
            $patient->fecha_nacimiento,
            $patient->fecha_inscripcion,
            $patient->informacion_paciente,
            $patient->consumo_medicamentos,
            $patient->envio_correo,
            $patient->envio_whatsapp,
            $patient->envio_correo_fisico,
            $patient->fecha_inicio_programa,
            $patient->verificado,
            $patient->cantidad_canjes,
            $patient->nombre_doctor,
            $patient->nombre_institucion,
            $patient->id_operador,
            $patient->estado_civil,
            $patient->estado_paciente,
            $patient->genero,
            $patient->nombre_contacto,
            $patient->descripcion
        ];
    }

    public function headings(): array
    {
        return [
            'Nombre',
            'Direccion',
            'Correo',
            'Sexo',
            'Celular',
            'Pais',
            'Departamento',
            'Tipo de documento',
            'Numero de documento',
            'Fecha de nacimiento',
            'Fecha de inscripcion',
            'Informacion del paciente',
            'Consumo de medicamentos',
            'Envio de correo',
            'Envio de whatsapp',
            'Envio de correo fisico',
            'Fecha de inicio del programa',
            'Verificado',
            'Cantidad de canjes',
            'Nombre del doctor',
            'Nombre de la institucion',
            'Id del operador',
            'Estado civil',
            'Estado del paciente',
            'Genero',
            'Nombre del contacto',
            'Descripcion'
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet;
                $sheet->autoSize();
                $sheet->getStyle('A1:AA1')->getFont()->setBold(true);
            }
        ];
    }
}
