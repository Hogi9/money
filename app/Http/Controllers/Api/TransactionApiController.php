<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Transaction;
use App\Models\TransactionName;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransactionApiController extends Controller
{
    public function index(Request $request)
    {
        $transactions = Transaction::where('user_id', $request->user()->id)
            ->with(['wallet:id,uuid,name', 'category:id,uuid,name,type'])
            ->orderByDesc('date')
            ->paginate(20, ['uuid', 'type', 'amount', 'description', 'date', 'wallet_id', 'category_id']);

        return response()->json($transactions);
    }

    public function store(Request $request)
    {
        $userId = $request->user()->id;

        $request->validate([
            'type'             => 'required|in:income,outcome,transfer',
            'amount'           => 'required|numeric|min:0.01',
            'wallet_name'      => 'required|string',
            'to_wallet_name'   => 'required_if:type,transfer|nullable|string|different:wallet_name',
            'transfer_fee'     => 'nullable|numeric|min:0',
            'fee_bearer'       => 'nullable|in:sender,receiver',
            'transaction_name' => 'nullable|string',
            'category'         => 'nullable|string',
            'description'      => 'nullable|string|max:500',
            'date'             => 'required|date',
        ]);

        $wallet = Wallet::where('name', $request->wallet_name)->where('user_id', $userId)->first();
        if (!$wallet) {
            return response()->json(['errors' => ['wallet_name' => ["Wallet \"{$request->wallet_name}\" tidak ditemukan."]]], 422);
        }

        $toWallet = null;
        if ($request->type === 'transfer') {
            $toWallet = Wallet::where('name', $request->to_wallet_name)->where('user_id', $userId)->first();
            if (!$toWallet) {
                return response()->json(['errors' => ['to_wallet_name' => ["Wallet \"{$request->to_wallet_name}\" tidak ditemukan."]]], 422);
            }
        }

        $transactionNameId = null;
        if ($request->filled('transaction_name')) {
            $transactionNameId = TransactionName::where('name', $request->transaction_name)
                ->where('user_id', $userId)->value('id');
        }

        $categoryId = null;
        if ($request->filled('category')) {
            $categoryId = Category::where('name', $request->category)
                ->where('user_id', $userId)->value('id');
            if (!$categoryId) {
                return response()->json(['errors' => ['category' => ["Category \"{$request->category}\" tidak ditemukan."]]], 422);
            }
        }

        $transaction = DB::transaction(function () use ($request, $wallet, $toWallet, $transactionNameId, $categoryId, $userId) {
            $isTransfer  = $request->type === 'transfer';
            $transaction = Transaction::create([
                'type'                => $request->type,
                'amount'              => $request->amount,
                'transfer_fee'        => $isTransfer ? ($request->transfer_fee ?? 0) : 0,
                'fee_bearer'          => $isTransfer ? ($request->fee_bearer ?? 'sender') : null,
                'wallet_id'           => $wallet->id,
                'to_wallet_id'        => $isTransfer ? $toWallet->id : null,
                'transaction_name_id' => $transactionNameId,
                'category_id'         => $categoryId,
                'description'         => $request->description,
                'date'                => $request->date,
                'user_id'             => $userId,
            ]);

            $this->applyWalletEffect($transaction);

            return $transaction;
        });

        $transaction->load(['wallet:id,uuid,name', 'toWallet:id,uuid,name', 'category:id,uuid,name,type', 'transactionName:id,uuid,name']);

        return response()->json(['data' => $transaction], 201);
    }

    public function show(Request $request, Transaction $transaction)
    {
        if ($transaction->user_id !== $request->user()->id) {
            abort(403);
        }

        $transaction->load(['wallet:id,uuid,name', 'toWallet:id,uuid,name', 'category:id,uuid,name,type', 'transactionName:id,uuid,name']);

        return response()->json(['data' => $transaction]);
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
                    $wallet->decrement('balance', $transaction->amount);
                    $toWallet->increment('balance', $transaction->amount - $fee);
                }
            })(),
        };
    }
}

// {
//     "type": "income",
//     "amount": 2500000,
//     "wallet_name": "BCA",
//     "date": "2026-05-02"
// }
// Untuk transfer:


// {
//     "type": "transfer",
//     "amount": 500000,
//     "wallet_name": "BCA",
//     "to_wallet_name": "Mandiri",
//     "fee_bearer": "sender||receiver",
//     "date": "2026-05-02"
// }