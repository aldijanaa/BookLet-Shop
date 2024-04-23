<?php
require_once __DIR__ . '/BaseDao.class.php';

class CartDao extends BaseDao {

    public function __construct() {
        parent::__construct("cart");
    }

    public function get_cart_by_id($id) {
        return $this->query_unique("SELECT * FROM cart WHERE id = :id", ["id" => $id]);
    }

    public function add_to_cart($cart_item) {
        return $this->insert('cart', $cart_item);
    }

    public function update_cart_item($id, $cart_item) {
        $this->execute_update('cart', $id, $cart_item);
    }

    public function delete_cart_item($id) {  //by id
        $this->execute("DELETE FROM cart WHERE id = :id", ["id" => $id]);
    }
    
    public function update_cart_quantity($cart_id, $quantity)
    {
        $query = "UPDATE cart SET quantity = :quantity WHERE id = :cart_id";
        $stmt = $this->connection->prepare($query);
        $stmt->execute(['cart_id' => $cart_id, 'quantity' => $quantity]);
        return $stmt->rowCount();
    }
    public function get_carts_by_user($user_id, $offset = 0, $limit = 25, $order = "-id")
{
    list($order_column, $order_direction) = self::parse_order($order);
    $query = "SELECT *
              FROM cart
              WHERE user_id = :user_id
              ORDER BY {$order_column} {$order_direction}
              LIMIT {$limit} OFFSET {$offset}";
    return $this->query($query, ["user_id" => $user_id]);
}

}
?>
