<?php
require_once __DIR__ . '/../rest/services/UserService.class.php';

header('Content-Type: application/json');

// Assuming you're passing the user ID as a URL parameter (e.g., delete_user.php?id=123)
$user_id = isset($_GET['id']) ? $_GET['id'] : null;

if (empty($user_id)) {
    header('HTTP/1.1 400 Bad Request');
    echo json_encode(['error' => 'User ID is required.']);
    exit();
}

$user_service = new UserService();

try {
    // Attempt to delete the user
    $user_service->delete_user_by_id($user_id);
    
    // Respond with success message
    echo json_encode(['message' => 'User deleted successfully']);
} catch (Exception $e) {
    // If something goes wrong, send a 500 server error and the error message
    header('HTTP/1.1 500 Internal Server Error');
    echo json_encode(['error' => $e->getMessage()]);
}
