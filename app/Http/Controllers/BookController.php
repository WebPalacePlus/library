<?php

namespace App\Http\Controllers;

use App\Http\Resources\BookResources;
use App\Models\Book;
use App\Models\Field;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\Console\Input\Input;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $sort = $request->get('sort', 'id-desc');
        $sortableColumns = ['id', 'name'];
        $count = $request->get('count', 25);

        $query = Book::with('field');
        $query = $this->sortService->Sort($query, $sort, $sortableColumns);
        $userId = Auth::id();
        $books = $query->with([
            'queues' => function ($query) use ($userId) {
                $query->where('user_id', $userId);
            },
            'reservations' => function ($query) use ($userId) {
                $query->where('user_id', $userId);
            },
        ])->paginate($count);
        foreach ($books as $book) {
            $hasReservation = $book->reservations->isNotEmpty();
            if ($hasReservation) {
                $book->status = 2;
            } else {
                $inQueue = $book->queues->isNotEmpty();
                $book->status = $inQueue ? 1 : 0;
            }
            
            $book->available_count = $book->availableForReservation();
        }
        return BookResources::collection($books);
    }



    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $fields = Field::all();
        return view("admin.book.add", ['fields' => $fields]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:64',
            'author' => 'required|string|max:64',
            'publisher' => 'required|string|max:64',
            'barcode' => 'required|string|max:17|min:17|unique:books,barcode',
            'amount' => 'required|integer|max:99|min:1',
            'field' => 'required|exists:fields,id',
            'publish_date' => 'required'
        ]);

        $book = new Book([
            'name' => $validatedData['name'],
            'author' => $validatedData['author'],
            'publisher' => $validatedData['publisher'],
            'barcode' => $validatedData['barcode'],
            'field_id' => $validatedData['field'],
            'amount' => $validatedData['amount'],
            'publish_date' => $validatedData['publish_date']
        ]);
        $book->save();

        return response()->json([
            'message' => 'Book created successfully',
            'data' => $book,
        ]);
    }


    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $book = Book::find($id);
        return new BookResources($book);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Book $book)
    {
        $fields = Field::all();
        return view("admin.book.edit", ['book' => $book, 'fields' => $fields]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $validatedData = $request->validate([
            'id' => 'required|exists:books,id',
            'name' => 'required|string|max:64',
            'author' => 'required|string|max:64',
            'publisher' => 'required|string|max:64',
            'barcode' => 'required|string|max:17|min:17',
            'field' => 'required|exists:fields,id',
            'amount' => 'required|max:99|min:1',
            'publish_date' => 'required'
        ]);

        $book = Book::findOrFail($validatedData['id']);

        $book->update([
            'name' => $validatedData['name'],
            'author' => $validatedData['author'],
            'publisher' => $validatedData['publisher'],
            'barcode' => $validatedData['barcode'],
            'field_id' => $validatedData['field'],
            'amount' => $validatedData['amount'],
            'publish_date' => $validatedData['publish_date']
        ]);


        return response()->json([
            'message' => 'Book updated successfully',
            'data' => $book,
        ]);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Book $book)
    {
        $book->delete();
        return response()->json([
            'message' => 'Book deleted successfully',
        ]);
    }
}
