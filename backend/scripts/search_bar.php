<?php
// search_books.php
require_once __DIR__ . '/../rest/services/BooksService.class.php';

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

$service = new BooksService();
$search_term = $_GET['search'] ?? '';  // Get the search term from the URL query parameter

try {
    $results = $service->search_books($search_term);
    echo json_encode($results);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
