<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class RoleController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new middleware('permission:view roles', only: ['index']),
            new middleware('permission:update roles', only: ['update, edit']),
            new middleware('permission:delete roles', only: ['destroy']),
            new middleware('permission:create roles', only: ['create','store']),
        ];
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roles = Role::all();
        return view('roles.index', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required','unique:roles,name', 'string', 'max:255'],
            'permissions' => ['required']
        ]);

        $role = Role::create(['name' => $request->name]);
        $role->syncPermissions($request->permissions);

        $roles = Role::all();
        $view = view('roles.table', compact('roles'))->render();
        return response()->json(['roles' => $view]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $roles = Role::findOrFail($id);
        $hasPermissions = $roles->permissions->pluck('name')->toArray();
        return response()->json(['roles' => $roles, 'hasPermissions' => $hasPermissions]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            // 'permissions' => ['required']
        ]);

        $role = Role::findOrFail($id);
        $role->name = $request->name;
        $role->save();
        $role->syncPermissions($request->permissions);

        $roles = Role::all();
        $view = view('roles.table', compact('roles'))->render();
        return response()->json(['roles' => $view]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $role = Role::findOrFail($id);
        $role->delete();
        $roles = Role::all();
        $view = view('roles.table', compact('roles'))->render();
        return response()->json(['roles'=> $view]);
    }
    public function search(Request $request)
    {
        $roles = Role::where('name', 'like', '%' . $request->search . '%')->get();
        $view = view('roles.table', compact('roles'))->render();
        return response()->json(['roles' => $view]);
    }
}
