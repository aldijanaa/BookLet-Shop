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
    
    //Get a cart item by user and book
    public function get_cart_item_by_user_and_book($user_id, $book_id) {
        return $this->cart_dao->get_cart_item_by_user_and_book($user_id, $book_id);
    }

    //Add a cart item to the cart   
    public function add_item_to_cart($cart_item) {
         //$existing_item = $this->get_cart_item_by_user_and_book($cart_item['user_id'], $cart_item['book_id']);       // Check if the item already exists in the cart
         
         /*if ($existing_item) { 
            //$new_quantity = $existing_item['quantity'] + $cart_item['quantity'];
            $new_quantity = $existing_item['quantity'] + 1;
            error_log("Existing quantity: " . $existing_item['quantity']);
            error_log("Added quantity: " . $cart_item['quantity']);
            error_log("New quantity: " . $new_quantity);
            return $this->cart_dao->update_cart_quantity($existing_item['id'], $new_quantity);
        } else {
            return $this->cart_dao->add_to_cart($cart_item);
        }*/

        return $this->cart_dao->add_to_cart($cart_item);
    }

    public function delete_cart_item($cart_id) {
         return $this->cart_dao->delete_cart_item($cart_id);
    }

    public function update_cart_quantity($cart_id, $quantity) {
        return $this->cart_dao->update_cart_quantity($cart_id, $quantity);
    }

    //Function to count number of items in cart  - za onu malu ikonicu u headeru
    public function get_cart_count_by_user($user_id) {
        return $this->cart_dao->get_cart_count_by_user($user_id);
    }
    
}

