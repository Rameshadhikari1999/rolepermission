@if ($permissionUsers->count() > 0)
    @foreach ($permissionUsers as $key => $user)
        <tr>
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                {{ $key + 1 }}
            </td>
            <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
                {{ $user->name }}
            </td>

            <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
                {{ $user->getRoleNames()->implode(', ') }}
            </td>
            <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
                {{ $user->getPermissionNames()->implode(', ') }}
            </td>
            <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap flex items-center gap-5">
                <button type="button" data-id="{{ $user->id }}"
                    class="editBtn py-2 px-4 bg-green-500 text-white hover:bg-green-600 rounded font-medium">Edit</button>
                <button data-id="{{ $user->id }}"
                    class="deleteBtn py-2 px-4 bg-red-500 hover:bg-red-600 text-white rounded font-medium">Delete</button>
            </td>
        </tr>
    @endforeach
@endif


<script>
    $(document).ready(function() {
        $('.editBtn').click(function() {
            const id = $(this).data('id');
            $.ajax({
                type: "GET",
                url: "{{ route('editOnlyUserPermission', ':id') }}".replace(':id', id),
                dataType: "json",
                success: function(res) {
                    $('#id').val(res.permissionUsers.id);
                    $('#userName').val(res.permissionUsers.id).trigger('change');
                    $('#permission').val(res.hasPermission[0].name).trigger('change');
                    // console.log(res.hasPermission[0].name);
                },
                error: function(xhr, status, error) {
                    console.log(xhr.responseText);
                }
            })

        });
        $('.deleteBtn').click(function() {
            const id = $(this).data('id');
            $('#message').text('Are you sure you want to remove permission from this user');
            $('#conformBtn').attr('data-url', "{{ route('destroyOnlyUserPermission', ':id') }}".replace(
                ':id', id));
            $('#conformModal').show();
        });
    });
</script>
