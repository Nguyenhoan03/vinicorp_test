<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmployeeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'name'                => ['required', 'string', 'max:255'],
            'email'               => ['required', 'email'],
            'role_id'             => ['required', 'exists:roles,id'],
            'img'                 => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            'equipment_manager'   => ['nullable', 'array'],
            'equipment_manager.*' => ['exists:assets,id'],
        ];

        if ($this->isMethod('post')) {
            $rules['password'] = ['required', 'string', 'min:2'];
        }

        return $rules;
    }
}
