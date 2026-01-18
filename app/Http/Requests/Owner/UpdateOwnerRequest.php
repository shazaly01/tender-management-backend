<?php

namespace App\Http\Requests\Owner;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateOwnerRequest extends FormRequest
{
    public function authorize(): bool
    {
        // التحقق من صلاحية التحديث للمالك الحالي
        return $this->user()->can('update', $this->route('owner'));
    }

    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
                // التحقق من الفرادة مع تجاهل المالك الحالي الذي نقوم بتعديله
                Rule::unique('owners')->ignore($this->route('owner')),
            ],
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'اسم الجهة المالكة مطلوب.',
            'name.unique' => 'اسم الجهة المالكة هذا مسجل مسبقاً.',
        ];
    }
}
