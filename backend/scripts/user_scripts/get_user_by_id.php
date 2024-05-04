<?php
require_once __DIR__ . '/../../rest/services/UserService.class.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');


$user_service = new UserService();
$user_id = isset($_GET['id']) ? $_GET['id'] : null;

if ($user_id) {
    $user = $user_service->get_user_by_id($user_id);
    echo json_encode($user);
} else {
    header('HTTP/1.1 400 Bad Request');
    echo json_encode(['error' => 'User ID is required.']);
}
