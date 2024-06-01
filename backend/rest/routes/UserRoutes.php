<?php

require_once __DIR__ . '/../services/UserService.class.php';
require_once __DIR__ . '/MiddlewareRoutes.php';


Flight::group('/users', function () {  


    //GET ALL USESRS
    Flight::route('GET /all', function () {




        error_log("Accessing all users");
        $offset = Flight::request()->query['offset'] ?? 0;
        $limit = Flight::request()->query['limit'] ?? 25;
        $order = Flight::request()->query['order'] ?? 'id';

        $user_service = new UserService();
        $users = $user_service->get_all_users($offset, $limit, $order);
        error_log("Printing users and calling service, ima li problema ovdje??");

        Flight::json($users);
        error_log($users);

    });

    /**
    * @OA\Get(
    *     path="/users/all",
    *     tags={"Users"},
    *     summary="Get all users",
    *     security={
    *         {"ApiKey": {}}
    *      },
     *     @OA\Parameter(
     *         name="offset",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="limit",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="order",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Returns a list of all users"
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

     //Route to add a new user --> DOESN'T NEED JWT AUTHENTICATION
     Flight::route('POST /add', function () {
        $data = Flight::request()->data->getData();
        $user_service = new UserService();

        $user = $user_service->registerUser($data);
        Flight::json($user, 201);
    });


     /**
     * @OA\Post(
     *     path="/users/add",
     *     tags={"Users"},
     *     summary="Add a new user",
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 required={"first_name", "last_name", "email", "password"},
     *                 @OA\Property(property="first_name", type="string"),
     *                 @OA\Property(property="last_name", type="string"),
     *                 @OA\Property(property="email", type="string"),
     *                 @OA\Property(property="password", type="string")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="User created successfully"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Password must be at least 8 characters long"
     *     )
     * ) 
     */


    //Route to update user information
     Flight::route('PUT /update/@id', function ($id) {
        $data = Flight::request()->data->getData();
        $user_service = new UserService();

        $user_service->update_user($id, $data);
        Flight::json(['message' => "User successfully updated"], 201);
    }, true); // Requires JWT authentication

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
     *                 @OA\Property(property="password", type="string")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="User updated successfully"
     *     )
     * )
     */

    //Route to delete a user
    Flight::route('DELETE /delete/@id', function ($id) {
        $user_service = new UserService();
        $user = $user_service->delete_user_by_id($id);

        /*if ($success) {
            Flight::json(['message' => "User successfully deleted"], 201);
        } else {
            Flight::halt(500, 'User not found or could not be deleted');
        }*/
        Flight::json($user, 200);

    }, true); // Requires JWT authentication


     /**
     * @OA\Delete(
     *     path="/users/delete/{id}",
     *     tags={"Users"},
     *     summary="Delete a user",
     *     security={
     *      {"ApiKey": {}}
     *   },
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="User successfully deleted"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="User not found or could not be deleted"
     *     )
     * )
     */
   
     //Route to get a user by email
     Flight::route('GET /email/@email', function ($email) {
        $user_service = new UserService();

        $user = $user_service->get_user_by_email($email);
        /*if ($user) {
            Flight::json($user);
        } else {
            Flight::halt(500, 'User not found');
        }*/
        Flight::json($user, 200);

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
 



});

Flight::start();