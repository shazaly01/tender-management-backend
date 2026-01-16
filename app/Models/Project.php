<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'project_owner',
        'contract_number',
        'region',
        'contract_value',
        'due_value',
        'award_date',
        'company_id',
        'calculation_option_id',
        'has_contract_permission',
    ];

    protected $casts = [
        'has_contract_permission' => 'boolean',
    ];

    // علاقة "ينتمي إلى" شركة واحدة
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    // علاقة "لديه العديد من" الدفعات
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }


    // أضف هذه العلاقة في كلا الملفين
public function documents()
{
    return $this->morphMany(Document::class, 'documentable');
}

protected static function boot()
    {
        parent::boot();

        static::deleting(function ($project) {
            // فحص وجود دفعات مالية
            if ($project->payments()->exists()) {
                abort(409, 'لا يمكن حذف المشروع لوجود دفعات مالية مسجلة عليه. يرجى حذف الدفعات أولاً.');
            }

            // فحص وجود مستندات
            if ($project->documents()->exists()) {
                abort(409, 'لا يمكن حذف المشروع لوجود مستندات مرفقة به.');
            }
        });
    }


    public function calculationOption()
    {
        return $this->belongsTo(CalculationOption::class);
    }
}
