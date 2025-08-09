<?php
require_once 'db.php';

// Set proper headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json; charset=UTF-8");

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Only allow GET requests
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode([
        "status" => "error",
        "message" => "Method not allowed"
    ]);
    exit;
}

try {
    // Get query parameters for pagination and filtering
    $page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
    $limit = isset($_GET['limit']) ? min(100, max(1, intval($_GET['limit']))) : 10;
    $search = isset($_GET['search']) ? trim($_GET['search']) : '';
    $gender_filter = isset($_GET['gender']) ? trim($_GET['gender']) : '';
    
    $offset = ($page - 1) * $limit;
    
    // Build the base query
    $where_conditions = [];
    $params = [];
    
    // Add search condition
    if (!empty($search)) {
        $where_conditions[] = "(name LIKE ? OR email LIKE ? OR phone LIKE ?)";
        $search_param = "%$search%";
        $params[] = $search_param;
        $params[] = $search_param;
        $params[] = $search_param;
    }
    
    // Add gender filter
    if (!empty($gender_filter) && in_array($gender_filter, ['Male', 'Female', 'Other'])) {
        $where_conditions[] = "gender = ?";
        $params[] = $gender_filter;
    }
    
    // Build WHERE clause
    $where_clause = !empty($where_conditions) ? "WHERE " . implode(" AND ", $where_conditions) : "";
    
    // Get total count for pagination
    $count_sql = "SELECT COUNT(*) as total FROM submissions $where_clause";
    $count_stmt = $pdo->prepare($count_sql);
    $count_stmt->execute($params);
    $total_records = $count_stmt->fetch()['total'];
    
    // Get the actual data
    $sql = "SELECT id, name, email, phone, gender, dob, created_at, updated_at 
            FROM submissions 
            $where_clause 
            ORDER BY created_at DESC 
            LIMIT ? OFFSET ?";
    
    $params[] = $limit;
    $params[] = $offset;
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $data = $stmt->fetchAll();
    
    // Format the response
    $response = [
        "status" => "success",
        "data" => $data,
        "pagination" => [
            "current_page" => $page,
            "per_page" => $limit,
            "total_records" => $total_records,
            "total_pages" => ceil($total_records / $limit),
            "has_next" => $page < ceil($total_records / $limit),
            "has_prev" => $page > 1
        ]
    ];
    
    http_response_code(200);
    echo json_encode($response, JSON_PRETTY_PRINT);
    
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
