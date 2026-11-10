<?php
// db.php - MySQL PDO Connection Manager
require_once 'config.php';

/**
 * Establishes and returns a PDO database connection.
 * Uses a static variable to ensure only one connection is made per request.
 * @return PDO
 */
function get_db_connection() {
    static $conn = null;

    if ($conn === null) {
        $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4';
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];
        $conn = new PDO($dsn, DB_USER, DB_PASS, $options);
    }
    return $conn;
}
?>