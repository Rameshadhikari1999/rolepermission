@php
    use Spatie\Permission\Models\Role;

    $roles = Role::all();
@endphp
<div x-data="{ isOpen: false }">
    <!-- Button to open modal -->
    <div class="w-full flex justify-end mr-32 mt-5">
        {{-- <input type="text" name="searchUser" id="searchUser"
            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-1/3 p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
            placeholder="Search here......."> --}}
        @can('create users')
            <button id="addBtn"
                class="block text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                type="button">
                Add
            </button>
        @endcan
    </div>

    <!-- Main modal -->
    <div x-show="isOpen" id="user-modal" tabindex="-1" aria-hidden="true"
        class="fixed inset-0 z-50 flex items-center justify-center w-full h-full bg-black bg-opacity-50">
        <div @click.away="isOpen = false" class="relative p-4 w-full max-w-md max-h-full">
            <!-- Modal content -->
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                <!-- Modal header -->
                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white" id="user-modal-title">
                    </h3>
                    <button id="closeModalBtn" type="button"
                        class="end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white">
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                </div>
                <!-- Modal body -->
                <div class="p-4 md:p-5">
                    <form id="myForm" class="space-y-4" action="#">
                        @csrf
                        <input type="hidden" name="id" id="id">
                        <div>
                            <label for="name"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Name</label>
                            <input type="text" name="name" id="name"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
                                placeholder="Example: add user" required />
                        </div>
                        <div>
                            <label for="email"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Email</label>
                            <input type="email" name="email" id="email"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
                                placeholder="Example: 0WbqZ@example.com" required />
                        </div>
                        <div>
                            <img id="previousImage" src="" width="50" height="50" alt=" not found"
                                style="display: none">
                        </div>
                        <div>
                            <label for="image"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Image</label>
                            <input type="file" name="image" id="image"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white" />
                        </div>
                        <div class="flex gap-5" id="passwordDiv" style="display: none">
                            <div class="w-1/2">
                                <label for="password"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Password</label>
                                <input type="password" name="password" id="password"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
                                    placeholder="*************" />
                            </div>
                            <div class="w-1/2">
                                <label for="confirm-password"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Confirm
                                    Password</label>
                                <input type="password" name="password_confirmation" id="conform-password"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
                                    placeholder="*************" />
                            </div>
                        </div>
                        <div>
                            <label for=""
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Role</label>
                            <div class="flex items-center gap-5 flex-wrap">
                                @foreach ($roles as $role)
                                @if ($role->name !=='superadmin' || Auth::user()->hasRole('superadmin'))
                                <div class="flex items-center gap-2">
                                    <input type="radio" value="{{ $role->name }}" name="roles"
                                    id="role-{{ $role->id }}" class="" required />
                                    <label for="roles">{{ $role->name }}</label>
                                </div>
                                @endif
                                @endforeach

                            </div>
                        </div>
                        <div class="text-sm font-medium text-gray-500 dark:text-gray-300">
                            <button type="submit" id="submitBtn"
                                class="py-2 px-4 bg-green-500 hover:bg-green-700 text-white rounded"></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {

//         window.addEventListener('error', function(event) {
//     console.error('Global Error:', event.error || event.message);
// });

// window.addEventListener('unhandledrejection', function(event) {
//     console.error('Unhandled Promise Rejection:', event.reason);
// });


        function showSuccessMessage(text) {
            $('#successText').text(text);
            $('#successMessage').show();
            setTimeout(function() {
                $('#successMessage').hide();
            }, 5000);
        }

        $('#closeModalBtn').on('click', function() {
            $('#user-modal').hide();
        });

        $('#addBtn').click(function() {
            $('#myForm').trigger('reset');
            $('#id').val(null);
            $('#user-modal-title').text('Add User');
            $('#submitBtn').text('Add');
            $('#previousImage').hide();
            $('#passwordDiv').show();
            $('#user-modal').show();
        });

        $('#myForm').on('submit', function(e) {
            e.preventDefault();
            console.log('e', e);
            var id = $('#id').val();
            // alert(id);
            var formData = new FormData(this);
            $.ajax({
                url: id ? "{{ route('users.update', ':id') }}".replace(':id', id) :
                    "{{ route('users.store') }}",
                method: id ? "POST" : "POST",
                data: formData,
                contentType: false,
                cache: false,
                processData: false,
                dataType: "json",
                success: function(data) {
                    showSuccessMessage(id ? 'User Updated Successfully' :
                        'User Added Successfully');
                    $('#myForm')[0].reset();
                    $('#tbody').html(data.users);
                    if(data.user_image){
                        console.log('user_image',data.user_image);
                    $('#navbar-profile').attr('src', data.user_image);

                    }
                     $('#user-modal').hide();
                },
                error: function(xhr) {
                    if (xhr.responseJSON.errors) {
                        $('#errorMessage').show();
                        $('#errorList').empty();
                        var errors = xhr.responseJSON.errors;
                        $.each(errors, function(key, value) {
                            $('#errorList').append('<li>' + value[0] + '</li>');
                        });
                        setTimeout(function() {
                            $('#errorMessage').hide();
                        }, 5000);
                        console.log(xhr.responseJSON.errors, 'error');
                    } else {
                        console.log(xhr.responseText, 'known error');
                    }
                }
            });
        });


        // search

        $('#searchUser').on('keyup', function() {
            var value = $(this).val().toLowerCase();
            console.log('value', value);
            $.ajax({
                url: "{{ route('users.search') }}",
                method: "GET",
                data: {
                    'searchUser': value
                },
                success: function(data) {
                    console.log(data, 'data');
                    $('#tbody').html(data.users);
                },
                error: function(xhr) {
                    if(xhr.responseJSON.errors) {
                        var errors = xhr.responseJSON.errors;
                        $.each(errors, function(key, value) {
                            $('#errorList').append('<li>' + value[0] + '</li>');
                        });
                        setTimeout(function() {
                            $('#errorMessage').hide();
                        }, 5000);
                    }
                }
            })
        });
    });
</script>
