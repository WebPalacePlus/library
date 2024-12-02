<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class logs extends Model
{
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    
    public function book(){
        return $this->belongsTo(Book::class, 'book_id');
    }
    
    protected $fillable = [
        'user_id',
        'book_id',
        'reserve_date',
        'deadline_date',
        'return_date'
    ];
}
