<?php

namespace App\Http\Controllers;

use App\Models\Pocket;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    public function index(Request $request, Pocket $pocket)
    {
        abort_if($pocket->user_id !== Auth::id(), 403);

        $query = $pocket->transactions();

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('date', [$request->start_date, $request->end_date]);
        }
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $transactions = $query->latest('date')->paginate(20);

        return view('transactions.index', compact('pocket', 'transactions'));
    }

    public function create(Pocket $pocket)
    {
        abort_if($pocket->user_id !== Auth::id(), 403);
        return view('transactions.form', compact('pocket'));
    }

    public function store(Request $request, Pocket $pocket)
    {
        abort_if($pocket->user_id !== Auth::id(), 403);

        $validated = $request->validate([
            'type' => 'required|in:income,expense',
            'amount' => 'required|numeric|min:0.01',
            'description' => 'nullable|string|max:500',
            'date' => 'required|date',
        ]);

        $pocket->transactions()->create($validated);
        $pocket->recalculateBalance();

        return redirect()->route('pockets.transactions.index', $pocket)
            ->with('success', 'Transaksi berhasil ditambahkan.');
    }

    public function edit(Pocket $pocket, Transaction $transaction)
    {
        abort_if($pocket->user_id !== Auth::id(), 403);
        return view('transactions.form', compact('pocket', 'transaction'));
    }

    public function update(Request $request, Pocket $pocket, Transaction $transaction)
    {
        abort_if($pocket->user_id !== Auth::id(), 403);

        $validated = $request->validate([
            'type' => 'required|in:income,expense',
            'amount' => 'required|numeric|min:0.01',
            'description' => 'nullable|string|max:500',
            'date' => 'required|date',
        ]);

        $transaction->update($validated);
        $pocket->recalculateBalance();

        return redirect()->route('pockets.transactions.index', $pocket)
            ->with('success', 'Transaksi berhasil diupdate.');
    }

    public function destroy(Pocket $pocket, Transaction $transaction)
    {
        abort_if($pocket->user_id !== Auth::id(), 403);
        $transaction->delete();
        $pocket->recalculateBalance();

        return redirect()->route('pockets.transactions.index', $pocket)
            ->with('success', 'Transaksi berhasil dihapus.');
    }
}
