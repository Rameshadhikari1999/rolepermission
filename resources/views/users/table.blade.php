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
    <td
        class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap flex items-center gap-5">
        @can('edit users')
            <a href="users/{{ $user->id }}/edit"
                class="py-2 px-4 bg-green-500 text-white hover:bg-green-600 rounded font-medium">Edit</a>
        @endcan
        @can('delete users')
            <button type="button"
                data-id="{{$user->id}}"
                class="deleteBtn py-2 px-4 bg-red-500 hover:bg-red-600 text-white rounded font-medium">Delete</button>
        @endcan
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
    })
</script>
