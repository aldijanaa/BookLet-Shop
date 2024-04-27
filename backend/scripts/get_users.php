<?php
require_once __DIR__ . '/../rest/services/UserService.class.php';

header('Content-Type: application/json');

$user_service = new UserService();

// Optionally, receive parameters for pagination
$offset = isset($_GET['offset']) ? $_GET['offset'] : 0;
$limit = isset($_GET['limit']) ? $_GET['limit'] : 25;
$order = isset($_GET['order']) ? $_GET['order'] : 'id'; // Default order

try {
    $users = $user_service->get_all_users($offset, $limit, $order);
    
    // Respond with the list of users
    echo json_encode($users);
} catch (Exception $e) {
    // If something goes wrong, send a 500 server error and the error message
    header('HTTP/1.1 500 Internal Server Error');
    echo json_encode(['error' => $e->getMessage()]);
}
?>
