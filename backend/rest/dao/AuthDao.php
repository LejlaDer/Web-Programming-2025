<?php

class AuthDao {
    private $conn;

    public function __construct() {
        $this->conn = new PDO("mysql:host=PROD_DB_HOST;dbname=exam_db", "user", "pass");
    }

    public function get_user_by_credentials($username, $password) {
        $stmt = $this->conn->prepare("
            SELECT id, username, role 
            FROM users 
            WHERE username = :username AND password = :password
        ");
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $password); 
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}

?>