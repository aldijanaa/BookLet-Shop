<?php

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Content-Type: application/json');


require_once __DIR__ . '/../../rest/services/UserService.class.php';

$service = new UserService();  //calling service
$data = json_decode(file_get_contents('php://input'), true); // Read the JSON POST input

// Check if the required fields are set with the empty() function
if (!$data || empty($data['first_name']) || empty($data['last_name']) || empty($data['email']) || empty($data['password']) || empty($data['confirm_password'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing fields']);
    exit;
}

//Checking for format of email
if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid email format']);
    exit;
}
//checking if password and confirm password match
if ($data['password'] !== $data['confirm_password']) {
    http_response_code(400);
    echo json_encode(['error' => 'Passwords do not match']);
    exit;
}

//CHECKING DOES THE USER ALREADY EXIST
//calling the function get_user_by_email from UserService to see if user with that email already exists
$existingUser = $service->get_user_by_email($data['email']); 
if ($existingUser) {
    http_response_code(409); 
    echo json_encode(['error' => 'User with those credentials already exists, either login or choose other credentials']);
    exit;
}

/************************************************************************************************************** */
function validateEmailTLD($email) { 
    $url = 'https://data.iana.org/TLD/tlds-alpha-by-domain.txt';  //fetching from remote url, can change later
    $tlds = file($url, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    array_shift($tlds); 

    $validTLDs = array_map('strtolower', $tlds);
    $partition = explode('@', $email);
    $domainPart = explode('.', $partition[1]);
    $tld = strtolower(end($domainPart));

    return in_array($tld, $validTLDs);
}
/************************************************************************************************************** */

//Kod sa sssda:
//Validate email TLDS
if (!validateEmailTLD($data['email'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid TLD in email address']);
    exit;
}

//Hashing password
$data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
$user = [
    'first_name' => $data['first_name'],
    'last_name' => $data['last_name'],
    'email' => $data['email'],
    'password' => $data['password']   //storing hashed password
];


// Try to add a new user
try {
    $result = $service->add_user($user);
    if ($result) {
        http_response_code(201); // Created
        echo json_encode(['message' => 'User registered successfully']);
    } else {
        http_response_code(500); // Internal Server Error
        echo json_encode(['error' => 'An unexpected error occurred']);
    }
} catch (Exception $e) {
    http_response_code(500); // If an exception occurs during the user registration process, return a 500 error
    echo json_encode(['error' => 'An unexpected error occurred: ' . $e->getMessage()]);
}



