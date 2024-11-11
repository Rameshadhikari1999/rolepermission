@foreach ($users as $user)
    <tr>
        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
            {{ $user->id }}
        </td>
        <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
            {{ $user->name }}
        </td>
        <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
            {{ $user->email }}
        </td>
        <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
            {{ $user->getRoleNames()->implode(', ') }}
        </td>
        <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
            {{ $user->getPermissionNames()->implode(', ') }}
        </td>
        <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
            <img src="{{ $user->image ? asset('storage/' . $user->image) : 'https://ui-avatars.com/api/?name=' . Auth::user()->name }}"
                width="50" height="50" alt="" class="rounded">
        </td>
        <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap flex items-center gap-5">
            @if (!$user->hasRole('superadmin') || Auth::user()->hasRole('superadmin'))
                @can('edit users')
                    <button type="button" {{-- @click="isOpen = true" --}} data-id="{{ $user->id }}"
                        class="editBtn py-2 px-4 bg-green-500 text-white hover:bg-green-600 rounded font-medium">Edit</button>
                @endcan
                @can('delete users')
                    <button type="button" data-id="{{ $user->id }}"
                        class="deleteBtn py-2 px-4 bg-red-500 hover:bg-red-600 text-white rounded font-medium">Delete</button>
                @endcan
            @endif
        </td>
    </tr>
@endforeach

<script>
    $(document).ready(function() {
        $('.deleteBtn').on('click', function() {

            let id = $(this).data('id');
            $('#message').text('Are you sure you want to delete this user');
            $('#conformBtn').attr('data-url', "{{ route('users.destory', ':id') }}".replace(':id',
                id));
            $('#conformModal').show();

        });

        $('.editBtn').on('click', function() {

            let id = $(this).data('id');
            // $('#myForm').trigger('reset');
            $('#passwordDiv').hide();
            $('#user-modal-title').text('Edit User');
            $('#submitBtn').text('Update');
            $('#user-modal').show();
            $.ajax({
                type: "GET",
                url: "{{ route('users.edit', ':id') }}".replace(':id', id),
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                dataType: "json",
                success: function(res) {

                    $('#id').val(res.user.id);
                    $('#name').val(res.user.name);
                    $('#email').val(res.user.email);
                    if (res.user.image) {
                        var imageUrl = "{{ asset('storage') }}";
                        $('#previousImage').show();
                        $('#previousImage').attr('src', imageUrl + '/' + res.user.image);
                    } else {
                        $('#previousImage').hide();
                    }
                    res.user.roles.forEach((role) => {
                        $('#role-' + role.id).prop('checked', true);
                    })
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
