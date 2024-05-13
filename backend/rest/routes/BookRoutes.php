<?php


require_once __DIR__ . '/../services/BooksService.class.php';
require_once __DIR__ . '/../../middleware.php';

Flight::group('/books', function() {
    
    /**
     * @OA\Get(
     *     path="/books/all",
     *     tags={"Books"},
     *     summary="Retrieve all books with optional pagination and sorting",
     *     @OA\Parameter(
     *         name="offset",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="integer"),
     *         description="Offset for pagination"
     *     ),
     *     @OA\Parameter(
     *         name="limit",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="integer"),
     *         description="Limit for pagination"
     *     ),
     *     @OA\Parameter(
     *         name="order",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="string"),
     *         description="Order by field"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="An array of books"
     *     )
     * )
     */
    Flight::route('GET /all', function() {
        $books_service = new BooksService();
        $offset = Flight::request()->query['offset'] ?? 0;
        $limit = Flight::request()->query['limit'] ?? 25;
        $order = Flight::request()->query['order'] ?? 'id';
        $books = $books_service->get_all_books($offset, $limit, $order);
        Flight::json($books);
    });

    // GET a single book by ID
    Flight::route('GET /@id', function($id) {
        $books_service = new BooksService();
        $book = $books_service->get_book_by_id($id);
        if ($book) {
            Flight::json($book);
        } else {
            Flight::halt(404, 'Book not found');
        }
    });

    // POST a new book
    Flight::route('POST /add', function() {
        $books_service = new BooksService();
        $payload = Flight::request()->data->getData();
        $book = $books_service->add_book($payload);
        Flight::json($book, 201);
    });

    // PUT an update to a book by ID
    Flight::route('PUT /update/@id', function($id) {
        $books_service = new BooksService();
        $payload = Flight::request()->data->getData();
        $books_service->update_book($id, $payload);
        Flight::json(['message' => 'Book updated successfully'], 200);
    });

    // DELETE a book by ID
    Flight::route('DELETE /delete/@id', function($id) {
        $books_service = new BooksService();
        $books_service->delete_book_by_id($id);
        Flight::json(['message' => 'Book deleted successfully'], 200);
    });

    // GET books by search term (title or author)
    Flight::route('GET /search', function() {
        $books_service = new BooksService();
        $search_term = Flight::request()->query['term'];
        $books = $books_service->search_books($search_term);
        Flight::json($books);
    });

});
