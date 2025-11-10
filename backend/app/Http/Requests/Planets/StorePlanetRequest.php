<?php

namespace App\Http\Requests\Planets;

use Illuminate\Foundation\Http\FormRequest;

class StorePlanetRequest extends FormRequest
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
                'unique:planets,name',
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
            'name.string' => '*Tên hành tinh phải là chuỗi',
            'name.unique' => '*Tên hành tinh đã tồn tại',
            'name.max' => '*Tên hành tinh quá dài',
            'description.max' => '*Mô tả quá dài',
        ];
    }
}
