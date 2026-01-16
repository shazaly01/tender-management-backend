<?php

namespace App\Http\Requests\Company;

use App\Models\Company;
use Illuminate\Foundation\Http\FormRequest;

class StoreCompanyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', Company::class);
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'commercial_record' => 'nullable|string|max:50|unique:companies,commercial_record',
            'tax_number' => 'nullable|string|max:255|unique:companies,tax_number',
            'license_number' => 'nullable|string|max:50|unique:companies,license_number', // <-- [تمت الإضافة هنا]
            'address' => 'nullable|string',
            'owner_name' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20', // حددنا الطول بـ 20 ليتناسب مع أرقام الهواتف
            'bank_name' => 'nullable|string|max:100',
            'account_number' => 'nullable|string|max:50',
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
