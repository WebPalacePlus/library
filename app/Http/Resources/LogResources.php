<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LogResources extends JsonResource
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
            'user_name' => $this->user->name,
            'user_code' => $this->user->code,
            'book_name' => $this->book->name,
            'book_id' => $this->book->barcode,
            'reserve_date' => $this->reserve_date,
            'deadline_date' => $this->deadline_date,
            'return_date' => $this->return_date
        ];
    }
}
