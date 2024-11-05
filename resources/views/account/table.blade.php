@if($accounts->count() > 0)
@foreach ($accounts as $account)
<tr>
    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
        {{ $account->id }}
    </td>
    <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
        {{ $account->cheque_number }}
    </td>
    <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
        {{ $account->bank_name }}
    </td>
    <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
    {{ $account->amount }}
    </td>
    <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
        @if($account->status == 0)
        False
        @else
        True
        @endif
    </td>
    <td>

    </td>
    <td
        class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap flex items-center gap-5">

            <button type="button"
            {{-- @click="isOpen = true" --}}
                data-id="{{$account->id}}"
                class="editBtn py-2 px-4 bg-green-500 text-white hover:bg-green-600 rounded font-medium">Edit</button>
        {{-- @can('delete accounts') --}}
            <button type="button"
                data-id="{{$account->id}}"
                class="deleteBtn py-2 px-4 bg-red-500 hover:bg-red-600 text-white rounded font-medium">Delete</button>
        {{-- @endcan --}}
    </td>
</tr>
@endforeach
@else
<tr>
    <td class=" text-base font-bold px-6 py-4 whitespace-nowrap text-center text-red-400" colspan="8">Data Not Found</td>
</tr>
@endif

<script>
    $(document).ready(function() {
        $('.deleteBtn').on('click', function() {

            let id = $(this).data('id');
            $('#message').text('Are you sure you want to delete this account');
            $('#conformBtn').attr('data-url', "{{ route('accounts.destory', ':id') }}".replace(':id',
                id));
            $('#conformModal').show();

        });

        $('.editBtn').on('click', function() {

            let id = $(this).data('id');
            $('#myForm').trigger('reset');
            $('#modal-title').text('Edit account');
            $('#submitBtn').text('Update');
            $('#cheque_number').show();
            $('#chequeNumberDiv').hide();
            $('#account-modal').show();
            $.ajax({
                type: "GET",
                url: "{{ route('accounts.edit', ':id') }}".replace(':id', id),
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                dataType: "json",
                success: function(res) {

                    $('#id').val(res.account.id);
                    $('#cheque_number').val(res.account.cheque_number);
                    $('#bank_name').val(res.account.bank_name);
                    $('#amount').val(res.account.amount);
                },
                error: function(xhr, status, error) {
                    if(xhr.responseJSON.errors){
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
