<?php
require_once __DIR__ . '/../rest/services/UserService.class.php';

header('Content-Type: application/json');
$json_str = file_get_contents('php://input');
$payload = json_decode($json_str, true);

// Check if the ID is provided for the user to update
if (empty($payload['id'])) {
    header('HTTP/1.1 400 Bad Request');
    echo json_encode(['error' => 'User ID is required.']);
    exit();
}

// You can add more validation for the input fields if needed

$user_service = new UserService();

try {
    // If password is provided, hash it before storing it
    if (isset($payload['password'])) {
        $payload['password'] = password_hash($payload['password'], PASSWORD_DEFAULT);
    }
    
    // Attempt to update the user
    $user_service->update_user($payload['id'], $payload);
    
    // Respond with success message
    echo json_encode(['message' => 'User updated successfully']);
} catch (Exception $e) {
    // If something goes wrong, send a 500 server error and the error message
    header('HTTP/1.1 500 Internal Server Error');
    echo json_encode(['error' => $e->getMessage()]);
}
?>
