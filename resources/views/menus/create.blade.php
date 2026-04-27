@extends('template.master')
@section('content')
    <div class="pb-4">
        <h1 class="text-2xl font-semibold tracking-tight">Add Menu</h1>
        <p class="text-base-content/70 mt-1">Create a new sidebar menu item. If a permission name is provided, it will be
            auto-created and assigned to the admin role.</p>
    </div>
    <div class="border-base-content/25 w-full rounded-lg border bg-base-100 p-6 shadow-sm">
        <form action="{{ route('menus.store') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="label mb-1 text-sm font-medium" for="name">Menu Name <span class="text-error">*</span></label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}"
                        class="input input-bordered w-full rounded-sm @error('name') input-error @enderror"
                        placeholder="e.g. Dashboard, Users, Reports" />
                    @error('name')
                        <p class="text-error text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="label mb-1 text-sm font-medium" for="icon">Icon <span class="text-error">*</span></label>
                    <div class="flex gap-2 items-center">
                        <div class="flex items-center justify-center size-10 border border-base-content/20 rounded-sm bg-base-200 shrink-0">
                            <iconify-icon id="icon-preview" icon="tabler:circle" width="20" height="20"></iconify-icon>
                        </div>
                        <input type="text" id="icon" name="icon" value="{{ old('icon', 'tabler--circle') }}"
                            class="input input-bordered flex-1 rounded-sm @error('icon') input-error @enderror"
                            placeholder="e.g. tabler--home, tabler--users" />
                        <button type="button" class="btn btn-outline btn-sm rounded-sm h-10 shrink-0"
                            data-overlay="#icon-picker-modal">
                            <span class="icon-[tabler--icons] size-4"></span>
                            Browse
                        </button>
                    </div>
                    <p class="text-base-content/50 text-xs mt-1">
                        Type a <a href="https://tabler.io/icons" target="_blank" class="link link-primary">Tabler icon</a> name or
                        <button type="button" class="link link-primary" data-overlay="#icon-picker-modal">browse</button> to pick one.
                    </p>
                    @error('icon')
                        <p class="text-error text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="label mb-1 text-sm font-medium" for="route_name">Route Name</label>
                    <input type="text" id="route_name" name="route_name" value="{{ old('route_name') }}"
                        class="input input-bordered w-full rounded-sm @error('route_name') input-error @enderror"
                        placeholder="e.g. dashboard, users.index (leave blank for dropdown)" />
                    @error('route_name')
                        <p class="text-error text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="label mb-1 text-sm font-medium" for="permission_name">Permission Name</label>
                    <input type="text" id="permission_name" name="permission_name" value="{{ old('permission_name') }}"
                        class="input input-bordered w-full rounded-sm @error('permission_name') input-error @enderror"
                        placeholder="e.g. view-reports (auto-created & assigned to admin)" />
                    @error('permission_name')
                        <p class="text-error text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="label mb-1 text-sm font-medium" for="parent_id">Parent Menu</label>
                    <select id="parent_id" name="parent_id"
                        class="select select-bordered w-full rounded-sm @error('parent_id') select-error @enderror">
                        <option value="">— Top Level —</option>
                        @foreach($parents as $parent)
                            <option value="{{ $parent->id }}" {{ old('parent_id') == $parent->id ? 'selected' : '' }}>
                                {{ $parent->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('parent_id')
                        <p class="text-error text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="label mb-1 text-sm font-medium" for="group">Group Label</label>
                    <input type="text" id="group" name="group" value="{{ old('group') }}"
                        class="input input-bordered w-full rounded-sm @error('group') input-error @enderror"
                        placeholder="e.g. Access Management, Account (optional)" />
                    <p class="text-base-content/50 text-xs mt-1">Shown as section header above this menu item.</p>
                    @error('group')
                        <p class="text-error text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="label mb-1 text-sm font-medium" for="sort_order">Sort Order <span class="text-error">*</span></label>
                    <input type="number" id="sort_order" name="sort_order" value="{{ old('sort_order', 0) }}"
                        class="input input-bordered w-full rounded-sm @error('sort_order') input-error @enderror"
                        min="0" />
                    @error('sort_order')
                        <p class="text-error text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="flex items-center gap-3 pt-6">
                    <input type="checkbox" id="is_active" name="is_active" value="1"
                        class="checkbox checkbox-primary"
                        {{ old('is_active', '1') ? 'checked' : '' }} />
                    <label class="label text-sm font-medium cursor-pointer" for="is_active">Active</label>
                </div>
            </div>
            <div class="flex items-center gap-3 justify-between mt-2">
                <a href="{{ route('menus.index') }}" class="btn btn-ghost rounded-sm">Cancel</a>
                <button type="submit" class="btn btn-primary rounded-sm">
                    <span class="icon-[tabler--device-floppy] size-5"></span>
                    Save
                </button>
            </div>
        </form>
    </div>

    @include('menus.icon-picker-modal')
@endsection
