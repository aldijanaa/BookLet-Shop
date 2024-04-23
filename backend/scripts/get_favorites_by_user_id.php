<?php
require_once __DIR__ . '/../rest/services/FavoritesService.class.php';

header('Content-Type: application/json');


$user_id = isset($_GET['user_id']) ? $_GET['user_id'] : null;

if (empty($user_id)) {
    header('HTTP/1.1 400 Bad Request');
    echo json_encode(['error' => 'User ID is required.']);
    exit();
}

$favorites_service = new FavoritesService();

try {
    // Retrieve all favorites for the user
    $favorites = $favorites_service->get_favorites_by_user($user_id);
    
    // Respond with the list of favorites
    echo json_encode(['data' => $favorites]);
} catch (Exception $e) {
    // If something goes wrong, send a 500 server error and the error message
    header('HTTP/1.1 500 Internal Server Error');
    echo json_encode(['error' => $e->getMessage()]);
}

//http://localhost/WEB_Projekat%20sa%20spappom/backend/scripts/get_favorites_by_user_id.php?user_id=1  (providaj mu user id)