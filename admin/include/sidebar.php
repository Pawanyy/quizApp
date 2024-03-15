<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="#" class="brand-link">
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
                    <a href="dashboard.php" class="nav-link">
                        <i class="nav-icon fas fa-home"></i>
                        <p>Home</p>
                    </a>
                </li>
                <!-- Add Change Password Link -->
                <li class="nav-item">
                    <a href="change_password.php" class="nav-link">
                        <i class="nav-icon fas fa-key"></i>
                        <p>Change Password</p>
                    </a>
                </li>
                <!-- Add Profile Page Link -->
                <li class="nav-item">
                    <a href="profile.php" class="nav-link">
                        <i class="nav-icon fas fa-user"></i>
                        <p>Profile</p>
                    </a>
                </li>
                <?php if($_SESSION['type'] !== 'TEACHER'): ?>
                <li class="nav-item">
                    <a href="users.php" class="nav-link">
                        <i class="nav-icon fas fa-users"></i>
                        <p>Users</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="teachers.php" class="nav-link">
                        <i class="nav-icon fas fa-chalkboard-teacher"></i>
                        <p>Teachers</p>
                    </a>
                </li>
                <?php endif; ?>
                <li class="nav-item">
                    <a href="quizzes.php" class="nav-link">
                        <i class="nav-icon fas fa-book-open"></i>
                        <p>Quizzes</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="difficulty_levels.php" class="nav-link">
                        <i class="nav-icon fas fa-tasks"></i>
                        <p>Difficulty Levels</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="categories.php" class="nav-link">
                        <i class="nav-icon fas fa-list"></i>
                        <p>Categories</p>
                    </a>
                </li>
                <?php if($_SESSION['type'] !== 'TEACHER'): ?>
                <li class="nav-item">
                    <a href="colleges.php" class="nav-link">
                        <i class="nav-icon fas fa-university"></i>
                        <p>Colleges</p>
                    </a>
                </li>
                <?php endif; ?>
                <li class="nav-item">
                    <a href="quiz_types.php" class="nav-link">
                        <i class="nav-icon fas fa-puzzle-piece"></i>
                        <p>Quiz Types</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="questions.php" class="nav-link">
                        <i class="nav-icon fas fa-question"></i>
                        <p>Questions</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="scores.php" class="nav-link">
                        <i class="nav-icon fas fa-chart-bar"></i>
                        <p>Scores</p>
                    </a>
                </li>
                <?php if($_SESSION['type'] !== 'TEACHER'): ?>
                <li class="nav-item">
                    <a href="contacts.php" class="nav-link">
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