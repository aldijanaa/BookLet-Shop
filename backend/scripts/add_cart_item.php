<?php

//OVO ĆE SE MIJENJATI KASNIJE!! mislim da neće trebati....

require_once __DIR__ . '/../rest/services/CartService.class.php';

header('Content-Type: application/json');
$json_str = file_get_contents('php://input');
$payload = json_decode($json_str, true);

if (empty($payload['user_id']) || empty($payload['book_id']) || empty($payload['quantity'])) {
    header('HTTP/1.1 400 Bad Request');
    echo json_encode(['error' => 'Missing fields: user_id, book_id, and quantity are required.']);
    exit();
}

$cart_service = new CartService();

try {
    $cart_item = [
        'user_id' => $payload['user_id'],
        'book_id' => $payload['book_id'],
        'quantity' => $payload['quantity']
    ];
    $added_item = $cart_service->add_item_to_cart($cart_item);
    echo json_encode(['message' => 'Item added to cart successfully', 'data' => $added_item]);
} catch (Exception $e) {
    header('HTTP/1.1 500 Internal Server Error');
    echo json_encode(['error' => $e->getMessage()]);
}
?>
