<?php include 'db_connect.php' ?>

<?php
if(isset($_GET['id'])){
$booking = $conn->query("SELECT * from venue_booking where id = ".$_GET['id']);
foreach($booking->fetch_array() as $k => $v){
    $$k = $v;
}
}
?>
<div class="container-fluid">
    <form action="" id="manage-book">
        <input type="hidden" name="id" value="<?php echo isset($id) ? $id :'' ?>">
        <div class="form-group">
            <label for="" class="control-label">Venue</label>
            <select name="venue_id" id="" class="custom-select select2">
                <option></option>
                <?php 
                $venue = $conn->query("SELECT * FROM venue order by venue asc");
                while($row=$venue->fetch_assoc()):
                ?>
                <option value="<?php echo $row['id'] ?>" <?php echo isset($venue_id) && $venue_id == $row['id'] ? 'selected' : '' ?>><?php echo ucwords($row['venue']) ?></option>
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
            <label for="" class="control-label">Duration</label>
            <input type="text" class="form-control" name="duration"  value="<?php echo isset($duration) ? $duration :'' ?>" required>
        </div>
        <div class="form-group">
            <label for="" class="control-label">Desired Event Schedule</label>
            <input type="text" class="form-control datetimepicker" name="schedule"  value="<?php echo isset($datetime) ? date("Y-m-d H:i",strtotime($datetime)) :'' ?>" required>
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
        var venue_id = $('select[name="venue_id"]').val().trim();
        var name = $('input[name="name"]').val().trim();
        var address = $('textarea[name="address"]').val().trim();
        var email = $('input[name="email"]').val().trim();
        var contact = $('input[name="contact"]').val().trim();
        var duration = $('input[name="duration"]').val().trim();
        var schedule = $('input[name="schedule"]').val().trim();
        var status = $('select[name="status"]').val().trim();

        // Regular expression to check if the full name contains only letters and spaces
        var nameRegex = /^[a-zA-Z\s]+$/;

        if (venue_id === '') {
            alert("Please select a venue.");
            return false;
        }

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
        $.ajax({
            url: 'ajax.php?action=save_book',
            method: "POST",
            data: $(this).serialize(),
            success: function(resp) {
                if (resp == 1) {
                    alert_toast("Book successfully updated", "success")
                    setTimeout(function() {
                        location.reload()
                    }, 1500)
                }
            }
        })
    })
</script>
