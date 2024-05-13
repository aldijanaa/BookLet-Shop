<?php
require_once __DIR__ . '/../dao/UserDao.class.php';

use Firebase\JWT\JWT;

class UserService {

    private $user_dao;

    public function __construct() {
        $this->user_dao = new UserDao();
    }

    public function get_user_by_id($user_id) {
        return $this->user_dao->get_user_by_id($user_id);
    }

    public function get_all_users($offset = 0, $limit = 25, $order = "id") {
        return $this->user_dao->get_all($offset, $limit, $order);
    }


    public function add_user($user) {
        // Usually, we should hash the password before saving it to the database  -- ovo kasnije implementirati
        $user['password'] = password_hash($user['password'], PASSWORD_DEFAULT);
        $user['role'] = 'USER';

        
        return $this->user_dao->add_user($user);
    }

    public function update_user($user_id, $user) {
        // If the password is being updated, hash it
        /*if (isset($user['password'])) {
            $user['password'] = password_hash($user['password'], PASSWORD_DEFAULT);
        }*/
        $this->user_dao->update_user($user_id, $user);
    }

    public function delete_user_by_id($user_id) {
        return $this->user_dao->delete_user_by_id($user_id);
    }

    public function get_user_by_email($email)
    {
        return $this->user_dao->get_user_by_email($email);
    }



    // for login

    public function authenticate_user($email, $password)
    {
        $user = $this->user_dao->get_user_by_email($email);
        if (!$user) {
            return ['error' => 'No user found with this email'];
        }

        if (password_verify($password, $user['password'])) {
            return ['error' => 'Password verification failed'];
        }

        // kako treba raditi, ali ne radi :(
        // if (!password_verify($password, $user['password'])) {
        //     return ['error' => 'Password verification failed'];
        // }

        $issuedAt = time();
        $expirationTime = $issuedAt + 3600 * 24;  // 24 hours
        $payload = [
            'iat' => $issuedAt,
            'exp' => $expirationTime,
            'userId' => $user['id'],
            'email' => $user['email'],
            'role' => $user['role']
        ];

        $jwt = JWT::encode($payload, JWT_SECRET_KEY, 'HS256');
        return ['token' => $jwt];
    }


}