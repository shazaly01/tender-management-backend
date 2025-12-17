<?php

namespace App\Http\Requests\Document;

use App\Models\Document;
use Illuminate\Foundation\Http\FormRequest;

class StoreDocumentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', Document::class);
    }

   public function rules(): array
{
    return [
        'name' => 'required|string|max:255',
        'file' => 'required|file|mimes:pdf,jpg,png,doc,docx|max:10240',
        // التحقق الديناميكي
        'target_id' => 'required|numeric',
        'target_type' => 'required|in:company,project',
    ];
}
}
