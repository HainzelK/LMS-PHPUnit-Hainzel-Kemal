<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Admin;
use App\Models\Librarian;
use App\Models\CollectionUpdate;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Check if the user is an admin.
     */
    private function ensureAdmin()
    {
        if (!Admin::where('user_id', auth()->id())->exists()) {
            abort(403, 'Unauthorized');
        }
    }

    public function showBooks()
{
    $this->ensureAdmin();

    // Get all books, you can paginate if you want to limit the results
    $books = Book::latest()->get(); // Use paginate() if you prefer pagination

    return view('admin.books.index', compact('books'));
}


    /**
     * Show the list of librarians.
     */
    public function index()
    {
        $this->ensureAdmin();

        $librarians = Librarian::with('user')->get();
        return view('admin.librarians.index', compact('librarians'));
    }

    /**
     * Show the form to add a new librarian.
     */
    public function createLibrarian()
    {
        $this->ensureAdmin();

        return view('admin.librarians.create');
    }

    /**
     * Store a new librarian.
     */
    public function storeLibrarian(Request $request)
    {
        $this->ensureAdmin();

        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        Librarian::create($validated);
        return redirect()->route('admin.librarians.index')->with('success', 'Librarian added successfully.');
    }

    /**
     * Delete a librarian.
     */
    public function destroyLibrarian($id)
    {
        $this->ensureAdmin();

        $librarian = Librarian::findOrFail($id);
        $librarian->delete();

        return redirect()->route('admin.librarians.index')->with('success', 'Librarian removed successfully.');
    }

    /**
     * Approve or reject a book.
     */
    public function updateBookStatus(Request $request, $id)
    {
        $this->ensureAdmin();

        $validated = $request->validate([
            'status' => 'required|in:approved,rejected',
        ]);

        $book = Book::findOrFail($id);
        $book->status = $validated['status'];
        $book->save();

        return redirect()->route('admin.books.index')->with('success', 'Book status updated successfully.');
    }

    /**
     * Approve or reject a collection update.
     */
    public function updateCollectionStatus(Request $request, $id)
    {
        $this->ensureAdmin();

        $validated = $request->validate([
            'status' => 'required|in:approved,rejected',
        ]);

        $collectionUpdate = CollectionUpdate::findOrFail($id);
        $collectionUpdate->status = $validated['status'];
        $collectionUpdate->save();

        return redirect()->route('admin.collection-updates.index')->with('success', 'Collection update status changed successfully.');
    }
}
