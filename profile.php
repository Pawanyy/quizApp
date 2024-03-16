<?php 
include_once 'bootstrap.php';

if(!isset($_SESSION['user_id']) || (isset($_SESSION['user_id']) && $_SESSION['type'] != "STUDENT")){
    setMessageRedirect("Pls Login First!", "login.php", false);
}

$table = "users";
// Fetch user's current profile information
$user_id = $_SESSION['user_id'];
$user = $db->getSingleRow("SELECT * FROM $table WHERE id = $user_id");

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $username = $_POST['username'];
    $email = $_POST['email'];
    $full_name = $_POST['full_name'];

    // Check if username exists
    $existing_username = $db->getSingleRow("SELECT id FROM $table WHERE username = '{$_POST['username']}'");
    if ($existing_username && $existing_username['id'] != $user_id) {
        // Handle error - Username already exists
        setMessageRedirect("Username already exists.", "profile.php", false);
        exit;
    }

    // Check if email exists
    $existing_email = $db->getSingleRow("SELECT id FROM $table WHERE email = '{$_POST['email']}'");
    if ($existing_email && $existing_email['id'] != $user_id) {
        // Handle error - Email already exists
        setMessageRedirect("Email already exists.", "profile.php", false);
        exit;
    }

    // Update user's profile information in the database
    $update_result = $db->update("users", [
        'username' => $username,
        'email' => $email,
        'full_name' => $full_name
    ], "id = $user_id");

    if ($update_result) {
        // Update session with new user information if needed
        $_SESSION['username'] = $username;
        $_SESSION['email'] = $email;
        $_SESSION['full_name'] = $full_name;

        // Redirect to the profile page or any other page
        setMessageRedirect("Profile updated Successfully.", "profile.php", true);
    } else {
        // Handle update failure
        setMessageRedirect("Failed to update profile. Please try again.", "profile.php", false);
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
                        <h2 class="card-title">Update Profile</h2>
                        <form action="profile.php" method="post">
                            <div class="form-group">
                                <label for="username">Username:</label>
                                <input type="text" class="form-control" id="username" name="username"
                                    value="<?=$user['username']?>" required minlength="3" maxlength="20">
                            </div>
                            <div class="form-group">
                                <label for="email">Email:</label>
                                <input type="email" class="form-control" id="email" name="email"
                                    value="<?=$user['email']?>" required>
                            </div>
                            <div class="form-group">
                                <label for="full_name">Full Name:</label>
                                <input type="text" class="form-control" id="full_name" name="full_name"
                                    value="<?=$user['full_name']?>" required minlength="2" maxlength="50">
                            </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Update</button>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
</div>
<?php include_once 'includes/footer.php'; ?>