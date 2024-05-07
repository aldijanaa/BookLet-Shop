<?php
require_once __DIR__ . '/../dao/UserDao.class.php';

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
}
?>
