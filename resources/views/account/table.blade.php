@if ($accounts->count() > 0)
    @foreach ($accounts as $key => $account)
        <tr class="relative">
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                {{ $key+1 }}
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
                    {!! Auth::user()->hasRole('superadmin') || Auth::user()->hasPermissionTo('update status')
                        ? '<button onclick="updateStatus(' .
                            $account->id .
                            ', 1)" type="button" class="py-2 px-4 bg-red-400 text-white hover:bg-red-300 rounded font-medium">Pending</button>'
                        : '<span class="text-red-500">Pending</span>' !!}
                @elseif ($account->status == 1)
                    {!! Auth::user()->hasRole('superadmin')
                        ? '<button onclick="updateStatus(' .
                            $account->id .
                            ', 2)" type="button" class="py-2 px-4 bg-blue-400 text-white hover:bg-blue-300 rounded font-medium">Inprogress</button>'
                        : '<span class="text-blue-500">Inprogress</span>' !!}
                @else
                    {!! Auth::user()->hasRole('superadmin')
                        ? '<button onclick="updateStatus(' .
                            $account->id .
                            ', 0)" type="button" class="py-2 px-4 bg-green-400 text-white hover:bg-green-300 rounded font-medium">Approved</button>'
                        : '<span class="text-green-500">Approved</span>' !!}
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
            </td>
        </tr>
    @endforeach
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
                    $('#cheque_number_input').val(res.account.cheque_number);
                    $('#bank_name').val(res.account.bank_name);
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

        // update status
        // function updateStatus(){
        //     console.log('clikc');

        //     alert('click')
        //     // let id = $(this).data('id');
        //     // $('#message').text('Are you sure you want to delete this account');
        //     // $('#conformBtn').attr('data-url', "{{ route('accounts.destory', ':id') }}".replace(':id',
        //     //     id));
        //     // $('#conformModal').show();
        // }
    })
</script>
<script>
    function updateStatus(accountId, newStatus) {
        $(document).ready(function() {
            let id = accountId;
            let status = newStatus;
            $('#message').text('Are you sure you want to update status');
            $('#conformBtn').attr('data-url', "{{ route('accounts.updateStatus', ':id') }}".replace(':id',
                id));
            $('#status').val(status);
            $('#conformModal').show();
        })

    }
</script>
