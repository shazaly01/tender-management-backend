<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Owner extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * الحقول القابلة للتعبئة
     */
    protected $fillable = [
        'name',
    ];


    public function projects()
    {
        return $this->hasMany(Project::class);
    }
}
