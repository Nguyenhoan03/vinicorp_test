<?php

namespace App\Imports;

use App\Models\User;
use App\Models\Asset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;

HeadingRowFormatter::default('none');

class ImportFile implements ToModel, WithHeadingRow, WithValidation, WithStartRow
{
    protected $validStatuses = ['available', 'in_use', 'broken'];
    protected $requiredHeadings = ['Name', 'Email', 'Password', 'Role_id', 'Thiết Bị (Trạng Thái)'];
    protected $headingChecked = false;

    public function startRow(): int
    {
        return 2;
    }

    public function model(array $row)
    {
        if (!$this->headingChecked) {
            $this->validateHeadings(array_keys($row));
            $this->headingChecked = true;
        }

        try {
            // Tạo hoặc cập nhật user
            $user = User::updateOrCreate(
                ['email' => $row['Email']],
                [
                    'name'     => $row['Name'],
                    'password' => Hash::make($row['Password']),
                    'role_id'  => $row['Role_id'],
                ]
            );

            // Nếu có dữ liệu thiết bị
            if (!empty($row['Thiết Bị (Trạng Thái)'])) {
                $deviceList = explode(',', $row['Thiết Bị (Trạng Thái)']);
                $assetIds = collect(); // ID của asset mới từ Excel

                foreach ($deviceList as $item) {
                    // Tách tên và trạng thái
                    if (preg_match('/^(.*?)\s*\((.*?)\)$/', trim($item), $matches)) {
                        $rawName = trim($matches[1]);
                        $status = strtolower(trim($matches[2]));
                    } else {
                        $rawName = trim($item);
                        $status = 'available';
                    }

                    if (!in_array($status, $this->validStatuses)) {
                        throw new \Exception("Trạng thái '$status' của thiết bị '$rawName' không hợp lệ.");
                    }

                    // Tách tên và type assets
                    $nameParts = explode('-', $rawName);
                    $name = trim($nameParts[0]);
                    $type = $nameParts[1] ?? 'default';

                    // Tạo hoặc cập nhật thiết bị
                    $asset = Asset::updateOrCreate(
                        ['name' => $name, 'type' => $type],
                        ['status' => $status]
                    );

                    if ($asset) {
                        $assetIds->push($asset->id);
                    } else {
                        Log::warning("Không tạo được thiết bị", [
                            'user_email' => $row['Email'],
                            'asset_name' => $name,
                            'status'     => $status,
                        ]);
                    }
                }

                // Lấy danh sách ID thiết bị hiện tại của user
                $currentAssetIds = $user->assets()->pluck('assets.id');

                // So sánh để tối ưu hóa
                $toAttach = $assetIds->diff($currentAssetIds);
                $toDetach = $currentAssetIds->diff($assetIds);

                // Chỉ thao tác nếu có thay đổi
                if ($toAttach->isNotEmpty()) {
                    $user->assets()->attach($toAttach->all());
                }

                if ($toDetach->isNotEmpty()) {
                    $user->assets()->detach($toDetach->all());
                }
            }

            return $user;
        } catch (\Throwable $e) {
            Log::error('Import thất bại: ' . $e->getMessage(), ['row' => $row]);
            throw $e;
        }
    }

    protected function validateHeadings(array $headings)
    {
        foreach ($this->requiredHeadings as $required) {
            if (!in_array($required, $headings)) {
                throw new \Exception("Thiếu cột bắt buộc: '{$required}'. Vui lòng kiểm tra lại tiêu đề cột trong file Excel.");
            }
        }
    }

    public function rules(): array
    {
        return [
            '*.Name'     => ['required', 'string', 'max:255'],
            '*.Email'    => ['required', 'email'],
            '*.Password' => ['required', 'string', 'min:2'],
            '*.Role_id'  => ['required', 'integer', 'exists:roles,id'],
            '*.Thiết Bị (Trạng Thái)' => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function customValidationMessages()
    {
        return [
            '*.Name.required'     => 'Tên là bắt buộc.',
            '*.Email.email'       => 'Email không hợp lệ.',
            '*.Password.required' => 'Mật khẩu là bắt buộc.',
            '*.Role_id.exists'    => 'Vai trò không tồn tại.',
        ];
    }
}
