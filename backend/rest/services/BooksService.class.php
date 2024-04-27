<?php
require_once __DIR__ . '/../dao/BooksDao.class.php';

class BooksService
{
    private $books_dao;

    public function __construct()
    {
        $this->books_dao = new BooksDao();
    }

    public function get_all_books($offset = 0, $limit = 25, $order = "id")
    {
        return $this->books_dao->get_books($offset, $limit, $order);
    }

    public function get_book_by_id($book_id)
    {
        return $this->books_dao->get_book_by_id($book_id);
    }

    public function add_book($book)
    {
        return $this->books_dao->add_book($book);  //book table
    }

    public function update_book($book_id, $book)
    {
        $this->books_dao->update_book($book_id, $book);
    }

    public function delete_book_by_id($book_id)
    {
        $this->books_dao->delete_book_by_id($book_id);
    }
}
?>
