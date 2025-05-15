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
        $userId = $this->id ?? null;
        $rules = [
            'name'                => ['string', 'max:255'],
            'email'               => ['email', 'max:255', 'unique:users,email' . ($userId ? ',' . $userId : '')],
            'role_id'             => ['required', 'exists:roles,id'],
            'img'                 => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            'equipment_manager'   => ['nullable', 'array'],
            'equipment_manager.*' => ['exists:assets,id'],
        ];

        if ($this->isMethod('post')) {
            $rules['password'] = ['required', 'string', 'min:2'];
            array_unshift($rules['name'], 'required');
            array_unshift($rules['email'], 'required');
        }

        return $rules;
    }
}
