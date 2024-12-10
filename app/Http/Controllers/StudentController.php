<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Borrow;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class StudentController extends Controller
{
    /**
     * Display a listing of books for students to borrow.
     *
     * @return View
     */
    public function index(): View
    {
        $books = Book::all();
        return view('students.index', compact('books'));
    }

    /**
     * Borrow a book for less than 5 days.
     *
     * @param  Request  $request
     * @return RedirectResponse
     */
    public function borrow(Request $request): RedirectResponse
    {
        $request->validate([
            'book_id' => 'required|exists:books,id',
            'days' => 'required|integer|min:1|max:4', // Only allow borrowing for 1-4 days
        ]);

        $book = Book::findOrFail($request->book_id);

        // Check if the student has a valid borrowing period
        $borrow = new Borrow();
        $borrow->student_id = Auth::user()->student->id;
        $borrow->book_id = $book->id;
        $borrow->borrowed_at = now();
        $borrow->due_date = now()->addDays($request->days); // Set the due date based on days
        $borrow->save();

        return redirect()->route('students.index')->with('success', 'Book borrowed successfully for ' . $request->days . ' days.');
    }
}
