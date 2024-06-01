<?php
require_once __DIR__ . '/../services/CartService.class.php';
require_once __DIR__ . '/MiddlewareRoutes.php';




Flight::route('GET /cart/@id', function($id){
    $service = new CartService();
    $cart_item = $service->get_cart_item_by_id($id);

    Flight::json($cart_item);
});
/**
 * @OA\Get(
 *   path="/cart/{id}",
 *   summary="Get a cart item by ID",
 *   tags={"Cart"},
 *   security={
 *      {"ApiKey": {}}
 *   },
 *   @OA\Parameter(
 *     name="id",
 *     in="path",
 *     required=true,
 *     description="The ID of the cart item to retrieve",
 *     @OA\Schema(type="integer")
 *   ),
 *   @OA\Response(
 *     response=200,
 *     description="Cart item retrieved successfully",
 *     @OA\JsonContent(
 *       ref="#/components/schemas/CartItem"
 *     )
 *   ),
 *   @OA\Response(
 *     response=404,
 *     description="Cart item not found"
 *   )
 * )
 */


// ADD ITEM TO CART - radi
Flight::route('POST /add-to-cart', function() {
    $service = new CartService();
    $data = Flight::request()->data->getData();
    

    try {
        $user_id = Flight::get('user_id'); // Retrieve user_id from Flight's storage
        if (!$user_id) {
            Flight::halt(401, json_encode(['message' => "User not authenticated"])); // Return error if user_id is not set
            return; // Stop further execution
        }

        $payload = Flight::request()->data->getData(); // Get the JSON payload from the request

        // Validate the payload
        if (!isset($payload['book_id']) || empty($payload['book_id'])) {
            Flight::halt(400, json_encode(['message' => "book ID is required"])); 
            return; // Stop further execution
        }

        // Prepare the cart item array
        $cart_item = [
            'user_id' => $user_id,
            'book_id' => $payload['book_id'],
            'quantity' => $payload['quantity'] ?? 1 // Default quantity to 1 if not provided
        ];

        // Attempt to add the item to the cart
        $added_item = $service->add_item_to_cart($cart_item);
        if ($added_item) {
            Flight::json(['message' => 'Product added to cart successfully', 'data' => $added_item]); // Return success message
        } else {
            Flight::halt(500, json_encode(['message' => "Failed to add the product to the cart"]));
        }
    } catch (Exception $e) {
        Flight::halt(500, json_encode(['message' => $e->getMessage()]));
    }

});


/**
 * @OA\Post(
 *   path="/add-to-cart",
 *   summary="Add a new item to the cart",
 *   description="Allows authenticated users to add a new item to their shopping cart. The user's ID, the book's ID, and the desired quantity must be provided.",
 *   tags={"Cart"},
 *   security={
 *      {"ApiKey": {}}
 *   },
 *   @OA\RequestBody(
 *     required=true,
 *     description="Payload to add an item to the user's cart.",
 *     @OA\JsonContent(
 *       type="object",
 *       required={"user_id", "book_id", "quantity"},
 *       @OA\Property(
 *         property="user_id",
 *         type="integer",
 *         description="ID of the user adding the item"
 *       ),
 *       @OA\Property(
 *         property="book_id",
 *         type="integer",
 *         description="ID of the book to be added to the cart"
 *       ),
 *       @OA\Property(
 *         property="quantity",
 *         type="integer",
 *         description="Quantity of the book to add"
 *       ),
 *       example={
 *         "user_id": 1,
 *         "book_id": 101,
 *         "quantity": 2
 *       }
 *     )
 *   ),
 *   @OA\Response(
 *     response=200,
 *     description="Cart item added successfully",
 *     @OA\JsonContent(
 *       type="object",
 *       @OA\Property(property="success", type="boolean", example=true),
 *       @OA\Property(property="message", type="string", example="Cart item added successfully")
 *     )
 *   ),
 *   @OA\Response(
 *     response=400,
 *     description="Invalid input. Failed to add item to cart",
 *     @OA\JsonContent(
 *       type="object",
 *       @OA\Property(property="success", type="boolean", example=false),
 *       @OA\Property(property="message", type="string", example="Invalid input parameters")
 *     )
 *   ),
 *   @OA\Response(
 *     response=401,
 *     description="Unauthorized. The user is not logged in or the token is invalid.",
 *     @OA\JsonContent(
 *       type="object",
 *       @OA\Property(property="success", type="boolean", example=false),
 *       @OA\Property(property="message", type="string", example="Unauthorized access")
 *     )
 *   )
 * )
 */



//Route to delete a cart item
 Flight::route('DELETE /cart/@id', function($id){
    $service = new CartService();

    $result = $service->delete_cart_item($id);
    Flight::json(['success' => $result]);
});



/**
 * @OA\Delete(
 *   path="/cart/{id}",
 *   summary="Delete a cart item",
 *   tags={"Cart"},
 *   security={
 *      {"ApiKey": {}}
 *   },
 *   @OA\Parameter(
 *     name="id",
 *     in="path",
 *     required=true,
 *     description="The ID of the cart item to delete",
 *     @OA\Schema(type="integer")
 *   ),
 *   @OA\Response(
 *     response=200,
 *     description="Cart item deleted successfully"
 *   )
 * )
 */


//UPDATE CART QUANTITY
Flight::route('PUT /cart/@id/quantity', function($id){
    $quantity = Flight::request()->data->quantity;

    $service = new CartService();
    $result = $service->update_cart_quantity($id, $quantity);


    /*if ($result) {
        Flight::json(['success' => true, 'message' => 'Cart quantity updated successfully']);
    } else {
        Flight::json(['success' => false, 'message' => 'Failed to update cart quantity']); 
    }*/

    //Since DAO methods return boolean values indicating success or failure we can simplify the above code
    Flight::json(['success' => $result, 'message' => 'Cart quantity updated successfully']); 
});

/**
 * @OA\Put(
 *   path="/cart/{id}/quantity",
 *   summary="Update the quantity of a cart item",
 *   tags={"Cart"},
 *   security={
 *      {"ApiKey": {}}
 *   },
 *   @OA\Parameter(
 *     name="id",
 *     in="path",
 *     required=true,
 *     description="The ID of the cart item to update the quantity",
 *     @OA\Schema(type="integer")
 *   ),
 *   @OA\RequestBody(
 *     required=true,
 *     description="Quantity to be updated",
 *     @OA\JsonContent(
 *       required={"quantity"},
 *       @OA\Property(property="quantity", type="integer")
 *     )
 *   ),
 *   @OA\Response(
 *     response=200,
 *     description="Cart quantity updated successfully"
 *   )
 * )
 */

//Route to get the count of items in the cart for a specific user
 Flight::route('GET /user/@user_id/cart/count', function($user_id){
    $service = new CartService();
 
    $count = $service->get_cart_count_by_user($user_id); //Returns the number of items in the cart for the user with the given ID
    Flight::json(['count' => $count]);
});

/**
 * @OA\Get(
 *     path="/user/{user_id}/cart/count",
 *     summary="Get the count of items in the cart for a specific user",
 *     tags={"Cart"},
 *     security={
 *      {"ApiKey": {}}
 *      },
 *     @OA\Parameter(
 *         name="user_id",
 *         in="path",
 *         required=true,
 *         description="The ID of the user whose cart count is being retrieved",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Cart count retrieved successfully",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="count", type="integer", description="The count of items in the user's cart")
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="User not found"
 *     )
 * )
 */







Flight::start();