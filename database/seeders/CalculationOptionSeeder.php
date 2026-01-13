<?php

namespace Database\Seeders;

use App\Models\CalculationOption;
use Illuminate\Database\Seeder;

class CalculationOptionSeeder extends Seeder
{
    public function run(): void
    {
        // إنشاء الخيارات من 1 إلى 10 كما طلبت
        // نسميها "Option 1" أو "الخيار 1" لتكون مرنة
        for ($i = 1; $i <= 10; $i++) {
            CalculationOption::create([
                'name' => "احتساب $i" // يمكنك تغيير التسمية هنا لاحقاً
            ]);
        }
    }
}
