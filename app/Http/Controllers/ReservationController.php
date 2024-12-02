<?php

namespace App\Http\Controllers;

use App\Http\Resources\ReservationResources;
use App\Models\Book;
use App\Models\logs;
use App\Models\queues;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReservationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $sort = $request->get('sort', 'id-desc');
        $sortableColumns = ['id'];

        $query = Reservation::with(['user','book']);
        $query = $this->sortService->Sort($query, $sort, $sortableColumns);

        $fields = $query->paginate(20);
        return ReservationResources::collection($fields);
    }

    public function return(Request $request){
        $validated = $request->validate([
            'id' => 'required|integer|exists:reservations,id',
        ]);

        $id = $validated['id'];
        $reservation = Reservation::findorFail($id);
        $user_id = $reservation->user_id;
        $book_id = $reservation->book_id;
        $reserve_date = $reservation->created_at;
        $deadline_date = $reservation->deadline;
        $return_date = date("Y-m-d");
        logs::create([
            'user_id' => $user_id,
            'book_id' => $book_id,
            'reserve_date' => $reserve_date,
            'deadline_date' => $deadline_date,
            'return_date' => $return_date
        ]);
        $reservation->delete();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Book $book)
    {
        $user = Auth::user();
        if (!$user) {
            return redirect("/login");
        }

        if (!$book) {
            return response()->json(['message' => 'Book not found'], 404);
        }

        $isQueued = queues::where(['book_id' => $book->id, 'user_id' => $user->id] )
            ->exists();

        if ($isQueued) {
            return response()->json(['message' => 'This book is already queued.'], 400);
        }

        queues::create([
            'user_id' => $user->id,
            'book_id' => $book->id,
            'deadline' => now()->addMonthWithoutOverflow(),
        ]);

        return response()->json(['message' => 'You added to queue successfully!'], 200);
    }


    /**
     * Display the specified resource.
     */
    public function show(Reservation $reservation)
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Reservation $reservation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Reservation $reservation)
    {
        $isReserved = Reservation::where(['book_id' => $reservation->book_id]);
        if($isReserved)
            return response()->json(['message' => 'کتاب در حال حاضر رزرو شده است.']);
        $reservation->update(['status' => 'active']);
        return response()->json(['message' => 'کتاب رزرو شد.']);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Reservation $reservation)
    {
        $user_id = Auth::id();
        
    }
}
