<?php

namespace App\Http\Controllers;

use App\Http\Resources\BookResources;
use App\Http\Resources\FieldResources;
use App\Http\Resources\LogResources;
use App\Http\Resources\QueueResources;
use App\Http\Resources\ReservationResources;
use App\Http\Resources\UserResources;
use App\Models\Book;
use App\Models\Field;
use App\Models\logs;
use App\Models\queues;
use App\Models\Reservation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SearchController extends Controller
{
    public function book(Request $request)
    {
        $like = $request->get('q');
        $results = Book::where('name', 'LIKE', "%{$like}%")->orWhere('barcode', 'LIKE', "%{$like}%")->paginate(20);
        return BookResources::collection($results);
    }

    public function user(Request $request)
    {
        $like = $request->get('q');
        $results = User::where('name', 'LIKE', "%{$like}%")->orWhere('code', 'LIKE', "%{$like}%")->paginate(20);
        return UserResources::collection($results);
    }

    public function queue(Request $request)
    {
        $like = $request->get('q');
        $results = queues::whereHas('user', function ($query) use ($like) {
            $query->where('name', 'LIKE', "%{$like}%")
                ->orWhere('code', 'LIKE', "%{$like}%");
        })
            ->orWhereHas('book', function ($query) use ($like) {
                $query->where('name', 'LIKE', "%{$like}%")
                    ->orWhere('barcode', 'LIKE', "%{$like}%");
            })
            ->paginate(20);
        return QueueResources::collection($results);
    }

    public function log(Request $request)
    {
        $like = $request->get('q');
        $results = logs::whereHas('user', function ($query) use ($like) {
            $query->where('name', 'LIKE', "%{$like}%")
                ->orWhere('code', 'LIKE', "%{$like}%");
        })
            ->orWhereHas('book', function ($query) use ($like) {
                $query->where('name', 'LIKE', "%{$like}%")
                    ->orWhere('barcode', 'LIKE', "%{$like}%");
            })
            ->paginate(20);
        return LogResources::collection($results);
    }

    public function reservation(Request $request)
    {
        $like = $request->get('q');
        $results = Reservation::whereHas('user', function ($query) use ($like) {
            $query->where('name', 'LIKE', "%{$like}%")
                ->orWhere('code', 'LIKE', "%{$like}%");
        })
            ->orWhereHas('book', function ($query) use ($like) {
                $query->where('name', 'LIKE', "%{$like}%")
                    ->orWhere('barcode', 'LIKE', "%{$like}%");
            })
            ->paginate(20);
        return ReservationResources::collection($results);
    }

    public function field(Request $request)
    {
        $like = $request->get('q');
        $results = Field::where('name', 'LIKE', "%$like%")
            ->paginate(20);
        return FieldResources::collection($results);
    }

    

}
