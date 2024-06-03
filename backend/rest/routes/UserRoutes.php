<?php
require_once __DIR__ . '/../services/UserService.class.php';
require_once __DIR__ . '/MiddlewareRoutes.php';


Flight::group('/users', function () { 

    /**
     * @OA\Get(
     *     path="/users/all-users",
     *     summary="List all users",
     *     tags={"Users"},
     *     security={{"ApiKey": {}}},
     *     @OA\Parameter(
     *         name="order",
     *         in="query",
     *         required=false,
     *         description="Ordering of returned users",
     *         @OA\Schema(type="string", example="-id")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful retrieval of user list",
     *         
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Access denied"
     *     )
     * )
     */

 
    //GET ALL USESRS - radi
    Flight::route('GET /all-users', function () {
        try {
            //error_log("Accessing all users");

            $order = Flight::request()->query['order'] ?? 'id';
            $user_service = new UserService();
            $users = $user_service->get_all_users($order);
            Flight::json($users);
        } catch (Exception $e) {
            error_log($e->getMessage());
            Flight::json(['error' => $e->getMessage()], 500);
        }
    });

    /**
     * @OA\Get(
     *     path="/users/{id}",
     *     tags={"Users"},
     *     summary="Get user by ID",
     *     security={
     *       {"ApiKey": {}}
     *      },
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Returns the user object"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="User not found"
     *     )
     * )
     */

       
    //Route to get a user by ID
    Flight::route('GET /@id', function ($id) {
        $user_service = new UserService();
        $user = $user_service->get_user_by_id($id);

        /*if ($user) {
            Flight::json([$user, 'message' => "User found"]);
        } else {
            Flight::halt(404, 'User not found');
        }*/

        Flight::json($user, 200);

    },true);  // 'true' indicates this route requires JWT authentication


    /**
     * @OA\Put(
     *     path="/users/update/{id}",
     *     tags={"Users"},
     *     summary="Update user information",
     *     security={
     *      {"ApiKey": {}}
     *      },
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(property="first_name", type="string"),
     *                 @OA\Property(property="last_name", type="string"),
     *                 @OA\Property(property="email", type="string"),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="User updated successfully"
     *     )
     * )
     */
    
    //Route to update user information
    Flight::route('PUT /update/@id', function ($id) {
        $data = Flight::request()->data->getData();
        $user_service = new UserService();

        $user_service->update_user($id, $data);
        Flight::json(['message' => "User successfully updated"], 201);
    }, true); 



    /**
     * @OA\Delete(
     *     path="/users/delete/{id}",
     *     tags={"Users"},
     *     summary="Delete a user",
     *     description="Deletes a user by ID from the database. Provides messages for successful deletion or errors.",
     *     security={
     *         {"ApiKey": {}}
     *     },
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="The ID of the user to delete",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User successfully deleted",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="User was successfully deleted")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="User not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="User not found")
     *         )
     *     )
     * )
     */


    //Route to delete a user
    Flight::route('DELETE /delete/@id', function ($id) {
        $user_service = new UserService();

        try {
            $result = $user_service->delete_user_by_id($id);
            if ($result) {
                Flight::json(['message' => 'User was successfully deleted'], 200);
            } else {
                Flight::json(['message' => 'User not found'], 404);
            }
        } catch (Exception $e) {
            error_log("Error during deletion: " . $e->getMessage());
            Flight::json(['message' => 'Server error while processing your request', 'error' => $e->getMessage()], 500);
        }
    }, true);


    
     /**
     * @OA\Get(
     *     path="/users/email/{email}",
     *     tags={"Users"},
     *     summary="Get user by email",
     *      security={
     *          {"ApiKey": {}}
     *      },
     *     @OA\Parameter(
     *         name="email",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Returns the user object"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="User not found"
     *     )
     * )
     */
 


    //Route to get a user by email
    Flight::route('GET /email/@email', function ($email) {
        $user_service = new UserService();

        $user = $user_service->get_user_by_email($email);

        Flight::json($user, 200);

    }, true);

    


});




