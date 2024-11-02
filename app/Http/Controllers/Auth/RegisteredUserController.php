<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;
use Illuminate\Auth\Events\Registered;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class RegisteredUserController extends Controller implements HasMiddleware
{

    public static function middleware(): array
    {
        return [
            new middleware('permission:view users', only: ['index']),
            new middleware('permission:create users', only: ['store']),
            new middleware('permission:edit users', only: ['edit']),
            new middleware('permission:delete users', only: ['destory']),
        ];
    }
    public function index($role=null)
    {
        // dd($role);
        if ($role) {
            $users = User::role($role)->get();
            return view('users.index', compact('users'));
        } else {
            $users = User::all();
            return view('users.index', compact('users'));
        }
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        $roles = Role::all();
        $hasRole = $user->roles->pluck('name')->toArray();
        return response()->json(['user' => $user, 'roles' => $roles, 'hasRole' => $hasRole]);
    }

    // update user
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $id],
            'roles' => ['required']
        ]);

        $user = User::findOrFail($id);

        if($request->hasFile('image')) {
            if ($user->image) {
                $file_path = storage_path('app/public/' . $user->image);
                if (file_exists($file_path)) {
                    unlink($file_path);
                }
            }
            $original_name = $request->file('image')->getClientOriginalName();
            $extension = $request->file('image')->getClientOriginalExtension();
            $filename = $original_name . '_' . time() . '.' . $extension;
            $path = $request->file('image')->storeAs('upload/images', $filename, 'public');
            $user->image = $path;
        }

        if ($request->password) {
            $user->password = Hash::make($request->password);
        }
        $user->name = $request->name;
        $user->email = $request->email;
        $user->save();

        // Sync roles using Spatie package
        $user->syncRoles($request->roles);

        // return redirect(route('users'))->with('success', 'User updated successfully');

        $users = User::all();
        $view = view('users.table', compact('users'))->render();
        return response()->json(['users' => $view]);
    }

    public function destory($id)
    {
        $user = User::findOrFail($id);
        if ($user->image) {
            $file_path = storage_path('app/public/' . $user->image);

            if (file_exists($file_path)) {
                unlink($file_path);
            }
        }
        $user->delete();
        $users = User::all();
        $view = view('users.table', compact('users'))->render();
        return response()->json(['users' => $view]);
    }

    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request) //: RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'roles' => ['required'],
            'images' => ['image', 'mimes:jpeg,png,jpg,gif,svg'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // upload image
        $path = null;
        if ($request->hasFile('image')) {
            $original_name = $request->file('image')->getClientOriginalName();
            $extension = $request->file('image')->getClientOriginalExtension();
            $filename = $original_name . '_' . time() . '.' . $extension;
            $path = $request->file('image')->storeAs('upload/images', $filename, 'public');
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $user->image = $path;
        $user->save();

        // event(new Registered($user));

        // Auth::login($user);

        // return redirect(route('dashboard', absolute: false));
        $user->syncRoles($request->roles);
        $users = User::all();
        $view = view('users.table', compact('users'))->render();
        return response()->json(['users' => $view]);
    }

    public function search(Request $request)
    {
        // $search = $request->searchUser;
        $users = User::where('name', 'like', '%' . $request->searchUser . '%')
            ->orWhere('email', 'like', '%' . $request->searchUser . '%')
            ->get();
        $view = view('users.table', compact('users'))->render();
        return response()->json(['users' => $view]);
    }
}
