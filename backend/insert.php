<?php
require_once 'db.php';

// Set proper headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json; charset=UTF-8");

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode([
        "status" => "error",
        "message" => "Method not allowed"
    ]);
    exit;
}

try {
    // Get and decode JSON input
    $input = file_get_contents("php://input");
    $data = json_decode($input, true);
    
    // Check if JSON is valid
    if (json_last_error() !== JSON_ERROR_NONE) {
        http_response_code(400);
        echo json_encode([
            "status" => "error",
            "message" => "Invalid JSON format"
        ]);
        exit;
    }
    
    // Validate required fields
    $required_fields = ['name', 'email', 'phone', 'gender', 'dob'];
    $missing_fields = [];
    
    foreach ($required_fields as $field) {
        if (!isset($data[$field]) || empty(trim($data[$field]))) {
            $missing_fields[] = $field;
        }
    }
    
    if (!empty($missing_fields)) {
        http_response_code(400);
        echo json_encode([
            "status" => "error",
            "message" => "Missing required fields: " . implode(', ', $missing_fields)
        ]);
        exit;
    }
    
    // Sanitize and validate input data
    $name = trim($data['name']);
    $email = trim($data['email']);
    $phone = trim($data['phone']);
    $gender = trim($data['gender']);
    $dob = trim($data['dob']);
    
    // Validate name (2-100 characters, letters and spaces only)
    if (strlen($name) < 2 || strlen($name) > 100 || !preg_match("/^[a-zA-Z\s]+$/", $name)) {
        http_response_code(400);
        echo json_encode([
            "status" => "error",
            "message" => "Invalid name format"
        ]);
        exit;
    }
    
    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($email) > 150) {
        http_response_code(400);
        echo json_encode([
            "status" => "error",
            "message" => "Invalid email format"
        ]);
        exit;
    }
    
    // Validate phone (10-20 digits)
    if (!preg_match("/^[0-9+\-\s()]{10,20}$/", $phone)) {
        http_response_code(400);
        echo json_encode([
            "status" => "error",
            "message" => "Invalid phone number format"
        ]);
        exit;
    }
    
    // Validate gender
    if (!in_array($gender, ['Male', 'Female', 'Other'])) {
        http_response_code(400);
        echo json_encode([
            "status" => "error",
            "message" => "Invalid gender value"
        ]);
        exit;
    }
    
    // Validate date of birth
    $dob_date = DateTime::createFromFormat('Y-m-d', $dob);
    if (!$dob_date || $dob_date->format('Y-m-d') !== $dob) {
        http_response_code(400);
        echo json_encode([
            "status" => "error",
            "message" => "Invalid date format (YYYY-MM-DD required)"
        ]);
        exit;
    }
    
    // Check if date is not in the future
    if ($dob_date > new DateTime()) {
        http_response_code(400);
        echo json_encode([
            "status" => "error",
            "message" => "Date of birth cannot be in the future"
        ]);
        exit;
    }
    
    // Check if email already exists
    $checkEmail = "SELECT id FROM submissions WHERE email = ?";
    $stmt = $pdo->prepare($checkEmail);
    $stmt->execute([$email]);
    
    if ($stmt->rowCount() > 0) {
        http_response_code(409);
        echo json_encode([
            "status" => "error",
            "message" => "Email already exists"
        ]);
        exit;
    }
    
    // Insert data using prepared statement
    $sql = "INSERT INTO submissions (name, email, phone, gender, dob) VALUES (?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $result = $stmt->execute([$name, $email, $phone, $gender, $dob]);
    
    if ($result) {
        $lastId = $pdo->lastInsertId();
        http_response_code(201);
        echo json_encode([
            "status" => "success",
            "message" => "Data inserted successfully",
            "id" => $lastId
        ]);
    } else {
        http_response_code(500);
        echo json_encode([
            "status" => "error",
            "message" => "Failed to insert data"
        ]);
    }
    
} catch (PDOException $e) {
    // Log the actual error
    error_log("Database error: " . $e->getMessage());
    
    // Return generic error to client
    http_response_code(500);
    echo json_encode([
        "status" => "error",
        "message" => "Database operation failed"
    ]);
} catch (Exception $e) {
    // Log the actual error
    error_log("General error: " . $e->getMessage());
    
    // Return generic error to client
    http_response_code(500);
    echo json_encode([
        "status" => "error",
        "message" => "An unexpected error occurred"
    ]);
}
?>
