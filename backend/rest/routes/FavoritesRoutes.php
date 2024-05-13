<?php
require_once __DIR__ . '/../services/FavoritesService.class.php';

$service = new FavoritesService();

/**
 * @OA\Get(
 *   path="/favorites/{id}",
 *   summary="Get a favorite by ID",
 *   tags={"Favorites"},
 *   @OA\Parameter(
 *     name="id",
 *     in="path",
 *     required=true,
 *     description="The ID of the favorite to retrieve",
 *     @OA\Schema(
 *       type="integer"
 *     )
 *   ),
 *   @OA\Response(
 *     response=200,
 *     description="Favorite retrieved successfully",
 *     @OA\JsonContent(
 *       type="object",
 *       @OA\Property(property="user_id", type="integer"),
 *       @OA\Property(property="book_id", type="integer")
 *     )
 *   ),
 *   @OA\Response(
 *     response=404,
 *     description="Favorite not found"
 *   )
 * )
 */
Flight::route('GET /favorites/@id', function($id) use ($service) {
    $favorite = $service->get_favorite_by_id($id);
    Flight::json($favorite);
});

/**
 * @OA\Get(
 *   path="/user/{user_id}/favorites",
 *   summary="Get all favorites for a user",
 *   tags={"Favorites"},
 *   @OA\Parameter(
 *     name="user_id",
 *     in="path",
 *     required=true,
 *     description="User ID whose favorites are to be retrieved",
 *     @OA\Schema(
 *       type="integer"
 *     )
 *   ),
 *   @OA\Response(
 *     response=200,
 *     description="Favorites retrieved successfully",
 *     @OA\JsonContent(
 *       type="array",
 *       @OA\Items(ref="#/components/schemas/Favorite")
 *     )
 *   )
 * )
 */
Flight::route('GET /user/@user_id/favorites', function($user_id) use ($service) {
    $favorites = $service->get_favorites_by_user($user_id);
    Flight::json($favorites);
});

/**
 * @OA\Post(
 *   path="/favorites",
 *   summary="Add a new favorite",
 *   tags={"Favorites"},
 *   @OA\RequestBody(
 *     required=true,
 *     description="User ID and Book ID to create a favorite",
 *     @OA\JsonContent(
 *       required={"user_id","book_id"},
 *       @OA\Property(property="user_id", type="integer"),
 *       @OA\Property(property="book_id", type="integer")
 *     )
 *   ),
 *   @OA\Response(
 *     response=201,
 *     description="Favorite added successfully"
 *   )
 * )
 */
Flight::route('POST /favorites', function() use ($service) {
    $user_id = Flight::request()->data->user_id;
    $book_id = Flight::request()->data->book_id;
    $result = $service->add_to_favorites($user_id, $book_id);
    Flight::json($result);
});

/**
 * @OA\Delete(
 *   path="/favorites/{id}",
 *   summary="Delete a favorite",
 *   tags={"Favorites"},
 *   @OA\Parameter(
 *     name="id",
 *     in="path",
 *     required=true,
 *     description="The ID of the favorite to delete",
 *     @OA\Schema(
 *       type="integer"
 *     )
 *   ),
 *   @OA\Response(
 *     response=200,
 *     description="Favorite deleted successfully"
 *   ),
 *   @OA\Response(
 *     response=404,
 *     description="Favorite not found"
 *   )
 * )
 */
Flight::route('DELETE /favorites/@id', function($id) use ($service) {
    $result = $service->delete_favorite($id);
    Flight::json(['success' => $result]);
});

Flight::start();
