<?php 
include('db_connect.php');
session_start();
if(isset($_GET['id'])){
$user = $conn->query("SELECT * FROM users where id =".$_GET['id']);
foreach($user->fetch_array() as $k =>$v){
	$meta[$k] = $v;
}
}
?>
<div class="container-fluid">
	<div id="msg"></div>
	
	<form action="" id="manage-user">	
		<input type="hidden" name="id" value="<?php echo isset($meta['id']) ? $meta['id']: '' ?>">
		<div class="form-group">
			<label for="name">Name</label>
			<input type="text" name="name" id="name" class="form-control" value="<?php echo isset($meta['name']) ? $meta['name']: '' ?>" required>
		</div>
		<div class="form-group">
			<label for="username">Username</label>
			<input type="text" name="username" id="username" class="form-control" value="<?php echo isset($meta['username']) ? $meta['username']: '' ?>" required  autocomplete="off">
		</div>
		<div class="form-group">
			<label for="password">Password</label>
			<input type="password" name="password" id="password" class="form-control" value="" autocomplete="off">
			<?php if(isset($meta['id'])): ?>
			<small><i>Leave this blank if you dont want to change the password.</i></small>
		<?php endif; ?>
		</div>
		<?php if(!isset($_GET['mtype'])): ?>
		<div class="form-group">
			<label for="type">User Type</label>
			<select name="type" id="type" class="custom-select">
				<option value="2" <?php echo isset($meta['type']) && $meta['type'] == 2 ? 'selected': '' ?>>Artist</option>
				<option value="1" <?php echo isset($meta['type']) && $meta['type'] == 1 ? 'selected': '' ?>>Admin</option>
			</select>
		</div>
		<?php endif; ?>
		

	</form>
</div>
<script>
    $('#manage-user').submit(function(e) {
        e.preventDefault();

        // Retrieve form data
        var id = $('input[name="id"]').val();
        var name = $('#name').val().trim();
        var username = $('#username').val().trim();
        var password = $('#password').val().trim();
        var type = $('#type').val();

        // Validate name
        if (name === '') {
            $('#msg').html('<div class="alert alert-danger">Please enter your name.</div>');
            return false;
        }

        // Validate username
        if (username === '') {
            $('#msg').html('<div class="alert alert-danger">Please enter a username.</div>');
            return false;
        }

        // Validate password if it's not empty
        if (password !== '' && password.length < 6) {
            $('#msg').html('<div class="alert alert-danger">Password must be at least 6 characters long.</div>');
            return false;
        }

        // Prepare data for submission
        var formData = {
            id: id,
            name: name,
            username: username,
            password: password,
            type: type
        };

        // Check if username already exists before submitting the form
        $.ajax({
            url: 'ajax.php?action=check_username',
            method: 'POST',
            data: { username: username, id: id },
            success: function(resp) {
                if (resp == 1) {
                    // Username already exists
                    $('#msg').html('<div class="alert alert-danger">Username already exists. Please choose a different one.</div>');
                    return false;
                } else {
                    // Username is unique, proceed with form submission
                    start_load();
                    $.ajax({
                        url: 'ajax.php?action=save_user',
                        method: 'POST',
                        data: formData,
                        success: function(resp) {
                            if (resp == 1) {
                                alert_toast("Data successfully saved", 'success');
                                setTimeout(function() {
                                    location.reload();
                                }, 1500);
                            } else {
                                $('#msg').html('<div class="alert alert-danger">Error saving data. Please try again.</div>');
                                end_load();
                            }
                        }
                    });
                }
            }
        });
    });
</script>
