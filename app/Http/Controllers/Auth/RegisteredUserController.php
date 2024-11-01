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
            // new middleware('permission:view users', only: ['index']),
            new middleware('permission:edit users', only: ['edit']),
            new middleware('permission:delete users', only: ['destory']),
        ];
    }
    public function index(){
        $users = User::all();
        return view('users.index', compact('users'));
    }

    public function edit($id){
        $user = User::findOrFail($id);
        $roles = Role::all();
        return view('users.edit', ['user' => $user, 'roles' => $roles]);
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

        $user->name = $request->name;
        $user->email = $request->email;
        $user->save();

        // Sync roles using Spatie package
        $user->syncRoles($request->roles);

        return redirect(route('users'))->with('success', 'User updated successfully');
    }

    public function destory($id){
        $user = User::findOrFail($id);
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
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', '', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // event(new Registered($user));

        // Auth::login($user);

        // return redirect(route('dashboard', absolute: false));
        $user->syncRoles($request->roles);
        $users = User::all();
        $view = view('users.table', compact('users'))->render();
        return response()->json(['users' => $view]);

    }

    public function search(Request $request){
        $users = User::where('name', 'like', '%' . $request->search . '%')
            ->orWhere('email', 'like', '%' . $request->search . '%')->get();
        $view = view('users.table', compact('users'))->render();
        return response()->json(['users' => $view]);
    }
}
