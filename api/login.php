<?php
// api/login.php
require_once '../model/User.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    if ($email && $password) {
        $user = new User();
        $response = $user->login($email, $password);
        echo json_encode($response);
    } else {
        echo json_encode(["success" => false, "message" => "Email and password are required!"]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Invalid request method!"]);
}
?>
