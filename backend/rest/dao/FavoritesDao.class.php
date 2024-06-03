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

    public function add_to_favorites($favorite){
        $query = "INSERT INTO favorites (user_id, book_id, added_at) VALUES (:user_id, :book_id, NOW())";
        $stmt = $this->connection->prepare($query);
        $stmt->execute([
            'user_id' => $favorite['user_id'],
            'book_id' => $favorite['book_id']
        ]);
        return $this->connection->lastInsertId();  
    
    }

    //Delete favorite
    public function delete_favorite($favorite_id) {
        try {
            $stmt = $this->connection->prepare("DELETE FROM favorites WHERE id = :id");
            $stmt->execute(['id' => $favorite_id]);
            if ($stmt->rowCount() > 0) {
                return ['message' => 'Favorite deleted successfully'];
            } else {
                return ['error' => 'Favorite not found'];
            }
        } catch (PDOException $e) {
            error_log("SQL Error: " . $e->getMessage());
            return ['error' => 'An error occurred while deleting the favorite'];
        }
    }
    
}

