<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Roles & Permissions') }}
        </h2>

    </x-slot>

    <div class="my-5">
        @include('components.success')
        @if(session()->has('success'))
            <script>
                $(document).ready(function() {
                    $('#successMessage').show();
                    $('#successText').text('{{ session()->get('success') }}');
                    setTimeout(function() {
                        $('#successMessage').hide();
                    }, 3000);
                })
            </script>
        @endif
        <div class="w-full sm:px-6">
            <div class="flex items-center justify-end gap-5 px-12 mb-2">
                @can('view permissions')
                <a href="{{route('permissions')}}" class="py-2 px-6 bg-yellow-500 border-none outline-none text-sm text-white hover:bg-yellow-600 rounded cursor-pointer">Permissions</a>
                @endcan
                @can('view roles')
                <a href="{{'roles'}}" class="py-2 px-6 bg-yellow-500 border-none outline-none text-sm text-white hover:bg-yellow-600 rounded cursor-pointer">Roles</a>
                @endcan

            </div>
            <form action="{{ route('updatePermission') }}" method="POST">
                @csrf
                @method('POST')

                <table class="min-w-full border ">
                    <thead class="border-b">
                        <tr>
                            <th class="text-sm font-medium text-gray-900 px-6 py-4 text-left capitalize">Roles</th>
                            @foreach($roles as $role)
                                <th class="text-sm font-medium text-gray-900 px-6 py-4 text-left capitalize">{{ $role->name }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="border-b">
                        @foreach($permissions as $permission)
                            <tr>
                                <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap capitalize">{{ $permission->name }}</td>
                                @foreach($roles as $role)
                                    <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
                                        <input type="checkbox" name="permissions[{{ $role->id }}][]" value="{{ $permission->name }}"
                                               @if($role->hasPermissionTo($permission->name)) checked @endif>
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <button type="submit" class="mt-4 px-4 py-2 bg-green-500 text-white hover:bg-green-700 rounded focus:bg-green-600">Update</button>
            </form>
        </div>
    </div>
</x-app-layout>
