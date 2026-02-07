<?php
// db.php - PDO connection and ensure database + table exist
// Update credentials below if needed for your environment
$DB_HOST = '127.0.0.1';
$DB_USER = 'root';
$DB_PASS = '';
$DB_NAME = 'feedback_stepup_v2';
$DB_CHARSET = 'utf8mb4';

try {
    // Connect without database to create it if missing
    $dsn = "mysql:host=$DB_HOST;charset=$DB_CHARSET";
    $opts = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ];

    $pdo = new PDO($dsn, $DB_USER, $DB_PASS, $opts);

    // Create database if not exists with proper character set and collation
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `$DB_NAME` 
                CHARACTER SET $DB_CHARSET 
                COLLATE ${DB_CHARSET}_unicode_ci");

    // Switch to the database
    $pdo->exec("USE `$DB_NAME`");

    // Create table if not exists
    $createTableSql = <<<SQL
CREATE TABLE IF NOT EXISTS `feedback` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `acara` VARCHAR(64) NOT NULL,
    `manfaat` VARCHAR(64) NOT NULL,
    `pendapat_panitia` TEXT NOT NULL,
    `kesan` TEXT NOT NULL,
    `kritik` TEXT NOT NULL,
    `rating` TINYINT UNSIGNED NOT NULL,
    `ip_address` VARCHAR(45) DEFAULT NULL,
    `user_agent` TEXT DEFAULT NULL,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX `idx_created_at` (`created_at`),
    INDEX `idx_rating` (`rating`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SQL;

    $pdo->exec($createTableSql);

    // Check if table exists and has data
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM feedback");
    $result = $stmt->fetch();
    // You can log this if needed

} catch (PDOException $e) {
    // Log error to file for debugging
    error_log('Database Error: ' . $e->getMessage());
    
    // Return JSON error for AJAX requests
    if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && 
        strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['success' => false, 'message' => 'Database connection error']);
        exit;
    } else {
        // For regular page loads, show generic error
        die('Database connection error. Please check configuration.');
    }
}

// Export $pdo for include usage
return $pdo;
?>