<?php

namespace App\Http\Requests\Project;

use App\Models\Project;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
class StoreProjectRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', Project::class);
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'project_owner' => 'nullable|string|max:255',
            'contract_number' => [
    'nullable',
    'string',
    'max:100',
    // التحقق: يجب أن يكون فريداً في جدول المشاريع، ولكن فقط حيث company_id يساوي الشركة المختارة
    Rule::unique('projects')->where(function ($query) {
        return $query->where('company_id', $this->company_id);
    }),
], // جعلناه نصاً ليقبل حروفاً وأرقاماً
            'region' => 'nullable|string|max:255',
            'calculation_option_id' => 'nullable|integer|exists:calculation_options,id',
            'contract_value' => 'nullable|numeric|min:0',
            'due_value' => 'required|numeric|min:0',
           // 'award_date' => 'nullable|date',
            'company_id' => 'required|integer|exists:companies,id',
            'has_contract_permission' => 'nullable|boolean',
        ];
    }


public function messages()
{
    return [
        'contract_number.unique' => 'رقم العقد هذا مسجل بالفعل لمشروع آخر داخل هذه الشركة.',
    ];
}
}
