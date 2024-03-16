<?php
// Include the necessary files and start the session
include_once dirname(__DIR__). '/bootstrap.php';

// Fetch user's current profile information
$user_id = $_SESSION['user_id'];
$user = $db->getSingleRow("SELECT * FROM users WHERE id = $user_id");

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $old_password = $_POST['old_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Validate old password
    if (!password_verify($old_password, $user['password'])) {
        adminMessageRedirect("Old password is incorrect. Please try again.", "change_password.php", false);
    }

    // Validate new password and confirm password
    if ($new_password !== $confirm_password) {
        adminMessageRedirect("New password and confirm password do not match. Please try again.", "change_password.php", false);
    }

    // Update user's password in the database
    $update_result = $db->update("users", [
        'password' => password_hash($new_password, PASSWORD_DEFAULT)
    ], "id = $user_id");

    if ($update_result) {
        adminMessageRedirect("Password updated successfully.", "change_password.php", true);
    } else {
        adminMessageRedirect("Failed to update password. Please try again.", "change_password.php", false);
    }
}
?>
<?php include __DIR__ . "/include/header.php";  ?>
<?php include __DIR__ . "/include/navbar.php";  ?>
<?php include __DIR__ . "/include/sidebar.php";  ?>
<!-- Main content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Change Password</h1>
                </div>
            </div>
        </div>
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-6">
                    <!-- Change Password Form -->
                    <div class="card">
                        <div class="card-body">
                            <form id="changePasswordForm" action="" method="post">
                                <div class="form-group">
                                    <label for="old_password">Old Password:</label>
                                    <input type="password" class="form-control" id="old_password" name="old_password"
                                        required minlength="4" maxlength="16">
                                </div>
                                <div class="form-group">
                                    <label for="new_password">New Password:</label>
                                    <input type="password" class="form-control" id="new_password" name="new_password"
                                        required minlength="4" maxlength="16">
                                </div>
                                <div class="form-group">
                                    <label for="confirm_password">Confirm Password:</label>
                                    <input type="password" class="form-control" id="confirm_password"
                                        name="confirm_password" required minlength="4" maxlength="16">
                                </div>
                                <!-- Add more fields as needed -->
                                <button type="submit" class="btn btn-primary">Change Password</button>
                            </form>
                        </div>
                    </div>
                    <!-- /.card -->
                </div>
            </div>
        </div>
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<script>
document.getElementById('changePasswordForm').addEventListener('submit', function(event) {
    var newPassword = document.getElementById('new_password').value;
    var confirmPassword = document.getElementById('confirm_password').value;
    var oldPassword = document.getElementById('old_password').value;

    if (newPassword !== confirmPassword) {
        alert("New password and confirm password do not match.");
        event.preventDefault();
    }

    if (newPassword === oldPassword) {
        alert("New password should be different from the old password.");
        event.preventDefault();
    }
});
</script>
<?php include __DIR__ . "/include/footer.php";  ?>