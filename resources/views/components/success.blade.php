<!-- Custom Alert Box -->
<div id="successMessage" role="alert" style=" display: none; position: fixed; top: 5%; right: 5%; width: 300px; padding: 15px; background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; border-radius: 4px; box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1); z-index: 2050;">
    <strong id="msgTitle" style="font-weight: bold;"></strong>
    <span id="successText"></span>
    <button type="button" id="closeAlertBtn" aria-label="Close" style="background: none; border: none; color: #155724; font-size: 20px; font-weight: bold; position: absolute; top: 10px; right: 10px; cursor: pointer;">&times;</button>
</div>

<script>
    $(document).ready(function() {

        // Close the alert on button click
        $('#closeAlertBtn').click(function() {
            $('#successMessage').hide();
        });

        setTimeout(() => {
            $('#successMessage').hide();
        }, 5000);
    });
</script>
