<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Users/Edit') }}
        </h2>

    </x-slot>

    <div class="">
        <div class="w-full sm:px-6">
            <div class="my-5 w-1/2">
                <form action="{{ route('users.update', $user->id) }}" method="POST">
                    @csrf
                    <div class="my-3">
                        <label for="name"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Name</label>
                        <input type="text" name="name" id="name" value="{{ $user->name }}"   class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
                        placeholder="admin" required>
                        @error('name')
                            <p class="text-red-500 mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="my-3">
                        <label for="email"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Name</label>
                        <input type="text" name="email" id="email" value="{{ $user->email }}"   class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
                        placeholder="admin" required>
                        @error('email')
                            <p class="text-red-500 mt-2">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="mb-3">
                        @foreach ($roles as $role)
                            <div>
                                <input type="checkbox" name="roles[]" id="roles-{{ $role->id }}"
                                    value="{{ $role->name }}" {{ in_array($role->name, $user->roles->pluck('name')->toArray()) ? 'checked' : '' }}>
                                <label for="roles-{{ $role->id }}">{{ $role->name }}</label>
                            </div>
                        @endforeach
                    </div>
                    <div class="mb-3">
                        <button type="submit" class="py-2 px-4 bg-green-500 hover:bg-green-700 text-white rounded">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
