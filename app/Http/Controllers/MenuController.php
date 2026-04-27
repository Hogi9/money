<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', Menu::class);

        $menus = Menu::with('parent')
            ->when($request->search, fn ($q) => $q->where('name', 'like', '%'.$request->search.'%'))
            ->orderBy('sort_order')
            ->paginate(10)
            ->onEachSide(1)
            ->withQueryString();

        return view('menus.index', compact('menus'));
    }

    public function create()
    {
        $this->authorize('create', Menu::class);

        $parents = Menu::whereNull('parent_id')->orderBy('sort_order')->get();

        return view('menus.create', compact('parents'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', Menu::class);

        $data = $request->validate([
            'name'            => 'required|string|max:100',
            'icon'            => 'required|string|max:100',
            'route_name'      => 'nullable|string|max:100',
            'parent_id'       => 'nullable|exists:menus,id',
            'group'           => 'nullable|string|max:100',
            'sort_order'      => 'required|integer|min:0',
            'permission_name' => 'nullable|string|max:100',
        ]);

        $data['is_active'] = $request->boolean('is_active', true);

        Menu::create($data);

        return redirect()->route('menus.index')->with('success', 'Menu created successfully.');
    }

    public function edit(Menu $menu)
    {
        $this->authorize('update', $menu);

        $parents = Menu::whereNull('parent_id')
            ->where('id', '!=', $menu->id)
            ->orderBy('sort_order')
            ->get();

        return view('menus.edit', compact('menu', 'parents'));
    }

    public function update(Request $request, Menu $menu)
    {
        $this->authorize('update', $menu);

        $data = $request->validate([
            'name'            => 'required|string|max:100',
            'icon'            => 'required|string|max:100',
            'route_name'      => 'nullable|string|max:100',
            'parent_id'       => 'nullable|exists:menus,id',
            'group'           => 'nullable|string|max:100',
            'sort_order'      => 'required|integer|min:0',
            'permission_name' => 'nullable|string|max:100',
        ]);

        $data['is_active'] = $request->boolean('is_active');

        $menu->update($data);

        return redirect()->route('menus.index')->with('success', 'Menu updated successfully.');
    }

    public function destroy(Menu $menu)
    {
        $this->authorize('delete', $menu);

        $menu->delete();

        return redirect()->route('menus.index')->with('success', 'Menu deleted successfully.');
    }
}
