<?php 
include_once 'bootstrap.php';

if(!isset($_SESSION['user_id']) || (isset($_SESSION['user_id']) && $_SESSION['type'] != "STUDENT")){
    setMessageRedirect("Pls Login First!", "login.php", false);
}

$table = "users";
// Fetch user's current profile information// Fetch user's current profile information
$user_id = $_SESSION['user_id'];
$user = $db->getSingleRow("SELECT * FROM $table WHERE id = $user_id");

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $old_password = $_POST['old_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Validate old password
    if (!password_verify($old_password, $user['password'])) {
        setMessageRedirect("Old password is incorrect. Please try again.", "change_password.php", false);
    }

    // Validate new password and confirm password
    if ($new_password !== $confirm_password) {
        setMessageRedirect("New password and confirm password do not match. Please try again.", "change_password.php", false);
    }

    // Update user's password in the database
    $update_result = $db->update("users", [
        'password' => password_hash($new_password, PASSWORD_DEFAULT)
    ], "id = $user_id");

    if ($update_result) {
        setMessageRedirect("Password updated successfully.", "profile.php", true);
    } else {
        setMessageRedirect("Failed to update password. Please try again.", "change_password.php", false);
    }
}

include_once 'includes/header.php'; 
?>
<div class="container">
    <div class="row">

        <?php include_once 'includes/sidebar.php';  ?>

        <!-- Main Content -->
        <div class="col-md-9">
            <div class="container">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h2 class="card-title">Change Password</h2>
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
            </div>
        </div>
    </div>
</div>

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
<?php include_once 'includes/footer.php'; ?>