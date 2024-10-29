<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReceiptResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_email' => $this->user->email,
            'budget_id' => $this->budget_id,
            'total' => $this->total,
            'total_verified' => $this->name,
            'name' => $this->name,
            'url' => $this->url,
            'created_at' => $this->created_at->format('d F'),
            'updated_at' => $this->updated_at->format('d F'),
        ];
    }
}
