<?php

namespace App\Exports\Sale;

use App\Models\Opportunity;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Illuminate\Support\Facades\DB;

class ExportOpportunity implements FromCollection, WithHeadings, WithMapping, WithEvents
{
    /**
     * @return \Illuminate\Support\Collection
     */

    public function collection()
    {
        return Opportunity::select(
            'o.serie_factura',
            'o.no_factura',
            'o.fecha_facturacion',
            DB::raw('CONCAT(pa.nombre, " ", pa.apellido) as nombre_completo'),
            'pa.numero_documento',
            'pro.descripcion as nombre_producto',
            'o.cantidad',
            'fa.sucursal as nombre_farmacia',
            'o.cantidades_utilizadas',
            'o.activo',
            'o.estado_canje',
            'o.validacion',
            'o.observaciones',
            'o.fecha_bonificacion',
            'o.fecha_ultima_toma',
            'o.fecha_abandono_tratamiento',
            'o.anulacion',
            'o.id_usuario_anulacion',
            'o.id_motivo_compra',
            'o.id_motivo_anulacion',
            'o.id_diagnostico',
            'o.id_dosis',
            'o.id_tiempo_tratamiento',
            'o.id_otros'
            // 'o.fecha_creacion',
            // 'o.fecha_actualiza',
            // 'o.usuario_crea',
            // 'o.usuario_modifica',
        )
            ->from('oportunidades as o')
            ->leftJoin('pacientes as pa', 'o.id_paciente', '=', 'pa.id')
            ->leftJoin('farmacias as fa', 'o.id_farmacia', '=', 'fa.id')
            ->leftJoin('productos as pro', 'o.id_producto', '=', 'pro.id')->get();
    }

    public function map($opportunity): array
    {
        return [
            $opportunity->serie_factura,
            $opportunity->no_factura,
            $opportunity->fecha_facturacion,
            $opportunity->nombre_completo,
            $opportunity->numero_documento,
            $opportunity->nombre_producto,
            $opportunity->cantidad,
            $opportunity->nombre_farmacia,
            $opportunity->cantidades_utilizadas,
            $opportunity->activo,
            $opportunity->estado_canje,
            $opportunity->validacion,
            $opportunity->observaciones,
            $opportunity->fecha_bonificacion,
            $opportunity->fecha_ultima_toma,
            $opportunity->fecha_abandono_tratamiento,
            $opportunity->anulacion,
            $opportunity->id_usuario_anulacion,
            $opportunity->id_motivo_compra,
            $opportunity->id_motivo_anulacion,
            $opportunity->id_diagnostico,
            $opportunity->id_dosis,
            $opportunity->id_tiempo_tratamiento,
            $opportunity->id_otros
        ];
    }

    public function headings(): array
    {
        return [
            'Serie Factura',
            'Número Factura',
            'Fecha Facturación',
            'Nombre',
            'Número Documento',
            'Producto',
            'Cantidad',
            'Farmacia',
            'Cantidades Utilizadas',
            'Activo',
            'Estado Canje',
            'Validación',
            'Observaciones',
            'Fecha Bonificación',
            'Fecha Última Toma',
            'Fecha Abandono Tratamiento',
            'Anulación',
            'Usuario Anulación',
            'Motivo Compra',
            'Motivo Anulación',
            'Diagnóstico',
            'Dosis',
            'Tiempo Tratamiento',
            'Otros'
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet;
                $sheet->autoSize();
                $sheet->getStyle('A1:X1')->getFont()->setBold(true);
            }
        ];
    }
}
