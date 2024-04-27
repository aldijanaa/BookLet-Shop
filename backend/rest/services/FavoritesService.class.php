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
        $favorite = [
            'user_id' => $user_id,
            'book_id' => $book_id
        ];
        return $this->favorites_dao->add_to_favorites($favorite);
    }

    public function delete_favorite($favorite_id) {
        return $this->favorites_dao->delete_favorite($favorite_id);
    }
}
?>
