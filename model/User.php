<?php
// model/User.php
require_once 'db.php';

class User {
    private $db;

    public function __construct() {
        $this->db = (new DB())->connect();
    }

    public function signup($username, $email, $phone, $password) {
        try {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $sql = "INSERT INTO user (username, email, phone, password) VALUES (:username, :email, :phone, :password)";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':username' => $username,
                ':email' => $email,
                ':phone' => $phone,
                ':password' => $hashedPassword
            ]);
            return ["success" => true, "message" => $password];
        } catch (PDOException $e) {
            return ["success" => false, "message" => $e->getMessage()];
        }
    }

    public function login($email, $password) {
        try {
            $sql = "SELECT * FROM user WHERE email = :email";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':email' => $email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($password, $user['password'])) {
                return ["success" => true, "message" => "Login successful!", "user" => $user];
            }
            return ["success" => false, "message" => password_hash($password, PASSWORD_DEFAULT)];
        } catch (PDOException $e) {
            return ["success" => false, "message" => $e->getMessage()];
        }
    }

    public function getUsersCount() {
        try {
            $sql = "SELECT COUNT(*) as total_users 
                    FROM user where isAdmin = 0";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return ["success" => true, "total_users" => $result['total_users']];
        } catch (PDOException $e) {
            return ["success" => false, "message" => $e->getMessage()];
        }
    }
}
?>
