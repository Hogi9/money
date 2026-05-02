<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Wallet;
use Illuminate\Http\Request;

class WalletApiController extends Controller
{
    public function index(Request $request)
    {
        $wallets = Wallet::where('user_id', $request->user()->id)
            ->orderBy('name')
            ->get(['uuid', 'name', 'description', 'balance']);

        return response()->json(['data' => $wallets]);
    }

    public function show(Request $request, Wallet $wallet)
    {
        if ($wallet->user_id !== $request->user()->id) {
            abort(403);
        }

        return response()->json(['data' => $wallet->only(['uuid', 'name', 'description', 'balance'])]);
    }
}
