<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Wallet;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = auth()->id();

        $totalBalance = Wallet::where('user_id', $userId)->sum('balance');
        $walletCount  = Wallet::where('user_id', $userId)->count();

        $startOfMonth = now()->startOfMonth();
        $endOfMonth   = now()->endOfMonth();

        $monthlyIncome = Transaction::where('user_id', $userId)
            ->where('type', 'income')
            ->whereBetween('date', [$startOfMonth, $endOfMonth])
            ->sum('amount');

        $monthlyOutcome = Transaction::where('user_id', $userId)
            ->where('type', 'outcome')
            ->whereBetween('date', [$startOfMonth, $endOfMonth])
            ->sum('amount');

        $monthlyTransferFee = Transaction::where('user_id', $userId)
            ->where('type', 'transfer')
            ->whereBetween('date', [$startOfMonth, $endOfMonth])
            ->sum('transfer_fee');

        $netMonthly = $monthlyIncome - $monthlyOutcome - $monthlyTransferFee;

        $chartMonths  = [];
        $chartIncome  = [];
        $chartOutcome = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $start = $month->copy()->startOfMonth();
            $end   = $month->copy()->endOfMonth();

            $chartMonths[]  = $month->format('M Y');
            $chartIncome[]  = (float) Transaction::where('user_id', $userId)->where('type', 'income')->whereBetween('date', [$start, $end])->sum('amount');
            $chartOutcome[] = (float) Transaction::where('user_id', $userId)->where('type', 'outcome')->whereBetween('date', [$start, $end])->sum('amount');
        }

        $categoryBreakdown = Transaction::where('user_id', $userId)
            ->where('type', 'outcome')
            ->whereBetween('date', [$startOfMonth, $endOfMonth])
            ->whereNotNull('category_id')
            ->with('category')
            ->get()
            ->groupBy('category_id')
            ->map(fn($txns) => [
                'name'  => $txns->first()->category->name ?? 'Unknown',
                'total' => (float) $txns->sum('amount'),
            ])
            ->values();

        $wallets = Wallet::where('user_id', $userId)
            ->orderByDesc('balance')
            ->paginate(5);

        $recentTransactions = Transaction::where('user_id', $userId)
            ->with(['wallet', 'category', 'transactionName'])
            ->orderByDesc('date')
            ->orderByDesc('created_at')
            ->paginate(5);

        return view('dashboard', compact(
            'totalBalance', 'walletCount',
            'monthlyIncome', 'monthlyOutcome', 'monthlyTransferFee', 'netMonthly',
            'chartMonths', 'chartIncome', 'chartOutcome',
            'categoryBreakdown', 'wallets', 'recentTransactions'
        ));
    }

    public function walletsPage(Request $request)
    {
        $wallets = Wallet::where('user_id', auth()->id())
            ->orderByDesc('balance')
            ->paginate(5, ['*'], 'page', $request->integer('page', 1));

        return response()->json([
            'html'         => view('dashboard.partials.wallets', compact('wallets'))->render(),
            'current_page' => $wallets->currentPage(),
            'last_page'    => $wallets->lastPage(),
            'has_pages'    => $wallets->hasPages(),
        ]);
    }

    public function transactionsPage(Request $request)
    {
        $transactions = Transaction::where('user_id', auth()->id())
            ->with(['wallet', 'category', 'transactionName'])
            ->orderByDesc('date')
            ->orderByDesc('created_at')
            ->paginate(5, ['*'], 'page', $request->integer('page', 1));

        return response()->json([
            'html'         => view('dashboard.partials.transactions', compact('transactions'))->render(),
            'current_page' => $transactions->currentPage(),
            'last_page'    => $transactions->lastPage(),
            'has_pages'    => $transactions->hasPages(),
        ]);
    }
}
