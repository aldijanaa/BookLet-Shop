<?php
require_once __DIR__ . '/../rest/services/BooksService.class.php';

header('Content-Type: application/json');

// Assuming you're passing the book ID as a URL parameter (e.g., delete_book.php?id=123)
$book_id = isset($_GET['id']) ? $_GET['id'] : null;

if (empty($book_id)) {
    header('HTTP/1.1 400 Bad Request');
    echo json_encode(['error' => 'Book ID is required.']);
    exit();
}

$book_service = new BooksService();

try {
    // Attempt to delete the book
    $book_service->delete_book_by_id($book_id);
    
    // Respond with success message
    echo json_encode(['message' => 'Book deleted successfully']);
} catch (Exception $e) {
    // If something goes wrong, send a 500 server error and the error message
    header('HTTP/1.1 500 Internal Server Error');
    echo json_encode(['error' => $e->getMessage()]);
}
//Da bi runnalo u postmanu, mora se dodati id u URL, npr:
//http://localhost/WEB_Projekat%20sa%20spappom/backend/scripts/delete_book.php?id=11