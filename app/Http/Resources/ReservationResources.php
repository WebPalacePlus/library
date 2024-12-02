<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReservationResources extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'user_name' => $this->user->name,
            'user_code' => $this->user->code,
            'book_name' => $this->book->name,
            'book_id' => $this->book->barcode,
            'created_at' => $this->created_at,
        ];
    }
}
