<?php

namespace App\Imports;

use App\Models\User;
use App\Models\Asset;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ImportFile implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        // Chuẩn hóa key vì heading có thể bị viết hoa/thường khác nhau
        $row = array_change_key_case($row, CASE_LOWER);

        // Kiểm tra nếu user đã tồn tại (name + email)
        $exists = User::where('email', $row['email'])
            ->where('name', $row['name'])
            ->exists();

        if ($exists) {
            Log::info('Bỏ qua user vì đã tồn tại: ', [
                'email' => $row['email'],
                'name'  => $row['name'],
            ]);
            return null;
        }

        // Validate dữ liệu
        $validator = Validator::make($row, [
            'id'                  => 'nullable|integer',
            'name'                => 'required|string|max:255',
            'image'               => 'nullable|string|max:255',
            'email'               => 'required|email',
            'password'            => 'required|string|min:2',
            'role_role_id'        => 'required|exists:roles,id|integer',
            'thiet_bi_trang_thai' => 'nullable|string|max:1000',
        ]);


        if ($validator->fails()) {
            Log::warning('Validation failed for row: ', $row);
            return null;
        }

        // Tạo user mới
        $user = new User([
            'id'        => $row['id'] ?? null,
            'name'      => $row['name'],
            'img'       => $row['image'] ?? null,
            'email'     => $row['email'],
            'password'  => $row['password'], // hoặc Hash::make() nếu cần
            'role_id'   => $row['role_role_id'],
            'created_at' => now(),
        ]);

        $user->save();

        // Xử lý thiết bị
       $assetText = $row['thiet_bi_trang_thai'] ?? '';

        $assets = explode(',', $assetText);
        $assetIds = [];

        foreach ($assets as $assetItem) {
            if (preg_match('/^(.*?)\s*\((.*?)\)$/', trim($assetItem), $matches)) {
                $assetName = trim($matches[1]);
                $status = trim($matches[2]);

                $asset = Asset::where('name', $assetName)
                    ->where('status', $status)
                    ->first();

                if ($asset) {
                    $assetIds[] = $asset->id;
                } else {
                    Log::warning("Không tìm thấy asset", [
                        'user_email' => $row['email'],
                        'asset_name' => $assetName,
                        'status'     => $status,
                    ]);
                }
            } elseif (trim($assetItem) !== '') {
                Log::warning("Định dạng thiết bị không hợp lệ", [
                    'user_email' => $row['email'],
                    'raw_value'  => $assetItem,
                ]);
            }
        }

        if (!empty($assetIds)) {
            $user->assets()->attach($assetIds);
        }

        return $user;
    }
}
