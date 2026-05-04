<?php

namespace App\Http\Controllers;

use App\Models\Pocket;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $year = $request->get('year', now()->year);
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');

        $pockets = Pocket::where('user_id', $user->id)->get();
        $totalBalance = $pockets->sum('balance');

        // Monthly expense data for chart
        $query = Transaction::whereIn('pocket_id', $pockets->pluck('id'));

        if ($startDate && $endDate) {
            $query->whereBetween('date', [$startDate, $endDate]);
        } else {
            $query->whereYear('date', $year);
        }

        $monthlyExpenses = (clone $query)->where('type', 'expense')
            ->select(DB::raw('MONTH(date) as month'), DB::raw('SUM(amount) as total'))
            ->groupBy('month')->orderBy('month')
            ->pluck('total', 'month')->toArray();

        $monthlyIncomes = (clone $query)->where('type', 'income')
            ->select(DB::raw('MONTH(date) as month'), DB::raw('SUM(amount) as total'))
            ->groupBy('month')->orderBy('month')
            ->pluck('total', 'month')->toArray();

        $months = [];
        $expenseData = [];
        $incomeData = [];
        $monthNames = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];

        for ($i = 1; $i <= 12; $i++) {
            $months[] = $monthNames[$i - 1];
            $expenseData[] = $monthlyExpenses[$i] ?? 0;
            $incomeData[] = $monthlyIncomes[$i] ?? 0;
        }

        // Recent transactions
        $recentTransactions = Transaction::whereIn('pocket_id', $pockets->pluck('id'))
            ->with('pocket')->latest('date')->limit(10)->get();

        $totalExpense = $query->where('type', 'expense')->sum('amount');

        return view('dashboard', compact(
            'pockets', 'totalBalance', 'months', 'expenseData', 'incomeData',
            'recentTransactions', 'year', 'startDate', 'endDate', 'totalExpense'
        ));
    }
}
