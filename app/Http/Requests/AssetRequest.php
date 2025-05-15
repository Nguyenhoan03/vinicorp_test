<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AssetRequest extends FormRequest
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
        $assetId = $this->id ?? null;
        return [
            'name' => 'required|string|max:255|unique:assets,name' . ($assetId ? ',' . $assetId : ''),
            'type' => 'required|string|max:255',
            'status' => 'required|string|max:255',
        ];
    }
}
