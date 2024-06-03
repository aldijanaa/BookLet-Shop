<?php
require_once __DIR__ .  '/../../config.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

Flight::route('/*', function() {   //$role = "USER" is default role for all routes
    // Token is not needed for login or register page, because these two routes are used to authorize the user and get the token
    if (
         strpos(Flight::request()->url, '/auth/login') === 0 ||
         strpos(Flight::request()->url, '/auth/register') === 0
    ){ 
        return TRUE;   //don't want to authenticate 
    } else {
        try {
            $token = Flight::request()->getHeader("Authentication");

            //using middleware to check if token is valid
            if(!$token)
                Flight::halt(401, "Unauthorized access. This will be reported to administrator!");

            $decoded_token = JWT::decode($token, new Key(JWT_SECRET_KEY, 'HS256')); //JWT_SECRET_KEY is defined in config.php

            
            Flight::set('user', $decoded_token->user);
            Flight::set('user_id', $decoded_token->user_id);
            Flight::set('jwt_token', $token);

            return TRUE;
        } catch (\Exception $e) {
            Flight::halt(401, $e->getMessage());
            //return FALSE;
        }
    }
});
 
//log all errors to file 
Flight::map('error', function($e) {
    file_put_contents('logs.txt', $e.PHP_EOL , FILE_APPEND | LOCK_EX);
    Flight::halt($e->getCode(), $e->getMessage());
    Flight::stop($e->getCode());
});