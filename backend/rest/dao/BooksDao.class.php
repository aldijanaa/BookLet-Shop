<?php
require_once __DIR__ . '/BaseDao.class.php';

class BooksDao extends BaseDao
{
    public function __construct()
    {
        parent::__construct("books");  //TABLE NAME: books
    }

    public function get_books($offset = 0, $limit = 25, $order = "id")
    {
        list($order_column, $order_direction) = $this->parse_order($order);
        $query = "SELECT * FROM books ORDER BY {$order_column} {$order_direction} LIMIT {$limit} OFFSET {$offset}";
        return $this->query($query, []);
    }

    public function get_book_by_id($book_id)
    {
        return $this->query_unique("SELECT * FROM books WHERE id = :id", ["id" => $book_id]);
    }

    public function add_book($book)
    {
        return $this->insert('books', $book);
    }

    public function update_book($book_id, $book)
    {
        $this->execute_update('books', $book_id, $book);
    }

    public function delete_book_by_id($book_id)
    {
        $this->execute("DELETE FROM books WHERE id = :id", ["id" => $book_id]);
    }
}

