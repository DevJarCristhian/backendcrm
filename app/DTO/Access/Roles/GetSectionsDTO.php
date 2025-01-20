<?php

namespace App\DTO\Access\Roles;

class GetSectionsDTO
{
    public string $parent_id;
    public string $role_id;
    public function __construct(
        string $parent_id,
        string $role_id,
    ) {
        $this->parent_id = $parent_id;
        $this->role_id = $role_id;
    }

    public function rules(): array
    {
        return [
            'parent_id' => 'nullable|integer',
            'role_id' => 'required|integer'
        ];
    }
}
