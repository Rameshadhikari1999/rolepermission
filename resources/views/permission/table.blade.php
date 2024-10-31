@if (count($permissions) > 0)
    @foreach ($permissions as $permission)
        <tr>
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                {{ $permission->id }}
            </td>
            <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
                {{ $permission->name }}
            </td>
            <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap flex items-center gap-5">
                <button @click="isOpen = true"
                    class="editBtn block text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800"
                    type="button" data-id="{{ $permission->id }}">
                    Edit
                </button>
                <button
                    class="deleteBtn block text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-800"
                    type="button" data-id="{{ $permission->id }}"> Delete</button>
            </td>
        </tr>
    @endforeach
@endif


<script>
    $(document).ready(function() {

        $('.editBtn').on('click', function() {

            let id = $(this).data('id');
            $('#modal-title').text('Edit Permission');
            $('#submitBtn').text('Update');
            $('#myForm').trigger('reset');
            $('#permission-modal').show();
            $.ajax({
                type: "GET",
                url: "{{ route('permissions.edit', ':id') }}".replace(':id', id),
                dataType: "json",
                success: function(res) {
                    $('#id').val(res.permission.id);
                    $('#name').val(res.permission.name);
                },
                error: function(xhr, status, error) {
                    console.log(xhr.responseText);
                }
            });
        });

        $('.deleteBtn').on('click', function() {

            let id = $(this).data('id');
            $('#message').text('Are you sure you want to delete this permission');
            $('#conformBtn').attr('data-url',"{{route('permissions.destroy',':id')}}".replace(':id', id));
             $('#conformModal').show();

        });
    });
</script>
