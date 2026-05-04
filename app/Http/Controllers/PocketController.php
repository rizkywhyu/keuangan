<?php

namespace App\Http\Controllers;

use App\Models\Pocket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PocketController extends Controller
{
    public function index()
    {
        $pockets = Pocket::where('user_id', Auth::id())
            ->withCount('transactions')->get();
        return view('pockets.index', compact('pockets'));
    }

    public function create()
    {
        return view('pockets.form');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
        ]);

        Pocket::create([...$validated, 'user_id' => Auth::id()]);

        return redirect()->route('pockets.index')->with('success', 'Pocket berhasil dibuat.');
    }

    public function edit(Pocket $pocket)
    {
        $this->authorize($pocket);
        return view('pockets.form', compact('pocket'));
    }

    public function update(Request $request, Pocket $pocket)
    {
        $this->authorize($pocket);
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
        ]);

        $pocket->update($validated);

        return redirect()->route('pockets.index')->with('success', 'Pocket berhasil diupdate.');
    }

    public function destroy(Pocket $pocket)
    {
        $this->authorize($pocket);
        $pocket->delete();
        return redirect()->route('pockets.index')->with('success', 'Pocket berhasil dihapus.');
    }

    private function authorize(Pocket $pocket): void
    {
        abort_if($pocket->user_id !== Auth::id(), 403);
    }
}
