<?php

namespace App\Http\Controllers;

use App\Models\CD;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;

class CDController extends Controller
{
    /**
     * Display a listing of CDs.
     *
     * @return View
     */
    public function index(): View
    {
        // Get all CDs with pagination
        $cds = CD::latest()->paginate(10);

        // Render view with CDs
        return view('cds.index', compact('cds'));
    }

    /**
     * Show the form for creating a new CD.
     *
     * @return View
     */
    public function create(): View
    {
        return view('cds.create');
    }

    /**
     * Store a newly created CD in storage.
     *
     * @param  Request  $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        // Validate the form
        $request->validate([
            'title'        => 'required|min:3',
            'artist'       => 'required|min:3',
            'genre'        => 'required|min:3',
            'release_date' => 'required|date',
            'copies'       => 'required|integer|min:1',
        ]);

        // Create the CD
        CD::create($request->only('title', 'artist', 'genre', 'release_date', 'copies'));

        return redirect()->route('cds.index')->with(['success' => 'CD created successfully!']);
    }

    /**
     * Show a single CD.
     *
     * @param  string  $id
     * @return View
     */
    public function show(string $id): View
    {
        $cd = CD::findOrFail($id);
        return view('cds.show', compact('cd'));
    }

    /**
     * Show the form for editing a CD.
     *
     * @param  string  $id
     * @return View
     */
    public function edit(string $id): View
    {
        $cd = CD::findOrFail($id);
        return view('cds.edit', compact('cd'));
    }

    /**
     * Update the specified CD in storage.
     *
     * @param  Request  $request
     * @param  string   $id
     * @return RedirectResponse
     */
    public function update(Request $request, string $id): RedirectResponse
    {
        $request->validate([
            'title'        => 'required|min:3',
            'artist'       => 'required|min:3',
            'genre'        => 'required|min:3',
            'release_date' => 'required|date',
            'copies'       => 'required|integer|min:1',
        ]);

        $cd = CD::findOrFail($id);

        // Update the CD details
        $cd->update($request->only('title', 'artist', 'genre', 'release_date', 'copies'));

        return redirect()->route('cds.index')->with(['success' => 'CD updated successfully!']);
    }

    /**
     * Remove the specified CD from storage.
     *
     * @param  string  $id
     * @return RedirectResponse
     */
    public function destroy(string $id): RedirectResponse
    {
        $cd = CD::findOrFail($id);

        $cd->delete();

        return redirect()->route('cds.index')->with(['success' => 'CD deleted successfully!']);
    }
}
