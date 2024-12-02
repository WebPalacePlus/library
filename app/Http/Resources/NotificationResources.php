<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NotificationResources extends JsonResource
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
            'subject' => $this->subject,
            'text' => $this->text,
            'isRead' => $this->isRead,
            'created_at' => $this->created_at
        ];
    }
}
