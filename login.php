<?php 
include_once 'bootstrap.php';

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $user = $db->loginUser($username, $password, 'STUDENT');
    if ($user) {

        // Set session variables
        $_SESSION['logged_in'] = true;
        $_SESSION['username'] = $username;
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['type'] = $user['userType'];
        $_SESSION['logged_in'] = $user['email'];

        setMessageRedirect("Login successful!", "quiz.php", true);
    } else {
        setMessageRedirect("Invalid username or password!", "login.php", false);
    }
}

include_once 'includes/header.php';
?>
<style>
.login-container {
    height: 50vh;
    display: flex;
    justify-content: center;
    align-items: center;
}

.login-form {
    background-color: #fff;
    padding: 40px;
    border-radius: 10px;
    box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
}

.login-form h2 {
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
<div class="container">
    <div class="row login-container">
        <div class="col-md-6">
            <div class="login-form">
                <h2>Login</h2>
                <form method="POST" action="">
                    <div class="form-group">
                        <label for="username">Username:</label>
                        <input type="text" class="form-control" id="username" name="username" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Password:</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <button type="submit" name="login" class="btn btn-primary">Login</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include_once 'includes/footer.php'; ?>