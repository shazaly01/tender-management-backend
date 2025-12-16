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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->decimal('contract_value', 15, 2); // قيمة العقد، مناسب للأرقام المالية الكبيرة
            $table->date('award_date')->nullable(); // تاريخ إسناد المشروع

            // الربط مع جدول الشركات
            $table->foreignId('company_id')->constrained('companies')->onDelete('cascade');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
