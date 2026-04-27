<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FeedbackController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', Feedback::class);

        $query = Feedback::with('user')
            ->when($request->filled('type'), fn ($q) => $q->where('type', $request->type))
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->status))
            ->when($request->filled('search'), fn ($q) => $q->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('message', 'like', '%' . $request->search . '%');
            }));

        $feedbacks = $query->latest()->paginate(10)->onEachSide(1)->withQueryString();

        return view('feedbacks.index', compact('feedbacks'));
    }

    public function create()
    {
        $this->authorize('create', Feedback::class);

        return view('feedbacks.create');
    }

    public function store(Request $request)
    {
        $this->authorize('create', Feedback::class);

        $request->validate([
            'type'    => 'required|in:kritik,saran',
            'title'   => 'required|string|max:255',
            'message' => 'required|string|max:2000',
        ]);

        Feedback::create([
            'user_id' => Auth::id(),
            'type'    => $request->type,
            'title'   => $request->title,
            'message' => $request->message,
            'status'  => 'pending',
        ]);

        return redirect()->route('feedbacks.create')
            ->with('success', 'Terima kasih! Kritik & saran Anda telah berhasil dikirim.');
    }

    public function show(Feedback $feedback)
    {
        $this->authorize('view', $feedback);

        return view('feedbacks.show', compact('feedback'));
    }

    public function update(Request $request, Feedback $feedback)
    {
        $this->authorize('update', $feedback);

        $request->validate([
            'status'      => 'required|in:pending,reviewed,resolved',
            'admin_notes' => 'nullable|string|max:2000',
        ]);

        $feedback->update([
            'status'      => $request->status,
            'admin_notes' => $request->admin_notes,
        ]);

        return redirect()->route('feedbacks.show', $feedback)
            ->with('success', 'Status feedback berhasil diperbarui.');
    }

    public function destroy(Feedback $feedback)
    {
        $this->authorize('delete', $feedback);

        $feedback->delete();

        return redirect()->route('feedbacks.index')
            ->with('success', 'Feedback berhasil dihapus.');
    }
}
