<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
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
        $rules = [
            'name' => 'required|string|max:255',
            'img' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ];
        if ($this->filled('new_password') || $this->filled('current_password')) {
            $rules += [
                'current_password' => 'required',
                'new_password' => 'required|string|min:2|confirmed',
            ];
        }
        return $rules;
    }
    public function messages()
    {
        return [
            'name.required' => 'Vui lòng nhập tên.',
            'current_password.required' => 'Vui lòng nhập mật khẩu cũ để đổi mật khẩu!',
            'new_password.required' => 'Vui lòng nhập mật khẩu mới!',
            'new_password.min' => 'Mật khẩu mới phải có ít nhất 2 ký tự!',
            'new_password.confirmed' => 'Xác nhận mật khẩu mới không khớp!',
        ];
    }
}
