<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; // 1. استيراد الحذف الناعم

class Company extends Model
{
    use HasFactory, SoftDeletes; // 2. استخدام الحذف الناعم

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    // 3. تحديد الحقول القابلة للتعبئة
    protected $fillable = [
        'name',
        'tax_number',
        'license_number',
        'address',
        'owner_name',
    ];

    /**
     * Get the projects for the company.
     */
    // 4. تعريف علاقة "لديه العديد من" المشاريع
    public function projects()
    {
        return $this->hasMany(Project::class);
    }

  // أضف هذه العلاقة في كلا الملفين
public function documents()
{
    return $this->morphMany(Document::class, 'documentable');
}
}
