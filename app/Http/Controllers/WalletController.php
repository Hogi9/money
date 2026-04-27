<?php

namespace App\Http\Controllers;

use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WalletController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', Wallet::class);

        $query = Wallet::where('user_id', Auth::id());

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $wallets = $query->latest()->paginate(10)->onEachSide(1)->withQueryString();
        $totalBalance = Wallet::where('user_id', Auth::id())->sum('balance');

        return view('wallets.index', compact('wallets', 'totalBalance'));
    }

    public function create()
    {
        $this->authorize('create', Wallet::class);
        return view('wallets.create');
    }

    public function store(Request $request)
    {
        $this->authorize('create', Wallet::class);
        $request->validate([
            'name'            => 'required|string|max:255',
            'description'     => 'nullable|string|max:500',
            // 'initial_balance' => 'nullable|numeric|min:0',
        ]);

        Wallet::create([
            'name'        => $request->name,
            'description' => $request->description,
            'balance'     => $request->initial_balance ?? 0,
            'user_id'     => Auth::id(),
        ]);

        return redirect()->route('wallets.index')->with('success', 'Dompet berhasil ditambahkan.');
    }

    public function edit(Wallet $wallet)
    {
        $this->authorize('update', $wallet);
        return view('wallets.edit', compact('wallet'));
    }

    public function update(Request $request, Wallet $wallet)
    {
        $this->authorize('update', $wallet);
        $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
        ]);

        $wallet->update([
            'name'        => $request->name,
            'description' => $request->description,
        ]);

        return redirect()->route('wallets.index')->with('success', 'Dompet berhasil diperbarui.');
    }

    public function destroy(Wallet $wallet)
    {
        $this->authorize('delete', $wallet);

        if ($wallet->transactions()->exists() || $wallet->incomingTransfers()->exists()) {
            return back()->with('error', 'Dompet tidak dapat dihapus karena masih memiliki transaksi.');
        }

        $wallet->delete();
        return redirect()->route('wallets.index')->with('success', 'Dompet berhasil dihapus.');
    }
}
