<?php
// Include the database connection and necessary functions
include_once dirname(__DIR__). '/bootstrap.php';

// Fetch user's current profile information
$user_id = $_SESSION['user_id'];
$user = $db->getSingleRow("SELECT * FROM users WHERE id = $user_id");

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $username = $_POST['username'];
    $email = $_POST['email'];
    $full_name = $_POST['full_name'];

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
        adminMessageRedirect("Profile updated Successfully.", "profile.php", true);
    } else {
        // Handle update failure
        adminMessageRedirect("Failed to update profile. Please try again.", "profile.php", false);
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
                    <h1 class="m-0">Profile Edit</h1>
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
                    <!-- Profile Edit Form -->
                    <div class="card">
                        <div class="card-body">
                            <form action="" method="post">
                                <div class="form-group">
                                    <label for="username">Username:</label>
                                    <input type="text" class="form-control" id="username" name="username"
                                        value="<?= $user['username'] ?>" required minlength="3" maxlength="20">
                                </div>
                                <div class="form-group">
                                    <label for="email">Email:</label>
                                    <input type="email" class="form-control" id="email" name="email"
                                        value="<?= $user['email'] ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="full_name">Full Name:</label>
                                    <input type="text" class="form-control" id="full_name" name="full_name"
                                        value="<?= $user['full_name'] ?>" required minlength="2" maxlength="50">
                                </div>
                                <!-- Add more fields as needed -->
                                <button type="submit" class="btn btn-primary">Save Changes</button>
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
<?php include __DIR__ . "/include/footer.php";  ?>