<?php
require_once __DIR__ . '/../rest/services/BooksService.class.php';

header('Content-Type: application/json');
$json_str = file_get_contents('php://input');
$payload = json_decode($json_str, true);

// Check if the ID is provided for the book to update
if (empty($payload['id'])) {
    header('HTTP/1.1 400 Bad Request');
    echo json_encode(['error' => 'Book ID is required.']);
    exit();
}

// You can also check for the existence of the book here before attempting to update
// ...

$book_service = new BooksService();

try {
    // Attempt to update the book
    $book_service->update_book($payload['id'], [
        'title' => $payload['title'],
        'author' => $payload['author'],
        'price' => $payload['price'],
        'image' => $payload['image'] ?? null, // Assuming these fields are optional
        'description' => $payload['description'] ?? null,
        'stars' => $payload['stars'] ?? null
    ]);
    
    // Respond with success message
    echo json_encode(['message' => 'Book updated successfully']);
} catch (Exception $e) {
    // If something goes wrong, send a 500 server error and the error message
    header('HTTP/1.1 500 Internal Server Error');
    echo json_encode(['error' => $e->getMessage()]);
}
?>
