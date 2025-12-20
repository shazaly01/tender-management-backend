<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            // إضافة عمود قيمة العقد الكلية بعد عمود القيمة المستحقة
            // نجعله nullable ليكون اختياريًا ويمكن أن يكون بنفس قيمة due_value مبدئيًا
            $table->decimal('contract_value', 15, 2)->nullable()->after('due_value');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            // حذف العمود في حال التراجع عن الترحيل
            $table->dropColumn('contract_value');
        });
    }
};
