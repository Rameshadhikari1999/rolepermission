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
        <img src="{{ $user->image? asset('storage/'.$user->image) : 'https://ui-avatars.com/api/?name='.Auth::user()->name }}" width="50" height="50" alt="" class="rounded">
    </td>
    <td
        class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap flex items-center gap-5">
        @can('edit users')
            <button type="button"
            {{-- @click="isOpen = true" --}}
                data-id="{{$user->id}}"
                class="editBtn py-2 px-4 bg-green-500 text-white hover:bg-green-600 rounded font-medium">Edit</button>
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

        $('.editBtn').on('click', function() {
            // alert('clicked')
            let id = $(this).data('id');
            $('#modal-title').text('Edit User');
            $('#submitBtn').text('Update');
            $('#edit-user-modal').show();
            $.ajax({
                type: "GET",
                url: "{{ route('users.edit', ':id') }}".replace(':id', id),
                dataType: "json",
                success: function(res) {
                    var imageUrl = "{{ $user->image ? asset('storage/') : '' }}";
                    console.log(imageUrl+res.user.image);
                    $('#id').val(res.user.id);
                    $('#name').val(res.user.name);
                    $('#email').val(res.user.email);
                    $('#previousImage').val(imageUrl+res.user.image);
                },
                error: function(xhr, status, error) {
                    console.log(xhr.responseText);
                }
            });
        });
    })
</script>
