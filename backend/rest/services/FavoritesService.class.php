<?php
require_once __DIR__ . '/../dao/FavoritesDao.class.php';

class FavoritesService {

    private $favorites_dao;

    public function __construct() {
        $this->favorites_dao = new FavoritesDao();
    }

    public function get_favorite_by_id($favorite_id) { 
        return $this->favorites_dao->get_favorite_by_id($favorite_id);
    }

    public function get_favorites_by_user($user_id) {
        return $this->favorites_dao->get_favorites_by_user($user_id);
    }

    public function add_to_favorites($user_id, $book_id) {
        try {
            $favorite = [
                'user_id' => $user_id,
                'book_id' => $book_id
            ];
            return $this->favorites_dao->add_to_favorites($favorite);
        } catch (PDOException $e) {
            error_log('Failed to add favorite: ' . $e->getMessage());
            throw new Exception("Error adding favorite. Please try again.");
        }
    }
    

    public function delete_favorite($favorite_id) {
        return $this->favorites_dao->delete_favorite($favorite_id);
    }
}

