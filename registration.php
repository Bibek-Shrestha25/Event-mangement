<div class="container-fluid">
    <form action="" id="manage-register">
        <input type="hidden" name="id" value="<?php echo isset($id) ? $id :'' ?>">
        <input type="hidden" name="event_id" value="<?php echo isset($_GET['event_id']) ? $_GET['event_id'] :'' ?>">
        <div class="form-group">
            <label for="" class="control-label">Full Name</label>
            <input type="text" class="form-control" name="name" value="<?php echo isset($name) ? $name :'' ?>" required>
        </div>
        <div class="form-group">
            <label for="" class="control-label">Address</label>
            <textarea cols="30" rows="2" required="" name="address" class="form-control"><?php echo isset($address) ? $address :'' ?></textarea>
        </div>
        <div class="form-group">
            <label for="" class="control-label">Email</label>
            <input type="email" class="form-control" name="email" value="<?php echo isset($email) ? $email :'' ?>" required>
        </div>
        <div class="form-group">
            <label for="" class="control-label">Contact #</label>
            <input type="text" class="form-control" name="contact" value="<?php echo isset($contact) ? $contact :'' ?>" required>
        </div>
    </form>
</div>

 <script>
	$('.datetimepicker').datetimepicker({
	      format:'Y/m/d H:i',
	      startDate: '+3d'
	  })
    function validateEmail(email) {
        // Regular expression for strong email validation 
        const re = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
        return re.test(email);
    }

    $('#manage-register').submit(function(e) {
        e.preventDefault();
        var name = $('input[name="name"]').val().trim();
        var address = $('textarea[name="address"]').val().trim();
        var email = $('input[name="email"]').val().trim();
        var contact = $('input[name="contact"]').val().trim();

        // Regular expression to check if the full name contains only letters and spaces
        var nameRegex = /^[a-zA-Z\s]+$/;

        if (name === '') {
            alert("Please enter your full name.");
            return false;
        } else if (!nameRegex.test(name)) {
            alert("Full name should contain only letters and spaces.");
            return false;
        }

        if (address === '') {
            alert("Please enter your address.");
            return false;
        } else if (!isNaN(address)) {
            alert("Address should not be only a number.");
            return false;
        }

        if (email === '') {
            alert("Please enter your email address.");
            return false;
        } else if (!validateEmail(email)) {
            alert("Please enter a valid email address.");
            return false;
        }

        if (contact === '') {
            alert("Please enter your contact number.");
            return false;
        } else if (!/^\d{10}$/.test(contact)) {
            alert("Please enter a valid 10-digit contact number.");
            return false;
        }

        // If all fields are valid, proceed with form submission
        start_load();
        $('#msg').html('');
        $.ajax({
            url: 'admin/ajax.php?action=save_register',
            data: new FormData($(this)[0]),
            cache: false,
            contentType: false,
            processData: false,
            method: 'POST',
            type: 'POST',
            success: function(resp) {
                if (resp == 1) {
                    alert_toast("Registration Request Sent.", 'success')
                    end_load()
                    uni_modal("", "register_msg.php")
                }
            }
        });
    });
</script>

