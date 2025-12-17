<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DocumentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,

            // --- التعديل هنا ---
            // نستخدم الخاصية $this->url التي أنشأناها في الموديل
            // هذا سيضمن أن الرابط كامل وصحيح (http://localhost:8000/storage/...)
            'url' => $this->url,

            'company' => CompanyResource::make($this->whenLoaded('company')),
            'created_at' => $this->created_at->toDateTimeString(),
        ];
    }
}
