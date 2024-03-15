<?php
include_once dirname(__DIR__) . '/bootstrap.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo SITE_NAME; ?></title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
        integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- Custom CSS -->
    <link href="css/styles.css" rel="stylesheet">
</head>

<body class="bg-light">
    <header>
        <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
            <div class="container">
                <a class="navbar-brand" href="<?=BASE_URL?>">
                    <img src="https://static.vecteezy.com/system/resources/previews/016/062/449/original/quiz-logo-icon-symbol-cartoon-yellow-bubble-speech-vector.jpg"
                        width="30" height="30" class="d-inline-block align-top" alt="">
                    <?php echo SITE_NAME ?>
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
                    aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="index.php">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="quiz.php">Quiz</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="about.php">About</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="contact.php">Contact</a>
                        </li>
                        <?php
                        if(isset($_SESSION['username'])) {
                            echo '<li class="nav-item dropdown">';
                            echo '<a class="nav-link dropdown-toggle text-white" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">' . $_SESSION['username'] . '</a>';
                            echo '<div class="dropdown-menu" aria-labelledby="navbarDropdown">';
                            echo '<a class="dropdown-item" href="profile.php">Profile</a>';
                            echo '<a class="dropdown-item" href="change_password.php">Change Password</a>';
                            echo '<a class="dropdown-item" href="scores.php">Scores</a>';
                            echo '<div class="dropdown-divider"></div>';
                            echo '<a class="dropdown-item text-danger" href="logout.php">Logout</a>';
                            echo '</div>';
                            echo '</li>';
                        } else {
                            echo '<li class="nav-item">';
                            echo '<a class="nav-link" href="login.php">Login</a>';
                            echo '</li>';
                            echo '<li class="nav-item">';
                            echo '<a class="nav-link" href="register.php">Register</a>';
                            echo '</li>';
                        }
                        ?>
                    </ul>
                </div>
            </div>
        </nav>
        <style>
        .nav-link {
            color: white !important;
        }
        </style>
    </header>
    <main role="main" class="container mt-5">
        <!-- Display alert -->
        <?php displayAlert(); ?>