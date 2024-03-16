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
                    <img src="data:image/jpeg;base64,<?= base64_encode($siteSettings['SiteLogo']) ?>" width="45"
                        height="45" class="d-inline-block align-top" alt="">
                    <?php echo SITE_NAME ?>
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
                    aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="<?=BASE_URL?>/index.php">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?=BASE_URL?>/quiz.php">Quiz</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?=BASE_URL?>/about.php">About</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?=BASE_URL?>/contact.php">Contact</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?=BASE_URL?>/colleges_list.php">Colleges</a>
                        </li>
                        <?php
                        if(isset($_SESSION['username'])) {
                        ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle text-white" href="#" id="navbarDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <?=$_SESSION['username']?></a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="<?=BASE_URL?>/profile.php">Profile</a>
                                <a class="dropdown-item" href="<?=BASE_URL?>/change_password.php">Change Password</a>
                                <a class="dropdown-item" href="<?=BASE_URL?>/scores.php">Scores</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item text-danger" href="<?=BASE_URL?>/logout.php">Logout</a>
                            </div>
                        </li>
                        <?php
                        } else {
                            ?>
                        <li class="nav-item">
                            <a class="btn btn-outline-light mr-2 ml-2" href="<?=BASE_URL?>/login.php">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="btn btn-outline-light" href="<?=BASE_URL?>/register.php">Register</a>
                        </li>
                        <?php
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