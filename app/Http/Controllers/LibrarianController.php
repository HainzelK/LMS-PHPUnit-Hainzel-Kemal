<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\CD;
use App\Models\Newspaper;
use App\Models\Journal;
use App\Models\Reservation;
use App\Models\CollectionUpdate;
use Illuminate\Http\Request;

class LibrarianController extends Controller
{
    /**
     * Show the library inventory.
     */
    public function index()
    {
        $books = Book::all();
        $cds = CD::all();
        $newspapers = Newspaper::all();
        $journals = Journal::all();

        return view('librarian.inventory.index', compact('books', 'cds', 'newspapers', 'journals'));
    }

    /**
     * Request a new collection update.
     */
    public function requestCollectionUpdate(Request $request)
    {
        $validated = $request->validate([
            'book_id' => 'nullable|exists:books,id',
            'cd_id' => 'nullable|exists:cds,id',
            'newspaper_id' => 'nullable|exists:newspapers,id',
            'journal_id' => 'nullable|exists:journals,id',
            'action' => 'required|in:add,update,remove',
        ]);

        CollectionUpdate::create([
            'librarian_id' => auth()->user()->librarian->id,
            'book_id' => $validated['book_id'],
            'cd_id' => $validated['cd_id'],
            'newspaper_id' => $validated['newspaper_id'],
            'journal_id' => $validated['journal_id'],
            'action' => $validated['action'],
        ]);

        return redirect()->route('librarian.collection-updates.index')->with('success', 'Collection update requested.');
    }

    /**
     * Approve or reject a reservation.
     */
    public function updateReservationStatus(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => 'required|in:approved,rejected',
        ]);

        $reservation = Reservation::findOrFail($id);
        $reservation->status = $validated['status'];
        $reservation->save();

        return redirect()->route('librarian.reservations.index')->with('success', 'Reservation status updated successfully.');
    }
}
