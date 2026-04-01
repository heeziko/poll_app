<?php
// POLL Application Configuration Template
// Copy this file to config.php and update with your own values.

define('DB_HOST', 'localhost');
define('DB_USER', 'your_db_user');
define('DB_PASS', 'your_db_password');
define('DB_NAME', 'POLL');
define('ADMIN_PASS', 'your_admin_password'); // Minimum 8 characters recommended

/**
 * Get a connection to the database.
 * @return mysqli
 */
function get_db_connection() {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    $conn->set_charset("utf8mb4");
    return $conn;
}
?>
