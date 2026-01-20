<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class BookController extends Controller
{
    public function index(Request $request)
    {
        $query = Book::query();

        if ($request->has('search') && $request->search) {
            $keyword = $request->search;
            $query->where(function($q) use ($keyword) {
                $q->where('book_name', 'like', "%{$keyword}%")
                  ->orWhere('description', 'like', "%{$keyword}%");
            });
        }

        $books = $query->latest()->paginate(4);

        return response()->json([
            'status' => 'success',
            'data' => $books
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'book_name' => [
                'required', 
                'string', 
                'max:150',
                Rule::unique('books')->where(fn ($query) => $query->where('author', $request->author))
            ],
            'author' => 'required|string|max:150',
            'description' => 'required|string',
            'published_date' => 'required|date',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'errors' => $validator->errors()], 422);
        }

        $book = Book::create($request->all());

        return response()->json([
            'status' => 'success', 
            'message' => 'Book created successfully.', 
            'data' => $book
        ], 201);
    }

    public function show($id)
    {
        $book = Book::find($id);
        if (!$book) {
            return response()->json(['status' => 'error', 'message' => 'Book not found'], 404);
        }
        return response()->json(['status' => 'success', 'data' => $book]);
    }

    public function update(Request $request, $id)
    {
        $book = Book::find($id);
        if (!$book) {
            return response()->json(['status' => 'error', 'message' => 'Book not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'description' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'errors' => $validator->errors()], 422);
        }

        $book->update(['description' => $request->description]);

        return response()->json([
            'status' => 'success', 
            'message' => 'Description updated successfully.', 
            'data' => $book
        ]);
    }

    public function destroy($id)
    {
        $book = Book::find($id);
        if (!$book) {
            return response()->json(['status' => 'error', 'message' => 'Book not found'], 404);
        }
        
        $book->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Book deleted successfully.'
        ]);
    }
}
