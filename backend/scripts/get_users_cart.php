<?php
require_once __DIR__ . '/../rest/services/CartService.class.php';

header('Content-Type: application/json');

// Get user ID from the URL query parameter
$user_id = isset($_GET['user_id']) ? $_GET['user_id'] : null;

// Check if user ID is provided
if (empty($user_id)) {
    header('HTTP/1.1 400 Bad Request');
    echo json_encode(['error' => 'User ID is required.']);
    exit();
}

$cart_service = new CartService();

// Optionally, receive parameters for pagination
$offset = isset($_GET['offset']) ? $_GET['offset'] : 0;
$limit = isset($_GET['limit']) ? $_GET['limit'] : 25;
$order = isset($_GET['order']) ? $_GET['order'] : 'id'; // Default to sorting by 'id'

try {
    $carts = $cart_service->get_carts_by_user($user_id, $offset, $limit, $order);
    echo json_encode(['data' => $carts]);
} catch (Exception $e) {
    header('HTTP/1.1 500 Internal Server Error');
    echo json_encode(['error' => $e->getMessage()]);
}
?>
