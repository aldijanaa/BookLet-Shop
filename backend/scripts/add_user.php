<?php
require_once __DIR__ . '/../rest/services/UserService.class.php';

header('Content-Type: application/json');
$json_str = file_get_contents('php://input');
$payload = json_decode($json_str, true);

// Validate the input fields
if (empty($payload['first_name']) || empty($payload['last_name']) || empty($payload['email']) || empty($payload['password'])) {
    header('HTTP/1.1 400 Bad Request');
    echo json_encode(['error' => 'Required fields are missing. First name, last name, email, and password must be provided.']);
    exit();
}

$user_service = new UserService();

try {
    // FOR NOW: Hashing password for security
    $payload['password'] = password_hash($payload['password'], PASSWORD_DEFAULT);
    
    // Add the user
    $user = $user_service->add_user($payload);

    // Respond with success message and the user ID
    echo json_encode([
        'message' => 'User added successfully',
        'user_id' => $user['id']
    ]);
} catch (Exception $e) {
    header('HTTP/1.1 500 Internal Server Error');
    echo json_encode(['error' => $e->getMessage()]);
}

