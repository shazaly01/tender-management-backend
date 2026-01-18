<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Project\StoreProjectRequest;
use App\Http\Requests\Project\UpdateProjectRequest;
use App\Http\Resources\Api\ProjectResource;
use App\Models\Project;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class ProjectController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Project::class, 'project');
    }

public function index(): AnonymousResourceCollection
    {
        $this->authorize('viewAny', Project::class);

        // 1. نبدأ الاستعلام مع العلاقات (أضفنا owner هنا)
        $query = Project::query()
            ->with(['company', 'owner', 'calculationOption']) // <--- تم التعديل
            ->withSum('payments', 'amount');

        // 2. البحث الذكي (اسم المشروع، اسم الشركة، أو اسم المالك)
        if ($search = request('search')) {
            $query->where(function($q) use ($search) {
                // البحث في اسم المشروع
                $q->where('name', 'like', "%{$search}%")
                  // البحث في اسم الشركة
                  ->orWhereHas('company', function($companyQuery) use ($search) {
                      $companyQuery->where('name', 'like', "%{$search}%");
                  })
                  // البحث في اسم المالك (الجديد)
                  ->orWhereHas('owner', function($ownerQuery) use ($search) {
                      $ownerQuery->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // 3. الفلاتر
        if ($companyId = request('company_id')) {
            $query->where('company_id', $companyId);
        }

        // فلتر المالك الجديد (إضافة مفيدة للمستقبل)
        if ($ownerId = request('owner_id')) {
            $query->where('owner_id', $ownerId);
        }

        if (request()->filled('has_contract_permission')) {
            $query->where('has_contract_permission', request()->boolean('has_contract_permission'));
        }

        // 4. الترتيب والترقيم
        $projects = $query->latest()->paginate(15);

        return ProjectResource::collection($projects);
    }


    public function store(StoreProjectRequest $request): JsonResponse
    {
        $project = Project::create($request->validated());
        // بعد الإنشاء، لا توجد دفعات، لذا يمكننا إرجاعه مباشرة
        return response()->json([
            'message' => 'Project created successfully.',
            'data' => ProjectResource::make($project),
        ], Response::HTTP_CREATED);
    }

    public function show(Project $project): ProjectResource
    {
        // === [التعديل هنا] ===
        // سنستخدم نفس الطريقة لتحميل المجموع لمشروع واحد
        $project->loadSum('payments', 'amount')->load(['company', 'calculationOption']);

        return ProjectResource::make($project);
    }

    public function update(UpdateProjectRequest $request, Project $project): JsonResponse
    {
        $project->update($request->validated());

        // === [التعديل هنا] ===
        // بعد التحديث، أعد تحميل المشروع مع المجموع الجديد
        $project->fresh()->loadSum('payments', 'amount')->load('company');

        return response()->json([
            'message' => 'Project updated successfully.',
            'data' => ProjectResource::make($project),
        ]);
    }

    public function destroy(Project $project): Response
    {
        $project->delete();
        return response()->noContent();
    }
}
