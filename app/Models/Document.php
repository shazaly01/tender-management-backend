<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Document extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'file_path',
        'company_id',
    ];

    // علاقة "ينتمي إلى" شركة واحدة
    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
