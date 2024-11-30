<?php include 'db_connect.php' ?>

<?php
if(isset($_GET['id'])){
$booking = $conn->query("SELECT * from audience where id = ".$_GET['id']);
foreach($booking->fetch_array() as $k => $v){
    $$k = $v;
}
}
?>
<div class="container-fluid">
    <form action="" id="manage-register">
        <input type="hidden" name="id" value="<?php echo isset($id) ? $id :'' ?>">
        <div class="form-group">
            <label for="" class="control-label">Event</label>
            <select name="event_id" id="" class="custom-select select2">
                <option></option>
                <?php 
                $event = $conn->query("SELECT * FROM events order by event asc");
                while($row=$event->fetch_assoc()):
                ?>
                <option value="<?php echo $row['id'] ?>" <?php echo isset($event_id) && $event_id == $row['id'] ? 'selected' : '' ?>><?php echo ucwords($row['event']) ?></option>
            <?php endwhile; ?>
            </select>
        </div>
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
            <div class="form-check">
              <input class="form-check-input" type="checkbox" value="1" id="payment_status" name="payment_status" <?php echo isset($payment_status) && $payment_status == 1 ? "checked" : '' ?>>
              <label class="form-check-label" for="payment_status">
                Paid
              </label>
            </div>
        </div>
        <div class="form-group">
            <label for="" class="control-label">Status</label>
            <select name="status" id="" class="custom-select">
                <option value="0" <?php echo isset($status) && $status == 0 ? "selected" : '' ?>>For Verification</option>
                <option value="1" <?php echo isset($status) && $status == 1 ? "selected" : '' ?>>Confirmed</option>
                <option value="2" <?php echo isset($status) && $status == 2 ? "selected" : '' ?>>Cancelled</option>
            </select>
        </div>
    </form>
</div>

<script>
    $('#manage-register').submit(function(e) {
        e.preventDefault();

        // Retrieve form data
        var event_id = $('select[name="event_id"]').val().trim();
        var name = $('input[name="name"]').val().trim();
        var address = $('textarea[name="address"]').val().trim();
        var email = $('input[name="email"]').val().trim();
        var contact = $('input[name="contact"]').val().trim();
        var payment_status = $('input[name="payment_status"]').is(':checked');
        var status = $('select[name="status"]').val();

        // Regular expression to validate email format
        var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

        // Regular expression to validate 10-digit contact number
        var contactRegex = /^\d{10}$/;

        // Validate event selection
        if (event_id === '') {
            alert("Please select an event.");
            return false;
        }

        // Validate full name
        if (name === '') {
            alert("Please enter your full name.");
            return false;
        }

        // Validate address
        if (address === '') {
            alert("Please enter your address.");
            return false;
        }

        // Validate email
        if (email === '') {
            alert("Please enter your email address.");
            return false;
        } else if (!emailRegex.test(email)) {
            alert("Please enter a valid email address.");
            return false;
        }

        // Validate contact number
        if (contact === '') {
            alert("Please enter your contact number.");
            return false;
        } else if (!contactRegex.test(contact)) {
            alert("Please enter a valid 10-digit contact number.");
            return false;
        }

        // If all fields are valid, proceed with form submission
        start_load();
        $.ajax({
            url: 'ajax.php?action=save_register',
            method: "POST",
            data: $(this).serialize(),
            success: function(resp) {
                if (resp == 1) {
                    alert_toast("Registration request sent.", "success")
                    setTimeout(function() {
                        location.reload()
                    }, 1500)
                }
            }
        });
    });
</script>
