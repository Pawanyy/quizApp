<?php
// Include configuration file
require_once 'includes/config.php';

// Start session
session_start();

// Autoload classes
spl_autoload_register(function ($class) {
    include 'classes/' . $class . '.php';
});

// Include database configuration
require_once 'includes/database.php';

// Create Database instance
$db = new Database();

// Function to display Bootstrap alert
function displayAlert() {
    if (isset($_SESSION['alert'])) {
        // Get alert details from session
        $alert = $_SESSION['alert'];

        // Display alert HTML
        echo "<div class='alert alert-{$alert['type']} alert-dismissible fade show' role='alert'>
                  {$alert['message']}
                  <button type='button' class='close' data-bs-dismiss='alert' aria-label='Close'>
                    <span aria-hidden='true'>&times;</span>
                  </button>
              </div>";

        // Clear the session variable after displaying the alert
        unset($_SESSION['alert']);
    }
}

// Function to reload the current page with optional location
function reloadPage($location = '') {
    // If a location is provided, append it to the base URL
    $url = defined('BASE_URL') && $location !== '' ? rtrim(BASE_URL, '/') . '/' . ltrim($location, '/') : $_SERVER['PHP_SELF'];
    header("Location: $url");
    exit;
}

// Function to set alert message
function setAlert($type, $message, $location = '') {
    // Set alert details in session
    $_SESSION['alert'] = compact('type', 'message');
    if ($location) {
        // Reload the page if specified
        header("Location: ${BASE_URL}/{$location}");
        exit;
    }
}

// Function to set success message
function setMessageRedirect($message, $location, $success = true) {
    // Call setAlert function with success type
    setAlert($success ? 'success' : 'danger', $message, $location);
}

function adminMessageRedirect($message, $location, $success = true) {

    // Unset error message if it exists
    if($success){
        // unset($_SESSION['errmsg']);
        $_SESSION['sucmsg'] = $message;

    } else {
        // unset($_SESSION['sucmsg']);
        $_SESSION['errmsg'] = $message;
    }

    // Redirect to admin-users.php
    header("Location: ${BASE_URL}/admin/{$location}");
    exit;
}

$siteSettings = $db->getSingleRow("SELECT * FROM siteSettings WHERE id = 1");
?>