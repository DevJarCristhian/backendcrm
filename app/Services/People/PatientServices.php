<?php

namespace App\Services\People;

use App\DTO\People\Patient\UpdatePatientDTO;
use App\DTO\People\Patient\GetPatientDTO;
use App\Models\Patient;
use Illuminate\Support\Facades\DB;

class PatientServices
{
    public function get(GetPatientDTO $dto)
    {
        $query = Patient::select(
            'p.id',
            'dept.nombre as departmentName',
            'pa.nombre as countryName',
            DB::raw('CONCAT(p.nombre, " ", p.apellido) as fullName'),
            'p.direccion as address',
            'p.correo as email',
            'p.sexo as gender',
            'p.celular as phone',
            'p.tipo_documento as documentType',
            'p.numero_documento as documentNumber',
            'p.fecha_nacimiento as birthDate',
            'p.fecha_inscripcion as enrollmentDate',
        )
            ->from('pacientes as p')
            ->leftJoin('paises as pa', 'p.id_pais', '=', 'pa.id')
            ->leftJoin('departamentos as dept', 'p.id_departamento', '=', 'dept.id');

        if ($dto->search) {
            $query->where(function ($query) use ($dto) {
                $query->where('p.nombre', 'like', '%' . $dto->search . '%')
                    ->orWhere('p.apellido', 'like', '%' . $dto->search . '%')
                    ->orWhere('dept.nombre', 'like', '%' . $dto->search . '%')
                    ->orWhere('p.numero_documento', 'like', '%' . $dto->search . '%');
            });
        }


        $data = $query->orderBy('p.id', 'desc')->paginate($dto->perPage, $dto->page);

        return $data;
    }

    public function getById($id)
    {
        $query = Patient::select(
            'p.id',
            'p.informacion_paciente as patientInformation',
            'p.consumo_medicamentos as medicationConsumption',
            'p.id_operador as operatorId',
            // 'p.envio_correo as emailSending',
            // 'p.envio_whatsapp as whatsappSending',
            // 'p.envio_correo_fisico as physicalMailSending',
            'p.sexo as gender',
            'p.fecha_inicio_programa as programStartDate',
            'p.verificado as verified',
            'p.cantidad_canjes as quantityRedemptions',
            'd.nombre as doctorName',
            'i.nombre as institutionName',

            'p.estado_civil as civilStatus',
            'p.estado_paciente as patientStatus',
            'p.tipo_paciente as patientType',
            'p.nombre_contacto as contactName',
            'p.descripcion as description',
            'p.fecha_actualiza as dateUpdated',
            // 'p.fecha_creacion as createdAt',
            // 'p.usuario_crea as createdBy',
            // 'p.usuario_modifica as updatedBy',
            // 'p.eliminado as deleted',
        )
            ->from('pacientes as p')
            ->leftJoin('doctores as d', 'p.id_medico', '=', 'd.id')
            ->leftJoin('instituciones as i', 'p.id_institucion', '=', 'i.id')
            ->where('p.id', $id)->first();

        if ($query) {
            $query->quantityRedemptions = (string) $query->quantityRedemptions;
            $query->verified = (string) $query->verified;
            $query->medicationConsumption = (string) $query->medicationConsumption;
            $query->operatorId = (string) $query->operatorId;
        }


        return $query;
    }

    public function store(UpdatePatientDTO $dto)
    {
        $Patient = new Patient();
        $Patient->name = $dto->description;
        $Patient->save();
    }

    public function update(UpdatePatientDTO $dto, $id)
    {
        $patient = Patient::find($id);
        $patient->sexo = $dto->gender;
        $patient->estado_civil = $dto->civilStatus;
        $patient->estado_paciente = $dto->patientStatus;
        $patient->tipo_paciente = $dto->patientType;
        $patient->nombre_contacto = $dto->contactName;
        $patient->descripcion = $dto->description;
        $patient->fecha_actualiza = now();
        $patient->usuario_modifica = auth()->user()->id;
        $patient->save();
    }

    // $query = Patient::select(
    //     'p.id',

    //     'dept.id as departmentId',
    //     'dept.nombre as departmentName',
    //     'pa.id as country',
    //     'pa.nombre as countryName',

    //     DB::raw('CONCAT(p.nombre, " ", p.apellido) as FullName'),
    //     'p.direccion as address',
    //     'p.correo as email',
    //     'p.sexo as gender',
    //     'p.celular as phone',
    //     'p.tipo_documento as documentType',
    //     'p.numero_documento as documentNumber',
    //     'p.fecha_nacimiento as birthDate',
    //     'p.informacion_paciente as patientInformation',
    //     'p.consumo_medicamentos as medicationConsumption',
    //     'p.envio_correo as emailSending',
    //     'p.envio_whatsapp as whatsappSending',
    //     'p.envio_correo_fisico as physicalMailSending',
    //     'p.fecha_inscripcion as enrollmentDate',
    //     'p.fecha_inicio_programa as programStartDate',
    //     'p.verificado as verified',
    //     'p.cantidad_canjes as redemptions',

    //     'd.id as doctorId',
    //     'd.nombre as doctorName',
    //     'i.id as intitutionId',
    //     'i.nombre as institutionName',
    //     'p.id_operador as operatorId',

    //     'p.estado_civil as civilStatus',
    //     'p.estado_paciente as patientStatus',
    //     'p.genero as gender',
    //     'p.nombre_contacto as contactName',
    //     'p.descripcion as description',
    //     'p.fecha_creacion as createdAt',
    //     'p.fecha_actualiza as updatedAt',
    //     'p.usuario_crea as createdBy',
    //     'p.usuario_modifica as updatedBy',
    //     'p.eliminado as deleted',
    // )
    //     ->from('pacientes as p')
    //     ->leftJoin('paises as pa', 'p.id_pais', '=', 'pa.id')
    //     ->leftJoin('departamentos as dept', 'p.id_departamento', '=', 'dept.id')
    //     ->leftJoin('doctores as d', 'p.id_medico', '=', 'd.id')
    //     ->leftJoin('instituciones as i', 'p.id_institucion', '=', 'i.id')
    //     ->where('p.id', $id)->first();
}
