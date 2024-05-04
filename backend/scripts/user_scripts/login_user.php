<?php

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Content-Type: application/json');

require_once __DIR__ . '/../../rest/services/UserService.class.php';

$service = new UserService();
$data = json_decode(file_get_contents('php://input'), true);

// Check if the email and password are provided
if (!$data || empty($data['email']) || empty($data['password'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Email and password are required']);
    exit;
}

$email = $data['email'];
$password = $data['password'];

// Fetch user by email
$user = $service->get_user_by_email($email);
if (!$user) {
    http_response_code(401); // Unauthorized
    echo json_encode(['error' => 'No user found with that email address']);
    exit;
}

// Verify password (plain text comparison)
// LATER TO DO: Replace with password_verify after implementing password hashing
if ($password !== $user['password']) {
    http_response_code(401); // Unauthorized
    echo json_encode(['error' => 'Invalid password']);
    exit;
}

// If login is successful, you might want to start a session or generate a token here
http_response_code(200);
echo json_encode(['message' => 'Login successful', 'user' => $user]);

?>
