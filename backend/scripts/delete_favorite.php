<?php
require_once __DIR__ . '/../rest/services/FavoritesService.class.php';

header('Content-Type: application/json');

// Assuming you're passing the favorite ID as a URL parameter (e.g., delete_favorite.php?favorite_id=123)
$favorite_id = isset($_GET['favorite_id']) ? $_GET['favorite_id'] : null;

if (empty($favorite_id)) {
    header('HTTP/1.1 400 Bad Request');
    echo json_encode(['error' => 'Favorite ID is required.']);
    exit();
}

$favorites_service = new FavoritesService();

try {
    // Attempt to delete the favorite
    $favorites_service->delete_favorite($favorite_id);
    
    // Respond with a success message
    echo json_encode(['message' => 'Favorite successfully deleted']);
} catch (Exception $e) {
    // If something goes wrong, send a 500 server error and the error message
    header('HTTP/1.1 500 Internal Server Error');
    echo json_encode(['error' => $e->getMessage()]);
}
 
//http://localhost/WEB_Projekat%20sa%20spappom/backend/scripts/delete_favorite.php?favorite_id=1
