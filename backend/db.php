<?php
// Database configuration
$servername = "localhost";
$username = "root";
$password = "";
$database = "flutter_app";

// Set charset to prevent encoding issues
$charset = 'utf8mb4';

// Create DSN (Data Source Name)
$dsn = "mysql:host=$servername;dbname=$database;charset=$charset";

// PDO options for better security and error handling
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    // Create PDO connection (more secure than mysqli)
    $pdo = new PDO($dsn, $username, $password, $options);
    
    // Create table if it doesn't exist
    $createTable = "
        CREATE TABLE IF NOT EXISTS submissions (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(100) NOT NULL,
            email VARCHAR(150) NOT NULL,
            phone VARCHAR(20) NOT NULL,
            gender ENUM('Male', 'Female', 'Other') NOT NULL,
            dob DATE NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ";
    
    $pdo->exec($createTable);
    
} catch (PDOException $e) {
    // Log error instead of displaying it
    error_log("Database connection failed: " . $e->getMessage());
    
    // Return generic error to client
    http_response_code(500);
    echo json_encode([
        "status" => "error",
        "message" => "Database connection failed"
    ]);
    exit;
}
?>
