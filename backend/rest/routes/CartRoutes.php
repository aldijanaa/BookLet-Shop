<?php
require_once __DIR__ . '/../services/CartService.class.php';

$service = new CartService();

/**
 * @OA\Get(
 *   path="/cart/{id}",
 *   summary="Get a cart item by ID",
 *   tags={"Cart"},
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
Flight::route('GET /cart/@id', function($id) use ($service) {
    $cart_item = $service->get_cart_item_by_id($id);
    Flight::json($cart_item);
});

/**
 * @OA\Get(
 *   path="/user/{user_id}/cart",
 *   summary="Get all cart items for a user",
 *   tags={"Cart"},
 *   @OA\Parameter(
 *     name="user_id",
 *     in="path",
 *     required=true,
 *     description="User ID whose cart items are to be retrieved",
 *     @OA\Schema(type="integer")
 *   ),
 *   @OA\Response(
 *     response=200,
 *     description="Cart items retrieved successfully",
 *     @OA\JsonContent(
 *       type="array",
 *       @OA\Items(ref="#/components/schemas/CartItem")
 *     )
 *   )
 * )
 */
Flight::route('GET /user/@user_id/cart', function($user_id) use ($service) {
    $cart_items = $service->get_carts_by_user($user_id);
    Flight::json($cart_items);
});

/**
 * @OA\Post(
 *   path="/cart",
 *   summary="Add a new item to the cart",
 *   tags={"Cart"},
 *   @OA\RequestBody(
 *     required=true,
 *     description="Cart item to be added",
 *     @OA\JsonContent(
 *       type="object",
 *       required={"user_id", "book_id", "quantity"},
 *       @OA\Property(property="user_id", type="integer", description="ID of the user"),
 *       @OA\Property(property="book_id", type="integer", description="ID of the book being added to the cart"),
 *       @OA\Property(property="quantity", type="integer", description="Quantity of the book to add to the cart"),
 *       example={"user_id": 123, "book_id": 456, "quantity": 2}
 *     )
 *   ),
 *   @OA\Response(
 *     response=201,
 *     description="Cart item added successfully",
 *     @OA\JsonContent(
 *       type="object",
 *       @OA\Property(property="id", type="integer", description="The newly created cart item ID"),
 *       @OA\Property(property="user_id", type="integer", description="User ID"),
 *       @OA\Property(property="book_id", type="integer", description="Book ID"),
 *       @OA\Property(property="quantity", type="integer", description="Quantity of the book")
 *     )
 *   ),
 *   @OA\Response(
 *     response=400,
 *     description="Invalid input"
 *   )
 * )
 */
Flight::route('POST /cart', function() use ($service) {
    $data = Flight::request()->data->getData();
    $result = $service->add_item_to_cart($data);
    Flight::json($result);
});

/**
 * @OA\Put(
 *   path="/cart/{id}",
 *   summary="Update a cart item",
 *   tags={"Cart"},
 *   @OA\Parameter(
 *     name="id",
 *     in="path",
 *     required=true,
 *     description="The ID of the cart item to update",
 *     @OA\Schema(type="integer")
 *   ),
 *   @OA\RequestBody(
 *     required=true,
 *     description="Cart item data to update",
 *     @OA\JsonContent(ref="#/components/schemas/CartItem")
 *   ),
 *   @OA\Response(
 *     response=200,
 *     description="Cart item updated successfully"
 *   )
 * )
 */
Flight::route('PUT /cart/@id', function($id) use ($service) {
    $data = Flight::request()->data->getData();
    $result = $service->update_cart_item($id, $data);
    Flight::json($result);
});

/**
 * @OA\Delete(
 *   path="/cart/{id}",
 *   summary="Delete a cart item",
 *   tags={"Cart"},
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
Flight::route('DELETE /cart/@id', function($id) use ($service) {
    $result = $service->delete_cart_item($id);
    Flight::json(['success' => $result]);
});

/**
 * @OA\Put(
 *   path="/cart/{id}/quantity",
 *   summary="Update the quantity of a cart item",
 *   tags={"Cart"},
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
Flight::route('PUT /cart/@id/quantity', function($id) use ($service) {
    $quantity = Flight::request()->data->quantity;
    $result = $service->update_cart_quantity($id, $quantity);
    Flight::json(['success' => $result]);
});

Flight::start();