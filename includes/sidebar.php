<!-- Sidebar -->
<div class="col-md-3">
    <div class="card shadow-sm h-100">
        <div class="card-header">
            <h5 class="card-title mb-0 text-center">Menu</h5>
        </div>
        <div class="card-body">
            <ul class="nav flex-column sidebar-sticky">
                <li class="nav-item">
                    <a class="nav-link btn btn-light" href="<?=BASE_URL?>/profile.php">Profile</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link btn btn-light" href="<?=BASE_URL?>/change_password.php">Change Password</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link btn btn-light" href="<?=BASE_URL?>/scores.php">Scores</a>
                </li>
                <li class="nav-item">
                    <a class="w-100 btn btn-outline-danger" href="<?=BASE_URL?>/logout.php">Logout</a>
                </li>
            </ul>
        </div>
    </div>
</div>
<style>
/* Custom CSS for sidebar */
.sidebar {
    /* Height of navbar */
    box-shadow: inset -1px 0 0 rgba(0, 0, 0, .1);
}

.sidebar-sticky {
    /* Height of navbar */
    padding-top: .3rem;
    overflow-x: hidden;
    overflow-y: auto;
    /* Scrollable contents if viewport is shorter than content. */
}

.sidebar-sticky .nav-item {
    margin-bottom: 10px;
}

.sidebar-sticky .nav-link {
    font-weight: 500;
    color: #333 !important;
}

.sidebar-sticky .nav-link:hover {
    color: #007bff !important;
}

.sidebar-sticky .nav-link.active {
    color: #fff !important;
    background-color: #0056b3 !important;
}

.sidebar-sticky .nav-link.active:hover {
    background-color: #007bff !important;
}
</style>
<script>
// Get the current URL path
var path = window.location.pathname;

// Get all navigation links
var navLinks = document.querySelectorAll('.sidebar-sticky .nav-link');

// Loop through each link and check if its href contains the current path
navLinks.forEach(function(link) {
    console.log(link.getAttribute('href'), path)
    if (link.getAttribute('href').includes(path)) {
        // Add the 'active' class to the link if its href contains the current path
        link.classList.add('active');
    }
});
</script>