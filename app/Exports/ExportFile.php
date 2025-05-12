<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ExportFile implements FromCollection, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return User::with('assets', 'role')->get()->map(function ($employee) {
           return [
            'id' => $employee->id,
            'name' => $employee->name,
            'email' => $employee->email,
            'password' => $employee->password,
            'img' => $employee->img,
            'role' => $employee->role ? $employee->role_id . '-' . $employee->role->name : 'Không có',
            'assets' => $employee->assets->map(function ($asset) {
                return $asset->name . ' (' . $asset->status . ')';
            })->join(', ') ?: 'Không có thiết bị',
        ];
        });
    }

    /**
     * Define the headings for the columns
     * @return array
     */
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
