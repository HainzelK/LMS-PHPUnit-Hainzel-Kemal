<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;

class BookController extends Controller
{
    /**
     * Display a listing of books.
     *
     * @return View
     */
    public function index(): View
    {
        // Get all books with pagination
        $books = Book::latest()->paginate(10);

        // Render view with books
        return view('books.index', compact('books'));
    }

    /**
     * Show the form for creating a new book.
     *
     * @return View
     */
    public function create(): View
    {
        return view('books.create');
    }

    /**
     * Store a newly created book in storage.
     *
     * @param  Request  $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        // Validate the form
        $request->validate([
            'title'            => 'required|min:5',
            'type'             => 'required',
            'author'           => 'nullable|min:3',
            'publisher'        => 'nullable|min:3',
            'access_level'     => 'required|in:public,restricted',
            'is_physical'      => 'required|boolean',
            'file'             => 'nullable|file|mimes:pdf,epub|max:2048',
            'publication_date' => 'nullable|date',
        ]);
    
        // Handle file upload
        $filePath = null;
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $filePath = $file->storeAs('public/books', $file->hashName());
        }
    
        // Create the book with status 'pending'
        Book::create([
            'title'            => $request->title,
            'type'             => $request->type,
            'author'           => $request->author,
            'publisher'        => $request->publisher,
            'access_level'     => $request->access_level,
            'is_physical'      => $request->is_physical,
            'file_path'        => $filePath ? $file->hashName() : null,
            'publication_date' => $request->publication_date,
            'status'           => 'pending', // Default status is 'pending'
        ]);
    
        return redirect()->route('books.index')->with('success', 'Book created successfully and is pending approval!');
    }
        /**
     * Show a single book.
     *
     * @param  string  $id
     * @return View
     */
    public function show(string $id): View
    {
        $book = Book::findOrFail($id);
        return view('books.show', compact('book'));
    }

    /**
     * Show the form for editing a book.
     *
     * @param  string  $id
     * @return View
     */
    public function edit(string $id): View
    {
        $book = Book::findOrFail($id);
        return view('books.edit', compact('book'));
    }

    /**
     * Update the specified book in storage.
     *
     * @param  Request  $request
     * @param  string   $id
     * @return RedirectResponse
     */
    public function update(Request $request, string $id): RedirectResponse
    {
        $request->validate([
            'title'            => 'required|min:5',
            'type'             => 'required',
            'author'           => 'nullable|min:3',
            'publisher'        => 'nullable|min:3',
            'access_level'     => 'required|in:public,restricted',
            'is_physical'      => 'required|boolean',
            'file'             => 'nullable|file|mimes:pdf,epub|max:2048',
            'publication_date' => 'nullable|date',
        ]);

        $book = Book::findOrFail($id);

        // Handle file upload
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $filePath = $file->storeAs('public/books', $file->hashName());

            // Delete old file if exists
            if ($book->file_path) {
                Storage::delete('public/books/' . $book->file_path);
            }

            $book->file_path = $file->hashName();
        }

        // Update the book details
        $book->update([
            'title'            => $request->title,
            'type'             => $request->type,
            'author'           => $request->author,
            'publisher'        => $request->publisher,
            'access_level'     => $request->access_level,
            'is_physical'      => $request->is_physical,
            'file_path'        => $book->file_path,
            'publication_date' => $request->publication_date,
        ]);

        return redirect()->route('books.index')->with(['success' => 'Book updated successfully!']);
    }

    /**
     * Remove the specified book from storage.
     *
     * @param  string  $id
     * @return RedirectResponse
     */
    public function destroy(string $id): RedirectResponse
    {
        $book = Book::findOrFail($id);

        // Delete the associated file
        if ($book->file_path) {
            Storage::delete('public/books/' . $book->file_path);
        }

        $book->delete();

        return redirect()->route('books.index')->with(['success' => 'Book deleted successfully!']);
    }
}
