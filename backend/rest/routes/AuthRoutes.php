<?php

require_once __DIR__ . '/../services/UserService.class.php';

Flight::group('/auth', function () {


    Flight::route('GET /all', function () {
        $offset = Flight::request()->query['offset'] ?? 0;
        $limit = Flight::request()->query['limit'] ?? 25;
        $order = Flight::request()->query['order'] ?? 'id';
        $user_service = new UserService();
        $users = $user_service->get_all_users($offset, $limit, $order);
        Flight::json($users);
    });



    /**
     * @OA\Post(
     *     path="/auth/register",
     *     tags={"Authentication"},
     *     summary="Register a new user",
     *     description="Registers a new user by accepting mandatory personal details and credentials.",
     *     @OA\RequestBody(
     *         required=true,
     *         description="User registration data",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 type="object",
     *                 required={"first_name", "last_name", "email", "password", "confirm_password"},
     *                 @OA\Property(property="first_name", type="string"),
     *                 @OA\Property(property="last_name", type="string"),
     *                 @OA\Property(property="email", type="string", format="email"),
     *                 @OA\Property(property="password", type="string", format="password"),
     *                 @OA\Property(property="confirm_password", type="string", format="password")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="User registered successfully"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid input, missing fields, or password mismatch"
     *     ),
     *     @OA\Response(
     *         response=409,
     *         description="User with those credentials already exists"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error or unexpected error occurred during the registration process"
     *     )
     * )
     */
    Flight::route('POST /register', function() {
        $service = new UserService();
        $data = Flight::request()->data->getData(); // Get JSON POST data
    
        // Check if the required fields are set
        if (empty($data['first_name']) || empty($data['last_name']) || empty($data['email']) || empty($data['password']) || empty($data['confirm_password'])) {
            Flight::json(['error' => 'Missing fields'], 400);
            return;
        }
    
        // Validate email format
        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            Flight::json(['error' => 'Invalid email format'], 400);
            return;
        }
    
        // Check if password and confirm password match
        if ($data['password'] !== $data['confirm_password']) {
            Flight::json(['error' => 'Passwords do not match'], 400);
            return;
        }
    
        // Check if user already exists
        $existingUser = $service->get_user_by_email($data['email']);
        if ($existingUser) {
            Flight::json(['error' => 'User with those credentials already exists, either login or choose other credentials'], 409);
            return;
        }
    
        // Validate the TLD of the email
        if (!validateEmailTLD($data['email'])) {
            Flight::json(['error' => 'Invalid TLD in email address'], 400);
            return;
        }
    
        // Hash the password
        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        $user = [
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'password' => $data['password'] // Store hashed password
        ];
    
        // Try to add a new user
        try {
            $result = $service->add_user($user);
            if ($result) {
                Flight::json(['message' => 'User registered successfully'], 201); // Created
            } else {
                Flight::json(['error' => 'An unexpected error occurred'], 500); // Internal Server Error
            }
        } catch (Exception $e) {
            Flight::json(['error' => 'An unexpected error occurred: ' . $e->getMessage()], 500); // Server error
        }
    });
    
    // Function to validate email TLD
    function validateEmailTLD($email) {
        $url = 'https://data.iana.org/TLD/tlds-alpha-by-domain.txt'; // Fetching from remote url, can change later
        $tlds = file($url, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        array_shift($tlds); // Remove the first line
    
        $validTLDs = array_map('strtolower', $tlds);
        $partition = explode('@', $email);
        $domainPart = explode('.', $partition[1]);
        $tld = strtolower(end($domainPart));
    
        return in_array($tld, $validTLDs);
    }
    

});
