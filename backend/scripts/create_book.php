<?php
require_once __DIR__ . '/../rest/services/BooksService.class.php';

header('Content-Type: application/json');
$json_str = file_get_contents('php://input');
$payload = json_decode($json_str, true);

if (empty($payload['title']) || empty($payload['author']) || empty($payload['price'])) {
    header('HTTP/1.1 400 Bad Request');
    echo json_encode(['error' => 'Required fields are missing. Title, author, price, image, description and review must be provided.']);
    exit();
}

$book_service = new BooksService();

$book = $book_service->add_book([
    'title' => $payload['title'],
    'author' => $payload['author'],
    'price' => $payload['price'],
    'image' => $payload['image'], // Optional
    'description' => $payload['description'], // Optional
    'stars' => $payload['stars'] // Optional, default to 0
]);

echo json_encode($book);
?>
