@php
    use Spatie\Permission\Models\Permission;

    $permissions = Permission::all();
@endphp

@if (count($roles) > 0)
    @foreach ($roles as $role)
        <tr>
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                {{ $role->id }}
            </td>
            <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
                {{ $role->name }}
            </td>
            <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
                {{ $role->permissions->pluck('name')->implode(', ') }}
            </td>
            <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap flex items-center gap-5">
                <button @click="isOpen = true"
                    class="editBtn block text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800"
                    type="button" data-id="{{ $role->id }}">
                    Edit
                </button>
                <button
                    class="deleteBtn block text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-800"
                    type="button" data-id="{{ $role->id }}"> Delete</button>
            </td>
        </tr>
    @endforeach
@endif


<script>
    $(document).ready(function() {

        $('.editBtn').on('click', function() {

            let id = $(this).data('id');
            $('#modal-title').text('Edit role');
            $('#submitBtn').text('Update');
            // $('#role-modal').get(0).__x.$data.isOpen = true;
            $('#role-modal').show();

            $.ajax({
                type: "GET",
                url: "{{ route('roles.edit', ':id') }}".replace(':id', id),
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                dataType: "json",
                success: function(res) {
                    console.log(res, 'res')
                    $('#name').val(res.roles.name);
                    $('#id').val(res.roles.id);

                    res.roles.permissions.forEach((permission) => {
                        $('#permissions-' + permission.id).prop('checked', true);
                    })

                },
                error: function(xhr, status, error) {
                    console.log(xhr.responseText);
                }
            })
        })

        $('.deleteBtn').on('click', function() {

            let id = $(this).data('id');
            $('#message').text('Are you sure you want to delete this permission');
            $('#conformBtn').attr('data-url', "{{ route('roles.destroy', ':id') }}".replace(':id',
                id));
            $('#conformModal').show();

        });
    });
</script>
