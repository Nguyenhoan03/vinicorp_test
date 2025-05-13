<?php

namespace App\Imports;

use App\Models\User;
use App\Models\Asset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;

HeadingRowFormatter::default('none');

class ImportFile implements ToModel, WithHeadingRow, WithValidation
{
    protected $validStatuses = ['available', 'in_use', 'broken'];

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
                        $rawName = trim($matches[1]); 
                        $statusFromExcel = strtolower(trim($matches[2]));
                    } else {
                        $rawName = trim($item);
                        $statusFromExcel = 'available';
                    }

                    // Phân tích name và type nếu có dấu '-'
                    $nameParts = explode('-', $rawName);
                    $name = trim($nameParts[0]);
                    $type = isset($nameParts[1]) ? trim($nameParts[1]) : 'default';

                    // Kiểm tra thiết bị có tồn tại không
                    $asset = Asset::where('name', $name)->first();

                    if ($asset) {
                        $status = $asset->status; // Thiết bị đã tồn tại: dùng status hiện tại
                    } else {
                        // Thiết bị mới: kiểm tra status hợp lệ
                        if (!in_array($statusFromExcel, $this->validStatuses)) {
                            throw new \Exception("Trạng thái '$statusFromExcel' của thiết bị '$rawName' không hợp lệ. Chỉ chấp nhận: available, in_use, broken.");
                        }

                        // Tạo mới Asset với name, status và type
                        $asset = Asset::create([
                            'name'   => $name,
                            'status' => $statusFromExcel,
                            'type'   => $type,
                        ]);
                    }

                    if ($asset) {
                        $assetIds[] = $asset->id;
                    } else {
                        Log::warning("Asset not found or not created", [
                            'user_email' => $row['Email'],
                            'asset_name' => $name,
                            'status'     => $statusFromExcel,
                        ]);
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
            '*.Name.required'     => 'Tên là bắt buộc.',
            '*.Email.email'       => 'Email không hợp lệ.',
            '*.Role_id.exists'    => 'Vai trò không tồn tại.',
        ];
    }
}
