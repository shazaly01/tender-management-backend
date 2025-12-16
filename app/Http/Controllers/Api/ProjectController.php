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

    public function index(): AnonymousResourceCollection // <-- تم إضافة النوع للوضوح
    {
        $this->authorize('viewAny', Project::class);

        // === [التعديل هنا] ===
        // أضفنا withSum لحساب مجموع حقل 'amount' من علاقة 'payments'
        // سيتم تخزين النتيجة في خاصية جديدة اسمها `payments_sum_amount`
        $query = Project::query()
            ->with('company')
            ->withSum('payments', 'amount'); // <-- السطر الجديد والمهم

        if ($companyId = request('company_id')) { // <-- تحسين بسيط
            $query->where('company_id', $companyId);
        }

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
        $project->loadSum('payments', 'amount')->load('company');

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
