<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class PermissionController extends Controller implements HasMiddleware
{

    public static function middleware(): array
    {
        return [
            new middleware('permission:view permissions', only: ['index']),
            new middleware('permission:view permissions', only: ['rolePermission']),
            new middleware('permission:update permissions', only: ['updatePermission']),
            new middleware('permission:delete permissions', only: ['destroy']),
            new middleware('permission:create permissions', only: ['create','store']),
        ];
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $permissions = Permission::orderBy('id', 'asc')->get();
        return view('permission.index', compact('permissions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // return view('permission.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required','unique:permissions,name', 'string', 'max:255'],
        ]);

        $permission = Permission::create([
            'name' => $request->name,
        ]);

        $permissions = Permission::all();
        $view = view('permission.table', compact('permissions'))->render();
        return response()->json(['permissions' => $view]);
    }

    /**
     * Display the specified resource.
     */
    public function rolePermission()
    {
        $permissions = Permission::all();
        $roles = Role::all();
        return view('permission.show',[
            'permissions' => $permissions,
            'roles' => $roles
            ]);
    }

    public function updatePermission(Request $request)
    {
        $roles = Role::all();

        foreach ($roles as $role) {
            if (isset($request->permissions[$role->id]))
            {
                $role->syncPermissions($request->permissions[$role->id]);
            } else {
                $role->syncPermissions([]);
            }
        }

        return redirect()->back()->with('success', 'Permissions updated successfully');
        // $view = view('permission.table', compact('permissions'))->render();
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $permission = Permission::findOrFail($id);
        return response()->json(['permission' => $permission]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        $permission = Permission::findOrFail($id);
        $permission->name = $request->name;
        $permission->save();

        $permissions = Permission::all();
        $view = view('permission.table', compact('permissions'))->render();
        return response()->json(['permissions' => $view]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $permission = Permission::find($id);
        $permission->delete();
        $permissions = Permission::all();
        $view = view('permission.table', compact('permissions'))->render();
        return response()->json(['permissions' => $view]);
    }

    public function search(Request $request)
    {
        $permissions = Permission::where('name', 'like', '%' . $request->search . '%')->get();
        $view = view('permission.table', compact('permissions'))->render();
        return response()->json(['permissions' => $view]);
    }
}
