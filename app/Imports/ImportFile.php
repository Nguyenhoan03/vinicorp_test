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

        public function startRow(): int { return 2; }

    public function model(array $row)
    {
        if (!$this->headingChecked) {
            $this->validateHeadings(array_keys($row));
            $this->headingChecked = true;
        }

        try {
            // Tìm user theo email
            $user = User::where('email', $row['Email'])->first();

            // Nếu user đã tồn tại, giữ nguyên password nếu không nhập mới
            if ($user) {
                $password = !empty($row['Password']) ? Hash::make($row['Password']) : $user->password;
                $user->update([
                    'name'     => $row['Name'],
                    'password' => $password,
                    'role_id'  => $row['Role_id'],
                ]);
            } else {
                // Nếu user chưa tồn tại, password là bắt buộc
                $user = User::create([
                    'name'     => $row['Name'],
                    'email'    => $row['Email'],
                    'password' => Hash::make($row['Password']),
                    'role_id'  => $row['Role_id'],
                ]);
            }

            // Xử lý thiết bị
            if (!empty($row['Thiết Bị (Trạng Thái)'])) {
                $deviceList = explode(',', $row['Thiết Bị (Trạng Thái)']);
                $assetIds = collect();

                foreach ($deviceList as $item) {
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

                    $nameParts = explode('-', $rawName);
                    $name = trim($nameParts[0]);
                    $type = $nameParts[1] ?? 'default';

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

                $currentAssetIds = $user->assets()->pluck('assets.id');
                $toAttach = $assetIds->diff($currentAssetIds);
                $toDetach = $currentAssetIds->diff($assetIds);

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
            '*.Password' => ['nullable', 'string', 'min:2'],
            '*.Role_id'  => ['required', 'integer', 'exists:roles,id'],
            '*.Thiết Bị (Trạng Thái)' => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function customValidationMessages()
    {
        return [
            '*.Name.required'     => 'Tên là bắt buộc.',
            '*.Email.email'       => 'Email không hợp lệ.',
            '*.Password.min' => 'Mật khẩu phải nhiều hơn 2 ký tự.',
            '*.Role_id.exists'    => 'Vai trò không tồn tại.',
        ];
    }
}
