<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDeviceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
   public function rules(): array
    {
        return [
            'id'     => 'required|exists:assets,id',
            'name'   => 'required|string|exists:assets,name',
            'status' => 'required|in:available,in_use,broken',
        ];
    }

    public function messages(): array
    {
        return [
            'id.required'     => 'Thiếu thông tin người dùng.',
            'id.exists'       => 'Người dùng không tồn tại.',
            'name.required'   => 'Thiếu tên thiết bị.',
            'name.exists'     => 'Thiết bị không tồn tại.',
            'status.required' => 'Vui lòng chọn trạng thái.',
            'status.in'       => 'Trạng thái không hợp lệ.',
        ];
    }
}
