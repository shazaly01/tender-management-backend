<?php

namespace App\Http\Requests\Project;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
class UpdateProjectRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // استخدام الـ Policy للتحقق من صلاحية التحديث
        // يفترض أن الرابط هو /projects/{project}
        return $this->user()->can('update', $this->route('project'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'sometimes|required|string|max:255',
            'project_owner' => 'nullable|string|max:255',
            'contract_number' => [
    'nullable',
    'string',
    'max:100',
    Rule::unique('projects')
        ->where(function ($query) {
            // نتحقق ضمن نفس الشركة (سواء تم تغييرها في الفورم أو بقيت كما هي)
            return $query->where('company_id', $this->company_id ?? $this->route('project')->company_id);
        })
        ->ignore($this->route('project')), // نتجاهل المشروع الحالي
],
            'region' => 'nullable|string|max:255',
            'calculation_option_id' => 'nullable|integer|exists:calculation_options,id',
            'contract_value' => 'nullable|numeric|min:0',
            'due_value' => 'required|numeric|min:0',
            'award_date' => 'nullable|date',
            // عادةً لا نسمح بتغيير الشركة المرتبط بها المشروع
            // 'company_id' => 'sometimes|required|integer|exists:companies,id',
        ];
    }

 public function messages()
{
    return [
        'contract_number.unique' => 'رقم العقد هذا مسجل بالفعل لمشروع آخر داخل هذه الشركة.',
    ];
}
}
