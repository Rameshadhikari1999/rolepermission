@if ($accounts->count() > 0)
    @foreach ($accounts as $key => $account)
        <tr class="relative">
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                {{ $key + 1 }}
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
                @if ($account->status == 0)
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold text-red-800">
                        Pending
                    </span>
                @elseif($account->status == 1)
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold text-blue-800">Inprogress</span>
                @else
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold  text-green-800">
                        Approved
                    </span>
                @endif

            </td>
            <td class="group cursor-pointer">
                @if ($account->remark)
                    <span>
                        {{ Str::limit($account->remark, 10) }}
                    </span>
                    <span
                        class="absolute -left-full w-4/6 top-0 mt-2 group-hover:left-0 flex bg-gray-700 text-white text-xs rounded py-1 px-2">
                        {{ $account->remark }}
                    </span>
                @endif
            </td>
            <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap flex items-center gap-5">
                @if (Auth::user()->hasRole('superadmin') || Auth::user()->hasPermissionTo('edit accounts'))
                    <button type="button" data-id="{{ $account->id }}"
                        class="editBtn py-2 px-4 bg-green-500 text-white hover:bg-green-600 rounded font-medium">Edit</button>
                @endif
                @if (Auth::user()->hasRole('superadmin') || Auth::user()->hasPermissionTo('delete accounts'))
                    <button type="button" data-id="{{ $account->id }}"
                        class="deleteBtn py-2 px-4 bg-red-500 hover:bg-red-600 text-white rounded font-medium">Delete</button>
                @endif
                @if (Auth::user()->hasRole('superadmin') || Auth::user()->hasPermissionTo('update status'))
                    <button type="button" data-id="{{ $account->id }}"
                        class="updateStatus py-2 px-4 bg-blue-500 hover:bg-blue-600 text-white rounded font-medium">Change
                        status</button>
                @endif
            </td>
        </tr>
    @endforeach
    <tr class="bg-yellow-200">
        <td colspan="3" class="px-6 py-4 whitespace-nowrap text-sm font-medium text-yellow-900">Total Amount: </td>
        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-yellow-900">Rs.{{ $accounts->sum('amount') }}
        </td>
        <td colspan="3"></td>
    </tr>
@else
    <tr>
        <td class=" text-base font-bold px-6 py-4 whitespace-nowrap text-center text-red-400" colspan="8">Data Not
            Found</td>
    </tr>
@endif

<script>
    $(document).ready(function() {
        $('.deleteBtn').on('click', function() {

            let id = $(this).data('id');
            $('#status').val('');
            $('#message').text('Are you sure you want to delete this account');
            $('#conformBtn').attr('data-url', "{{ route('accounts.destory', ':id') }}".replace(':id',
                id));
            $('#conformModal').show();

        });

        $('.editBtn').on('click', function() {

            let id = $(this).data('id');
            $('#accountForm').trigger('reset');
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
                    console.log(res.account.id, 'res')
                    $('#id').val(res.account.id);
                    $('#cheque_number_input').val(res.account.cheque_number);
                    $('#bank_name').val(res.account.bank_name).trigger('change');
                    $('#amount').val(res.account.amount);
                    $('#remark').val(res.account.remark)
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

        $('.updateStatus').on('click', function() {

            let id = $(this).data('id');
            $('#id').val(id);
            $('#submitBtn').text('Update');
            $('#status-modal').show();
        });
    })
</script>
{{-- <script>
    function updateStatus(accountId, newStatus) {
        $(document).ready(function() {
            let id = accountId;
            let status = newStatus;
            if (status == 1) {
                $('#message').text('Are you sure you want to change the status to "In Progress"?');
            } else if (status == 2) {
                $('#message').text('Are you sure you want to change the status to "Approved"?');
            } else if (status == 0) {
                $('#message').text('Are you sure you want to change the status to "Pending"?');
            }

            $('#conformBtn').attr('data-url', "{{ route('accounts.updateStatus', ':id') }}".replace(':id',
                id));
            $('#status').val(status);
            $('#conformModal').show();
        })

    }
</script> --}}
