<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RoleRequest extends FormRequest
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
        $roleId = $this->id ?? null;
        $rules = [
            'role_name' => 'string|max:255|unique:roles,name' . ($roleId ? ',' . $roleId : ''),
            'permissions' => 'required|array',
        ];

        if ($this->isMethod('post')) {
            $rules['role_name'] = 'required|' . $rules['role_name'];
        }

        return $rules;
    }
}
