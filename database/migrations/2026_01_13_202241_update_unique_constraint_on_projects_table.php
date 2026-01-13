<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            // 1. حذف القيد القديم (إذا كنت قد طبقته)
            // اسم القيد عادة يكون: table_column_unique
            $table->dropUnique(['contract_number']);

            // 2. إضافة القيد الجديد المركب (الرقم + الشركة معاً)
            $table->unique(['company_id', 'contract_number']);
        });
    }

    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropUnique(['company_id', 'contract_number']);
            $table->unique('contract_number'); // إعادة القديم
        });
    }
};
