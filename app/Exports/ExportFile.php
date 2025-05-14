<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnWidths;

class ExportFile implements FromCollection, WithHeadings, WithColumnWidths
{
    protected $FilterUser;

    public function __construct($FilterUser = [])
    {
        $this->FilterUser = $FilterUser;
    }

    public function collection()
    {
        $query = User::FilterUser($this->FilterUser);
        return $query->get()->map(function ($employee) {
            $assets = $employee->assets->map(function ($asset) {
                return $asset->name . ' (' . $asset->status . ')';
            })->implode(', ') ?: 'Không có thiết bị';

            return [
                // 'ID' => $employee->id,
                'Name' => $employee->name,
                'Email' => $employee->email,
                // 'Image' => $employee->img,
                'Role (Role ID)' => $employee->role ? $employee->role_id . '-' . $employee->role->name : 'Không có',
                'Thiết Bị (Trạng Thái)' => $assets,
            ];
        });
    }

    public function headings(): array
    {
        return [
            // 'ID',
            'Name',
            'Email',
            // 'Image',
            'Role (Role ID)',
            'Thiết Bị (Trạng Thái)',
        ];
    }

    // Thiết lập độ rộng cho từng cột
    public function columnWidths(): array
    {
        return [
            // 'A' => 8,    // ID
            'A' => 25,   // Name
            'B' => 50,   // Email
            'C' => 20,   // Image
            'D' => 125,   // Role (Role ID)
            // 'F' => 120,   // Thiết Bị (Trạng Thái)
        ];
    }
}