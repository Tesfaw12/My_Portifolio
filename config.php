<?php
// Database configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'portfolio_db');

// Create database connection
function getDBConnection() {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    
    if ($conn->connect_error) {
        throw new Exception('DB connection failed: ' . $conn->connect_error);
    }
    
    return $conn;
}

// Email configuration
define('ADMIN_EMAIL', 'tesfawamare19125@gmail.com');
define('GMAIL_APP_PASSWORD', 'kifl zdua dzaj mmns'); // Replace with your Gmail App Password
define('SITE_NAME', 'Tesfaw Amare Portfolio');

// Timezone
date_default_timezone_set('UTC');

// Error reporting (disable in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>
