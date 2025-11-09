<?php

namespace App\Http\Requests\Servers;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateServerRequest extends FormRequest
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
            'name' => [
                'required',
                'string',
                'max: 255',
                Rule::unique('servers', 'name')->ignore($this->route('server')),
            ],
            'description' => [
                'nullable',
                'max:65535',
            ],
        ];
    }

    public function messages(){
        return [
            'name.required' => '*Không được để trống',
            'name.string' => '*Tên máy chủ phải là chuỗi',
            'name.unique' => '*Tên máy chủ đã tồn tại',
            'name.max' => '*Tên máy chủ quá dài',
            'description.max' => '*Mô tả quá dài',
        ];
    }
}
