<?php
require_once __DIR__ . '/BaseDao.class.php';

class CartDao extends BaseDao {

    public function __construct() {
        parent::__construct("cart");  //table name
    }

    //get cart by id
    public function get_cart_by_id($id) {
        return $this->query_unique("SELECT * FROM cart WHERE id = :id", ["id" => $id]);
    }

    /*public function add_to_cart($cart_item) {
        error_log("Inserting into cart: " . json_encode($cart_item));

        return $this->insert('cart', $cart_item);
        //return $this->add($cart_item);

    }*/

    //miralemova verzija
    public function add_to_cart($cart_item){

        //check if the product is already in user's cart
        $existing_item = $this->query_unique("SELECT * FROM cart WHERE user_id = :user_id AND book_id = :book_id", [
            "user_id" => $cart_item['user_id'], 
            "book_id" => $cart_item['book_id']]);
        
        if($existing_item){

            //if product is already in use, update the quantity
            $this->execute("UPDATE cart SET quantity = quantity + :quantity WHERE user_id = :user_id AND book_id =:book_id", [
                "user_id" => $cart_item['user_id'],
                "book_id" => $cart_item['book_id'],
            ]);
            return $this->query_unique("SELECT * FROM cart WHERE id = :id", ["id" => $existing_item['id']]);
        
        }else{
            //if product is not in user's cart, add it
            $this->execute("INSERT INTO cart (user_id, book_id, quantity) VALUES (:user_id, :book_id, :quantity)",[
                "user_id"=>$cart_item['user_id'],
                "book_id"=>$cart_item['book_id'],
                "quantity"=>$cart_item['quantity']
            ]);
            return $this->query_unique("SELECT * FROM cart WHERE id = :id", ["id"=> $this->connection->lastInsertId()]);
            //return $this->insert('cart', $cart_item);
        }
    }



    public function update_cart_item($id, $cart_item) {
        error_log("Updating cart item ID: " . $id . " with data: " . json_encode($cart_item));
        return $this->execute_update('cart', $id, $cart_item);
    }

    public function delete_cart_item($id) {  //by id
        error_log("Deleting cart item ID: " . $id);
        return $this->execute("DELETE FROM cart WHERE id = :id", ["id" => $id]);
    }
    
    public function update_cart_quantity($cart_id, $quantity){
        error_log("Updating cart quantity. ID: " . $cart_id . ", New Quantity: " . $quantity);

        $query = "UPDATE cart SET quantity = :quantity WHERE id = :cart_id";
        $stmt = $this->connection->prepare($query);
        $stmt->execute(['cart_id' => $cart_id, 'quantity' => $quantity]);
        return $stmt->rowCount() > 0; //return the number of rows affected

    }

     // Get cart item by user and book
     public function get_cart_item_by_user_and_book($user_id, $book_id) {
        error_log("Fetching cart item by User ID: " . $user_id . " and Book ID: " . $book_id);

        $item = $this->query_unique("SELECT * FROM cart WHERE user_id = :user_id AND book_id = :book_id", ["user_id" => $user_id, "book_id" => $book_id]);
        return $item ? $item : null;
    }

    public function get_carts_by_user($user_id, $offset = 0, $limit = 25, $order = "-id"){
    list($order_column, $order_direction) = self::parse_order($order);
    $query = "SELECT *
              FROM cart
              WHERE user_id = :user_id
              ORDER BY {$order_column} {$order_direction}
              LIMIT {$limit} OFFSET {$offset}";
    return $this->query($query, ["user_id" => $user_id]);
    }

    //Function to count number of items in cart
    public function get_cart_count_by_user($user_id) {
        return $this->query("SELECT  COUNT(*) AS count FROM cart WHERE user_id = :user_id", ["user_id" => $user_id])[0]['count'];
    }

}

