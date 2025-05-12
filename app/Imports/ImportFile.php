<?php

namespace App\Imports;

use App\Models\User;
use App\Models\Asset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;


HeadingRowFormatter::default('none');

class ImportFile implements ToModel, WithHeadingRow, WithValidation
{
    public function model(array $row)
    {
        try {
            $user = new User([
                'id'       => $row['ID'] ?? null,
                'name'     => $row['Name'],
                'email'    => $row['Email'],
                'password' => Hash::make($row['Password']),
                'role_id'  => $row['Role_id'],
                'img'      => $row['Image'] ?? null,
            ]);
            $user->save();

            if (!empty($row['Thiết Bị (Trạng Thái)'])) {
                $deviceList = explode(',', $row['Thiết Bị (Trạng Thái)']);
                $assetIds = [];
                foreach ($deviceList as $item) {
                    if (preg_match('/^(.*?)\s*\((.*?)\)$/', trim($item), $matches)) {
                        $name = trim($matches[1]);
                        $status = trim($matches[2]);

                        $asset = Asset::where('name', $name)->where('status', $status)->first();

                        if ($asset) {
                            $assetIds[] = $asset->id;
                        } else {
                            Log::warning("Asset not found", [
                                'user_email' => $row['Email'],
                                'asset_name' => $name,
                                'status'     => $status,
                            ]);
                        }
                    }
                }
                if (!empty($assetIds)) {
                    $user->assets()->attach($assetIds);
                }
            }
            return $user;
        } catch (\Throwable $e) {
            Log::error('Import failed: ' . $e->getMessage(), ['row' => $row]);
            return null;
        }
    }
    public function rules(): array
    {
        return [
            '*.Name'     => ['required', 'string', 'max:255', 'unique:users,name'],
            '*.Email'    => ['required', 'email', 'unique:users,email'],
            '*.Password' => ['required', 'string', 'min:2'],
            '*.Role_id'  => ['required', 'integer', 'exists:roles,id'],
            '*.Image'    => ['nullable', 'string', 'max:255'],
            '*.Thiết Bị (Trạng Thái)' => ['nullable', 'string', 'max:1000'],
        ];
    }
    public function customValidationMessages()
    {
        return [
            '*.Name.required' => 'Tên là bắt buộc.',
            '*.Email.email' => 'Email không hợp lệ.',
            '*.Role_id.exists' => 'Vai trò không tồn tại.',
        ];
    }
}
