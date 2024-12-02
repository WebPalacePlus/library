<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class QueueResources extends JsonResource
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
            'username' => $this->user->name,
            'usercode' => $this->user->code,
            'bookcode' => $this->book->barcode,
            'bookname' => $this->book->name,
            'created_at' => $this->created_at
        ];
    }
}
