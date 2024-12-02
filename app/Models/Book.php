<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $fillable = [
        'name',
        'author',
        'publisher',
        'barcode',
        'field_id',
        'publish_date',
        'amount',
        'status'
    ];

    public function field()
    {
        return $this->belongsTo(Field::class, 'field_id');
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    public function reservedCount()
    {
        return $this->reservations()->count();
    }

    public function availableForReservation()
    {
        return $this->amount - $this->reservedCount();
    }

    public function queues(){
        return $this->hasMany(queues::class);
    }
}
