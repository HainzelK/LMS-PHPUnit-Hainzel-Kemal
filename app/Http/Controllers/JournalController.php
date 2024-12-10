<?php

namespace App\Http\Controllers;

use App\Models\Journal;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class JournalController extends Controller
{
    /**
     * Display a listing of journals.
     *
     * @return View
     */
    public function index(): View
    {
        // Get all journals with pagination
        $journals = Journal::latest()->paginate(10);

        // Render view with journals
        return view('journals.index', compact('journals'));
    }

    /**
     * Show the form for creating a new journal.
     *
     * @return View
     */
    public function create(): View
    {
        return view('journals.create');
    }

    /**
     * Store a newly created journal in storage.
     *
     * @param  Request  $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        // Validate the form
        $request->validate([
            'title'             => 'required|min:5',
            'author'            => 'required|min:3',
            'publisher'         => 'required|min:3',
            'publication_date'  => 'required|date',
            'volume'            => 'nullable|string',
            'issue'             => 'nullable|string',
            'abstract'          => 'nullable|string',
            'restricted_access' => 'required|boolean',
        ]);

        // Create the journal
        Journal::create($request->only(
            'title',
            'author',
            'publisher',
            'publication_date',
            'volume',
            'issue',
            'abstract',
            'restricted_access'
        ));

        return redirect()->route('journals.index')->with(['success' => 'Journal created successfully!']);
    }

    /**
     * Show a single journal.
     *
     * @param  string  $id
     * @return View
     */
    public function show(string $id): View
    {
        $journal = Journal::findOrFail($id);
        return view('journals.show', compact('journal'));
    }

    /**
     * Show the form for editing a journal.
     *
     * @param  string  $id
     * @return View
     */
    public function edit(string $id): View
    {
        $journal = Journal::findOrFail($id);
        return view('journals.edit', compact('journal'));
    }

    /**
     * Update the specified journal in storage.
     *
     * @param  Request  $request
     * @param  string   $id
     * @return RedirectResponse
     */
    public function update(Request $request, string $id): RedirectResponse
    {
        $request->validate([
            'title'             => 'required|min:5',
            'author'            => 'required|min:3',
            'publisher'         => 'required|min:3',
            'publication_date'  => 'required|date',
            'volume'            => 'nullable|string',
            'issue'             => 'nullable|string',
            'abstract'          => 'nullable|string',
            'restricted_access' => 'required|boolean',
        ]);

        $journal = Journal::findOrFail($id);

        // Update the journal details
        $journal->update($request->only(
            'title',
            'author',
            'publisher',
            'publication_date',
            'volume',
            'issue',
            'abstract',
            'restricted_access'
        ));

        return redirect()->route('journals.index')->with(['success' => 'Journal updated successfully!']);
    }

    /**
     * Remove the specified journal from storage.
     *
     * @param  string  $id
     * @return RedirectResponse
     */
    public function destroy(string $id): RedirectResponse
    {
        $journal = Journal::findOrFail($id);

        $journal->delete();

        return redirect()->route('journals.index')->with(['success' => 'Journal deleted successfully!']);
    }
}
