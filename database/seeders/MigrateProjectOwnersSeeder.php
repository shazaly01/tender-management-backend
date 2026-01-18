<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Project;
use App\Models\Owner;
use Illuminate\Support\Facades\DB;

class MigrateProjectOwnersSeeder extends Seeder
{
    public function run(): void
    {
        // نستخدم Transaction لضمان سلامة البيانات
        DB::transaction(function () {
            // نجلب كل المشاريع التي لها اسم مالك قديم وليس لها ربط جديد
            $projects = Project::whereNotNull('project_owner')
                               ->where('project_owner', '!=', '')
                               ->whereNull('owner_id')
                               ->get();

            foreach ($projects as $project) {
                $oldName = trim($project->project_owner);

                // البحث عن المالك بالاسم، أو إنشاؤه إذا لم يكن موجوداً
                // firstOrCreate تضمن عدم تكرار الملاك
                $owner = Owner::firstOrCreate(
                    ['name' => $oldName]
                );

                // تحديث المشروع لربطه بالمالك الجديد
                $project->owner_id = $owner->id;
                $project->save();
            }
        });
    }
}
