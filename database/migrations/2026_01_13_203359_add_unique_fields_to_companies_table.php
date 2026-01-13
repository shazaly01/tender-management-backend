<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            // جعل رقم الرخصة فريداً
            $table->unique('license_number');

            // جعل السجل التجاري فريداً (تأكد من وجود العمود أولاً)
            // إذا كان العمود غير موجود، يمكنك إضافته هنا: $table->string('commercial_record')->nullable()->unique();
            $table->unique('commercial_record');
        });
    }

    public function down(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropUnique(['license_number']);
            $table->dropUnique(['commercial_record']);
        });
    }
};
