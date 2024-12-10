<?php

namespace App\Http\Controllers;

use App\Models\Newspaper;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class NewspaperController extends Controller
{
    /**
     * Display a listing of newspapers.
     *
     * @return View
     */
    public function index(): View
    {
        // Get all newspapers with pagination
        $newspapers = Newspaper::latest()->paginate(10);

        // Render view with newspapers
        return view('newspapers.index', compact('newspapers'));
    }

    /**
     * Show the form for creating a new newspaper.
     *
     * @return View
     */
    public function create(): View
    {
        return view('newspapers.create');
    }

    /**
     * Store a newly created newspaper in storage.
     *
     * @param  Request  $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        // Validate the form
        $request->validate([
            'title'            => 'required|min:5',
            'publisher'        => 'required|min:3',
            'publication_date' => 'required|date',
            'edition'          => 'nullable|string',
            'copies'           => 'required|integer|min:1',
        ]);

        // Create the newspaper
        Newspaper::create($request->only(
            'title',
            'publisher',
            'publication_date',
            'edition',
            'copies'
        ));

        return redirect()->route('newspapers.index')->with(['success' => 'Newspaper created successfully!']);
    }

    /**
     * Show a single newspaper.
     *
     * @param  string  $id
     * @return View
     */
    public function show(string $id): View
    {
        $newspaper = Newspaper::findOrFail($id);
        return view('newspapers.show', compact('newspaper'));
    }

    /**
     * Show the form for editing a newspaper.
     *
     * @param  string  $id
     * @return View
     */
    public function edit(string $id): View
    {
        $newspaper = Newspaper::findOrFail($id);
        return view('newspapers.edit', compact('newspaper'));
    }

    /**
     * Update the specified newspaper in storage.
     *
     * @param  Request  $request
     * @param  string   $id
     * @return RedirectResponse
     */
    public function update(Request $request, string $id): RedirectResponse
    {
        $request->validate([
            'title'            => 'required|min:5',
            'publisher'        => 'required|min:3',
            'publication_date' => 'required|date',
            'edition'          => 'nullable|string',
            'copies'           => 'required|integer|min:1',
        ]);

        $newspaper = Newspaper::findOrFail($id);

        // Update the newspaper details
        $newspaper->update($request->only(
            'title',
            'publisher',
            'publication_date',
            'edition',
            'copies'
        ));

        return redirect()->route('newspapers.index')->with(['success' => 'Newspaper updated successfully!']);
    }

    /**
     * Remove the specified newspaper from storage.
     *
     * @param  string  $id
     * @return RedirectResponse
     */
    public function destroy(string $id): RedirectResponse
    {
        $newspaper = Newspaper::findOrFail($id);
        $newspaper->delete();

        return redirect()->route('newspapers.index')->with(['success' => 'Newspaper deleted successfully!']);
    }
}
