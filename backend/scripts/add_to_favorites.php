<?php
require_once __DIR__ . '/../rest/services/FavoritesService.class.php';

header('Content-Type: application/json');
$json_str = file_get_contents('php://input');
$payload = json_decode($json_str, true);

// Validate the input
if (empty($payload['user_id']) || empty($payload['book_id'])) {
    header('HTTP/1.1 400 Bad Request');
    echo json_encode(['error' => 'User ID and Book ID are required.']);
    exit();
}

$favorites_service = new FavoritesService();

try {
    // Create favorite
    $result = $favorites_service->add_to_favorites($payload['user_id'], $payload['book_id']);
    echo json_encode(['message' => 'Favorite added successfully', 'favorite_id' => $result['id']]);
} catch (Exception $e) {
    header('HTTP/1.1 500 Internal Server Error');
    echo json_encode(['error' => $e->getMessage()]);
}
?>
