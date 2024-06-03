<?php
require_once __DIR__ . '/../services/BooksService.class.php';
require_once __DIR__ . '/MiddlewareRoutes.php';

Flight::group('/books', function() {

    /**
     * @OA\Get(
     *     path="/books/search",
     *     tags={"Books"},
     *     summary="Search for books by title or author",
     *     description="Search for books based on a partial match for book titles or authors.",
     *     security={{"ApiKey": {}}},
     *     @OA\Parameter(
     *         name="term",
     *         in="query",
     *         required=true,
     *         description="Search term to look for in book titles or authors. The search is case-insensitive and can match any part of the title or author name.",
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="An array of books matching the search term",
     *         @OA\JsonContent(
     *             type="array",
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Search term is required",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Search term is required")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No books found matching the search criteria",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="No books found matching the search criteria")
     *         )
     *     )
     * )
     */



    //Route to search book by key name
    Flight::route('GET /search', function() {
        $search_term = Flight::request()->query['term'];
     
        //Check if search term is provided
        if (empty($search_term)) {
            Flight::json(['message' => 'Search term is required'], 400);
            return;
        } 
        $books_service = new BooksService();

        try {
            $books = $books_service->search_books($search_term);     // Search for books using the provided term

            // Check if books are found
            if (empty($books)) {
                Flight::json(['message' => 'No books found matching the search criteria'], 404);
            } else {
                Flight::json($books, 200);
            }
        } catch (Exception $e) {
            error_log("Error during search: " . $e->getMessage());
            Flight::json(['message' => 'Server error while processing your request', 'error' => $e->getMessage()], 500);
        }
    });
    

    
    
    /**
     * @OA\Get(
     *     path="/books/all",
     *     tags={"Books"},
     *    security={{"ApiKey": {}}},
     *     summary="Retrieve all books with optional pagination and sorting",
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

     //Route for getting all books - radi
    Flight::route('GET /all', function() {
        $books_service = new BooksService();
        $limit = Flight::request()->query['limit'] ?? 25;
        $order = Flight::request()->query['order'] ?? 'id';

        $books = $books_service->get_all_books($limit, $order);
        Flight::json($books);
    });


    /**
     * @OA\Get(
     *     path="/books/{id}",
     *     summary="Get a single book by ID",
     *     tags={"Books"},
     *     description="Get a single book by ID",
     *     security={{"ApiKey": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the book to retrieve",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *      ),
     *     @OA\Response(
     *         response=404,
     *         description="Book not found"
     *     )
     * )
     */


    // GET a single book by ID  - radi
    Flight::route('GET /@id', function($id) {
        $books_service = new BooksService();
        $book = $books_service->get_book_by_id($id);
        
        if ($book) {
            Flight::json($book);
        } else {
            Flight::halt(404, 'Book not found');
        }
    });

    /**
     * @OA\Post(
     *     path="/books/add-book",
     *     summary="Add a new book",
     *     tags={"Books"},
     *     description="Add a new book to the library",
     *     security={{"ApiKey": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         description="Book object to be added",
     *         @OA\JsonContent(
     *             required={"title", "author", "price", "image", "description", "stars"},
     *             @OA\Property(property="title", type="string", example="New Book Title"),
     *             @OA\Property(property="author", type="string", example="Author Name"),
     *             @OA\Property(property="price", type="number", format="float", example=19.99),
     *             @OA\Property(property="image", type="string", example="/assets/images/book1.png"),
     *             @OA\Property(property="description", type="string", example="Description of the book"),
     *             @OA\Property(property="stars", type="integer", example=5)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Book added successfully",
     *     )
     * )
     */


    // ADD a new book
    Flight::route('POST /add-book', function() {
        $books_service = new BooksService();
        $payload = Flight::request()->data->getData();
        
        try {

            $book_id = $books_service->add_book($payload);
            if ($book_id) {
                $book = $books_service->get_book_by_id($book_id);
                Flight::json($book, 201); // Ensure proper status code is used
                error_log("JESI");
            } else {
                Flight::json(["error" => "Book not added"], 400); // Bad request or data invalid
            }

        } catch (Exception $e) {
            Flight::json(["error" => $e->getMessage()], 500); // Internal Server Error
        }
        error_log("Book added successfully da li je????");
    });


    /**
     * @OA\Put(
     *     path="/books/update-book/{id}",
     *     tags={"Books"},
     *     summary="Update an existing book by ID",
     *     description="Updates a book's details in the database.",
     *     security={{"ApiKey": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the book to update",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="Book object that needs to be updated",
     *         @OA\JsonContent(
     *             required={"title", "author", "price", "image", "description", "stars"},
     *             @OA\Property(property="title", type="string", example="Updated Book Title"),
     *             @OA\Property(property="author", type="string", example="Updated Author Name"),
     *             @OA\Property(property="price", type="number", format="float", example=25.99),
     *             @OA\Property(property="image", type="string", example="/assets/images/updated_book1.png"),
     *             @OA\Property(property="description", type="string", example="Updated description of the book"),
     *             @OA\Property(property="stars", type="integer", example=4)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Book updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Book updated successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid request"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Book not found"
     *     )
     * )
     */

    // PUT an update to a book by ID
    Flight::route('PUT /update-book/@id', function($id) {
        $books_service = new BooksService();
        $payload = Flight::request()->data->getData();
        $books_service->update_book($id, $payload);
        Flight::json(['message' => 'Book updated successfully'], 200);
    });

    /**
     * @OA\Delete(
     *     path="/books/delete/{id}",
     *     tags={"Books"},
     *     summary="Delete an existing book by ID",
     *     description="Deletes a book from the database.",
     *     security={{"ApiKey": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the book to delete",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Book deleted successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Book deleted successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid request"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Book not found"
     *     )
     * )
     */
    
     
    // DELETE a book by ID
    Flight::route('DELETE /delete/@id', function($id) {
        $books_service = new BooksService();

        $books_service->delete_book_by_id($id);
        Flight::json(['message' => 'Book deleted successfully'], 200);
    });



    /**
     * @OA\Get(
     *     path="/books/search",
     *     tags={"Books"},
     *     summary="Search for books by title or author",
     *     description="Search for books by title or author",
     *     security={{"ApiKey": {}}},
     *     @OA\Parameter(
     *         name="term",
     *         in="query",
     *         required=true,
     *         description="Search term to look for in book titles or authors",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="An array of books matching the search term"
     *     )
     * )
     */

 
 
     


});


   // SEARCH for books by title or author
   Flight::route('GET /search', function() {
    // Retrieve the search term from the query parameters
    $search_term = Flight::request()->query['term'];

    // Log the search term for debugging purposes
    error_log("Search term received: " . $search_term);

    // Check if the search term is provided
    if (empty($search_term)) {
        // Respond with an error if the search term is empty
        Flight::json(['message' => 'Search term is required'], 400);
        return;
    }

    // Instantiate the books service
    $books_service = new BooksService();

    try {
        // Search for books using the provided term
        $books = $books_service->search_books($search_term);

        // Check if books are found
        if (empty($books)) {
            // Respond with not found if no books match the search term
            Flight::json(['message' => 'No books found matching the search criteria'], 404);
        } else {
            // Respond with the found books
            Flight::json($books, 200);
        }
    } catch (Exception $e) {
        // Log and respond with an error if there is an exception
        error_log("Error during search: " . $e->getMessage());
        Flight::json(['message' => 'Server error while processing your request', 'error' => $e->getMessage()], 500);
    }
});
