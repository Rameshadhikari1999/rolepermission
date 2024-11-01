@php
    use App\Models\User;
    use Spatie\Permission\Models\Role;
    $users = User::all();
    $roles = Role::all();
@endphp

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>

    </x-slot>

    <div class="">
        <div class="w-full sm:px-6">
                <h1 class="text-2xl font-bold py-5 px-5">Welcome To Dashboard</h1>
                <div class="flex flex-wrap gap-5">
                    <a href="{{ route('users') }}" class="w-1/4 bg-white overflow-hidden shadow-sm sm:rounded-lg cursor-pointer">
                        <div class="p-6 bg-white border-b border-gray-200">
                            <h3 class="text-xl font-bold text-gray-800 py-3">Total Users</h3>
                            <p class="text-3xl font-bold text-gray-800">{{ $users->count() }}</p>
                        </div>
                    </a>
                        @foreach ($roles as $role)
                        <a href="{{ route('users', $role->name) }}" class="w-1/4 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6 bg-white border-b border-gray-200">
                                <h3 class="text-xl font-bold text-gray-800 py-3">Total {{ $role->name }}</h3>
                                <p class="text-3xl font-bold text-gray-800">{{ $role->users->count() }}</p>
                            </div>
                        </a>
                        @endforeach
                </div>
        </div>
    </div>
</x-app-layout>
