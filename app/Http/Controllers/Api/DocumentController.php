<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Document\StoreDocumentRequest;
use App\Http\Resources\Api\DocumentResource;
use App\Models\Document;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    public function __construct()
    {
        // تطبيق الصلاحيات تلقائيًا
        // ملاحظة: authorizeResource لا تدعم index بشكل جيد مع الفلترة، لذا سنتحقق منها يدويًا
        $this->authorizeResource(Document::class, 'document');
    }

    public function index(): AnonymousResourceCollection
    {
        // التحقق من الصلاحية يدويًا
        $this->authorize('viewAny', Document::class);

        $query = Document::with('company');

        if ($companyId = request('company_id')) {
            $query->where('company_id', $companyId);
        }

        $documents = $query->latest()->paginate(20);

        return DocumentResource::collection($documents);
    }

    public function store(StoreDocumentRequest $request): JsonResponse
    {
        // 1. تخزين الملف
        // 'documents' هو اسم المجلد داخل storage/app/public
        $path = $request->file('file')->store('documents', 'public');

        // 2. إنشاء السجل في قاعدة البيانات
        $document = Document::create([
            'name' => $request->name,
            'file_path' => $path,
            'company_id' => $request->company_id,
        ]);

        return response()->json([
            'message' => 'Document uploaded successfully.',
            'data' => DocumentResource::make($document),
        ], Response::HTTP_CREATED);
    }

    public function show(Document $document): DocumentResource
    {
        $document->load('company');
        return DocumentResource::make($document);
    }

    // لا نحتاج لدالة update، لأن تحديث مستند هو عملية حذف ثم رفع

    public function destroy(Document $document): Response
    {
        // 1. حذف الملف من الـ storage
        Storage::disk('public')->delete($document->file_path);

        // 2. حذف السجل من قاعدة البيانات
        $document->delete();

        return response()->noContent();
    }
}
