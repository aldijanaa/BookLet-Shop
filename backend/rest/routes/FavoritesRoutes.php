<?php
require_once __DIR__ . '/../services/FavoritesService.class.php';
require_once __DIR__ . '/MiddlewareRoutes.php';


Flight::group('/favorites', function () { 

    /**
     * @OA\Get(
     *   path="/favorites/user/{user_id}/favorites",
     *   summary="Get all favorites for a user",
     *   tags={"Favorites"},
     *  security={
     *    {"ApiKey": {}}
     * },
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
     *     )
     *   )
     * )
     */

     // Route - Get all favorites for a user by user_id
    Flight::route('GET /user/@user_id/favorites', function($user_id){
        $service = new FavoritesService();

        $favorites = $service->get_favorites_by_user($user_id);
        Flight::json($favorites);
    });

    /**
     * @OA\Post(
     *   path="/favorites/add-favorites",
     *   summary="Add a new favorite",
     *   tags={"Favorites"},
     *    security={
     *      {"ApiKey": {}}
     *   },
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

    // Route Add a new favorite
    Flight::route('POST /add-favorites', function() {
        $service = new FavoritesService();

        $data = Flight::request()->data->getData(); 
        error_log(print_r($data, true)); // This will log the data to your PHP error log

        
        // Check if user_id and book_id are set
        if (isset($data['user_id']) && isset($data['book_id'])) {
            $result = $service->add_to_favorites($data['user_id'], $data['book_id']);
            Flight::json(['message' => 'Favorite added successfully', 'id' => $result], 201);
        } else {
            Flight::json(['error' => 'Missing user_id or book_id'], 400);
        } 
    }, true); // 'true' indicates this route requires JWT authentication


    /**
     * @OA\Delete(
     *   path="/favorites/del-favorite/{id}",
     *   summary="Delete a favorite",
     *   tags={"Favorites"},
     *   security={
     *     {"ApiKey": {}}
     *   },  
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


    // Route - Delete a favorite
    Flight::route('DELETE /del-favorite/@id', function($id){
        $service = new FavoritesService();

        $result = $service->delete_favorite($id);
        Flight::json($result);
    }, true);




});
