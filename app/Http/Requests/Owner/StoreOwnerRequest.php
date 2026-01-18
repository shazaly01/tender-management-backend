<?php

namespace App\Http\Requests\Owner;

use App\Models\Owner;
use Illuminate\Foundation\Http\FormRequest;

class StoreOwnerRequest extends FormRequest
{
    public function authorize(): bool
    {
        // التحقق من صلاحية الإنشاء
        return $this->user()->can('create', Owner::class);
    }

    public function rules(): array
    {
        return [
            // الاسم مطلوب، نصي، وفريد في جدول owners
            'name' => 'required|string|max:255|unique:owners,name',
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
