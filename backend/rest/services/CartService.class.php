<?php
require_once __DIR__ . '/../dao/CartDao.class.php';

class CartService {

    private $cart_dao;

    public function __construct() {
        $this->cart_dao = new CartDao();
    }

    public function get_cart_item_by_id($cart_id) {
        return $this->cart_dao->get_cart_by_id($cart_id);
    }

    public function get_carts_by_user($user_id, $offset = 0, $limit = 25, $order = "id") {
        return $this->cart_dao->get_carts_by_user($user_id, $offset, $limit, $order);
    }
    

    public function add_item_to_cart($cart_item) {
        return $this->cart_dao->add_to_cart($cart_item);
    }

    public function update_cart_item($cart_id, $cart_item) {
        return $this->cart_dao->update_cart_item($cart_id, $cart_item);
    }

    public function delete_cart_item($cart_id) {
        return $this->cart_dao->delete_cart_item($cart_id);
    }

    public function update_cart_quantity($cart_id, $quantity) {
        return $this->cart_dao->update_cart_quantity($cart_id, $quantity);
    }
}
?>
