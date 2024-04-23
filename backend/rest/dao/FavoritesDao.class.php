<?php
require_once __DIR__ . '/BaseDao.class.php';

class FavoritesDao extends BaseDao {

    public function __construct() {
        parent::__construct("favorites");
    }

    public function get_favorite_by_id($favorite_id) {
        return $this->query_unique("SELECT * FROM favorites WHERE favorite_id = :favorite_id", ["favorite_id" => $favorite_id]);
    }

    public function get_favorites_by_user($user_id) {
        return $this->query("SELECT * FROM favorites WHERE user_id = :user_id", ["user_id" => $user_id]);
    }

    public function add_to_favorites($favorite) {
        return $this->insert('favorites', $favorite);
    }

    public function delete_favorite($favorite_id) {
        return $this->execute("DELETE FROM favorites WHERE favorite_id = :favorite_id", ["favorite_id" => $favorite_id]);
    }
}
?>
