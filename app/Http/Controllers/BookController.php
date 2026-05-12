<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function index() {
        $books = Book::all();
        return view('pages.master_data.books', compact('books'));
    }
    public function store(Request $request)
    {
        $data = $request->all();

        if($request->hasFile('cover')){
            $data['cover'] = $request->file('cover')->store('books', 'public');
        }

        Book::create($data);

        return response()->json(['success' => true]);
    }
    public function show($id)
    {
        $book = Book::findOrFail($id);

        $book->cover_url = $book->cover 
            ? asset('storage/' . $book->cover) 
            : asset('default-book.png');

        return response()->json($book);
    }
    public function update(Request $request, $id)
    {
        $book = Book::findOrFail($id);

        $data = $request->all();

        if($request->hasFile('cover')){
            $data['cover'] = $request->file('cover')->store('books', 'public');
        }

        $book->update($data);

        return response()->json(['success' => true]);
    }
    public function destroy($id) {
        $book = Book::findOrFail($id);
        $book->delete();

        return response()->json([
            'success' => true,
            'message' => 'Book deleted successfully.'
        ]);
    }
    public function search(Request $request)
    {
        $search = $request->q;

        $query = Book::query();

        if ($search) {
            $query->where('title', 'LIKE', "%{$search}%");
        }

        $books = $query->select('id', 'title')
                    ->limit(10)
                    ->get();

        return response()->json($books);
    }
}