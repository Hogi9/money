<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TeamController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', Team::class);

        $user = Auth::user();

        // Admin sees all teams; regular users only see teams they belong to
        if ($user->hasRole('admin')) {
            $query = Team::with(['owner', 'members']);
        } else {
            $query = Team::with(['owner', 'members'])
                ->whereHas('members', fn($q) => $q->where('user_id', $user->id));
        }

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $teams = $query->latest()->paginate(10)->onEachSide(1)->withQueryString();

        return view('teams.index', compact('teams'));
    }

    public function create()
    {
        $this->authorize('create', Team::class);
        return view('teams.create');
    }

    public function store(Request $request)
    {
        $this->authorize('create', Team::class);

        $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
        ]);

        $team = Team::create([
            'user_id'     => Auth::id(),
            'name'        => $request->name,
            'description' => $request->description,
        ]);

        // Buat owner sebagai anggota pertama dengan role owner
        $team->members()->attach(Auth::id(), [
            'role'      => 'owner',
            'joined_at' => now(),
        ]);

        return redirect()->route('teams.show', $team)->with('success', 'Group successfully created.');
    }

    public function show(string $id)
    {
        $team = Team::with(['owner', 'members'])->findOrFail($id);
        $this->authorize('view', $team);
        
        $team->load(['owner', 'members']);

        // User yang belum jadi anggota untuk pilihan tambah anggota
        $existingMemberIds = $team->members->pluck('id')->toArray();
        $availableUsers = User::whereNotIn('id', $existingMemberIds)
            ->orderBy('name')
            ->get();

        return view('teams.show', compact('team', 'availableUsers'));
    }

    public function edit(string $id)
    {
        $team = Team::findOrFail($id);
        $this->authorize('update', $team);
        return view('teams.edit', compact('team'));
    }

    public function update(Request $request, string $id)
    {
        $team = Team::findOrFail($id);
        $this->authorize('update', $team);

        $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
        ]);

        $team->update([
            'name'        => $request->name,
            'description' => $request->description,
        ]);

        return redirect()->route('teams.show', $team)->with('success', 'Group successfully updated.');
    }

    public function destroy(string $id)
    {
        $team = Team::findOrFail($id);
        $this->authorize('delete', $team);

        $team->delete();
        return redirect()->route('teams.index')->with('success', 'Group successfully deleted.');
    }

    public function addMember(Request $request, Team $team)
    {
        $this->authorize('addMember', $team);

        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        if ($team->isMember(User::find($request->user_id))) {
            return back()->with('error', 'User already is a member of this group.');
        }

        $team->members()->attach($request->user_id, [
            'role'      => 'member',
            'joined_at' => now(),
        ]);

        return back()->with('success', 'Member successfully added.');
    }

    public function removeMember(Team $team, User $user)
    {
        $this->authorize('removeMember', $team);

        if ($team->isOwner($user)) {
            return back()->with('error', 'Owner cannot be removed from the group.');
        }

        $team->members()->detach($user->id);

        return back()->with('success', 'Member successfully removed from the group.');
    }
}
