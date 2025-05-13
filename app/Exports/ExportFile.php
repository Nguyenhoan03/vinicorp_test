<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ExportFile implements FromCollection, WithHeadings
{
    protected $equipmentFilter;

    public function __construct($equipmentFilter = null)
    {
        $this->equipmentFilter = $equipmentFilter;
    }

    public function collection()
    {
    $query = User::withRoleAndAssets($this->equipmentFilter);
        return $query->get()->map(function ($employee) {
            return [
                'id' => $employee->id,
                'name' => $employee->name,
                'email' => $employee->email,
                'password' => $employee->password,
                'img' => $employee->img,
                'role' => $employee->role ? $employee->role_id . '-' . $employee->role->name : 'Không có',
                'assets' => $employee->assets->map(fn($asset) => $asset->name . ' (' . $asset->status . ')')->join(', ') ?: 'Không có thiết bị',
            ];
        });
    }

    public function headings(): array
    {
        return [
            'ID',
            'Name',
            'Email',
            'Password',
            'Image',
            'Role (Role ID)',
            'Thiết Bị (Trạng Thái)',
        ];
    }
}
