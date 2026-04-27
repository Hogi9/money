<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Transaction;
use App\Models\TransactionName;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', Transaction::class);

        $query = Transaction::with(['wallet', 'toWallet', 'category', 'transactionName'])
            ->where('user_id', Auth::id());

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('wallet_id')) {
            $query->where('wallet_id', $request->wallet_id);
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('date', '<=', $request->date_to);
        }

        if ($request->filled('search')) {
            $query->where('description', 'like', '%' . $request->search . '%');
        }

        $summaryQuery = clone $query;
        $summary = $summaryQuery->selectRaw("
                SUM(CASE WHEN type = 'income' THEN amount ELSE 0 END) as total_income,
                SUM(CASE WHEN type = 'outcome' THEN amount ELSE 0 END) as total_outcome,
                SUM(CASE WHEN type = 'transfer' THEN transfer_fee ELSE 0 END) as total_transfer_fee
            ")->first();

        $transactions = $query->latest('date')->latest()->paginate(10)->onEachSide(1)->withQueryString();

        $wallets    = Wallet::where('user_id', Auth::id())->orderBy('name')->get();
        $categories = Category::where('user_id', Auth::id())->orderBy('name')->get();

        $isFiltered = $request->hasAny(['type', 'wallet_id', 'category_id', 'date_from', 'date_to', 'search']);

        return view('transactions.index', compact('transactions', 'wallets', 'categories', 'summary', 'isFiltered'));
    }

    public function create()
    {
        $this->authorize('create', Transaction::class);
        $wallets          = Wallet::where('user_id', Auth::id())->orderBy('name')->get();
        $categories       = Category::where('user_id', Auth::id())->orderBy('type')->orderBy('name')->get();
        $transactionNames = TransactionName::where('user_id', Auth::id())->with('category')->orderBy('name')->get();

        return view('transactions.create', compact('wallets', 'categories', 'transactionNames'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', Transaction::class);
        $request->validate([
            'type'                => 'required|in:income,outcome,transfer',
            'amount'              => 'required|numeric|min:0.01',
            'wallet_id'           => 'required|exists:wallets,id',
            'to_wallet_id'        => 'required_if:type,transfer|nullable|exists:wallets,id|different:wallet_id',
            'transfer_fee'        => 'nullable|numeric|min:0',
            'fee_bearer'          => 'required_if:type,transfer|nullable|in:sender,receiver',
            'transaction_name_id' => 'nullable|exists:transaction_names,id',
            'category_id'         => 'nullable|exists:categories,id',
            'description'         => 'nullable|string|max:500',
            'date'                => 'required|date',
        ]);

        DB::transaction(function () use ($request) {
            $isTransfer = $request->type === 'transfer';
            $transaction = Transaction::create([
                'type'                => $request->type,
                'amount'              => $request->amount,
                'transfer_fee'        => $isTransfer ? ($request->transfer_fee ?? 0) : 0,
                'fee_bearer'          => $isTransfer ? $request->fee_bearer : null,
                'wallet_id'           => $request->wallet_id,
                'to_wallet_id'        => $isTransfer ? $request->to_wallet_id : null,
                'transaction_name_id' => $request->transaction_name_id,
                'category_id'         => $request->category_id,
                'description'         => $request->description,
                'date'                => $request->date,
                'user_id'             => Auth::id(),
            ]);

            $this->applyWalletEffect($transaction);
        });

        return redirect()->route('transactions.index')->with('success', 'Transaksi berhasil ditambahkan.');
    }

    public function edit(Transaction $transaction)
    {
        $this->authorize('update', $transaction);
        $wallets          = Wallet::where('user_id', Auth::id())->orderBy('name')->get();
        $categories       = Category::where('user_id', Auth::id())->orderBy('type')->orderBy('name')->get();
        $transactionNames = TransactionName::where('user_id', Auth::id())->with('category')->orderBy('name')->get();

        return view('transactions.edit', compact('transaction', 'wallets', 'categories', 'transactionNames'));
    }

    public function update(Request $request, Transaction $transaction)
    {
        $this->authorize('update', $transaction);
        $request->validate([
            'type'                => 'required|in:income,outcome,transfer',
            'amount'              => 'required|numeric|min:0.01',
            'wallet_id'           => 'required|exists:wallets,id',
            'to_wallet_id'        => 'required_if:type,transfer|nullable|exists:wallets,id|different:wallet_id',
            'transfer_fee'        => 'nullable|numeric|min:0',
            'fee_bearer'          => 'required_if:type,transfer|nullable|in:sender,receiver',
            'transaction_name_id' => 'nullable|exists:transaction_names,id',
            'category_id'         => 'nullable|exists:categories,id',
            'description'         => 'nullable|string|max:500',
            'date'                => 'required|date',
        ]);

        DB::transaction(function () use ($request, $transaction) {
            $this->reverseWalletEffect($transaction);

            $isTransfer = $request->type === 'transfer';
            $transaction->update([
                'type'                => $request->type,
                'amount'              => $request->amount,
                'transfer_fee'        => $isTransfer ? ($request->transfer_fee ?? 0) : 0,
                'fee_bearer'          => $isTransfer ? $request->fee_bearer : null,
                'wallet_id'           => $request->wallet_id,
                'to_wallet_id'        => $isTransfer ? $request->to_wallet_id : null,
                'transaction_name_id' => $request->transaction_name_id,
                'category_id'         => $request->category_id,
                'description'         => $request->description,
                'date'                => $request->date,
            ]);

            $this->applyWalletEffect($transaction->fresh());
        });

        return redirect()->route('transactions.index')->with('success', 'Transaksi berhasil diperbarui.');
    }

    public function destroy(Transaction $transaction)
    {
        $this->authorize('delete', $transaction);

        DB::transaction(function () use ($transaction) {
            $this->reverseWalletEffect($transaction);
            $transaction->delete();
        });

        return redirect()->route('transactions.index')->with('success', 'Transaksi berhasil dihapus.');
    }

    private function applyWalletEffect(Transaction $transaction): void
    {
        $wallet = Wallet::lockForUpdate()->findOrFail($transaction->wallet_id);

        match ($transaction->type) {
            'income'   => $wallet->increment('balance', $transaction->amount),
            'outcome'  => $wallet->decrement('balance', $transaction->amount),
            'transfer' => (function () use ($wallet, $transaction) {
                $fee      = (float) $transaction->transfer_fee;
                $toWallet = Wallet::lockForUpdate()->findOrFail($transaction->to_wallet_id);

                if ($transaction->fee_bearer === 'sender') {
                    $wallet->decrement('balance', $transaction->amount + $fee);
                    $toWallet->increment('balance', $transaction->amount);
                } else {
                    // receiver pays fee
                    $wallet->decrement('balance', $transaction->amount);
                    $toWallet->increment('balance', $transaction->amount - $fee);
                }
            })(),
        };
    }

    private function reverseWalletEffect(Transaction $transaction): void
    {
        $wallet = Wallet::lockForUpdate()->findOrFail($transaction->wallet_id);

        match ($transaction->type) {
            'income'   => $wallet->decrement('balance', $transaction->amount),
            'outcome'  => $wallet->increment('balance', $transaction->amount),
            'transfer' => (function () use ($wallet, $transaction) {
                $fee = (float) $transaction->transfer_fee;

                if ($transaction->fee_bearer === 'sender') {
                    $wallet->increment('balance', $transaction->amount + $fee);
                } else {
                    $wallet->increment('balance', $transaction->amount);
                }

                if ($transaction->to_wallet_id) {
                    $toWallet = Wallet::lockForUpdate()->findOrFail($transaction->to_wallet_id);
                    if ($transaction->fee_bearer === 'receiver') {
                        $toWallet->decrement('balance', $transaction->amount - $fee);
                    } else {
                        $toWallet->decrement('balance', $transaction->amount);
                    }
                }
            })(),
        };
    }
}
