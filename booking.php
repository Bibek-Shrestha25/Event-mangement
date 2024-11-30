<div class="container-fluid">
	<form action="" id="manage-book">
		<input type="hidden" name="id" value="<?php echo isset($id) ? $id :'' ?>">
		<input type="hidden" name="venue_id" value="<?php echo isset($_GET['venue_id']) ? $_GET['venue_id'] :'' ?>">
		<div class="form-group">
			<label for="" class="control-label">Full Name</label>
			<input type="text" class="form-control" name="name"  value="<?php echo isset($name) ? $name :'' ?>" required>
		</div>
		<div class="form-group">
			<label for="" class="control-label">Address</label>
			<textarea cols="30" rows = "2" required="" name="address" class="form-control"><?php echo isset($address) ? $address :'' ?></textarea>
		</div>
		<div class="form-group">
			<label for="" class="control-label">Email</label>
			<input type="email" class="form-control" name="email"  value="<?php echo isset($email) ? $email :'' ?>" required>
		</div>
		<div class="form-group">
			<label for="" class="control-label">Contact #</label>
			<input type="text" class="form-control" name="contact"  value="<?php echo isset($contact) ? $contact :'' ?>" required>
		</div>
		<div class="form-group">
			<label for="" class="control-label">Duration</label>
			<input type="text" class="form-control" name="duration"  value="<?php echo isset($duration) ? $duration :'' ?>" required>
		</div>
		<div class="form-group">
			<label for="" class="control-label">Desired Event Schedule</label>
			<input type="text" class="form-control datetimepicker" name="schedule"  value="<?php echo isset($schedule) ? $schedule :'' ?>" required>
		</div>
	</form>
</div>


	
<script>
	$('.datetimepicker').datetimepicker({
	      format:'Y/m/d H:i',
	      startDate: '+3d'
	  })
    function validateEmail(email) {
        // Regular expression for strong email validation (RFC 5321 and RFC 5322 compliant)
        const re = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
        return re.test(email);
    }

    function containsLettersAndNumbers(str) {
        return /[a-zA-Z]/.test(str) && /[0-9]/.test(str);
    }

    $('#manage-book').submit(function(e) {
        e.preventDefault();
        var name = $('input[name="name"]').val().trim();
        var address = $('textarea[name="address"]').val().trim();
        var email = $('input[name="email"]').val().trim();
        var contact = $('input[name="contact"]').val().trim();
        var duration = $('input[name="duration"]').val().trim();
        var schedule = $('input[name="schedule"]').val().trim();

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

        if (duration === '') {
            alert("Please enter the duration.");
            return false;
        } else if (!containsLettersAndNumbers(duration)) {
            alert("Duration should contain both letters and numbers.");
            return false;
        }

        if (schedule === '') {
            alert("Please select the desired event schedule.");
            return false;
        }

        // If all fields are valid, proceed with form submission
        start_load();
        $('#msg').html('');
        $.ajax({
            url: 'admin/ajax.php?action=save_book',
            data: new FormData($(this)[0]),
            cache: false,
            contentType: false,
            processData: false,
            method: 'POST',
            type: 'POST',
            success: function(resp) {
                if (resp == 1) {
                    alert_toast("Book Request Sent.", 'success')
                    end_load()
                    uni_modal("", "book_msg.php")
                }
            }
        });
    });
</script>
