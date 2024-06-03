<?php
require_once __DIR__ . '/BaseDao.class.php';

class BooksDao extends BaseDao{
    
    public function __construct(){
        parent::__construct("books");  //TABLE NAME: books
    }

    public function get_books( $limit = 25, $order = "id"){
        list($order_column, $order_direction) = $this->parse_order($order);
        $query = "SELECT * FROM books ORDER BY {$order_column} {$order_direction} LIMIT {$limit}";
        return $this->query($query, []);
    }

    public function get_book_by_id($book_id){
        return $this->query_unique("SELECT * FROM books WHERE id = :id", ["id" => $book_id]);
    }

    //Add a book
    public function add_book($book){
        error_log("Attempting to add book: " . json_encode($book));

        try{
            // SQL query to insert a new book
        $query = "INSERT INTO books (title, author, price, image, description, stars) VALUES (:title, :author, :price, :image, :description, :stars)";
         
        // Prepare the statement
        $stmt = $this->connection->prepare($query);
        
        // Bind each parameter explicitly
        $stmt->bindParam(':title', $book['title'], PDO::PARAM_STR);
        $stmt->bindParam(':author', $book['author'], PDO::PARAM_STR);
        $stmt->bindParam(':price', $book['price'], PDO::PARAM_STR);  // Ensure type consistency; adjust if `price` should be PDO::PARAM_INT
        $stmt->bindParam(':image', $book['image'], PDO::PARAM_STR);
        $stmt->bindParam(':description', $book['description'], PDO::PARAM_STR);
        $stmt->bindParam(':stars', $book['stars'], PDO::PARAM_INT);

        // Execute the query
        $stmt->execute();

        // Returning the ID of the newly inserted book
        error_log("Book added with ALDIJANA: " );

        return $this->connection->lastInsertId();
        
        } catch (Exception $e) {
            throw new Exception("Error adding book: " . $e->getMessage());
        }
    }

    /*public function add_book($book)
    {
        return $this->insert("books", $book);
    }*/

    public function update_book($book_id, $book){
        $this->execute_update('books', $book_id, $book);
    }

    public function delete_book_by_id($book_id){
        $this->execute("DELETE FROM books WHERE id = :id", ["id" => $book_id]);
    }

    //search books by title or author
    public function search_books($search_term) {
        $search_term = '%' . strtolower($search_term) . '%';  // Ensure the search term is in lowercase for case-insensitive comparison

        $query = "SELECT * FROM books WHERE LOWER(title) LIKE :title_search_term OR LOWER(author) LIKE :author_search_term;";

        $params = [
            'title_search_term' => $search_term,
            'author_search_term' => $search_term
        ];

        return $this->query($query, $params);
    }


    
}

