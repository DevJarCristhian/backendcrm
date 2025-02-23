<?php

namespace App\Services\Sale;

use App\DTO\Sale\Opportunity\UpdateOpportunityDTO;
use App\DTO\GetDTO;
use App\Models\Opportunity;
use Illuminate\Support\Facades\DB;

class OpportunityServices
{
    public function get(GetDTO $dto)
    {
        $query = Opportunity::select(
            'o.id',
            'pa.numero_documento as documentNumber',
            DB::raw('CONCAT(pa.nombre, " ", pa.apellido) as patientFullName'),
            'fa.sucursal as farmacyName',
            'pro.descripcion as productName',
            'o.serie_factura as invoiceSerie',
            'o.no_factura as invoiceNumber',
            'o.cantidad as quantity',
            'o.fecha_facturacion as invoiceDate',
            'o.fecha_actualiza as dateUpdated',
        )
            ->from('oportunidades as o')
            ->leftJoin('pacientes as pa', 'o.id_paciente', '=', 'pa.id')
            ->leftJoin('farmacias as fa', 'o.id_farmacia', '=', 'fa.id')
            ->leftJoin('productos as pro', 'o.id_producto', '=', 'pro.id');

        if ($dto->search) {
            $query->where(function ($query) use ($dto) {
                $query->where('o.serie_factura', 'like', '%' . $dto->search . '%')
                    ->orWhere('o.no_factura', 'like', '%' . $dto->search . '%')
                    ->orWhere('o.fecha_facturacion', 'like', '%' . $dto->search . '%')
                    ->orWhere('pa.numero_documento', 'like', '%' . $dto->search . '%')
                    ->orWhere('pa.nombre', 'like', '%' . $dto->search . '%')
                    ->orWhere('pa.apellido', 'like', '%' . $dto->search . '%')
                    ->orWhere('pro.descripcion', 'like', '%' . $dto->search . '%');
            });
        }

        $data = $query->orderBy('o.id', 'desc')->paginate($dto->perPage, $dto->page);

        return $data;
    }

    public function getById($id)
    {
        $query = Opportunity::select(
            'o.id',

            // 'pa.id as patientId',
            // 'pa.numero_documento as documentNumber',
            // DB::raw('CONCAT(pa.nombre, " ", pa.apellido) as patientFullName'),

            // 'fa.id as farmacyId',
            // 'fa.sucursal as farmacyName',

            // 'pro.id as productId',
            // 'pro.descripcion as productName',

            // 'o.serie_factura as invoiceSerie',
            // 'o.no_factura as invoiceNumber',
            // 'o.cantidad as quantity',
            // 'o.fecha_facturacion as invoiceDate',

            // 'o.id_usuario_anulacion as cancelationUserId',
            'o.activo as active',
            'o.cantidades_utilizadas as usedQuantity',
            'o.anulacion as cancellation',
            'o.fecha_bonificacion as certificationDate',
            'o.estado_canje as exchangeState',
            'o.validacion as validation',

            'o.fecha_ultima_toma as lastDateTaken',
            'o.fecha_abandono_tratamiento as dateAbandonTreatment',

            'o.id_motivo_compra as reasonBuyId',
            'o.id_motivo_anulacion as reasonAnulationId',
            'o.id_diagnostico as diagnosisId',
            'o.id_dosis as doseId',
            'o.id_tiempo_tratamiento as treatmentTimeId',
            // 'o.id_otros as otherId',
            'o.observaciones as observations',
            'o.fecha_actualiza as dateUpdated',
            // 'o.fecha_creacion as createdAt',
            // 'o.usuario_crea as createdBy',
            // 'o.usuario_modifica as updatedBy',
        )
            ->from('oportunidades as o')
            ->where('o.id', $id)->first();

        return $query;
    }

    public function update(UpdateOpportunityDTO $dto, $id)
    {
        $Opportunity = Opportunity::find($id);
        $Opportunity->id_motivo_compra = $dto->reasonBuyId;
        $Opportunity->id_motivo_anulacion = $dto->reasonAnulationId;
        $Opportunity->id_diagnostico = $dto->diagnosisId;
        $Opportunity->id_dosis = $dto->doseId;
        $Opportunity->id_tiempo_tratamiento = $dto->treatmentTimeId;
        $Opportunity->fecha_ultima_toma = $dto->lastDateTaken;
        $Opportunity->fecha_abandono_tratamiento = $dto->dateAbandonTreatment;
        $Opportunity->observaciones = $dto->observations;
        $Opportunity->fecha_actualiza = now();
        $Opportunity->usuario_modifica = auth()->user()->id;
        $Opportunity->save();
    }
}

// $query = Opportunity::select(
//     'o.id',

//     'pa.id as patientId',
//     'pa.numero_documento as documentNumber',
//     DB::raw('CONCAT(pa.nombre, " ", pa.apellido) as patientFullName'),

//     'fa.id as farmacyId',
//     'fa.sucursal as farmacyName',

//     'pro.id as productId',
//     'pro.descripcion as productName',

//     'o.id_usuario_anulacion as cancelationUserId',
//     'o.serie_factura as invoiceSerie',
//     'o.no_factura as invoiceNumber',
//     'o.cantidad as quantity',
//     'o.fecha_facturacion as invoiceDate',
//     'o.activo as active',
//     'o.cantidades_utilizadas as usedQuantity',
//     'o.anulacion as cancellation',
//     'o.fecha_bonificacion as certificationDate',
//     'o.estado_canje as exchangeState',
//     'o.validacion as validation',
//     'o.fecha_ultima_toma as lastTakeDate',
//     'o.fecha_abandono_tratamiento as abandonmentTreatmentDate',

//     'o.id_motivo_compra as purchaseReasonId',
//     'o.id_motivo_anulacion as cancellationReasonId',
//     'o.id_diagnostico as diagnosisId',
//     'o.id_dosis as doseId',
//     'o.id_tiempo_tratamiento as treatmentTimeId',
//     'o.id_otros as otherId',
//     'o.observaciones as observations',
//     'o.fecha_creacion as createdAt',
//     'o.fecha_actualiza as updatedAt',
//     'o.usuario_crea as createdBy',
//     'o.usuario_modifica as updatedBy',
// )
//     ->from('oportunidades as o')
//     ->leftJoin('pacientes as pa', 'o.id_paciente', '=', 'pa.id')
//     ->leftJoin('farmacias as fa', 'o.id_farmacia', '=', 'fa.id')
//     ->leftJoin('productos as pro', 'o.id_producto', '=', 'pro.id')
//     ->where('o.id', $id)->first();
