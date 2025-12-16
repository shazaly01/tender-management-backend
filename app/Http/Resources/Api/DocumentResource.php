<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class DocumentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            // إنشاء رابط كامل للملف
            'url' => Storage::url($this->file_path),
            'company' => CompanyResource::make($this->whenLoaded('company')),
            'created_at' => $this->created_at->toDateTimeString(),
        ];
    }
}
