<div x-data="{ isOpen: false }">

    <!-- Main modal -->
    <div x-show="isOpen" id="status-modal" tabindex="-1" aria-hidden="true"
        class="fixed inset-0 z-50 flex items-center justify-center w-full h-full bg-black bg-opacity-50">
        <div @click.away="isOpen = false" class="relative p-4 w-full max-w-md max-h-full">
            <!-- Modal content -->
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                <!-- Modal header -->
                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white" id="">
                        Change Status
                    </h3>
                    <button id="closeStatusBtn" type="button"
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
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="pending" class="mb-2 text-sm font-medium">Pending</label>
                                <input type="radio" name="status" id="pending" value="0"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded ">
                            </div>
                            <div>
                                <label for="inprogress" class="mb-2 text-sm font-medium">Inprogress</label>
                                <input type="radio" name="status" id="inprogress" value="1"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded ">
                            </div>
                            <div>
                                <label for="approved" class="mb-2 text-sm font-medium">Approved</label>
                                <input type="radio" name="status" id="approved" value="2"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded ">
                            </div>
                        </div>
                        <div class="text-sm font-medium text-gray-500 dark:text-gray-300">
                            <button type="submit" id=""
                                class="py-2 px-4 bg-green-500 hover:bg-green-700 text-white rounded">Change</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {



        function showSuccessMessage(text) {
            $('#successText').text(text);
            $('#successMessage').show();
            setTimeout(function() {
                $('#successMessage').hide();
            }, 3000);
        }

        $('#closeStatusBtn').click(function() {
            $('#status-modal').hide();
        })


        $('#myForm').on('submit', function(e) {
            e.preventDefault();
            var id = $('#id').val();
            $.ajax({
                url: "{{ route('accounts.updateStatus', ':id') }}".replace(':id', id),
                method: "POST",
                data: new FormData(this),
                contentType: false,
                cache: false,
                processData: false,
                dataType: "json",
                success: function(data) {
                    showSuccessMessage('Status Updated Successfully');
                    $('#status-modal').hide();
                    $('#myForm')[0].reset();
                    $('#tbody').html(data.accounts);
                    console.log(data, 'success');
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
                    }
                }
            });
        });

    });
</script>
