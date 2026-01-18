<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Owner;
use App\Http\Requests\Owner\StoreOwnerRequest;
use App\Http\Requests\Owner\UpdateOwnerRequest;
use App\Http\Resources\Api\OwnerResource;
use Illuminate\Http\Request;

class OwnerController extends Controller
{
    /**
     * تفعيل نظام الصلاحيات (Policy)
     */
    public function __construct()
    {
        // يربط دوال المتحكم بصلاحيات OwnerPolicy تلقائياً
        // مثلاً: index تطلب viewAny, store تطلب create... وهكذا
        $this->authorizeResource(Owner::class, 'owner');
    }

    /**
     * عرض قائمة الملاك
     */
    public function index()
    {
        // جلب الكل مرتبين حسب الأحدث
        $owners = Owner::latest()->get();
        return OwnerResource::collection($owners);
    }

    /**
     * حفظ مالك جديد
     */
    public function store(StoreOwnerRequest $request)
    {
        // البيانات القادمة هنا مفحوصة وجاهزة
        $owner = Owner::create($request->validated());

        return response()->json([
            'message' => 'تم إضافة الجهة المالكة بنجاح.',
            'data' => new OwnerResource($owner),
        ], 201);
    }

    /**
     * عرض تفاصيل مالك محدد
     */
    public function show(Owner $owner)
    {
        return new OwnerResource($owner);
    }

    /**
     * تحديث بيانات مالك
     */
    public function update(UpdateOwnerRequest $request, Owner $owner)
    {
        $owner->update($request->validated());

        return response()->json([
            'message' => 'تم تعديل البيانات بنجاح.',
            'data' => new OwnerResource($owner),
        ]);
    }

    /**
     * حذف مالك
     */
    public function destroy(Owner $owner)
    {
        $owner->delete();

        return response()->json([
            'message' => 'تم حذف الجهة المالكة بنجاح.',
        ]);
    }
}
