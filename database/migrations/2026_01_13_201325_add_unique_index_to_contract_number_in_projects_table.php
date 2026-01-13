<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            // إضافة قيد عدم التكرار
            // ملاحظة: تأكد أنه لا توجد أرقام مكررة حالياً في الجدول وإلا سيفشل الأمر
            $table->unique('contract_number');
        });
    }

    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropUnique(['contract_number']);
        });
    }
};
