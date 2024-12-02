<?php
namespace App\Http\Controllers;

use App\Http\Resources\BookResources;
use App\Models\Book;
use Illuminate\Http\Request;
use Auth;

class LibraryController extends Controller
{
    public function index(Request $request)
    {
        $sort = explode('-', $request->get('sort', 'id-desc'));
        $column = $sort[0] ?? 'id';
        $direction = $sort[1] ?? 'desc';
        $sortableColumns = ['id', 'name'];
        $count = $request->get('count',25);

        if (!in_array($column, $sortableColumns)) {
            $column = 'id';
        }

        $userId = Auth::id();
        $books = Book::with('field')
            ->with([
                'queues' => function ($query) use ($userId) {
                    $query->where('user_id', $userId);
                },
                'reservations' => function ($query) use ($userId) {
                    $query->where('user_id', $userId);
                },
            ])
            ->orderBy($column, $direction)
            ->paginate($count);

        foreach ($books as $book) {
            $hasReservation = $book->reservations->isNotEmpty();
            if ($hasReservation) {
                $book->status = 2;
            } else {
                $inQueue = $book->queues->isNotEmpty();
                $book->status = $inQueue ? 1 : 0;
            }
        }

        return BookResources::collection($books);
    }
}
