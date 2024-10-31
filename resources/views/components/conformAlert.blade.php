<div id="conformModal" class="w-1/2 min-h-40 my-5 fixed top-0 left-1/2 -translate-x-1/2 z-50 bg-white shadow rounded"
    style="display: none">
    {{-- close button  --}}
    <div class="w-full flex justify-end">
        <button class="closeBtn w-5 h-5 rounded-full font-bold text-gray-700 cursor-pointer">X</button>
    </div>
    {{-- body  --}}
    <div class="w-full px-5 py-5">
        <p id="message" class="text-center font-bold text-lg text-red-400 font-serif"></p>
    </div>
    {{-- footer  --}}
    <div class="fixed bottom-5 right-5">
        <button
            class="closeBtn px-5 py-2 bg-green-400 hover:bg-green-500 text-white font-mono font-semibold outline-none border-none rounded capitalize">No</button>
        <button id="conformBtn"
            class="px-5 py-2 bg-red-400 hover:bg-red-500 text-white font-mono font-semibold outline-none border-none rounded capitalize">yes</button>
    </div>
</div>

<script>
    $(document).ready(function() {

        function showSuccessMessage(title = "Success", text) {
            $('#msgTitle').text(title);
            $('#successText').text(text);
            $('#successMessage').show();
        }

        $('.closeBtn').on('click', function() {
            $('#conformModal').hide();
        });

        $('#conformBtn').on('click', function() {
            var url = $(this).attr('data-url');
            // console.log(url, 'url')
            $.ajax({
                type: "DELETE",
                url: url,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                dataType: "json",
                contentType: false,
                cache: false,
                processData: false,
                success: function(res) {
                    if (res.permissions) {
                        $('#tbody').html(res.permissions);
                        $('#conformModal').hide();
                        showSuccessMessage('Permission deleted successfully');
                    } else if (res.roles) {
                        $('#tbody').html(res.roles);
                        $('#conformModal').hide();
                        showSuccessMessage('Role deleted successfully');
                    } else {
                        $('#conformModal').hide();
                        showSuccessMessage('Deleted successfully');
                    }
                },
                error: function(xhr, status, error) {
                    console.log(xhr.responseText);
                }
            })

        })
    })
</script>
