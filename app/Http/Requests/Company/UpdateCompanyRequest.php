<?php

namespace App\Http\Requests\Company;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCompanyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('company'));
    }

    public function rules(): array
    {
        return [
            'name' => 'sometimes|required|string|max:255',

            'commercial_record' => [
                'nullable',
                'string',
                'max:50',
                // التحقق مع تجاهل الشركة الحالية
                Rule::unique('companies', 'commercial_record')->ignore($this->route('company')),
            ],

            // --- [تم التعديل] أصبح الآن بنفس التنسيق المرتب ---
            'tax_number' => [
                'sometimes',
                'nullable',
                'string',
                'max:50', // وحدنا الطول ليكون 50 مثل البقية (أو اجعله 255 حسب قاعدة بياناتك)
                // التحقق مع تجاهل الشركة الحالية
                Rule::unique('companies', 'tax_number')->ignore($this->route('company')),
            ],

            'license_number' => [
                'nullable',
                'string',
                'max:50',
                // التحقق مع تجاهل الشركة الحالية
                Rule::unique('companies', 'license_number')->ignore($this->route('company')),
            ],

            'address' => 'sometimes|nullable|string',
            'owner_name' => 'sometimes|nullable|string|max:255',
        ];
    }

    public function messages()
    {
        return [
            'license_number.unique' => 'رقم الرخصة هذا مستخدم بالفعل لشركة أخرى.',
            'commercial_record.unique' => 'رقم السجل التجاري هذا مستخدم بالفعل لشركة أخرى.',
            // أضفت لك رسالة الرقم الضريبي أيضاً لتكتمل الصورة
            'tax_number.unique' => 'الرقم الضريبي هذا مستخدم بالفعل لشركة أخرى.',
        ];
    }
}
