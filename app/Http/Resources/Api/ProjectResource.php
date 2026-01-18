<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'owner_id' => $this->owner_id,
            // 2. نرسل كائن المالك كاملاً (إذا تم تحميله) لعرض اسمه في الجدول
            'owner' => new OwnerResource($this->whenLoaded('owner')),
            'project_owner' => $this->project_owner,
            'contract_number' => $this->contract_number,
            'region' => $this->region,
            // نرسل الكائن كاملاً إذا كان محملاً، أو المعرف فقط إذا لم يكن
            'calculation_option' => $this->whenLoaded('calculationOption', function() {
                return [
                    'id' => $this->calculationOption->id,
                    'name' => $this->calculationOption->name,
                ];
            }),
            'calculation_option_id' => $this->calculation_option_id, // مفيد للـ Edit Form
            'contract_value' => (float) $this->contract_value,
            // تحويل القيمة إلى رقم عشري للتناسق
            'due_value' => (float) $this->due_value,
           // 'award_date' => $this->award_date,
            'has_contract_permission' => (bool) $this->has_contract_permission,
            'description' => $this->description,

            // === [التعديل هنا] ===
            // إضافة مجموع الدفعات.
            // whenNotNull يتحقق من أن القيمة `payments_sum_amount` تم تحميلها وحسابها.
            // إذا لم تكن موجودة (لتجنب الأخطاء)، ستكون قيمتها 0.
            'total_payments' => $this->whenNotNull($this->payments_sum_amount, (float) $this->payments_sum_amount, 0.0),

            // تضمين بيانات الشركة المرتبطة بالمشروع
            'company' => CompanyResource::make($this->whenLoaded('company')),
            'created_at' => $this->created_at->toDateTimeString(),
        ];
    }
}
