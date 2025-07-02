<?php
class AuthService {
    protected $dao;

    public function __construct() {
        $this->dao = new AuthDao();
    }

    public function login($username, $password) {
        $user = $this->dao->get_user_by_credentials($username, $password);
        if ($user) {
            return ["token" => "mock_jwt_" . $user['id']]; // Simplified for exam
        }
        return null;
    }
}
?>