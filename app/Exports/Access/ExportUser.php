<?php

namespace App\Exports\Access;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class ExportUser implements FromCollection, WithHeadings, WithMapping, WithEvents
{
    /**
     * @return \Illuminate\Support\Collection
     */

    public function collection()
    {
        return User::select(
            'u.name',
            'u.email',
            'r.description as role',
            'u.status',
            'u.created_at',
        )
            ->from('users as u')
            ->leftJoin('roles as r', 'r.id', '=', 'u.role_id')->get();
    }

    public function map($user): array
    {
        return [
            $user->name,
            $user->email,
            $user->role,
            $user->status,
            $user->created_at,
        ];
    }

    public function headings(): array
    {
        return [
            'Nombre',
            'Correo Electronico',
            'Roles',
            'Estado',
            'Fecha de Creacion',
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
