@extends('template.master')
@section('content')
    <div class="pb-4">
        <h1 class="text-2xl font-semibold tracking-tight">Menu Management</h1>
        <p class="text-base-content/70 mt-1">Manage sidebar menus. New menus auto-create permissions and assign them to the admin role.</p>
    </div>
    <div class="border-base-content/25 w-full rounded-lg border bg-base-100 p-4 shadow-sm">
        <div class="mb-4 flex items-center gap-3 justify-between">
            <div class="flex items-center gap-4">
                <form method="GET" action="{{ route('menus.index') }}" class="flex items-center gap-2">
                    <div class="input input-sm flex max-w-[250px] items-center space-x-4 rounded-sm">
                        <span class="icon-[tabler--search] text-base-content/80 size-5 shrink-0"></span>
                        <input type="search" name="search" class="grow bg-transparent outline-none" placeholder="Search menus..."
                            value="{{ request('search') }}" />
                    </div>
                    <button type="submit" class="btn btn-sm btn-outline btn-primary rounded-sm">Search</button>
                </form>
                @if(request('search'))
                    <a href="{{ route('menus.index') }}"
                        class="btn btn-sm btn-text flex items-center gap-1 p-0 h-auto min-h-0 font-normal normal-case text-base-content/60 hover:text-primary hover:bg-transparent shadow-none border-none">
                        <span class="icon-[tabler--x] size-4"></span>
                        Clear Filter
                    </a>
                @endif
            </div>
            @can('create', App\Models\Menu::class)
                <a href="{{ route('menus.create') }}" class="btn btn-sm btn-primary rounded-sm">
                    <span class="icon-[tabler--plus] size-5"></span>
                    Add Menu
                </a>
            @endcan
        </div>
        <div class="overflow-x-auto">
            <table class="table" id="main-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Actions</th>
                        <th>Name</th>
                        <th>Icon</th>
                        <th>Route</th>
                        <th>Parent</th>
                        <th>Group</th>
                        <th>Permission</th>
                        <th>Order</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($menus as $menu)
                        <tr class="row-hover">
                            <td>{{ $menus->firstItem() + $loop->index }}</td>
                            <td>
                                @can('update', $menu)
                                    <a href="{{ route('menus.edit', $menu->id) }}"
                                        class="btn btn-circle btn-text btn-sm hover:text-warning" aria-label="Edit">
                                        <span class="icon-[tabler--pencil] size-5"></span>
                                    </a>
                                @endcan
                                @can('delete', $menu)
                                    <button type="button" class="delete-button btn btn-circle btn-text btn-sm hover:text-error"
                                        aria-haspopup="dialog" aria-expanded="false" aria-controls="modal-delete"
                                        data-overlay="#modal-delete"
                                        data-action="{{ route('menus.destroy', $menu->id) }}">
                                        <span class="icon-[tabler--trash] size-5"></span>
                                    </button>
                                @endcan
                            </td>
                            <td class="font-medium">
                                <div class="flex items-center gap-2">
                                    <iconify-icon icon="{{ $menu->icon }}" width="16" height="16" class="text-base-content/60"></iconify-icon>
                                    {{ $menu->name }}
                                </div>
                            </td>
                            <td><code class="text-xs bg-base-200 px-1 rounded">{{ $menu->icon }}</code></td>
                            <td>
                                @if($menu->route_name)
                                    <span class="badge badge-soft badge-info text-xs">{{ $menu->route_name }}</span>
                                @else
                                    <span class="text-base-content/40 text-xs">— dropdown —</span>
                                @endif
                            </td>
                            <td>
                                @if($menu->parent)
                                    <span class="badge badge-soft badge-neutral text-xs">{{ $menu->parent->name }}</span>
                                @else
                                    <span class="text-base-content/40 text-xs">Top level</span>
                                @endif
                            </td>
                            <td>{{ $menu->group ?? '—' }}</td>
                            <td>
                                @if($menu->permission_name)
                                    <span class="badge badge-soft badge-primary text-xs">{{ $menu->permission_name }}</span>
                                @else
                                    <span class="text-base-content/40 text-xs">None</span>
                                @endif
                            </td>
                            <td>{{ $menu->sort_order }}</td>
                            <td>
                                @if($menu->is_active)
                                    <span class="badge badge-soft badge-success text-xs">Active</span>
                                @else
                                    <span class="badge badge-soft badge-error text-xs">Inactive</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="text-center text-base-content/50 py-8">No menus found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div id="modal-delete" class="overlay modal overlay-open:opacity-100 overlay-open:duration-300 modal-middle hidden"
        role="dialog" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">Confirm Deletion</h3>
                    <button type="button" class="btn btn-text btn-circle btn-sm absolute end-3 top-3"
                        aria-label="Close" data-overlay="#modal-delete">
                        <span class="icon-[tabler--x] size-4"></span>
                    </button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this menu? Child menus will be detached (parent set to null).
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-soft btn-secondary" data-overlay="#modal-delete">Close</button>
                    <form id="delete-form" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-error">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-4">
        {{ $menus->links('template.pagination') }}
    </div>
@endsection
@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const deleteButtons = document.querySelectorAll('.delete-button');
            const deleteForm = document.getElementById('delete-form');

            deleteButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const action = this.getAttribute('data-action');
                    if (deleteForm) {
                        deleteForm.setAttribute('action', action);
                    }
                });
            });
        });
    </script>
@endpush
