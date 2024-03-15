<?php

// Database Configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'quiz');

// Error Reporting and Logging
error_reporting(E_ALL);
ini_set('display_errors', 'On');
ini_set('log_errors', 'On');
ini_set('error_log', __DIR__ . '/logs/error.log');

// Timezone Configuration
date_default_timezone_set('UTC');

// Other Configurations
define('SITE_NAME', 'Quiz App');

// Paths
define('BASE_URL', 'http://quiz.test:3000');
define('ADMIN_URL', BASE_URL . '/admin');
define('CSS_PATH', BASE_URL . '/css/');
define('JS_PATH', BASE_URL . '/js/');
define('IMAGE_PATH', BASE_URL . '/images/');

// Pagination
define('ITEMS_PER_PAGE', 10);

// Session Configuration
ini_set('session.gc_maxlifetime', 3600);
ini_set('session.cookie_lifetime', 3600);
ini_set('session.use_only_cookies', 1);

// Security
define('CSRF_TOKEN_NAME', 'csrf_token');
?>