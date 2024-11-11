<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Users Permissions') }}
        </h2>

    </x-slot>

    <div class="w-11/12 mx-auto">
        @include('components.errerAlert')
        @include('components.success')
        @include('components.conformAlert')

        <div class="w-full mt-5">
            <h1 class="font-semibold text-xl text-gray-800 leading-tight">User Permissions</h1>
            <div class="w-full my-5">
                <form action="POST" id="myForm">
                    <div class=" w-full flex gap-5 items-center">
                        <input type="hidden" name="id" id="id">
                        <div class="w-1/2">
                            <label for="userName"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Select
                                User</label>
                            <select name="user_id" id="userName"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white">
                                <option value="">select user</option>
                                @foreach ($permissionUsers as $user)
                                    @if (Auth::user()->hasRole('superadmin') || !$user->hasRole('superadmin'))
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="w-1/2">

                            <label for="permission"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Select
                                Permission</label>
                            <select name="permission" id="permission"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white">
                                <option value="">select permission</option>
                                @foreach ($permissions as $permission)
                                    <option value="{{ $permission->name }}">{{ $permission->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div>
                        <button type="submit"
                            class="mt-4 px-4 py-2 bg-green-500 text-white hover:bg-green-700 rounded focus:bg-green-600">Update</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="w-full flex flex-col mt-5">
            <div class="overflow-x-auto sm:-mx-6 lg:-mx-8">
                <div class="py-2 inline-block min-w-full sm:px-6 lg:px-8">
                    <table class="min-w-full ">
                        <thead class="border-b" style="background: #FEDF15">
                            <tr>
                                <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                                    S/N
                                </th>
                                <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                                    Name
                                </th>

                                <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                                    Roles
                                </th>
                                <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                                    Permissions
                                </th>
                                <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                                    Action
                                </th>
                            </tr>
                        </thead>
                        <tbody id="permissiontbody">
                            @include('permission.permissionTable')
                        </tbody>
                    </table>
                    <div class="my-4 w-1/2 h-10">
                        {{-- {{ $users->links() }} --}}
                    </div>
                    {{-- </div> --}}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>


<script>
    $(document).ready(function() {

        function showSuccessMessage(message) {
            $('#successMessage').show();
            $('#successText').text(message);
            setTimeout(function() {
                $('#successMessage').hide();
            }, 3000);
        }
        $('#myForm').on('submit', function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            $.ajax({
                url: '{{ route('updateOnlyUserPermission') }}',
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    $('#myForm').trigger('reset');
                    showSuccessMessage('Permission Updated Successfully');
                    $('#permissiontbody').html(response.permissionUsers);
                },
                error: function(xhr, status, error) {
                    if (xhr.responseJSON.errors) {
                        $('#errorMessage').show();
                        $('#errorList').empty();
                        var errors = xhr.responseJSON.errors;
                        $.each(errors, function(key, value) {
                            $('#errorList').append('<li>' + value[0] + '</li>');
                        });
                    }
                }
            });
        });
    })
</script>
