<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', Role::class);
        $query = Role::with('permissions');

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $roles = $query->latest()->paginate(10)->onEachSide(1)->withQueryString();

        return view('roles.index', compact('roles'));
    }

    public function create()
    {
        $this->authorize('create', Role::class);
        $permissions = Permission::orderBy('name')->get();
        return view('roles.create', compact('permissions'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', Role::class);
        $request->validate([
            'name' => 'required|string|unique:roles,name',
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,name',
        ]);
        
        $role = Role::create(['name' => $request->name]);
        $role->syncPermissions($request->permissions ?? []);

        return redirect()->route('roles.index')->with('success', 'Role successfully created.');
    }

    public function edit(string $id)
    {
        $role = Role::findOrFail($id);
        $this->authorize('update', $role);
        $permissions = Permission::orderBy('name')->get();
        $rolePermissions = $role->permissions->pluck('name')->toArray();
        return view('roles.edit', compact('role', 'permissions', 'rolePermissions'));
    }

    public function update(Request $request, string $id)
    {
        $role = Role::findOrFail($id);
        $this->authorize('update', $role);
        $request->validate([
            'name' => 'required|string|unique:roles,name,' . $role->id,
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,name',
        ]);

        $role->update(['name' => $request->name]);
        $role->syncPermissions($request->permissions ?? []);

        return redirect()->route('roles.index')->with('success', 'Role successfully updated.');
    }

    public function destroy(Role $role)
    {
        $this->authorize('delete', $role);
        $role->delete();
        return redirect()->route('roles.index')->with('success', 'Role successfully deleted.');
    }
}
