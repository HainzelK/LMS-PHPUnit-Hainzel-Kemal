<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Borrow;
use App\Models\Lecturer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class LecturerController extends Controller
{
    /**
     * Display a listing of books for lecturers to borrow.
     *
     * @return View
     */
    public function index(): View
    {
        $books = Book::all();
        return view('lecturers.index', compact('books'));
    }

    /**
     * Borrow a book for 3 days.
     *
     * @param  Request  $request
     * @return RedirectResponse
     */
    public function borrow(Request $request): RedirectResponse
    {
        $request->validate([
            'book_id' => 'required|exists:books,id',
        ]);

        $book = Book::findOrFail($request->book_id);

        // Check if the lecturer has a valid borrowing period
        $borrow = new Borrow();
        $borrow->lecturer_id = Auth::user()->lecturer->id;
        $borrow->book_id = $book->id;
        $borrow->borrowed_at = now();
        $borrow->due_date = now()->addDays(3); // Set the due date to 3 days for lecturers
        $borrow->save();

        return redirect()->route('lecturers.index')->with('success', 'Book borrowed successfully for 3 days.');
    }
}
