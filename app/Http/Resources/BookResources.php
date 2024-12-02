<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookResources extends JsonResource
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
            'name' => $this->name,
            'author' => $this->author,
            'field' => $this->field->name,
            'publisher' => $this->publisher,
            'publish_date' => $this->publish_date,
            'barcode' => $this->barcode,
            'amount' => $this->amount,
            'available_count' => $this->available_count,
            'status' => $this->status
        ];
    }
}
