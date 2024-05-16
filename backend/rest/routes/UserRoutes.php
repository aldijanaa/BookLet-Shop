<?php

require_once __DIR__ . '/../services/UserService.class.php';

Flight::group('/users', function () {



    /**
     * @OA\Get(
     *     path="/users/all",
     *     tags={"Users"},
     *     summary="Get all users",
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
    //GET ALL USESRS
    Flight::route('GET /all', function () {
        $offset = Flight::request()->query['offset'] ?? 0;
        $limit = Flight::request()->query['limit'] ?? 25;
        $order = Flight::request()->query['order'] ?? 'id';
        $user_service = new UserService();
        $users = $user_service->get_all_users($offset, $limit, $order);
        Flight::json($users);
    });


    /**
     * @OA\Get(
     *     path="/users/{id}",
     *     tags={"Users"},
     *     summary="Get user by ID",
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
    //get user by id
    Flight::route('GET /@id', function ($id) {
        $user_service = new UserService();
        $user = $user_service->get_user_by_id($id);
        if ($user) {
            Flight::json($user);
        } else {
            Flight::halt(404, 'User not found');
        }
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
    Flight::route('POST /add', function () {

        $data = Flight::request()->data->getData();


        $password = $data["password"];

        if (strlen($password) < 8)  {
            Flight::halt(400, 'Password must be at least 8 characters long');
        }

            

        $user_service = new UserService();
        $user = $user_service->add_user($data);
        Flight::json($user, 201);
    });


    /**
     * @OA\Put(
     *     path="/users/update/{id}",
     *     tags={"Users"},
     *     summary="Update user information",
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
    Flight::route('PUT /update/@id', function ($id) {
        $data = Flight::request()->data->getData();
        $user_service = new UserService();
        $user_service->update_user($id, $data);
        Flight::json(['message' => "User successfully updated"], 201);
    });


     /**
     * @OA\Delete(
     *     path="/users/delete/{id}",
     *     tags={"Users"},
     *     summary="Delete a user",
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
    Flight::route('DELETE /delete/@id', function ($id) {
        $user_service = new UserService();
        $success = $user_service->delete_user_by_id($id);
        if ($success) {
            Flight::json(['message' => "User successfully deleted"], 201);
        } else {
            Flight::halt(404, 'User not found or could not be deleted');
        }
    });


     /**
     * @OA\Get(
     *     path="/users/email/{email}",
     *     tags={"Users"},
     *     summary="Get user by email",
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
    Flight::route('GET /email/@email', function ($email) {
        $user_service = new UserService();
        $user = $user_service->get_user_by_email($email);
        if ($user) {
            Flight::json($user);
        } else {
            Flight::halt(404, 'User not found');
        }
    });

});
