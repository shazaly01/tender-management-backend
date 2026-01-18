<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OwnerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            // تنسيق التاريخ ليكون نصاً مقروءاً كما في ProjectResource
            'created_at' => $this->created_at->toDateTimeString(),
        ];
    }
}
