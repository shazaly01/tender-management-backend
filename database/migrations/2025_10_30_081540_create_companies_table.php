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
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('tax_number')->nullable()->unique(); // الرقم الضريبي، يفضل أن يكون فريدًا
            $table->text('address')->nullable();
            $table->string('owner_name')->nullable();
            $table->timestamps(); // تنشئ created_at و updated_at
            $table->softDeletes(); // تضيف عمود deleted_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};
