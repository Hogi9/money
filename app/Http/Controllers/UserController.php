<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', User::class);
        $query = User::with('roles');

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('email', 'like', '%' . $request->search . '%')
                    ->orWhere('username', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('role')) {
            $query->whereHas('roles', fn($q) => $q->where('name', $request->role));
        }

        $users = $query->latest()->paginate(10)->onEachSide(1)->withQueryString();
        $roles  = Role::orderBy('name')->get();

        return view('users.index', compact('users', 'roles'));
    }

    public function create()
    {
        $this->authorize('create', User::class);
        $roles = Role::orderBy('name')->get();
        $teams = Team::orderBy('name')->get();
        return view('users.create', compact('roles', 'teams'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', User::class);
        $request->validate([
            'name'     => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username|alpha_dash',
            'email'    => 'required|email|unique:users,email',
            'password' => ['required', Password::min(8)],
            'roles'    => 'nullable|array',
            'roles.*'  => 'exists:roles,name',
        ]);

        $user = User::create([
            'name'     => $request->name,
            'username' => $request->username,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $user->syncRoles($request->filled('roles') ? $request->roles : ['user']);

        return redirect()->route('users.index')->with('success', 'User berhasil ditambahkan.');
    }

    public function edit(string $id)
    {
        $user = User::findOrFail($id);
        $this->authorize('update', $user);
        $roles        = Role::orderBy('name')->get();
        $teams        = Team::orderBy('name')->get();
        $currentRoles = $user->roles->pluck('name')->toArray();
        return view('users.edit', compact('user', 'roles', 'teams', 'currentRoles'));
    }

    public function update(Request $request, string $id)
    {
        $user = User::findOrFail($id);
        $this->authorize('update', $user);
        $request->validate([
            'name'     => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $user->id . '|alpha_dash',
            'email'    => 'required|email|unique:users,email,' . $user->id,
            'password' => ['nullable', Password::min(8)],
            'roles'    => 'required|array',
            'roles.*'  => 'exists:roles,name',
            'team_id'  => 'nullable|exists:teams,id',
        ]);

        $user->update([
            'name'     => $request->name,
            'username' => $request->username,
            'email'    => $request->email,
            ...($request->filled('password') ? ['password' => Hash::make($request->password)] : []),
        ]);

        $user->syncRoles($request->roles ?? []);

        return redirect()->route('users.index')->with('success', 'User berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $user = User::findOrFail($id);
        $this->authorize('delete', $user);

        if ($user->id === Auth::id()) {
            return back()->with('error', 'Cannot delete your own account.');
        }

        $user->delete();
        return redirect()->route('users.index')->with('success', 'User successfully deleted.');
    }
}
