<?php
require_once __DIR__ . '/../rest/services/BooksService.class.php';


header('Access-Control-Allow-Origin: *');       //ovaj čitav fajl će kasnije postati rute, služi trenutno kao dummy data
header('Content-Type: application/json');

$book_service = new BooksService();

$offset = isset($_GET['offset']) ? $_GET['offset'] : 0;
$limit = isset($_GET['limit']) ? $_GET['limit'] : 25;
$order = isset($_GET['order']) ? $_GET['order'] : "-id";


//OVDJE SE ZOVU FUNKCIJE IZ SERVISA
$books = $book_service->get_all_books($offset, $limit, $order);

echo json_encode($books);


?>
