<div x-data="{ isOpen: false }">
    <!-- Button to open modal -->
    <div class="w-full flex justify-between mr-32 mt-5">

        <input type="text" name="search" id="search" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-1/3 p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white" placeholder="Search here.......">
        <button id="addBtn" class="block text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800" type="button">
            Add
        </button>
    </div>

    <!-- Main modal -->
    <div x-show="isOpen"  id="permission-modal" tabindex="-1" aria-hidden="true" class="fixed inset-0 z-50 flex items-center justify-center w-full h-full bg-black bg-opacity-50">
        <div @click.away="isOpen = false" class="relative p-4 w-full max-w-md max-h-full">
            <!-- Modal content -->
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                <!-- Modal header -->
                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white" id="modal-title">
                    </h3>
                    <button id="closeBtn" type="button" class="end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white">
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                </div>
                <!-- Modal body -->
                <div class="p-4 md:p-5">
                    <form id="myForm" class="space-y-4" action="#">
                        @csrf
                        <input type="hidden" name="id" id="id">
                        <div>
                            <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Name</label>
                            <input type="text" name="name" id="name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white" placeholder="Example: add user" required />
                        </div>
                        <div class="text-sm font-medium text-gray-500 dark:text-gray-300">
                             <button type="submit" id="submitBtn" class="py-2 px-4 bg-green-500 hover:bg-green-700 text-white rounded"></button>
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

        $('#closeBtn').click(function() {
            $('#permission-modal').hide();
        })
        $('#addBtn').click(function() {
            $('#myForm').trigger('reset');
            $('#id').val('');
            $('#modal-title').text('Add Permission');
            $('#submitBtn').text('Add');
            $('#permission-modal').show();
        });

        $('#myForm').on('submit', function(e) {
            e.preventDefault();
            var id = $('#id').val();
            $.ajax({
                url: id ? "{{ route('permissions.update', ':id') }}".replace(':id', id) : "{{ route('permissions.store') }}",
                method: "POST",
                data: new FormData(this),
                contentType: false,
                cache: false,
                processData: false,
                dataType: "json",
                success: function(data) {
                    showSuccessMessage(id ? 'Permission Updated Successfully' : 'Permission Added Successfully');
                    $('#permission-modal').hide();
                    $('#myForm')[0].reset();
                    $('#tbody').html(data.permissions);
                    $('#permission-modal').hide();
                    console.log(data,'success');
                },
                error:function(xhr) {
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

        $('#search').on('keyup', function() {
            var value = $(this).val().toLowerCase();
            $.ajax({
                url: "{{ route('permissions.search') }}",
                method: "GET",
                data: {
                    'search': value
                },
                success: function(data) {
                    $('#tbody').html(data.permissions);
                }
            })
        })
    });
</script>


