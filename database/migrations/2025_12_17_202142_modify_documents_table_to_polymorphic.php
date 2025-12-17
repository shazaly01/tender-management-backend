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
        Schema::table('documents', function (Blueprint $table) {
            // 1. حذف المفتاح الأجنبي القديم أولاً (Foreign Key Constraint)
            $table->dropForeign(['company_id']);

            // 2. حذف العمود القديم
            $table->dropColumn('company_id');

            // 3. إضافة أعمدة العلاقة متعددة الأوجه (Polymorphic Columns)
            // نستخدم decimal(18, 0) للمعرف (ID) بناءً على تعليماتك الخاصة بالأكواد الطويلة
            $table->decimal('documentable_id', 18, 0);

            // عمود لنوع الموديل (مثلاً: App\Models\Project أو App\Models\Company)
            $table->string('documentable_type');

            // 4. إضافة Index لتحسين أداء الاستعلامات عند الفلترة
            $table->index(['documentable_id', 'documentable_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('documents', function (Blueprint $table) {
            // التراجع عن التغييرات في حال أردت عمل Rollback
            $table->dropIndex(['documentable_id', 'documentable_type']);
            $table->dropColumn(['documentable_id', 'documentable_type']);

            // إعادة العمود القديم
            $table->foreignId('company_id')->constrained('companies')->onDelete('cascade');
        });
    }
};
