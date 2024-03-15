<?php 
include_once 'bootstrap.php';
include_once 'includes/header.php'; 

if (isset($_POST['register'])) {
    
    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];
    $full_name = $_POST['full_name'];

    // Check if username is already taken
    if ($db->isUsernameTaken($username)) {
        setMessageRedirect("Username already exists!", "register.php", false);
    }

    // Check if email is already taken
    if ($db->isEmailTaken($email)) {
        setMessageRedirect("Email already exists!", "register.php", false);
    }

    if ($db->registerUser($username, $password, $email, $full_name, "STUDENT")) {
        setMessageRedirect("User registered successfully!", "register.php", true);
    } else {
        setMessageRedirect("Error registering user!", "register.php", false);
    }
}
?>

<style>
.register-container {
    height: 50vh;
    display: flex;
    justify-content: center;
    align-items: center;
}

.register-form {
    background-color: #fff;
    padding: 40px;
    border-radius: 10px;
    box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
}

.register-form h2 {
    text-align: center;
    margin-bottom: 30px;
}

.form-group {
    margin-bottom: 20px;
}

.btn-primary {
    width: 100%;
}
</style>

<div class="container mb-5">
    <div class="justify-content-center row">
        <div class="col-md-6">
            <div class="register-form">
                <h2>Register</h2>
                <form method="POST" action="">
                    <div class="form-group">
                        <label for="username">Username:</label>
                        <input type="text" class="form-control" id="username" name="username" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Password:</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <div class="form-group">
                        <label for="full_name">Full Name:</label><br>
                        <input type="text" class="form-control" id="full_name" name="full_name" required>
                    </div>
                    <button type="submit" name="register" class="btn btn-primary">Register</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include_once 'includes/footer.php'; ?>