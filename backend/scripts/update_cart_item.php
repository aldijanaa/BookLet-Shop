<?php
require_once __DIR__ . '/../rest/services/CartService.class.php';

header('Content-Type: application/json');
$json_str = file_get_contents('php://input');
$payload = json_decode($json_str, true);

// Check if the cart ID and the new quantity are provided
if (empty($payload['id']) || !isset($payload['quantity'])) {
    header('HTTP/1.1 400 Bad Request');
    echo json_encode(['error' => 'Cart item ID and new quantity are required.']);
    exit();
}

$cart_service = new CartService();

try {
    // Update the cart item quantity
    $cart_service->update_cart_quantity($payload['id'], $payload['quantity']);
    
    // Respond with success message
    echo json_encode(['message' => 'Cart item updated successfully']);
} catch (Exception $e) {
    // If something goes wrong, send a 500 server error and the error message
    header('HTTP/1.1 500 Internal Server Error');
    echo json_encode(['error' => $e->getMessage()]);
}
?>
