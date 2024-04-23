<?php
require_once __DIR__ . '/../rest/services/CartService.class.php';

header('Content-Type: application/json');

// npr. delete_cart_item.php?id=123
$cart_id = isset($_GET['id']) ? $_GET['id'] : null;

if (empty($cart_id)) {
    header('HTTP/1.1 400 Bad Request');
    echo json_encode(['error' => 'Cart item ID is required.']);
    exit();
}

$cart_service = new CartService();

try {
    // Attempt to delete the cart item
    $cart_service->delete_cart_item($cart_id);
    
    // Respond with a success message
    echo json_encode(['message' => 'Cart item deleted successfully']);
} catch (Exception $e) {
    // If something goes wrong, send a 500 server error and the error message
    header('HTTP/1.1 500 Internal Server Error');
    echo json_encode(['error' => $e->getMessage()]);
}
 //DELETE FROM cart WHERE id = :id