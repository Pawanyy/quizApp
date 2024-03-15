<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="<?=ADMIN_URL?>" class="brand-link">
        <span class="brand-text font-weight-light">Admin Panel</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- User Info -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="https://adminlte.io/themes/v3/dist/img/AdminLTELogo.png" class="img-circle elevation-2"
                    alt="User Image">
            </div>
            <div class="info">
                <a href="#" class="d-block">Welcome, <?php echo $_SESSION['username']; ?></a>
                <a href="#" class="d-block"><?php echo $_SESSION['type']; ?></a>
            </div>
        </div>
        <!-- End User Info -->
        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <li class="nav-item">
                    <a href="<?=ADMIN_URL?>/dashboard.php" class="nav-link">
                        <i class="nav-icon fas fa-home"></i>
                        <p>Home</p>
                    </a>
                </li>
                <!-- Add Change Password Link -->
                <li class="nav-item">
                    <a href="<?=ADMIN_URL?>/change_password.php" class="nav-link">
                        <i class="nav-icon fas fa-key"></i>
                        <p>Change Password</p>
                    </a>
                </li>
                <!-- Add Profile Page Link -->
                <li class="nav-item">
                    <a href="<?=ADMIN_URL?>/profile.php" class="nav-link">
                        <i class="nav-icon fas fa-user"></i>
                        <p>Profile</p>
                    </a>
                </li>
                <?php if($_SESSION['type'] !== 'TEACHER'): ?>
                <li class="nav-item">
                    <a href="<?=ADMIN_URL?>/users.php" class="nav-link">
                        <i class="nav-icon fas fa-users"></i>
                        <p>Users</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?=ADMIN_URL?>/teachers.php" class="nav-link">
                        <i class="nav-icon fas fa-chalkboard-teacher"></i>
                        <p>Teachers</p>
                    </a>
                </li>
                <?php endif; ?>
                <li class="nav-item">
                    <a href="<?=ADMIN_URL?>/quizzes.php" class="nav-link">
                        <i class="nav-icon fas fa-book-open"></i>
                        <p>Quizzes</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?=ADMIN_URL?>/difficulty_levels.php" class="nav-link">
                        <i class="nav-icon fas fa-tasks"></i>
                        <p>Difficulty Levels</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?=ADMIN_URL?>/categories.php" class="nav-link">
                        <i class="nav-icon fas fa-list"></i>
                        <p>Categories</p>
                    </a>
                </li>
                <?php if($_SESSION['type'] !== 'TEACHER'): ?>
                <li class="nav-item">
                    <a href="<?=ADMIN_URL?>/colleges.php" class="nav-link">
                        <i class="nav-icon fas fa-university"></i>
                        <p>Colleges</p>
                    </a>
                </li>
                <?php endif; ?>
                <li class="nav-item">
                    <a href="<?=ADMIN_URL?>/quiz_types.php" class="nav-link">
                        <i class="nav-icon fas fa-puzzle-piece"></i>
                        <p>Quiz Types</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?=ADMIN_URL?>/questions.php" class="nav-link">
                        <i class="nav-icon fas fa-question"></i>
                        <p>Questions</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?=ADMIN_URL?>/scores.php" class="nav-link">
                        <i class="nav-icon fas fa-chart-bar"></i>
                        <p>Scores</p>
                    </a>
                </li>
                <?php if($_SESSION['type'] !== 'TEACHER'): ?>
                <li class="nav-item">
                    <a href="<?=ADMIN_URL?>/contacts.php" class="nav-link">
                        <i class="nav-icon fas fa-address-book"></i>
                        <p>Contacts</p>
                    </a>
                </li>
                <?php endif; ?>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
<script>
// Get the current URL path
var path = window.location.pathname;

// Get all navigation links
var navLinks = document.querySelectorAll('.nav-sidebar .nav-link');

// Loop through each link and check if its href contains the current path
navLinks.forEach(function(link) {
    console.log(link.getAttribute('href'), path)
    if (link.getAttribute('href').includes(path)) {
        // Add the 'active' class to the link if its href contains the current path
        link.classList.add('active');
    }
});
</script>