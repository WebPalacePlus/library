<?php

namespace App\Http\Controllers;

use App\Http\Resources\BookResources;
use App\Http\Resources\QueueResources;
use App\Http\Resources\ReservationResources;
use App\Models\Book;
use App\Models\queues;
use App\Models\Reservation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class QueuesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $sort = $request->get('sort', 'id-desc');
        $sortableColumns = ['id'];

        $query = queues::with(['user', 'book'])
            ->whereHas('book', function ($query) {
                $query->whereRaw('(amount - (SELECT COUNT(*) FROM reservations WHERE reservations.book_id = books.id)) > 0');
            });


        $query = $this->sortService->Sort($query, $sort, $sortableColumns);
        $queues = $query->paginate(20);
        return QueueResources::collection($queues);
    }



    public function show(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required|integer|exists:books,id',
        ]);

        $book_id = $validated['id'];

        // Find the book by ID
        $book = Book::find($book_id);
        if (!$book) {
            return view("404");
        }

        // $reserved = Reservation::where('book_id', $book_id)->with(['user'])->first();
        $reserved = '';
        $book_queue = queues::where('book_id', $book_id)
            ->with(['user', 'book'])
            ->paginate(20);

        return view('queue', [
            'book' => new BookResources($book),
            'reserve' => $reserved ? new ReservationResources($reserved) : null,
            'queues' => QueueResources::collection($book_queue),
        ]);
    }

    public function accept(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required|integer|exists:queues,id',
        ]);

        $queueId = $validated['id'];
        $queue = queues::findOrFail($queueId);
        $book_id = $queue->book_id;
        $user_id = $queue->user_id;

        $isReserved = Reservation::where(['user_id' => $user_id, 'book_id' => $book_id])->exists();
        if ($isReserved) {
            return response()->json(['error' => 'کتاب قبلاً توسط این کاربر رزرو شده است.'], 400);
        }

        $deadline_days = 30;
        $deadline = now()->addDays($deadline_days)->format('Y-m-d');

        Reservation::create([
            'user_id' => $user_id,
            'book_id' => $book_id,
            'deadline' => $deadline,
        ]);

        $queue->delete();

        return response()->json(['message' => 'رزرو با موفقیت انجام شد.'], 200);
    }


    public function reject(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required|integer|exists:queues,id',
        ]);

        $queueId = $validated['id'];
        $queue = queues::findOrFail($queueId);

        $queue->delete();

        return response()->json(['message' => 'درخواست از صف انتظار حذف شد.'], 200);
    }



    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // To be implemented if needed
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required|integer|exists:books,id',
        ]);

        $book_id = $validated['id'];
        $user_id = Auth::id();

        $isReserved = Reservation::where(['book_id' => $book_id, 'user_id' => $user_id])->exists();
        if ($isReserved) {
            return response()->json([
                'message' => 'کتاب در حال حاضر توسط شما رزرو شده است. ابتدا کتاب را تحویل داده سپس اقدام به رزرو آن کنید.'
            ]);
        }

        $isQueued = queues::where(['book_id' => $book_id, 'user_id' => $user_id])->exists();
        if ($isQueued) {
            return response()->json([
                'message' => 'درخواست رزرو شما فعلا در صف انتظار قرار دارد. پس از موجود شدن کتاب نسبت به رزرو آن اقدام کنید.'
            ]);
        }

        queues::create([
            'user_id' => $user_id,
            'book_id' => $book_id
        ]);

        return response()->json([
            'message' => 'درخواست رزرو شما در صف انتظار قرار گرفت. با توجه به صف نسبت به گرفتن کتاب از کتابخانه اقدام کنید.'
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(queues $queue)
    {
        // To be implemented if needed
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, queues $queue)
    {
        // To be implemented if needed
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required|integer|exists:books,id',
        ]);

        $book_id = $validated['id'];
        $user_id = Auth::id();

        // Find and delete the queue entry
        $queue = queues::where(['book_id' => $book_id, 'user_id' => $user_id]);
        if ($queue->exists()) {
            $queue->delete();
            return response()->json(['message' => 'درخواست شما از صف انتظار حذف شد']);
        }

        return response()->json(['error' => 'درخواستی برای لغو کردن وجود ندارد.'], 404);
    }
}
