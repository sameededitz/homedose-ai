<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FamilyMemberResource extends JsonResource
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
            'name' => $this->name,
            'gender' => $this->gender,
            'relationship' => $this->relationship,
            'message' => $this->message,
            'image_url' => $this->image_url,
            'created_at' => $this->created_at,
            'chat' => $this->whenLoaded('chat', function () {
                return new ChatResource($this->chat);
            }),
        ];
    }
}
