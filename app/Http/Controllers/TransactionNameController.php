<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\TransactionName;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionNameController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', TransactionName::class);

        $query = TransactionName::with('category')->where('user_id', Auth::id());

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('type')) {
            $query->whereHas('category', fn($q) => $q->where('type', $request->type));
        }

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $transactionNames = $query->latest()->paginate(10)->onEachSide(1)->withQueryString();
        $categories = Category::where('user_id', Auth::id())->orderBy('name')->get();

        return view('transaction-names.index', compact('transactionNames', 'categories'));
    }

    public function create()
    {
        $this->authorize('create', TransactionName::class);
        $categories = Category::where('user_id', Auth::id())->orderBy('type')->orderBy('name')->get();
        return view('transaction-names.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', TransactionName::class);
        $request->validate([
            'name'        => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
        ]);

        TransactionName::create([
            'name'        => $request->name,
            'category_id' => $request->category_id,
            'user_id'     => Auth::id(),
        ]);

        return redirect()->route('transaction-names.index')->with('success', 'Nama transaksi berhasil ditambahkan.');
    }

    public function edit(TransactionName $transactionName)
    {
        $this->authorize('update', $transactionName);
        $categories = Category::where('user_id', Auth::id())->orderBy('type')->orderBy('name')->get();
        return view('transaction-names.edit', compact('transactionName', 'categories'));
    }

    public function update(Request $request, TransactionName $transactionName)
    {
        $this->authorize('update', $transactionName);
        $request->validate([
            'name'        => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
        ]);

        $transactionName->update([
            'name'        => $request->name,
            'category_id' => $request->category_id,
        ]);

        return redirect()->route('transaction-names.index')->with('success', 'Nama transaksi berhasil diperbarui.');
    }

    public function destroy(TransactionName $transactionName)
    {
        $this->authorize('delete', $transactionName);
        $transactionName->delete();
        return redirect()->route('transaction-names.index')->with('success', 'Nama transaksi berhasil dihapus.');
    }
}
